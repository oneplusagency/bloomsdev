<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class json extends Controller
{
    /**
     * @file: json.php
     * @created:    Tue Feb 04 2020
     * @version:    1.0.0
     * @modified:   Tuesday January 28th 2020 1:07:56 pm
     * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\controllers
     * @author:     oppo
     * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
     * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
     */

    protected $configuration;
    /**
     * @var mixed
     */
    protected $db;
    // protected $f3;
    // \Base $f3
    public function __construct()
    {
        parent::__construct();
        // $this->f3 = $f3;

        $this->db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
        $this->configuration = new \DB\Jig\Mapper($this->db, 'sysconfig.json');
    }

    public function banner()
    {

        // $test = (int) helperblooms::inGet('test', 0);
        $_POST["action"] = "fetch";
        $_POST["salonId"] = "8";
        echo '<pre>';
        var_export($_POST);
        echo '</pre>';
        exit;
    }

    /**
     * @return null
     */
    public function kontakt()
    {

        $sitename = 'Bloom\'s';

        $success = 'Email was sent successfully';
        $failed = "Email could not be sent.";
        $success = 'E-Mail wurde erfolgreich gesendet';
        $failed = "E-Mail konnte nicht gesendet werden.";
        $invalid_email = "Invalid Email Address.";
        $empty_hield = "Füllen Sie alle Felder aus.";
        $invalid_email = "Ungültige E-Mail-Adresse!";
        $recipient = '';
        //inputs
        $inputs = $this->f3->get('POST.data');
        // https://fatfreeframework.com/3.7/base
        // $foo = "<h1><b>nice</b> <span>news</span> article <em>headline</em></h1>";
        // $h1 = $this->f3->clean($foo,'h1,span'); // <h1>nice <span>news</span> article headline</h1>
        // $inputs = $this->f3->scrub($inputs);

        //exit;

        $inputs_TEST = array(
            0 => array(
                'name' => 'fullname',
                'value' => 'Alex Jirty'
            ),
            1 => array(
                'name' => 'email',
                'value' => 'info@bloom.de'
            ),
            2 => array(
                'name' => 'strasse',
                'value' => 'Lange Straße 34, Baden-Baden, Germany'
            ),
            3 => array(
                'name' => 'city',
                'value' => 'Baden-Baden'
            ),
            4 => array(
                'name' => 'phone',
                'value' => '0454545878787'
            ),
            5 => array(
                'name' => 'professional',
                'value' => 'uyiyui'
            ),
            6 => array(
                'name' => 'message',
                'value' => 'test test'
            )
        );

        $email = $name = $message = $phone = '';
        $professional = $strasse = $city = '';

        $subject = "Ask a question";

        foreach ((array) $inputs as $input) {

            if (isset($input['name'])) {

                if ($input['name'] == 'email') {
                    $email = $input['value'];
                }

                if ($input['name'] == 'fullname') {
                    $name = $input['value'];
                }

                if ($input['name'] == 'message') {
                    $message = nl2br($input['value']);
                }

                if ($input['name'] == 'strasse') {
                    $strasse = trim($input['value']);
                }

                if ($input['name'] == 'city') {
                    $city = trim($input['value']);
                }

                if ($input['name'] == 'phone') {
                    $phone = trim($input['value']);
                }

                if ($input['name'] == 'professional') {
                    $professional = trim($input['value']);
                }
            }
        }

        if (!$name || !$email || !$message) {
            echo '<div class="alert alert-danger rounded-0" role="alert">' . $empty_hield . '</div>';
            return;
        }

        $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$valid_email) {
            echo '<div class="alert alert-danger rounded-0" role="alert">' . $invalid_email . '</div>';
            return;
        }

        // CLEAR MESSAGE
        $message = $this->f3->scrub($message);
        // $message = empty($message) ? 'null' : JComponentHelper::filterText($message, true);

        $message .= "<br />------------------<br />";
        $message .= 'Name: ' . $name . "<br />";
        $message .= 'Email: ' . $email . "<br />";
        $message .= 'Telefon: ' . $phone . '<br />';

        if ($strasse) {
            $message .= 'Straße: ' . $strasse . '<br />';
        }

        if ($city) {
            $message .= 'Stadt: ' . $city . '<br />';
        }

        if ($professional) {
            $message .= 'Fachkraft: ' . $professional . '<br />';
        }

        $message .= "<br />------------------<br />";
        $message .= 'Page URL: ' . $this->home_url . "<br />";
        $message .= 'Adresse IP: ' . $this->f3->get('IP') . "<br />";
        $message .= 'USER AGENT: ' . $this->f3->SERVER['HTTP_USER_AGENT'];

        // file_put_contents ( ONEPLUS_DIR_PATH."/kontakt.txt" , var_export( $inputs , true),  LOCK_EX );
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/Exception.php';
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/PHPMailer.php';

        $mail = new PHPMailer();

        try {
            // $mail->SMTPDebug = $this->f3->get('SMTPDEBUG');
            // $mail->isSMTP();
            // $mail->Host       = $this->f3->get('SMTPHOST');
            // $mail->SMTPAuth   = $this->f3->get('SMTPAUTH');
            // $mail->Username   = $this->f3->get('SMTPUSERNAME');
            // $mail->Password   = $this->f3->get('SMTPPASSWORD');
            // $mail->SMTPSecure = $this->f3->get('SMTPSECURE');
            // $mail->Port       = $this->f3->get('SMTPPORT');

            $recipient = $this->f3->get('email.mail_from_email');
            $recipient_name = $this->f3->get('email.mail_from_name');

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            // $mail->setLanguage('de');
            //reply to before setfrom: https://stackoverflow.com/questions/10396264/phpmailer-reply-using-only-reply-to-address
            // $mail->AddReplyTo($email, "No Reply"); // indicates ReplyTo headers
            $mail->ClearReplyTos();
            $mail->AddReplyTo($email, $name);

            //setting up the mail header

            $noreply_emal = helperblooms::ns_ondomain_email();
            $true_email = filter_var($noreply_emal, FILTER_VALIDATE_EMAIL);
            if ($true_email) {
                $mail->setFrom($noreply_emal, $recipient_name);
            } else {
                $noreply_emal = $this->f3->get('email.noreply_emal');
                $true_email = filter_var($noreply_emal, FILTER_VALIDATE_EMAIL);
                if ($true_email) {
                    ///$mail->setFrom( $noreply_emal, $recipient_name );
                    $mail->setFrom($recipient, $recipient_name);
                } else {
                    $mail->setFrom($recipient, $recipient_name);
                }
            }

            $mail->setFrom($recipient, $recipient_name);
            $mail->addAddress($recipient, $recipient_name); // Add a recipient

            // В поле СC: (Копія : TEST)
            // В поле ВСC: (Прихована копія : TEST)
            //Set BCC address
            // $mail->addBCC('svizina@gmail.com', 'oppo');
            // $mail->addCustomHeader("BCC: mybccaddress@mydomain.com");

            $mail->Subject = $this->f3->get('site') . ' Kontakt';

            $mail->Body = $message;

            $mail->WordWrap = 50;

            // $mail->AltBody = strip_tags($message);

            // //Attach a file
            // $mail->addAttachment("/messagestore/some.pdf","some.pdf","base64","application/pdf");
            // //generate mime message
            // $mail->preSend();

            $mail->send();

            // clear all addresses and attachments for the next mail
            // $mail->ClearAddresses();
            // $mail->ClearAttachments();

            echo '<div class="alert alert-success rounded-0" role="alert">' . $success . '</div>';
        } catch (Exception $e) {
            echo '<div class="alert alert-danger rounded-0" role="alert">' . $failed . '</div>';
            $logger = new \Log($this->f3->get('LOGS') . date("d.m.Y") . 'mail_error.log');
            $logger->write("Mail error: " . $mail->ErrorInfo, 'r');
        }

        // $to = 'svizina@gmail.com';
        // $to = 'gutschein@bloom-s.de';
        // $pishlo = $this->sendConfirmMail($to, $subject, $message);
        // if ($pishlo) {
        //     echo '<div class="alert alert-success rounded-0" role="alert">' . $success . '</div>';
        // } else {

        //     $e = error_get_last();
        //     if ($e['message'] !== '') {
        //         $logger = new \Log($this->f3->get('LOGS') . 'mail_error.log');
        //         // $logger->write('Error :' . $e['message'] . '');
        //         $logger->write(var_export( $e , true). '');
        //     }
        //     echo '<div class="alert alert-danger rounded-0" role="alert">' . $failed . '</div>';
        // }

        // if ($mail->Send()) {
        //     echo '<div class="alert alert-success rounded-0" role="alert">' . $success . '</div>';
        //     return null;
        // } else {
        //     echo '<div class="alert alert-danger rounded-0" role="alert">' . $failed . '</div>';
        //     $logger = new \Log($this->f3->get('LOGS') . 'mail_error.log');
        //     $logger->write('Error :' . $failed . '');
        //     return null;
        // }

        exit();
    }

    /**
     * @param $to
     * @param $subject
     * @param $message
     */
    public function sendConfirmMail($to, $subject, $message)
    {
        // To send HTML mail
        // CC in the customer header
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: From: Bloom <admin@bloom-s.de>',
            'Cc:entwicklung@1plus-agency.com'
        ];
        $headers = implode("\r\n", $headers);
        // Mail it
        return mail($to, '=?utf-8?B?' . base64_encode($subject) . '?=', $message, $headers, '-admin.bloom-s.de');
    }

    public function index()
    {
        // $this->page_host = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        // $this->f3->set('PAGE_HOST', $this->page_host);
        // // set PAGE_CONTROLLER  home / termine / json
        // $this->controller = $controller = $this->f3->get('PARAMS')['controller'];
        // $this->f3->set('PAGE_CONTROLLER', $this->controller);
        // // set base '/f3-url-shortener'
        // $this->base = $base = $this->f3->get('BASE');
        // $this->f3->route('GET|POST /@controller/@action/@id', '\@controller->@action');
        // $option_salon = (string) $this->f3->get('GET.option_salon');
        //http://localhost/f3-url-shortener/json/local/prices_allprices.json
    }

    public function local()
    {
        //http://localhost/f3-url-shortener/json/local/prices_allprices.json
        // $this->f3->route('GET|POST /@controller/@action/@id', '\@controller->@action');
        if ($this->f3->exists('PARAMS.id')) {
            $name = (string) $this->f3->get('PARAMS.id');
            // $this->f3->set( 'name', $name );
        }
        if ($name) {
            //prices_allprices.json
            if (is_file(ONEPLUS_DIR_PATH_APP . 'helper/json/' . $name)) {
                include_once ONEPLUS_DIR_PATH_APP . 'helper/json/' . $name;
            }
        }
        // $arr  = file_get_contents($page_host.$base'/helper/json/prices_allprices.json');
        //http://localhost/f3-url-shortener/helper/json/prices_allprices.json
    }

    /** @FIX by oppo (webiprog.de), @Date: 2020-11-03 13:28:20
     * @Desc:  ADD Funktion: <BASE>/appointment/iCalendar
     * Aufruf: <BASE>/appointment/iCalendar?appointmentId={APPOINTMENTID}
     */

    public function iCalendar()
    {
        $mode = null;

        if ($this->f3->exists('SESSION.pdf_sess')) {

            $pdf_sess = $this->f3->get('SESSION.pdf_sess');
        } else {

            $this->f3->set('SESSION.error', 'Data Kalender existiert nicht');
            $this->f3->reroute('/termine.html');
        }

        // getCode sms: {"success":true,"appointment_created":true,"appointment_id":"2307dcc7-0d29-4e48-92e0-e2958dfbed7f","sms_sent":true,"error_code":0}
        // $kliz_sel = [
        //     'success' => true,
        //     'appointment_created' => true,
        //     'appointment_id' => '2307dcc7-0d29-4e48-92e0-e2958dfbed7f',
        //     'sms_sent' => true,
        //     // 'error_code' => 64
        //     'error_code' => 0
        // ];
        // $this->f3->set(
        //     "SESSION.pdf_sess",
        //     array_merge(
        //         $this->f3->get('SESSION.pdf_sess'),
        //         ['appointment'=>$kliz_sel]
        //     )
        // );

        $appointment_data = bloomArrayHelper::getValueJoom($pdf_sess, 'appointment', null, 'ARRAY');

        $appointment_id = bloomArrayHelper::getValueJoom($appointment_data, 'appointment_id', null, 'STRING');

        $appontmentModel = new appontmentModel();

        $iCalendar = null;
        try {
            $iCalendar = $appontmentModel->iCalendar($appointment_id);

        } catch (Exception $e) {

            $logger = new \Log($this->f3->get('LOGS') . 'error_sms_appointment_id.log');
            $sms_error = ['appointment_id' => $appointment_id, 'error' => $e->getMessage()];
            $logger->write(json_encode($sms_error));
        }


        if ($appointment_id && $iCalendar && $iCalendar != 'null') {

            // $logger    = new \Log( $this->f3->get( 'LOGS' ).'iCalendar.log' );
            // $logiCalendar = ['appointment_id' => $appointment_id, 'iCalendar' => $iCalendar];
            // $logger->write( json_encode( $logiCalendar  ) );

            //set correct content-type-header
            header('Content-type: text/calendar; charset=utf-8');
            header('Content-Disposition: inline; filename=calendar.ics');
            // echo $iCalendar;
            echo json_decode($iCalendar);
            exit;
        } else {
            $this->f3->set('SESSION.error', 'Appointment ID (' . $appointment_id . ') existiert nicht');
            $this->f3->reroute('/termine.html');
            exit;
        }

    }

    public function pdf()
    {
        if ($this->f3->exists('SESSION.pdf_sess')) {
            $pdf_sess = $this->f3->get('SESSION.pdf_sess');
        } else {
            // $this->f3->set('SESSION.flash', array());
            // $this->f3->push('SESSION.flash', array('type' => 'warning', 'msg' => 'Please wait for your account to be approved before submitting forms!'));
            $this->f3->set('SESSION.error', 'Data pdf existiert nicht');
            $this->f3->reroute('/termine.html');
        }
        // array (
        //     'user_data_tab_three' =>
        //     array (
        //       'vertretung' => 'null',
        //       'vorname' => 'Alexopopo',
        //       'nachname' => 'ONEPLUS',
        //       'email' => 'info@1plus-agency.com',
        //       'mobilenumber' => '01645854290',
        //     ),
        //     'available_termine' =>
        //     array (
        //       'salonId' => '25',
        //       'termineDate' => '2020-02-10',
        //       'termineTime' => '9:45 AM',
        //       'dienstleistungId' => '4',
        //       'dienstleistungName' => 'Schnitt + Finish',
        //       'dienstleistungDescription' => '• Kompetente Beratung mit mehreren Vorschlägen
        //   • Individuell auf ihre Haarstruktur abgestimmter Haarschnitt
        //   • Professionelles Finish mit Rundbürste, Lockenstab oder Glätteisen',
        //       'wochenTagFertig' => 'понеділок 10 лютий 2020',
        //       'mitarbeiterId' => '912',
        //       'mitarbeiterName' => 'Lisa Täubl',
        //       'mitarbeiterFile' => 'http://localhost/f3-url-shortener/assets/images/employeeimage/emp_image.jpg',
        //       'salonAddress' => 'Elisabethenstr. 8, Darmstadt ',
        //     ),
        //     'pagecon_appointment_reconnect_id' => 'true',
        //   )

        //http://localhost/f3-url-shortener/json/local/prices_allprices.json
        // $this->f3->route('GET|POST /@controller/@action/@id', '\@controller->@action');
        if ($this->f3->exists('PARAMS.id')) {
            $mode = (string) $this->f3->get('PARAMS.id');
            // $this->f3->set( 'name', $mode );
        }

        // https://blooms.developservice.de/modules/mod_terminfinder/tmpl/pdf.php?view=print&s=25&vn=Alex&nn=ONEPLUS&str=&plz=&ort=&mobil=&email=info@1plus-agency.com&d=2020-02-06&t=10:00%20AM&m=912&dl=2&v=false

        $pdf_image_dir = ONEPLUS_DIR_PATH . '/assets/images/pdf/';

        // 'print' 'download'
        if ($mode) {
            // var_dump($pdf_sess);die;
            // setlocale(LC_TIME, "de_DE");
            $user_data_tab_three = bloomArrayHelper::getValueJoom($pdf_sess, 'user_data_tab_three', null, 'ARRAY');
            $available_termine = bloomArrayHelper::getValueJoom($pdf_sess, 'available_termine', null, 'ARRAY');
            $appointmentDetail = bloomArrayHelper::getValueJoom($pdf_sess, 'appointment', null, 'ARRAY');
            $appointmentId = $appointmentDetail['appointment_id'];
            

        

            //available_termine
            $salonId = bloomArrayHelper::getValueJoom($available_termine, 'salonId', null, 'INT');

            // salons Controller
            $salons_ctrl = new salons();
            $salon = $salons_ctrl->getSalonsController($salonId);
            // array (
            //     'Address' => 'Elisabethenstr. 8, Darmstadt ',
            //     'CategoryDisplayName' => '',
            //     'Description' => 'Großer Citysalon mitten in der Innenstadt gegenüber Saturn.
            //   Wir freuen uns auf Sie!',
            //     'DisplayName' => 'Darmstadt, Elisabethenstraße',
            //     'GooglePlacesUrl' => 'https://goo.gl/maps/7QEBG2grQT31xwsN6',
            //     'Id' => 25,
            //     'ImageUrl' => 'salonbild_da1.jpg',
            //     'Name' => 'Darmstadt 1',
            //     'OnlineAppointmentsEnabled' => true,
            //     'OpeningHours' => 'Montag - Mittwoch: 10:00 - 19:00 Uhr
            //   Donnerstag: 10:00 - 20:00 Uhr
            //   Freitag: 9:00 - 20:30 Uhr
            //   Samstag: 9:00 - 18:00 Uhr ',
            //     'Phone' => '06151 5044480',
            //   )

            date_default_timezone_set('Europe/Berlin');
            $loc = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');

            $mitarbeiterId = bloomArrayHelper::getValueJoom($available_termine, 'mitarbeiterId', null, 'INT');
            $datum = bloomArrayHelper::getValueJoom($available_termine, 'termineDate', null, 'STRING');
            $zeit = bloomArrayHelper::getValueJoom($available_termine, 'termineTime', null, 'STRING');
            // $wochenTagFertig = bloomArrayHelper::getValueJoom($available_termine, 'wochenTagFertig', null, 'STRING');
            $wochenTagFertig = strftime('%A %d %B %Y', strtotime($datum));

            $dienstleistungId = bloomArrayHelper::getValueJoom($available_termine, 'dienstleistungId', null, 'INT');
            
            $dienstleistungName = bloomArrayHelper::getValueJoom($available_termine, 'dienstleistungName', null, 'STRING');
            $salonAddress = bloomArrayHelper::getValueJoom($available_termine, 'salonAddress', null, 'STRING');
            $mitarbeiterFile = bloomArrayHelper::getValueJoom($available_termine, 'mitarbeiterFile', null, 'STRING');
            $mitarbeiterName = bloomArrayHelper::getValueJoom($available_termine, 'mitarbeiterName', null, 'STRING');

            // user_data_tab_three
            $vertretung = bloomArrayHelper::getValueJoom($user_data_tab_three, 'vertretung', false, 'STRING');

            $employee = $vertretung ? true : false;

            if ($employee == 'true') {
                $vertretung = 'Einen neuen Termin vereinbaren';
            } else {
                $vertretung = 'Vertretung durch anderen Kollegen';
            }

            $mobilenumber = bloomArrayHelper::getValueJoom($user_data_tab_three, 'mobilenumber', null, 'STRING');
            $email = bloomArrayHelper::getValueJoom($user_data_tab_three, 'email', null, 'STRING');
            $vorname = bloomArrayHelper::getValueJoom($user_data_tab_three, 'vorname', null, 'STRING');
            $nachname = bloomArrayHelper::getValueJoom($user_data_tab_three, 'nachname', null, 'STRING');
            $name = $vorname . ' ' . $nachname;
            

            if (!class_exists('FPDF')) {
                require ONEPLUS_DIR_PATH . '/app/helper/fpdf/fpdf.php';
                require ONEPLUS_DIR_PATH . '/app/helper/fpdi/fpdi.php';
            }

            try {

                $pdf = new FPDF();
                $pdf->AddPage();
                $h1 = 18;
                $h2 = 16;
                $h3 = 14;
                $h4 = 12;
                $h5 = 10;
                $h6 = 8;
                $h7 = 6;

                $indent = 55;
                $pdf->SetFont('Arial', 'B', $h1);

                $this->addImageInPdf($pdf, 50, 20, 100, 27, $pdf_image_dir . 'Logo bloom\'s weiss.jpg');

                $pdf->SetFont('Arial', 'B', $h1);
                $pdf->SetXY(50, 60);
                $pdf->Cell(0, 0, utf8_decode('Verbindliche Terminbestätigung'));


                // Personal Data
                $pdf->SetFont('Arial', 'B', $h3);
                $pdf->SetXY($indent, 80);
                $pdf->Cell(0, 0, utf8_decode('Persönliche Daten:'));

                $pdf->SetFont('Arial', '', $h5);
                $pdf->SetXY($indent, 87);
                $pdf->write(1, utf8_decode('Name: ' . $name));

                $pdf->SetXY($indent, 92);
                $pdf->write(1, utf8_decode('Email: ' . $email));

                $pdf->SetXY($indent, 97);
                $pdf->Cell(0, 0, utf8_decode('Telefon: ' . $mobilenumber));


                // Mein Termin
                $pdf->SetFont('Arial', 'B', $h3);
                $pdf->SetXY($indent, 107);
                $pdf->Cell(0, 0, utf8_decode('Mein Termin:'));

                $pdf->SetFont('Arial', '', $h5);
                $pdf->SetXY($indent, 114);
                $pdf->Cell(0, 0, utf8_decode('Was: ' . $dienstleistungName));


                $wochenTagFertig = helperblooms::uml($wochenTagFertig);
                $zeit = helperblooms::uml($zeit);

                $pdf->SetXY($indent, 119);
                $pdf->Cell(0, 0, utf8_decode('Wann: ' . trim($wochenTagFertig . ', ' . $zeit . ' Uhr')));

                $pdf->SetXY($indent, 124);
                $pdf->Cell(0, 0, utf8_decode('Wo: ' . $salon['Address']));

                $pdf->SetXY($indent, 129);
                $pdf->Cell(0, 0, utf8_decode('Wer: ' . $mitarbeiterName));


                $pdf->SetXY($indent, 137);
                $pdf->Cell(0, 0, utf8_decode('Salon-Telefon: ' . $salon['Phone']));


                $filename_patch = explode('/employeeimage/', $mitarbeiterFile);
                $filename_patch = end($filename_patch);
                $filename_patch = EMPLOYEEIMAGE_ABS_DIR . $filename_patch;
                $filename_abs = $filename_patch;
                // die($filename_abs);

                $this->addImageInPdf($pdf, $indent + 1, 145, 27, 40, $filename_abs);


                $pdf->SetFont('Arial', '', $h5);
                $pdf->SetXY($indent, 195);
                $pdf->Cell(0, 0, utf8_decode('Bei Ausfall des gewählten Mitarbeiters:'));

                $pdf->SetXY($indent, 200);
                $pdf->Cell(0, 0, utf8_decode($vertretung));

//                $pdf->SetXY($indent, 215);
//                $pdf->Cell(0, 0, utf8_decode('Liebe Kundin, lieber Kunde,'));


                $this->addImageInPdf($pdf, $indent, 207, 20, 16, $pdf_image_dir . 'buttons-Kartenzahlung_sw1_final.jpg');
                $this->addImageInPdf($pdf, $indent + 2, 227, 16, 16, $pdf_image_dir . 'Buttons-Storno_sw1_final.jpg');

                $pdf->SetFont('Arial', '', $h5);
                $pdf->SetXY($indent + 25, 213);
                $pdf->Cell(0, 0, utf8_decode('Bitte beachten Sie, dass Sie vor Ort nur mit Karte bezahlen können.'));

                $pdf->SetXY($indent + 25, 218);
                // $pdf->Cell(0, 0, utf8_decode('Bei Fragen zum bargeldlosem Bezahlen klicken Sie'));
                $pdf->write(1, utf8_decode('Bei Fragen zum bargeldlosem Bezahlen klicken Sie '));
                $pdf->SetTextColor(163, 163, 163);
                $pdf->SetFont('', 'U');
                $pdf->write(1, 'HIER', 'https://bargeldlos.bloom-s.de/');

                $pdf->SetFont('Arial', '', $h5);
                $pdf->SetTextColor(0, 0, 0);

                $pdf->SetXY($indent + 25, 232);
                $pdf->Cell(0, 0, utf8_decode('Sie möchten einen gebuchten Termin absagen?'));

                $pdf->SetXY($indent + 25, 237);
                // $pdf->Cell(0, 0, utf8_decode('Bitte im Salon anrufen oder klicken Sie'));
                $pdf->write(1, utf8_decode('Bitte im Salon anrufen oder klicken Sie '));
                $pdf->SetTextColor(163, 163, 163);
                $pdf->SetFont('', 'U');
                $pdf->write(1, 'HIER', "https://kundenservice.bloom-s.de/Appointment/AppointmentCancellation/$appointmentId");

                $pdf->SetFont('Arial', '', $h5);
                $pdf->SetTextColor(0, 0, 0);


                $pdf->SetFont('Arial', '', $h6);
                $pdf->SetXY($indent + 25, 245);
                $pdf->Cell(0, 0, utf8_decode('Bitte haben Sie Verständnis dafür, dass Termine nur bis 24 Stunden vor'));
                $pdf->SetXY($indent + 25, 248);
                // $pdf->Cell(0, 0, utf8_decode('Terminbeginn kostenfrei storniert werden können. AGBs Terminvergabe.'));
                $pdf->write(1, utf8_decode('Terminbeginn kostenfrei storniert werden können. '));
                $pdf->SetTextColor(163, 163, 163);
                $pdf->SetFont('', 'U');
                $pdf->write(1, 'AGBs', 'https://bloom-s.de/termineagb.html');

                $pdf->SetFont('Arial', '', $h6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->write(1, ' Terminvergabe.');

                // $this->addImageInPdf($pdf, $indent + 2, 227, 16, 16, $pdf_image_dir . 'Buttons-Storno_sw1_final.jpg');

//
//                $pdf->SetFont('Arial', '', $h5);
//                $pdf->SetXY($indent + 20, 222);
//                $pdf->Cell(0, 0, utf8_decode('Aus Sicherheitsgründen haben wir uns entschlossen nur noch Kartenzahlung '));
//
//                $pdf->SetXY($indent + 20, 227);
//                $pdf->Cell(0, 0, utf8_decode('zu akzeptieren. Uns ist bewusst, dass dies eine ungewöhnliche Maßnahme ist '));
//
//                $pdf->SetXY($indent + 20, 232);
//                $pdf->Cell(0, 0, utf8_decode('und wir bitten Sie dennoch dies zu unterstützen.'));

//                $pdf->SetXY($indent, 242);
//                $pdf->write(1, utf8_decode('Wir danken Ihnen sehr für Ihr Verständnis!'));
//
//                $pdf->SetXY($indent, 247);
//                $pdf->write(1, utf8_decode('Für weitere Informationen zum bargeldlosen Bezahlen klicken Sie bitte '));
//                $pdf->SetTextColor(163, 163, 163);
//                $pdf->SetFont('', 'U');
//                $pdf->write(1, 'hier', 'https://bargeldlos.bloom-s.de/');



                // $pdf->SetXY($indent + 25, 218);
                // $pdf->write(1, utf8_decode('Bei Fragen zum bargeldlosem Bezahlen klicken Sie '));
                // $pdf->SetTextColor(163, 163, 163);
                // $pdf->SetFont('', 'U');
                // $pdf->write(1, 'HIER', 'https://bargeldlos.bloom-s.de/');

                // $pdf->SetFont('Arial', '', $h5);
                // $pdf->SetTextColor(0, 0, 0);

                // $pdf->SetXY($indent + 25, 232);
                // $pdf->Cell(0, 0, utf8_decode('Sie möchten einen gebuchten Termin absagen?'));

                // $pdf->SetXY($indent + 25, 237);
                // $pdf->write(1, utf8_decode('Bitte im Salon anrufen oder klicken Sie '));
                // $pdf->SetTextColor(163, 163, 163);
                // $pdf->SetFont('', 'U');
                // $pdf->write(1, 'HIER', 'https://bloom-s.de/abmeldung.html');

                // $pdf->SetFont('Arial', '', $h5);
                // $pdf->SetTextColor(0, 0, 0);


                // $pdf->SetFont('Arial', '', $h6);
                // $pdf->SetXY($indent + 25, 245);
                // $pdf->Cell(0, 0, utf8_decode('Bitte haben Sie Verständnis dafür, dass Termine nur bis 24 Stunden vor'));
                // $pdf->SetXY($indent + 25, 248);
                // $pdf->write(1, utf8_decode('Terminbeginn kostenfrei storniert werden können. '));
                // $pdf->SetTextColor(163, 163, 163);
                // $pdf->SetFont('', 'U');
                // $pdf->write(1, 'AGBs', 'https://bloom-s.de/termineagb.html');

                // $pdf->SetFont('Arial', '', $h6);
                // $pdf->SetTextColor(0, 0, 0);
                // $pdf->write(1, ' Terminvergabe.');


                $pdf->SetFont('Arial', 'B', $h6);
                $pdf->SetXY($indent, 270);

                $pdf->SetTextColor(128, 128, 128);
                $pdf->write(1, utf8_decode('Wenn Sie Hilfe benötigen, schreiben sie bitte an unseren Kundenservice'));

                $pdf->SetXY($indent, 273);
                $pdf->write(1, utf8_decode('Email: '));
                $pdf->SetTextColor(163, 163, 163);
                $pdf->SetFont('', 'U');
                $pdf->write(1, utf8_decode('kundenservice@bloom-s.de'), 'mailto:kundenservice@bloom-s.de');

                $pdf->SetFont('Arial', 'B', $h6);
                $pdf->SetTextColor(128, 128, 128);
                $pdf->write(1, utf8_decode(' Internet: '));

                $pdf->SetFont('', 'U');
                $pdf->SetTextColor(163, 163, 163);
                $pdf->write(1, utf8_decode('www.bloom-s.de '), 'https://www.bloom-s.de');
                $pdf->SetTextColor(128, 128, 128);

                $pdf->SetFont('Arial', 'B', $h6);
                $pdf->write(1, ' | ');
                $pdf->SetFont('', 'U');
                $pdf->SetTextColor(163, 163, 163);
                $pdf->write(1, utf8_decode('Impressum'), 'https://bloom-s.de/impressum.html');


                if ($mode == 'print') {
                    $pdf->Output();
                } else {
                    $pdf->Output('blooms_termin.pdf', 'D');
                }

                // $pdf->closeParsers();
                $pdf->close();
            } catch (\Exception $e) {
                die($e->getMessage());
            }

        }
        // $arr  = file_get_contents($page_host.$base'/helper/json/prices_allprices.json');
        //http://localhost/f3-url-shortener/helper/json/prices_allprices.json
    }




    public function pdfNew()
    {
        // if ( $this->f3->exists( 'SESSION.pdf_sess' ) ) {
        //     $pdf_sess = $this->f3->get( 'SESSION.pdf_sess' );
        // } else {
        //     // $this->f3->set('SESSION.flash', array());
        //     // $this->f3->push('SESSION.flash', array('type' => 'warning', 'msg' => 'Please wait for your account to be approved before submitting forms!'));
        //     $this->f3->set( 'SESSION.error', 'Data pdf existiert nicht' );
        //     $this->f3->reroute( '/termine.html' );
        // }
        // array (
        //     'user_data_tab_three' =>
        //     array (
        //       'vertretung' => 'null',
        //       'vorname' => 'Alexopopo',
        //       'nachname' => 'ONEPLUS',
        //       'email' => 'info@1plus-agency.com',
        //       'mobilenumber' => '01645854290',
        //     ),
        //     'available_termine' =>
        //     array (
        //       'salonId' => '25',
        //       'termineDate' => '2020-02-10',
        //       'termineTime' => '9:45 AM',
        //       'dienstleistungId' => '4',
        //       'dienstleistungName' => 'Schnitt + Finish',
        //       'dienstleistungDescription' => '• Kompetente Beratung mit mehreren Vorschlägen
        //   • Individuell auf ihre Haarstruktur abgestimmter Haarschnitt
        //   • Professionelles Finish mit Rundbürste, Lockenstab oder Glätteisen',
        //       'wochenTagFertig' => 'понеділок 10 лютий 2020',
        //       'mitarbeiterId' => '912',
        //       'mitarbeiterName' => 'Lisa Täubl',
        //       'mitarbeiterFile' => 'http://localhost/f3-url-shortener/assets/images/employeeimage/emp_image.jpg',
        //       'salonAddress' => 'Elisabethenstr. 8, Darmstadt ',
        //     ),
        //     'pagecon_appointment_reconnect_id' => 'true',
        //   )

        //http://localhost/f3-url-shortener/json/local/prices_allprices.json
        // $this->f3->route('GET|POST /@controller/@action/@id', '\@controller->@action');

        // if ( $this->f3->exists( 'PARAMS.id' ) ) {
        //     $mode = (string) $this->f3->get( 'PARAMS.id' );
        //     // $this->f3->set( 'name', $mode );
        // }

        // https://blooms.developservice.de/modules/mod_terminfinder/tmpl/pdf.php?view=print&s=25&vn=Alex&nn=ONEPLUS&str=&plz=&ort=&mobil=&email=info@1plus-agency.com&d=2020-02-06&t=10:00%20AM&m=912&dl=2&v=false

        $pdf_image_dir = ONEPLUS_DIR_PATH . '/assets/images/pdf/';

        $mode = "print";

        // 'print' 'download'
        if ($mode) {
            // setlocale(LC_TIME, "de_DE");
            // $user_data_tab_three = bloomArrayHelper::getValueJoom($pdf_sess, 'user_data_tab_three', null, 'ARRAY');
            // $available_termine = bloomArrayHelper::getValueJoom($pdf_sess, 'available_termine', null, 'ARRAY');

            //available_termine
            // $salonId = bloomArrayHelper::getValueJoom($available_termine, 'salonId', null, 'INT');

            // salons Controller
            // $salons_ctrl = new salons();
            // $salon = $salons_ctrl->getSalonsController($salonId);
            // array (
            //     'Address' => 'Elisabethenstr. 8, Darmstadt ',
            //     'CategoryDisplayName' => '',
            //     'Description' => 'Großer Citysalon mitten in der Innenstadt gegenüber Saturn.
            //   Wir freuen uns auf Sie!',
            //     'DisplayName' => 'Darmstadt, Elisabethenstraße',
            //     'GooglePlacesUrl' => 'https://goo.gl/maps/7QEBG2grQT31xwsN6',
            //     'Id' => 25,
            //     'ImageUrl' => 'salonbild_da1.jpg',
            //     'Name' => 'Darmstadt 1',
            //     'OnlineAppointmentsEnabled' => true,
            //     'OpeningHours' => 'Montag - Mittwoch: 10:00 - 19:00 Uhr
            //   Donnerstag: 10:00 - 20:00 Uhr
            //   Freitag: 9:00 - 20:30 Uhr
            //   Samstag: 9:00 - 18:00 Uhr ',
            //     'Phone' => '06151 5044480',
            //   )

            date_default_timezone_set('Europe/Berlin');
            $loc = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');

            $mitarbeiterId = ''; //bloomArrayHelper::getValueJoom($available_termine, 'mitarbeiterId', null, 'INT');
            $datum = ''; //bloomArrayHelper::getValueJoom($available_termine, 'termineDate', null, 'STRING');
            $zeit = ''; //bloomArrayHelper::getValueJoom($available_termine, 'termineTime', null, 'STRING');
            // $wochenTagFertig = bloomArrayHelper::getValueJoom($available_termine, 'wochenTagFertig', null, 'STRING');
            $wochenTagFertig = strftime('%A %d %B %Y', strtotime($datum));

            $dienstleistungId = bloomArrayHelper::getValueJoom($available_termine, 'dienstleistungId', null, 'INT');
            $dienstleistungName = 'Service name'; //bloomArrayHelper::getValueJoom($available_termine, 'dienstleistungName', null, 'STRING');
            $salonAddress = bloomArrayHelper::getValueJoom($available_termine, 'salonAddress', null, 'STRING');
            $mitarbeiterFile = bloomArrayHelper::getValueJoom($available_termine, 'mitarbeiterFile', null, 'STRING');
            $mitarbeiterName = bloomArrayHelper::getValueJoom($available_termine, 'mitarbeiterName', null, 'STRING');

            // user_data_tab_three
            $vertretung = 'vertretung tex'; //bloomArrayHelper::getValueJoom($user_data_tab_three, 'vertretung', false, 'STRING');

            $employee = $vertretung ? true : false;

            if ($employee == 'true') {
                $vertretung = 'Einen neuen Termin vereinbaren';
            } else {
                $vertretung = 'Vertretung durch anderen Kollegen';
            }

            $mobilenumber = '+91 80000 78982'; //bloomArrayHelper::getValueJoom( $user_data_tab_three, 'mobilenumber', null, 'STRING' );
            $email = 'ashtekarmukesh@gmail.com'; //bloomArrayHelper::getValueJoom( $user_data_tab_three, 'email', null, 'STRING' );
            $vorname = 'Mukesh'; //bloomArrayHelper::getValueJoom( $user_data_tab_three, 'vorname', null, 'STRING' );
            $nachname = 'Ashtekar'; //bloomArrayHelper::getValueJoom( $user_data_tab_three, 'nachname', null, 'STRING' );
            $name = $vorname . ' ' . $nachname;


            if (!class_exists('FPDF')) {
                require ONEPLUS_DIR_PATH . '/app/helper/fpdf/fpdf.php';
                require ONEPLUS_DIR_PATH . '/app/helper/fpdi/fpdi.php';
            }

            $pdf = new FPDF();
            $pdf->AddPage();


            $h1 = 18;
            $h2 = 16;
            $h3 = 14;
            $h4 = 12;
            $h5 = 10;
            $h6 = 8;
            $h7 = 6;

            $indent = 55;

            // $pdf->SetFont('Arial', '', 18);
            // x,y,w,h
            $this->addImageInPdf($pdf, 50, 20, 107.16, 30, $pdf_image_dir . 'Logo bloom\'s weiss.jpg');

            $pdf->SetFont('Arial', 'B', $h1);
            $pdf->SetXY(50, 60);
            $pdf->Cell(0, 0, utf8_decode('Verbindliche Terminbestätigung'));

            // Personal Data
            $pdf->SetFont('Arial', 'B', $h3);
            $pdf->SetXY($indent, 80);
            $pdf->Cell(0, 0, utf8_decode('Persönliche Daten:'));

            $pdf->SetFont('Arial', '', $h5);
            $pdf->SetXY($indent, 87);
            $pdf->write(1, utf8_decode('Name: Adrian Sangeorgean'));

            $pdf->SetXY($indent, 92);
            $pdf->write(1, 'Email: adrian@1plus-agency.com');
            // $pdf->Cell(0, 0, utf8_decode('Email: '));
            // $pdf->SetXY(80, 92);
            // $pdf->SetFont('Arial', '', $h5);
            // $pdf->SetFont('', 'U');
            // $pdf->SetTextColor(163, 163, 163);
            // $pdf->write(1, utf8_decode('adrian@1plus-agency.com'), 'mailto:adrian@1plus-agency.com');

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', $h5);

            $pdf->SetXY($indent, 97);
            $pdf->Cell(0, 0, utf8_decode('Telefon: +491703225110'));

            // Mein Termin
            $pdf->SetFont('Arial', 'B', $h3);
            $pdf->SetXY($indent, 107);
            $pdf->Cell(0, 0, utf8_decode('Mein Termin:'));

            $pdf->SetFont('Arial', '', $h5);

            $pdf->SetXY($indent, 114);
            $pdf->Cell(0, 0, utf8_decode('Was: Ponyservice'));

            $pdf->SetXY($indent, 119);
            $pdf->Cell(0, 0, utf8_decode('Wann: Saturday 10 June 2023 08:00'));

            $pdf->SetXY($indent, 124);
            $pdf->Cell(0, 0, utf8_decode('Wo: Ponyservice'));

            $pdf->SetXY($indent, 129);
            $pdf->Cell(0, 0, utf8_decode('Wer: Jessica'));

            $pdf->SetXY($indent, 138);
            $pdf->Cell(0, 0, utf8_decode('Salon-Telefon: 065151 5044480'));

            $path = ONEPLUS_DIR_PATH . '/upload/employeeimageadmin/1257_paulina_rolka_1610699259.jpg';
            $this->addImageInPdf($pdf, $indent + 1, 143, 27, 40, $path);

            $pdf->SetFont('Arial', '', $h5);
            $pdf->SetXY($indent, 190);
            $pdf->Cell(0, 0, utf8_decode('Bei Ausfall des gewählten Mitarbeiters:'));

            $pdf->SetXY($indent, 195);
            $pdf->Cell(0, 0, utf8_decode($vertretung));



            $this->addImageInPdf($pdf, $indent, 207, 20, 16, $pdf_image_dir . 'buttons-Kartenzahlung_sw1_final.jpg');
            $this->addImageInPdf($pdf, $indent + 2, 227, 16, 16, $pdf_image_dir . 'Buttons-Storno_sw1_final.jpg');


            $pdf->SetFont('Arial', '', $h5);
            $pdf->SetXY($indent + 25, 213);
            $pdf->Cell(0, 0, utf8_decode('Bitte beachten Sie, dass Sie vor Ort nur mit Karte bezahlen können.'));

            $pdf->SetXY($indent + 25, 218);
            // $pdf->Cell(0, 0, utf8_decode('Bei Fragen zum bargeldlosem Bezahlen klicken Sie'));
            $pdf->write(1, utf8_decode('Bei Fragen zum bargeldlosem Bezahlen klicken Sie '));
            $pdf->SetTextColor(163, 163, 163);
            $pdf->SetFont('', 'U');
            $pdf->write(1, 'HIER', 'https://bargeldlos.bloom-s.de/');

            $pdf->SetFont('Arial', '', $h5);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetXY($indent + 25, 232);
            $pdf->Cell(0, 0, utf8_decode('Sie möchten einen gebuchten Termin absagen?'));

            $pdf->SetXY($indent + 25, 237);
            // $pdf->Cell(0, 0, utf8_decode('Bitte im Salon anrufen oder klicken Sie'));
            $pdf->write(1, utf8_decode('Bitte im Salon anrufen oder klicken Sie '));
            $pdf->SetTextColor(163, 163, 163);
            $pdf->SetFont('', 'U');
            $pdf->write(1, 'HIER', 'https://bloom-s.de/abmeldung.html');

            $pdf->SetFont('Arial', '', $h5);
            $pdf->SetTextColor(0, 0, 0);


            $pdf->SetFont('Arial', '', $h6);
            $pdf->SetXY($indent + 25, 245);
            $pdf->Cell(0, 0, utf8_decode('Bitte haben Sie Verständnis dafür, dass Termine nur bis 24 Stunden vor'));
            $pdf->SetXY($indent + 25, 248);
            // $pdf->Cell(0, 0, utf8_decode('Terminbeginn kostenfrei storniert werden können. AGBs Terminvergabe.'));
            $pdf->write(1, utf8_decode('Terminbeginn kostenfrei storniert werden können. '));
            $pdf->SetTextColor(163, 163, 163);
            $pdf->SetFont('', 'U');
            $pdf->write(1, 'AGBs', 'https://bloom-s.de/termineagb.html');

            $pdf->SetFont('Arial', '', $h6);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->write(1, ' Terminvergabe.');


            // AGBs .


            $pdf->SetFont('Arial', 'B', $h6);
            $pdf->SetXY($indent, 270);

            $pdf->SetTextColor(128, 128, 128);
            $pdf->write(1, utf8_decode('Wenn Sie Hilfe benötigen, schreiben sie bitte an unseren Kundenservice'));

            $pdf->SetXY($indent, 273);
            $pdf->write(1, utf8_decode('Email: '));
            $pdf->SetTextColor(163, 163, 163);
            $pdf->SetFont('', 'U');
            $pdf->write(1, utf8_decode('kundenservice@bloom-s.de'), 'mailto:kundenservice@bloom-s.de');

            $pdf->SetFont('Arial', 'B', $h6);
            $pdf->SetTextColor(128, 128, 128);
            $pdf->write(1, utf8_decode(' Internet: '));

            $pdf->SetFont('', 'U');
            $pdf->SetTextColor(163, 163, 163);
            $pdf->write(1, utf8_decode('www.bloom-s.de '), 'https://www.bloom-s.de');
            $pdf->SetTextColor(128, 128, 128);

            $pdf->SetFont('Arial', 'B', $h6);
            $pdf->write(1, ' | ');
            $pdf->SetFont('', 'U');
            $pdf->SetTextColor(163, 163, 163);
            $pdf->write(1, utf8_decode('Impressum'), 'https://bloom-s.de/impressum.html');

            // $pdf->Cell(0, 0, utf8_decode('Email :  Internet : www.bloom-s.de | Impressum'));

            //

            if ($mode == 'print') {
                $pdf->Output();
            } else {
                $pdf->Output('blooms_termin.pdf', 'D');
            }

            // $pdf->closeParsers();
            $pdf->close();
        }
        // $arr  = file_get_contents($page_host.$base'/helper/json/prices_allprices.json');
        //http://localhost/f3-url-shortener/helper/json/prices_allprices.json
    }


    function addImageInPdf(&$pdf, $x, $y, $h, $w, $imagePath)
    {

        $mime = helperblooms::get_image_mime($imagePath);

        switch ($mime) {
            case 'image/jpeg':
                $image_format = 'jpg';
                break;
            case 'image/gif':
                $image_format = 'gif';
                break;
            case 'image/png':
                $image_format = 'png';
                break;
            default:
                $image_format = false;
        }

        // die($mime);
        $pdf->Image($imagePath, $x, $y, $h, $w, $image_format);
    }
}