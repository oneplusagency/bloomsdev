<?php
// namespace Salons;
class abmeldung extends Controller
{
    /**
     * @var array
     */
    public $tokens = array(
        31536000 => 'year',
        2592000  => 'month',
        604800   => 'week',
        86400    => 'day',
        86400    => 'day',
        3600     => 'hour',
        60       => 'minute',
        1        => 'second'
    );
   public $SaloonManagers=array(); 

    public function __construct()
    {
        parent::__construct();
        $salonfinder  = new salonsModel($this->db);
        $salons_model =json_decode($salonfinder->getSalons());
        $SalonManagerArray=array();
        foreach($salons_model as $key=>$salanObj){
            
            $this->SaloonManagers[$salanObj->Id]=array("salon_supervisor_email"=>$salanObj->EmailAddressSalonSupervisor,"saloon_display_name"=>$salanObj->DisplayName);
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
        $id       = intval($id);
        $this->db = null;

        $salonfinder    = new salonsModel($this->db);
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
        $code        = 'SalonsXML' . $id;        

        $result = $cacheblooms->retrieve($code, true);
        if ($result) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $salons_array = json_decode($result, true);
        } else {
            $salonfinder  = new salonsModel($this->db);
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
        $addscripts = ['js/layout/validator.min.js','js/layout/abmeldung.js'];
        $this->f3->set('addscripts', $addscripts);
        // add Styles
        $addstyles = ['css/animate.css'];
           $this->f3->set('addstyles', $addstyles);
        $termine = new termine();
        $option_salon = $termine->select_salon(null,'Salon*');

       
        
        $this->f3->set('OPTION_SALON', $option_salon);

        // $adminObject= new kontakt();
        // $adminInfo = $adminObject->adminData();
        // $office = $adminObject->getEmailById(24);

        
        // $this->f3->set( 'ADMININFO', $adminInfo );
        // $this->f3->set( 'STAFFINFO', $office );

        $this->f3->set('view', 'abmeldung.html');
        $this->f3->set('classfoot', 'reklamation');

    
        $html ="<p>Sie möchten einen gebuchten Termin absagen? Kein Problem! <br>
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
            $stop  = stripos($s, '</script>');
            if ((is_numeric($start)) && (is_numeric($stop))) {
                $s = substr($s, 0, $start) . substr($s, ($stop + strlen('</script>')));
            } else {
                $do = false;
            }
        }
        return trim($s);
    }



