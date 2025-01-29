<?php
// namespace Salons;
class abmeldung extends Controller
{
    /**
     * @var array
     */
    public $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    public $SaloonManagers = array();

    public function __construct()
    {
        parent::__construct();
        $salonfinder = new salonsModel($this->db);
        $salons_model = json_decode($salonfinder->getSalons());
        $SalonManagerArray = array();
        foreach ($salons_model as $key => $salanObj) {

            $this->SaloonManagers[$salanObj->Id] = array("salon_supervisor_email" => $salanObj->EmailAddressSalonSupervisor, "saloon_display_name" => $salanObj->DisplayName);
        }
        // print_r( $this->SaloonManagers);

    }


    protected static $services = null;




    /**
     * @param $id
     * @return mixed
     */
    protected function getCasheSalonTeam($id = null, $redirect = false)
    {
        $id = intval($id);
        $this->db = null;

        $salonfinder = new salonsModel($this->db);
        $salon_team_arr = (array) $salonfinder->getCasheSalonTeam($id, $redirect);

        if (!empty($salon_team_arr)) {
            return $salon_team_arr;
        } else {
            return [];
        }
        return [];
    }

    /**
     * @return mixed
     */
    public function getSalonTeamController($id = null)
    {
        return $this->getCasheSalonTeam($id);
    }

