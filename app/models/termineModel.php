<?php

// namespace Model;
/**
 * @file: termineModel.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\models
 * @created:    Fri Jan 24 2020
 * @author:     oppo, 1plus-agency.com
 * @version:    1.0.0
 * @modified:   Friday January 24th 2020 5:30:29 pm
 * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// $this->user = new \Model\User($this->casted['uid']);
class termineModel extends DB\SQL\Mapper
{
    /**
     * @var mixed
     */
    protected $f3;
    protected $base;
    protected $page_host;

    /**
     * @param DB\SQL $db
     */

    public function __construct(DB\SQL $db = null)
    {
        $f3 = \Base::instance();
        $this->f3 = $f3;
        $this->base = $this->f3->get('BASE');
        $this->page_host = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
        // parent::__construct($db, 'servicepackage');
    }

    public static function serviceAvailable()
    {
        //https://api.bloom-s.de:780/api/ping
        return helperblooms::serviceAvailable();
    }

    public function getAllTimes($salonId, $mitarbeiterId = '', $dienstleistungId, $datum)
    {
        $resTimes = false;

        // Aufruf: <BASE>/salon/timetable? salonId={SALONID}&employeeId={EMPLOYEEID}&date={DATE}&servicePackageId={SERVICEPACKAGE ID}
        // Seite 93

        $arr = false;
        if (self::serviceAvailable()) {
            $arr = [];
            $http = new Bugzilla();
            $url = 'salon/timetable?salonId=' . $salonId . '&employeeId=' . $mitarbeiterId . '&date=' . $datum . '&servicePackageId=' . $dienstleistungId;
            $arr = $http->get($url);
        }

        if (!$arr) {

            // disable for non local host 24.04.2020 14:36
            $ip                        = $this->f3->get('IP');
            $local                     = ($ip == '127.0.0.1' ? true : false);
            if ($local) {

                ob_start();
                if ($mitarbeiterId) {
                    include ONEPLUS_DIR_PATH_APP . 'helper/json/salon_timetable_salonide_25.json';
                } else {
                    include ONEPLUS_DIR_PATH_APP . 'helper/json/salon_timetable_salonide_25_employeeid_0.json';
                }
                $arr = ob_get_clean(); // (string)"Hello World"
            }
        }

        if ($arr && ($AllTimes = helperblooms::jsJson($arr, true))) {
            // $res =  bloomArrayHelper::getColumn($AllTimes, 'Result');
            $res = $AllTimes['Employees'];
            $resTimes = helperblooms::parseXmlToArraysalons($res, 'Id');
        }

        return $resTimes;
    }

    public function getCasheAllTimes($salonId, $mitarbeiterId = '', $dienstleistungId, $datum, $redirect = true)
    {
        $AllTimes_arr = [];

        $cacheblooms = $this->f3->get('CacheBlooms');
        $code = 'AllTimes' . md5($salonId . $mitarbeiterId . $dienstleistungId . $datum);
        $result = $cacheblooms->retrieve($code, true);

        if ($result) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $AllTimes_arr = json_decode($result, true);
        } else {
            // $spackModel       = new termineModel();
            $AllTimes = $this->getAllTimes($salonId, $mitarbeiterId, $dienstleistungId, $datum);
            if (!empty($AllTimes) && is_array($AllTimes)) {
                $AllTimes_arr = $AllTimes;
                /** @FIX by oppo , @Date: 2020-04-28 11:25:31
                 * @Desc: fix cacheblooms - 5 min | constant in index - TIME_CACHEBLOOMS
                 */
                $cacheblooms->store($code, json_encode($AllTimes_arr, true), TIME_CACHEBLOOMS); // 3600 => 'hour',

            } elseif ($redirect) {
                // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
                $this->f3->set('SESSION.error', 'Preise existiert nicht');
                $this->f3->reroute('/');
            }
        }
        return $AllTimes_arr;
    }
}