    function sendinfo(){        
        $filename='';
        $fullPath='';
        $uploaddir = ONEPLUS_DIR_PATH.'/upload/kontakt/';
        $id=0;
        $to ='';
        $errorMSG = "";
        $name='';
        $phone='';
        $message='';
        $captcha;        
        $saloon_display_name="";
        $tosaloonmanager="";

        // NAME
        if (empty($_POST["name"])) {
            $errorMSG .= "Name wird ben&#246;tigt ";
        } else {
            $name = $_POST["name"];
        }
        // EMAIL
        if (empty($_POST["emailstaff"])) {
            $errorMSG .= "E-Mail wird ben&#246;tigt ";
        } else {
            $emailstaff = $_POST["emailstaff"];
        }


        // EMAIL
        if (empty($_POST["telefon"])) {
            $errorMSG .= "Telefon wird ben&#246;tigt ";
        } else {
            $phone = $_POST["telefon"];
        }


        // Date
        if (empty($_POST["iso_date"])) {
            $errorMSG .= "Datum wird ben&#246;tigt ";
        } else {
            $datum = date("d.m.Y", strtotime(  $_POST["iso_date"] ));
        }
        
        // Time
        if (empty($_POST["uhrzeit"])) {
            $errorMSG .= "Uhrzeit wird ben&#246;tigt ";
        } else {
            $datum_time = $_POST["uhrzeit"];
        }


        if (empty($_POST["option_salon"])) {
            $errorMSG .= "Salon wird ben&#246;tigt ";
        } else {
            $idSallon = $_POST["option_salon"];
            $tosaloonmanagerData=$this->SaloonManagers[$idSallon];
            $tosaloonmanager=$tosaloonmanagerData["salon_supervisor_email"];
            $saloon_display_name=$tosaloonmanagerData["saloon_display_name"];

        }

        $isCallback = (isset($_POST['callback']) && $_POST['callback'] == 'on') ? true : false;

        $to = 'kundenservice@bloom-s.de' ;
        // $to = 'ashtekarmukesh@gmail.com' ;


        if($to == ""){
            $errorMSG .= "E-Mail Adresse pr&#252;fen";
        }
      
        if($errorMSG !=""){
            echo '<div class="alert alert-danger rounded-0" role="alert">'.$errorMSG.'</div>';
        }else{
           
            $Body = file_get_contents(ONEPLUS_DIR_PATH_APP.'emails'.DIRECTORY_SEPARATOR.'storno.html');

            $Body = str_replace('{{$NAME}}', $name,$Body);
            $Body = str_replace('{{$EMAIL}}', $emailstaff,$Body);
            $Body = str_replace('{{$TELEFON}}', $phone,$Body);
            $Body = str_replace('{{$ZURUCKFRUFEN}}', $isCallback ? "Ja" : "Nein", $Body);
            $Body = str_replace('{{$SALON}}', $saloon_display_name, $Body);
            $Body = str_replace('{{$DATUM}}', $datum, $Body);
            $Body = str_replace('{{$UHRZEIT}}', $datum_time, $Body);

            // $Body = "";
            // $Body .= "<p>Name: ";
            // $Body .= $name;
            // $Body .= "</p>";

            // $Body .= "<p>Mail: ";
            // $Body .= $emailstaff;
            // $Body .= "</p>";

            // $Body .= "<p>Telefon: ";
            // $Body .= $phone;
            // $Body .= "</p>";

            // $Body .= "<p>Zurückrufen: ";
            // $Body .= $isCallback ? "Ja" : "Nein";
            // $Body .= "</p>";


            // $Body .= "<p>Salon: ";
            // $Body .= $saloon_display_name;
            // $Body .= "</p>";
        
            
            // $Body .= "<p>Datum: ";
            // $Body .= $datum;
            // $Body .= "</p>";

            // $Body .= "<p>Uhrzeit: ";
            // $Body .= $datum_time;
            // $Body .= "</p>";


            //$to="maansawebworldphp@gmail.com";
            $status = $this->mail_attachmentinfo('' ,$to,'Terminstornierung Form','info@developservice.de',"bloom's",'no-reply@developservice.de',$Body, $emailstaff);

            if($status){

                echo '<p> Vielen Dank für Ihre Nachricht. </p>
                <p class="pt-2" >Der Termin am: '.$datum.' um '.$datum_time.' Uhr wird storniert. </p>
                <p class="pt-2">Eine Bestätigung wurde Ihnen per Mail zugesendet.</p>';
            }else{
                echo 'Bitter Nachricht wurde erfolgreich versendet!';
            }
            
        }
        die();
        
    }
    
    function mail_attachmentinfo($attachment='',  $mailto='', $subject='', $from_mail='', $from_name='', $replyto='no-reply@developservice.de',  $body='', $bcc = '') {
        require ONEPLUS_DIR_PATH.'/app/libraries/PHPMailer/Exception.php';
        require ONEPLUS_DIR_PATH.'/app/libraries/PHPMailer/PHPMailer.php';
        require ONEPLUS_DIR_PATH.'/app/libraries/PHPMailer/SMTP.php';

        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            $mail->CharSet  = 'UTF-8';
            $mail->isHTML( true );
            $mail->ClearReplyTos();
            $mail->AddReplyTo( $replyto, '' );
            if($bcc != '') $mail->addBCC($bcc);
            
            $mail->setFrom( 'kontakt@bloom-s.de', $from_name );
            $mail->addAddress($mailto);       
            $mail->Subject = "Terminstornierung";
            $mail->Body = $body;
            if($attachment!=""){
                $mail->addAttachment($attachment);
            }
            
            return $mail->send();
        }catch ( Exception $e ) {
            return 0;
            $logger = new \Log( $this->f3->get( 'LOGS' ).date( "d.m.Y" ).'mail_error.log' );
            $logger->write( "Mail error: ".$mail->ErrorInfo, 'r' );
        }
    }

    

  
}
