<?php

// namespace Model;
/**
 * @file: appontmentModel.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\models
 * @created:    Fri Jan 24 2020
 * @author:     oppo, 1plus-agency.com
 * @version:    1.0.0
 * @modified:   Friday January 24th 2020 5:30:29 pm
 * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

//PM (11.03.2019) Bloom´s // Webseite // Terminbuchung // Einführung Kundenkonto/ReconnectID >> Produktion
if ( !defined( 'APPOINTMENT_RECONNECT_ID' ) ) {
    define( "APPOINTMENT_RECONNECT_ID", "pagecon_appointment_reconnect_id" );
}
// $this->user = new \Model\User($this->casted['uid']);
class appontmentModel extends DB\SQL\Mapper
{
    /**
     * @var mixed
     */
    protected $f3;
    /**
     * @var mixed
     */
    protected $base;
    /**
     * @var mixed
     */
    protected $page_host;

    /**
     * @param DB\SQL $db
     */

    public function __construct( DB\SQL $db = null )
    {

        $f3       = \Base::instance();
        $this->f3 = $f3;

        $this->base      = $this->f3->get( 'BASE' );
        $this->page_host = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" )."://$_SERVER[HTTP_HOST]";

        // parent::__construct($db, 'appontment');
    }

    public static function serviceAvailable()
    {
        //https://api.bloom-s.de:780/api/ping
        return helperblooms::serviceAvailable();
    }

    /**
     * @param $salonId
     * @param $mitarbeiterId
     * @param $datum
     * @param $zeit
     * @param $dienstleistungId
     * @param $mobilenumber
     * @param $email
     * @param $name
     * @param $employee
     * @param $reconnectId
     * @return mixed
     */
    public function createAppontmentCodeWithoutAccount( $salonId, $mitarbeiterId, $datum, $zeit, $dienstleistungId, $mobilenumber, $email, $name, $employee, $reconnectId )
    {
        $resTimes = false;
        // Seite 15
        if ( self::serviceAvailable() ) {

            // stdClass::__set_state(array(
            //     'AppointmentCreated' => false,
            //     'AppointmentId' => NULL,
            //     'ConfirmationCodeExpiryDate' => '0001-01-01T00:00:00',
            //     'ErrorCode' => 256,
            //     'ReconnectId' => NULL,
            //     'SMSSent' => false,
            //     'AdditionalInfo' => 'Salon is not available online',
            //     'ReturnCodeValue' => 256,
            //     'ReturnValueText' => 'NoOnlineAppointmentSalon',
            //  ))

            // $arr = [];
            if ( !is_string( $employee ) ) {
                $employee = intval( $employee ) ? 'true' : 'false';
            }
            $http = new Bugzilla();

            $objDateTime = new DateTime( $datum.' '.$zeit );
            // echo $objDateTime->format(DateTime::ISO8601).'<br>';
            $iso_data = $objDateTime->format( 'Y-m-d\TH:i' ); // 2020-02-10T09:45

            // $url = 'appointment/createWithoutAccountAndWithSmsLink?salonId='.$salonId.'&employeeId='.$mitarbeiterId.'&date='.$iso_data.'&servicePackageId='.$dienstleistungId.'&mobilePhoneNumber='.$mobilenumber.'&email='.$email.'&name='.$name.'&specificEmployee='.$employee.'&sendSmsLink=true';

            $url = 'appointment/CreateWithoutAccountAndWithSmsLink?employeeId='.$mitarbeiterId.'&salonId='.$salonId.'&date='.$iso_data.'&servicePackageId='.$dienstleistungId.'&mobilePhoneNumber='.$mobilenumber.'&email='.$email.'&name='.$name.'&specificEmployee='.$employee.'&sendSmsLink=true';

            if ( $reconnectId ) {
                $url .= "&reconnectId=$reconnectId";
            } else {
                $url .= "&reconnectId";
            }

            $esc_url = preg_replace( '/ /', '%20', $url );
            $arr     = $http->get( $esc_url );
        }

        if ( $arr && ( $createWithoutAccount = helperblooms::jsJson( $arr, false ) ) ) {
            return $createWithoutAccount;
        }

        return $resTimes;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function resendConfirmationCode( $appointmentId, $mobilenumber )
    {

        // stdClass::__set_state(array(
        //     'ConfirmationCodeExpiryDate' => '0001-01-01T00:00:00',
        //     'ConfirmationCodeResent' => false,
        //     'ErrorCode' => 16,
        //     'AdditionalInfo' => '',
        //     'ReturnCodeValue' => 16,
        //     'ReturnValueText' => 'AppointmentAlreadyConfirmed',
        //  ))

        $arr = false;
        if ( self::serviceAvailable() ) {
            // appointment/resendConfirmationCodeWithSmsLink?appointmentId={appointmentId}&mobilePhoneNumber={mobilePhoneNumber}&sendSmsLink={sendSmsLink}
            $http    = new Bugzilla();
            $url     = 'appointment/resendConfirmationCodeWithSmsLink?appointmentId='.$appointmentId.'&mobilePhoneNumber="'.$mobilenumber.'"&sendSmsLink=true';
            $esc_url = preg_replace( '/ /', '%20', $url );

            $arr = $http->get( $esc_url );
        }

        if ( $arr && ( $confirmAndGet = helperblooms::jsJson( $arr, false ) ) ) {
            return $confirmAndGet;
        }

        return $arr;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function confirmAppontmentCode( $code )
    {

        $arr = false;
        if ( self::serviceAvailable() ) {
            // $arr = [];
            $http    = new Bugzilla();
            $url     = 'appointment/confirmAndGet2?code='.$code;
            $esc_url = preg_replace( '/ /', '%20', $url );

            $arr = $http->get( $esc_url );
        }

        if ( $arr && ( $confirmAndGet = helperblooms::jsJson( $arr, false ) ) ) {
            return $confirmAndGet;
        }

        return $arr;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function iCalendar( $code )
    {

        $arr = false;
        if ( self::serviceAvailable() ) {
            // $arr = [];
            $http    = new Bugzilla();
            $url     = 'appointment/iCalendar?appointmentId='.$code;
            $esc_url = preg_replace( '/ /', '%20', $url );

            $arr = $http->get( $esc_url );
        }

        if ( $arr && ( $confirmAndGet = helperblooms::jsJson( $arr, false ) ) ) {
            return $confirmAndGet;
        }

        return $arr;
    }

} // end class
