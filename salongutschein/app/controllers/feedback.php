<?php
// namespace Salons;
class feedback extends Controller
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
        $this->f3->set('title', 'Reklamation');

        // ADD JS
        $addscripts = ['js/layout/validator.min.js','js/layout/reklamation.js'];
        $this->f3->set('addscripts', $addscripts);
        // add Styles
        $addstyles = ['css/animate.css'];
           $this->f3->set('addstyles', $addstyles);
        $termine = new termine();
        $option_salon = $termine->select_salon();

       
        
        $this->f3->set('OPTION_SALON', $option_salon);

        // $adminObject= new kontakt();
        // $adminInfo = $adminObject->adminData();
        // $office = $adminObject->getEmailById(24);

        
        // $this->f3->set( 'ADMININFO', $adminInfo );
        // $this->f3->set( 'STAFFINFO', $office );

        $this->f3->set('view', 'feedback.html');
        $this->f3->set('classfoot', 'reklamation');

    
        $html ="<p>Sie sind mit der erhaltenen Leistung nicht zufrieden?<br>Dann sind Sie hier richtig!</p>
        
        <p>Bitte einfach ausfüllen und senden.<br>
        Wir kümmern uns um Ihr Anliegen!</p>";
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
        
        if(isset($_FILES['file']) && $_FILES['file']['name'] !=""){
            
            
            $allowed = array('pdf', 'jpg','jpeg');
            $filenameCheck = $_FILES['file']['name'];
            $ext = pathinfo($filenameCheck, PATHINFO_EXTENSION);
           
            if (!in_array($ext, $allowed)) {
                $errorMSG .= "Falsches Dateiformat";
            }else{
                $filename = basename($_FILES['file']['name']);
                $fullPath = $uploaddir . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], $fullPath);
            }
            
            
        }  
               
        
        

        // NAME
        if (empty($_POST["name"])) {
            $errorMSG .= "Name wird ben&#246;tigt ";
        } else {
            $name = $_POST["name"];
        }
        
        if (empty($_POST["emailstaff"])) {
            $errorMSG .= "E-Mail wird ben&#246;tigt ";
        } else {
            $emailstaff = $_POST["emailstaff"];
        }
        
        if (empty($_POST["option_salon"])) {
            $errorMSG .= "E-Mail wird ben&#246;tigt ";
        } else {
            $idSallon = $_POST["option_salon"];
            $tosaloonmanagerData=$this->SaloonManagers[$idSallon];
            $tosaloonmanager=$tosaloonmanagerData["salon_supervisor_email"];
            $saloon_display_name=$tosaloonmanagerData["saloon_display_name"];

        }

        // EMAIL
        if (empty($_POST["telefon"])) {
            $errorMSG .= "Telefon wird ben&#246;tigt ";
        } else {
            $phone = $_POST["telefon"];
        }

        // MESSAGE
        if (empty($_POST["message"])) {
            $errorMSG .= "Nachricht wird ben&#246;tigt ";
        } else {
            $message = $_POST["message"];
        }
        
         $to = $tosaloonmanager ;//"ramsinghsaini@gmail.com" ;
        
        if($to == ""){
            $errorMSG .= "E-Mail Adresse pr&#252;fen";
        }
      
        if($errorMSG !=""){
            echo '<div class="alert alert-danger rounded-0" role="alert">'.$errorMSG.'</div>';
        }else{
           
            
            $Body = "";
            $Body .= "<p>Name: ";
            $Body .= $name;
            $Body .= "</p>";

            $Body .= "<p> Salon Name: ";
            $Body .= $saloon_display_name;
            $Body .= "</p>";

          
            if($emailstaff!=""){
                        $Body .= "<p>Email: ";
                        $Body .= $emailstaff;
                        $Body .= "</p>";
                }
        
            $Body .= "<p>Telefon: ";
            $Body .= $phone;
            $Body .= "</p>";
            $Body .= "<p>Nachricht: ";
            $Body .= $message;
            $Body .= "</p>";
            //$to="maansawebworldphp@gmail.com";
            $status = $this->mail_attachmentinfo($fullPath ,$to,'Reklamation Form','info@developservice.de',$name,'no-reply@developservice.de',$Body);

            if($status){
                echo '<div class="alert alert-success rounded-0" role="alert">Nachricht wurde erfolgreich versendet.</div>';
            }else{
                echo '<div class="alert alert-danger rounded-0" role="alert">Bitter Nachricht wurde erfolgreich versendet!</div>';
            }
            
        }
        die();
        
    }
    
    function mail_attachmentinfo($attachment='',  $mailto='', $subject='', $from_mail='', $from_name='', $replyto='no-reply@developservice.de',  $body='') {
        require ONEPLUS_DIR_PATH.'/app/libraries/PHPMailer/Exception.php';
        require ONEPLUS_DIR_PATH.'/app/libraries/PHPMailer/PHPMailer.php';
        require ONEPLUS_DIR_PATH.'/app/libraries/PHPMailer/SMTP.php';

        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            $mail->CharSet  = 'UTF-8';
            $mail->isHTML( true );
            $mail->ClearReplyTos();
            $mail->AddReplyTo( $replyto, '' );
            $mail->setFrom( 'kontakt@bloom-s.de', $from_name );
            $mail->addAddress($mailto);       
            $mail->Subject = "Reklamation";
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