    /**
     * @return mixed
     */
    public function getSalonsController($id = null)
    {
        return $this->getCasheSalons($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function getCasheSalons($id = null)
    {
        $this->db = null;

        //    save salons to cashe 16.01.2020 18:02
        $cacheblooms = $this->f3->get('CacheBlooms');
        $code = 'SalonsXML' . $id;

        $result = $cacheblooms->retrieve($code, true);
        if ($result) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $salons_array = json_decode($result, true);
        } else {
            $salonfinder = new salonsModel($this->db);
            $salons_model = $salonfinder->getSalons();

            // helperblooms::jsJson - check  If $value is a JSON encoded object or array it will be decoded
            if ($salons_array = helperblooms::jsJson($salons_model, true)) {
                $salons_array = helperblooms::parseXmlToArraysalons($salons_array, 'Id');
                /* @FIX by oppo , @Date: 2020-04-28 11:25:31
                 * @@Desc: fix cacheblooms - 5 min | constant in index - TIME_CACHEBLOOMS
                 */
                $cacheblooms->store($code, json_encode($salons_array, true), TIME_CACHEBLOOMS); // 3600 => 'hour',
            } else {
                // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
                $this->f3->set('SESSION.error', 'Salon existiert nicht');
                // $this->f3->reroute('/salons.html');
                $this->f3->reroute('/');
            }
        }

        $id = intval($id);
        if ($id > 0) {
            return $salons_array[$id];
        }
        return $salons_array;
    }

    public function index()
    {
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Storno');

        // ADD JS
        $addscripts = ['js/layout/validator.min.js', 'js/layout/abmeldung.js'];
        $this->f3->set('addscripts', $addscripts);
        // add Styles
        $addstyles = ['css/animate.css'];
        $this->f3->set('addstyles', $addstyles);
        $termine = new termine();
        $option_salon = $termine->select_salon(null, 'Salon*');



        $this->f3->set('OPTION_SALON', $option_salon);

        // $adminObject= new kontakt();
        // $adminInfo = $adminObject->adminData();
        // $office = $adminObject->getEmailById(24);


        // $this->f3->set( 'ADMININFO', $adminInfo );
        // $this->f3->set( 'STAFFINFO', $office );

        $this->f3->set('view', 'abmeldung.html');
        $this->f3->set('classfoot', 'reklamation');


        $html = "<p>Sie möchten einen gebuchten Termin absagen? Kein Problem! <br>
        Bitte einfach Formular ausfüllen und absenden. Vielen Dank! </p>";
        $this->f3->set('CONTENT', $html);
        $this->f3->set('ESCAPE', false);


    }



    /**
     * @param $s
     */
    public function clear_text($s)
    {
        $do = true;
        while ($do) {
            $start = stripos($s, '<script');
            $stop = stripos($s, '</script>');
            if ((is_numeric($start)) && (is_numeric($stop))) {
                $s = substr($s, 0, $start) . substr($s, ($stop + strlen('</script>')));
            } else {
                $do = false;
            }
        }
        return trim($s);
    }

    public function minifyHTML($html) {
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags
            '/[^\S ]+\</s',  // strip whitespaces before tags
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array('>', '<', '\\1');

        $minified = preg_replace($search, $replace, $html);

        return $minified;
    }


    function sendinfo()
    {


//            $Body = file_get_contents(ONEPLUS_DIR_PATH_APP . 'emails' . DIRECTORY_SEPARATOR . 'storno.html');

        $Body = <<<BODY
<html>
<head>
    <title>bloom's - Stornierungsbestätigung</title>
</head>
<body>
    
  <body
    bgcolor="#FFFFFF"
    style="
      font-family: Arial, Helvetica, sans-serif;
      padding: 0;
      margin: 0;
      background-color: #ffffff;
    "
  >
    <center>
      <table
        style="padding: 0; margin: 0"
        border="0"
        cellpadding="0"
        cellspacing="0"
        width="580"
      >
        <tr bgcolor="#FFFFFF">
          <td>
            <img alt="" />
            <img
              src="data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAAHAP8ALAAAAAABAAEAAAICRAEAOw=="
              alt=""
              width="1"
              height="10"
            />
          </td>
        </tr>

        <tr bgcolor="#FFFFFF">
          <td>
            <img
              src="data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAAHAP8ALAAAAAABAAEAAAICRAEAOw=="
              alt=""
              width="1"
              height="10"
            />
          </td>
        </tr>
      </table>

      <table
        style="font-size: 0"
        style="padding: 0; margin: 0"
        border="0"
        cellpadding="0"
        cellspacing="0"
        width="580"
      >
        <tr>
          <td align="center">
            <img
              src="https://bloom-s.de/assets/images/pdf/blooms_logo_pdf_cropped.jpg"
              alt="bloom's"
            />
          </td>
        </tr>

        <tr>
          <td align="center">
            <font
              style="
                font-size: 20px;
                color: #000000;
                font-family: Arial, Helvetica, sans-serif;
                font-weight: bold;
              "
              color="#000000"
              size="5"
            >
              <span
                style="
                  font-family: Arial, Helvetica, sans-serif;
                  font-weight: bold;
                  font-size: 20px;
                  color: #000000;
                "
              >
                Verbindliche Stornierungsbestätigung
              </span>
            </font>
          </td>
        </tr>
      </table>

      <br/><br/>
      <table>

        <tr>
          <td>Name: </td>
          <td><!--<<NAME>>--> </td>
        </tr>

        <tr>
          <td>E-Mail: </td>
          <td><!--<<EMAIL>>--> </td>
        </tr>

        <tr>
          <td>Telefon: </td>
          <td><!--<<TELEFON>>--> </td>
        </tr>
        <tr>
          <td>Zurückrufen: </td>
          <td><!--<<ZURUCKFRUFEN>>--> </td>
        </tr>
        <tr>
          <td>Salon: </td>
          <td><!--<<SALON>>--> </td>
        </tr>
        <tr>
          <td>Datum: </td>
          <td><!--<<DATUM>>--> </td>
        </tr>
        <tr>
          <td>Uhrzeit: </td>
          <td><!--<<UHRZEIT>>--> </td>
        </tr>
        <tr>
          <td>Auswahl: </td>
          <td><!--<<REASON>>--> </td>
        </tr>


      </table>
      <br/><br/>


      <!-- Footer -->
      <table >
        <tr >
          <td>
            <img
                    src="data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAAHAP8ALAAAAAABAAEAAAICRAEAOw=="
                    alt=""
                    width="1"
                    height="50"
            />
          </td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td align="center" bgcolor="#FFFFFF" style="font-family: Arial, Helvetica, sans-serif" >
            <font style=" font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #868179;" color="#868179" size="2" >
              <span style=" font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #868179; " >
                Wenn Sie Hilfe benötigen, schreiben Sie bitte an unseren
                Kundenservice. <br />
                E-Mail: <a href="mailto:kundenservice@bloom-s.de">kundenservice@bloom-s.de</a> Internet:<a href="http://bloom-s.de" target="_blank" >www.bloom-s.de</a > | <a href="http://bloom-s.de/impressum" target="_blank" >Impressum</a>
              </span>
            </font>
          </td>
        </tr>


      </table>

    </center>
</body>
</html>
BODY;


        $name =  isset( $_POST['name'] )  ? $_POST['name'] : '';
        $phone =  isset( $_POST['telefon'] )  ? $_POST['telefon'] : '';
        $iso_date = isset( $_POST['iso_date'] )  ? $_POST['iso_date'] : '';
        $datum  = isset( $_POST['date'] )  ? $_POST['date'] : '';
        $datum_time = isset( $_POST['uhrzeit'] )  ? $_POST['uhrzeit'] : '';
        $reason = isset( $_POST['option_reason'] )  ? $_POST['option_reason'] : '';
        $salon = isset( $_POST['option_salon'] )  ? $_POST['option_salon'] : '';
        $emailstaff = isset( $_POST['emailstaff'] )  ? $_POST['emailstaff'] : '';

        $errorMSG = "";
        $message = '';
        $saloon_display_name = "";
        $tosaloonmanager = "";

        // NAME
        if ( empty($name)) { $errorMSG .= "Name wird ben&#246;tigt "; }
        // EMAIL
        if (empty($emailstaff)) { $errorMSG .= "E-Mail wird ben&#246;tigt "; }
        // PHONE
        if (empty($phone)) { $errorMSG .= "Telefon wird ben&#246;tigt "; }


        // Date
        if (empty($iso_date)) { $errorMSG .= "Datum wird ben&#246;tigt "; } else { $datum = date("d.m.Y", strtotime($iso_date)); }
        // Time
        if (empty($datum_time)) { $errorMSG .= "Uhrzeit wird ben&#246;tigt "; }


        if (empty($salon)) {
            $errorMSG .= "Salon wird ben&#246;tigt ";
        } else {
            $idSallon = $_POST["option_salon"];
            $tosaloonmanagerData = $this->SaloonManagers[$idSallon];
            $tosaloonmanager = $tosaloonmanagerData["salon_supervisor_email"];
            $saloon_display_name = $tosaloonmanagerData["saloon_display_name"];
        }


        if (empty($reason)) {
            $errorMSG .= "Bitte Grund auswählen";
        }

        $isCallback = (isset($_POST['callback']) && $_POST['callback'] == 'on') ? 'Ja' : 'Nein';

        $to = 'kundenservice@bloom-s.de';
//        $to = 'maksixty9@gmail.com';
//        $to = 'adrian@1plus-agency.com';


        if ($to == "") {
            $errorMSG .= "E-Mail Adresse pr&#252;fen";
        }

        if ($errorMSG != "") {
            echo '<div class="alert alert-danger rounded-0" role="alert">' . $errorMSG . '</div>';
        }
        else {

            $Body = str_ireplace('<!--<<NAME>>-->',$name, $Body);
            $Body = str_ireplace('<!--<<EMAIL>>-->', $emailstaff , $Body);
            $Body = str_ireplace('<!--<<TELEFON>>-->', $phone, $Body);
            $Body = str_replace('<!--<<ZURUCKFRUFEN>>-->', $isCallback , $Body);
            $Body = str_replace('<!--<<SALON>>-->', $saloon_display_name, $Body);
            $Body = str_replace('<!--<<DATUM>>-->', $datum, $Body);
            $Body = str_replace('<!--<<UHRZEIT>>-->', $datum_time, $Body);
            $Body = str_replace('<!--<<REASON>>-->', $reason, $Body);


            $replacements = array(
                'Ä' => '&Auml;',
                'ä' => '&auml;',
                'Ö' => '&Ouml;',
                'ö' => '&ouml;',
                'Ü' => '&Uuml;',
                'ü' => '&uuml;',
                'ß' => '&szlig;'
            );

            $Body = str_replace(array_keys($replacements), array_values($replacements), $Body);

            $status = $this->mail_attachmentinfo( $to, 'Terminstornierung Form', 'info@developservice.de', "bloom's", 'no-reply@developservice.de', $Body, $emailstaff);
//            $status2 = $this->mail_attachmentinfo( $emailstaff, 'Terminstornierung Form', 'info@developservice.de', "bloom's", 'no-reply@developservice.de', $Body);


            if ($status) {

                echo '<p> Vielen Dank für Ihre Nachricht. </p>
                <p class="pt-2" >Der Termin am: ' . $datum . ' um ' . $datum_time . ' Uhr wird storniert. </p>
				<p class="pt-2">  </p>';
            } else {
                echo 'Die Nachricht wurde erfolgreich versendet!';
            }

        }

        die();
    }


    function sendinfotest()
    {

        $uploaddir = ONEPLUS_DIR_PATH . '/upload/kontakt/';
        $errorMSG = "";
        $name = 'Mukesh Ashtekar Stornierungsbestätigung';
        $phone = '08000078982';
        $emailstaff = 'ashtekarmukesh@gmail.com';
        $isCallback = false;
        $datum = 'Freitag, 24. November 2023';
        $datum_time = '12:30';
        $reason = "No Reason Stornierungsbestätigung";
        $message = '';

        $saloon_display_name = "Karlsruhe, Karlstraße 29";
        $tosaloonmanager = "";

//        $to = 'kundenservice@bloom-s.de';
         $to = 'adrian@1plus-agency.com' ;


        if ($to == "") {
            $errorMSG .= "E-Mail Adresse pr&#252;fen";
        }

        if ($errorMSG != "") {
            echo '<div class="alert alert-danger rounded-0" role="alert">' . $errorMSG . '</div>';
        }
        else {

//            $Body = file_get_contents(ONEPLUS_DIR_PATH_APP . 'emails' . DIRECTORY_SEPARATOR . 'storno.html');

            $Body = <<<BODY
<html>
  <head>
    <meta name="format-detection" content="telephone=no" />
    <title>bloom's - Stornierungsbestätigung</title>

    <style>
      * {
        font-family: Arial, Helvetica, sans-serif !important;
      }

      img {
        border: 0;
      }

      a {
        color: #b4aea3;
      }

      body{
        background: #ffffff;
      }

    </style>
  </head>

  <body
    bgcolor="#FFFFFF"
    style="
      font-family: Arial, Helvetica, sans-serif;
      padding: 0;
      margin: 0;
      background-color: #ffffff;
    "
  >
    <center>
      <table
        style="padding: 0; margin: 0"
        border="0"
        cellpadding="0"
        cellspacing="0"
        width="580"
      >
        <tr bgcolor="#FFFFFF">
          <td>
            <img alt="" />
            <img
              src="data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAAHAP8ALAAAAAABAAEAAAICRAEAOw=="
              alt=""
              width="1"
              height="10"
            />
          </td>
        </tr>

        <tr bgcolor="#FFFFFF">
          <td>
            <img
              src="data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAAHAP8ALAAAAAABAAEAAAICRAEAOw=="
              alt=""
              width="1"
              height="10"
            />
          </td>
        </tr>
      </table>

      <table
        style="font-size: 0"
        style="padding: 0; margin: 0"
        border="0"
        cellpadding="0"
        cellspacing="0"
        width="580"
      >
        <tr>
          <td align="center">
            <img
              src="https://bloom-s.de/assets/images/pdf/blooms_logo_pdf_cropped.jpg"
              alt="bloom's"
            />
          </td>
        </tr>

        <tr>
          <td align="center">
            <font
              style="
                font-size: 20px;
                color: #000000;
                font-family: Arial, Helvetica, sans-serif;
                font-weight: bold;
              "
              color="#000000"
              size="5"
            >
              <span
                style="
                  font-family: Arial, Helvetica, sans-serif;
                  font-weight: bold;
                  font-size: 20px;
                  color: #000000;
                "
              >
                Verbindliche Stornierungsbestätigung
              </span>
            </font>
          </td>
        </tr>
      </table>

      <br/><br/>
      <table>

        <tr>
          <td>Name: </td>
          <td>##NAME## </td>
        </tr>

        <tr>
          <td>E-Mail: </td>
          <td>##EMAIL## </td>
        </tr>

        <tr>
          <td>Telefon: </td>
          <td>##TELEFON## </td>
        </tr>
        <tr>
          <td>Zurückrufen: </td>
          <td>##ZURUCKFRUFEN## </td>
        </tr>
        <tr>
          <td>Salon: </td>
          <td>##SALON## </td>
        </tr>
        <tr>
          <td>Datum: </td>
          <td>##DATUM## </td>
        </tr>
        <tr>
          <td>Uhrzeit: </td>
          <td>##UHRZEIT## </td>
        </tr>
        <tr>
          <td>Auswahl: </td>
          <td>##REASON## </td>
        </tr>


      </table>
      <br/><br/>


      <!-- Footer -->
      <table >
        <tr >
          <td>
            <img
                    src="data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAAHAP8ALAAAAAABAAEAAAICRAEAOw=="
                    alt=""
                    width="1"
                    height="50"
            />
          </td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td align="center" bgcolor="#FFFFFF" style="font-family: Arial, Helvetica, sans-serif" >
            <font style=" font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #868179;" color="#868179" size="2" >
              <span style=" font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #868179; " >
                Wenn Sie Hilfe benötigen, schreiben Sie bitte an unseren
                Kundenservice. <br />
                E-Mail: <a href="mailto:kundenservice@bloom-s.de">kundenservice@bloom-s.de</a> Internet:<a href="http://bloom-s.de" target="_blank" >www.bloom-s.de</a > | <a href="http://bloom-s.de/impressum" target="_blank" >Impressum</a>
              </span>
            </font>
          </td>
        </tr>


      </table>

    </center>
  </body>
</html>

BODY;


            $Body = str_ireplace('##NAME##', $name, $Body);
            $Body = str_ireplace('##EMAIL##', $emailstaff, $Body);
            $Body = str_ireplace('##TELEFON##', $phone, $Body);
            $Body = str_replace('##ZURUCKFRUFEN##', $isCallback ? "Ja" : "Nein", $Body);
            $Body = str_replace('##SALON##', $saloon_display_name, $Body);
            $Body = str_replace('##DATUM##', $datum, $Body);
            $Body = str_replace('##UHRZEIT##', $datum_time, $Body);
            $Body = str_replace('##REASON##', $reason, $Body);


//            $Body = "<h2>Hello Testing message here</h2>";
            $status = $this->mail_attachmentinfo( $to, 'Terminstornierung Form', 'info@developservice.de', "bloom's", 'no-reply@developservice.de', $Body);

            if ($status) {

                echo '<p> Vielen Dank für Ihre Nachricht. </p>
                <p class="pt-2" >Der Termin am: ' . $datum . ' um ' . $datum_time . ' Uhr wird storniert. </p>
                <p class="pt-2">Eine Bestätigung wurde Ihnen per Mail zugesendet.</p>';
            } else {
                echo 'Bitter Nachricht wurde erfolgreich versendet!';
            }

        }
        die();

    }



    function mail_attachmentinfo( $mailto = '', $subject = '', $from_mail = '', $from_name = '', $replyto = 'no-reply@developservice.de', $body = '', $bcc = '')
    {
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/Exception.php';
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/PHPMailer.php';
        require ONEPLUS_DIR_PATH . '/app/libraries/PHPMailer/SMTP.php';

        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(null, true);


//            mail: smtp@e-scooter-roller.de
//            pw: fsF8pZkrGo2k8AG8
//            smpt server: mail.your-server.de
//            port: SSL: 465

            $mail->isSMTP();
            $mail->Host       = 'mail.your-server.de';                    // Specify the SMTP server
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'smtp@e-scooter-roller.de';           // SMTP username
            $mail->Password   = 't57jAe2ZIeh0y27q';                       // SMTP password
//            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 25;

//            var_dump($mail);die;

            $mail->isHTML(true);
//            $mail->setLanguage('de');
            $mail->CharSet = 'UTF-8';
//            $mail->Encoding = 'base64';  // Set encoding
            $mail->ContentType = 'text/html';  // Set content type

            $mail->ClearReplyTos();
            $mail->AddReplyTo($replyto, '');
            if ($bcc != ''){
                $mail->addBCC($bcc);
            }

            $mail->setFrom('kontakt@bloom-s.de', $from_name);
            $mail->addAddress($mailto);
            $mail->Subject = "Terminstornierung";
            $mail->Body = $body;

//             var_dump($mail);die;

            return $mail->send();
        } catch (Exception $e) {

            die($e->getMessage());
            return 0;
            $logger = new \Log($this->f3->get('LOGS') . date("d.m.Y") . 'mail_error.log');
            $logger->write("Mail error: " . $mail->ErrorInfo, 'r');
        }
    }


}
