<?php
/**
 * @file: kontakt.php
 * @package:    d:\OSPanel\domains\localhost\f3-blooms\app\controllers
 * @created:    Sun Jul 26 2020
 * @author:     oppo, webiprog.de
 * @version:    1.0.0
 * @modified:   Sunday July 26th 2020 9:53:11 am
 * @copyright   (c) 2008-2020 Webiprog GmbH, UA. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

class kontakt extends Controller
{

        /**
     * @return mixed
     */
    private function banners()
    {

        $banners = [];

        $bannerAdmin = new bannerKontaktAdmin();
        $images      = (array) $bannerAdmin->loadBysort();

        $query = array(
            // 'playlist'        => $video_id,
            'enablejsapi'    => 1,
            'iv_load_policy' => 3,
            'disablekb'      => 1,
            'autoplay'       => 1,
            'modestbranding' => 1,
            // Показывает�?�? меню плеера перед началом проигровани�?. �?е нужно показывать какие-либо �?имволы плеера. 25.05.2020 16:19
            'controls'       => 0,
            'showinfo'       => 0,
            'rel'            => 0,
            'loop'           => 0,
            'mute'           => 0,
            'wmode'          => 'transparent',
            'color'          => 'white',
            'theme'          => 'dark'
        );

        $yt = 0;

        /* @FIX by oppo , @Date: 2020-03-16 16:46:03
         * @Desc:  carousel_interval
         */
        // $interval = [25000,500,8500,500,3000,1500,2000,500,2000,500,2000,500];
        $def_carousel_interval = (int) $this->f3->get( 'carousel_interval' );

        foreach ( $images as $n => $img ) {

            if ( empty( $img['carousel_interval'] ) ) {
                $img['carousel_interval'] = $def_carousel_interval;
            }

            // false &&
            if ( !empty( $img['src'] ) ) {
                $banners[] = ['type' => 'img', 'src' => BANNER_PARENT_URL_DIR.'/kontakt-banner/'.$img['src'], 'interval' => $img['carousel_interval']];
                $yt++;
            } elseif ( !empty( $img['video_url'] ) ) {

                if ( $yt > 0 ) {
                    // $query['autoplay'] = 0;
                }
                /* @FIX by oppo (webiprog.de), @Date: 2020-03-18 11:34:55
                 * @Desc: LOOP
                 * https://sergeychunkevich.com/dlya-web-mastera/youtube-parametry/#param13
                 */
                // 'playlist'        => $video_id,
                $video_id = str_replace( 'https://www.youtube.com/embed/', '', $img['video_url'] );
                if ( $video_id ) {
                    $query['playlist'] = $video_id;
                }

                $youtube_addon_url = '?'.http_build_query( $query, '&' );

                $youtube_url = rtrim( $img['video_url'], '?' );
                $youtube_url = $youtube_url.$youtube_addon_url;
                $banners[]   = ['type' => 'youtube', 'src' => $youtube_url, 'interval' => $img['carousel_interval']];
                $yt++;
            }
        }

        return $banners;
	}

	public function index()
	{
            
        $this->f3->set('isHomePage',false);
		$this->f3->set('title', "Kontakt");
		$this->f3->set('view', 'kontakt.html');
		$this->f3->set('classfoot', 'kontakt');
        // ADD JS
        $addscripts = ['js/layout/validator.min.js','js/layout/kontakt.js'];
        $this->f3->set('addscripts', $addscripts);
        // add Styles
        $addstyles = ['css/animate.css'];
        $this->f3->set('addstyles', $addstyles);

        /* @FIX by oppo @Date: 13.01.2021 11:37
         * @Desc: add slider
         */
        $banners = $this->banners();
        $this->f3->set( 'BANNERS', $banners );
        
        $adminTeam = '';
        
        $adminInfo = $this->adminData();
        $office = $this->officeData();
        $this->f3->set( 'ADMININFO', $adminInfo );
        $this->f3->set( 'STAFFINFO', $office );

        $this->f3->set( 'ESCAPE', false );
    }
   
    function getEmailById($id=0){
        $email = $this->convertJson($this->getXMLDataAdmin(PAGECON_ONWERK_V2 . 'employee/businessemail?employeeId='.$id));
        if(!empty($email[0])){
            return $email[0];
        }
    }
    
    function adminData(){
        $adminTeam = $this->convertJson($this->getXMLDataAdmin(PAGECON_ONWERK_V2 . 'salon/administrationteam'));
        
        if(!empty($adminTeam)){
            $record = array();
            $index =0;
            $kontaktModel = new salonsModel();
            foreach($adminTeam['Employee'] as $team){
                $record[$index]['id'] = $team['@attributes']['Id'];
                $record[$index]['Name'] = $team['Name'];
                $record[$index]['FirstName'] = $team['FirstName'];
                if(!empty($team['EmailWork'])){
                    $record[$index]['Email'] = $team['EmailWork'];
                }else{
                    $record[$index]['Email'] = '';
                }
                
                if(!empty($team['Position'])){
                    $record[$index]['Position'] = $team['Position'];
                }else{
                    $record[$index]['Position'] = '';
                }
                if(!empty($team['Description'])){
                    $record[$index]['Description'] = $team['Description'];
                }else{
                    $record[$index]['Description'] ='';
                }
                
                $record[$index]['Image'] = $kontaktModel->getKontactFilename($team,$team['@attributes']['Id'],'admin');;
                $record[$index]['WebImage'] = $kontaktModel->getKontactWebImageFilename($team,$team['@attributes']['Id'],'admin');;
                $index++;
            }
            return $record;
        }
    }
    function officeData(){
        $adminTeam = $this->convertJson($this->getXMLDataAdmin(PAGECON_ONWERK_V2 . 'salon/officeteam'));
        
        if(!empty($adminTeam)){
            $record = array();
            $index =0;
            $kontaktModel = new salonsModel();
            foreach($adminTeam['Employee'] as $team){
                $record[$index]['id'] = $team['@attributes']['Id'];
                $record[$index]['Name'] = $team['Name'];
                $record[$index]['FirstName'] = $team['FirstName'];
                if(!empty($team['EmailWork'])){
                    $record[$index]['Email'] = $team['EmailWork'];
                }else{
                    $record[$index]['Email'] = '';
                }
                
                if(!empty($team['Position'])){
                    $record[$index]['Position'] = $team['Position'];
                }else{
                    $record[$index]['Position'] = '';
                }
               
                if(!empty($team['Description'])){
                    $record[$index]['Description'] = $team['Description'];
                }else{
                    $record[$index]['Description'] ='';
                }
                $record[$index]['Image'] = $kontaktModel->getKontactFilename($team,$team['@attributes']['Id'],'staff');;
                $record[$index]['WebImage'] = $kontaktModel->getKontactWebImageFilename($team,$team['@attributes']['Id'],'staff');;
                $index++;
            }
            return $record;
        }
    }
    function convertJson($xml_string=""){
        if($xml_string!=""){
            $xml = simplexml_load_string($xml_string);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            return $array;
        }
        
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
        
        
        
        
        
        
//        if (isset($_POST["g-recaptcha-response"])) {
//            $captcha = $_POST['g-recaptcha-response'];
//        } else {
//            $errorMSG .= 'Invalid captcha';
//        }
        
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
        
        if (empty($_POST["email"])) {
            $errorMSG .= "E-Mail wird ben&#246;tigt ";
        } else {
            $id = $_POST["email"];
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
        
         $to = $this->getEmailById($id);
        
        if($to == ""){
            $errorMSG .= "E-Mail Adresse pr&#252;fen";
        }
      
        if($errorMSG !=""){
            echo '<div class="alert alert-danger rounded-0" role="alert">'.$errorMSG.'</div>';
        }else{
            /*
            $secretKey = "6LdAaVMaAAAAAIMEgAWDY9MhNPvCxfRBluvXhb0l";
            $ip = $_SERVER['REMOTE_ADDR'];
            $url =  'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response,true);
            header('Content-type: application/json');
             if($responseKeys["success"]) {
                 echo "Success";
             }else{
                 echo '<div class="alert alert-danger rounded-0" role="alert">Invalid google captcha</div>';
             }
            die();*/
            
            $Body = "";
            $Body .= "<p>Name: ";
            $Body .= $name;
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
            $status = $this->mail_attachmentinfo($fullPath ,$to,'Kontakt Form','info@developservice.de',$name,'no-reply@developservice.de',$Body);

            if($status){
                echo '<div class="alert alert-success rounded-0" role="alert"> Die Anfrage wurde versendet.</div>';
            }else{
                echo '<div class="alert alert-danger rounded-0" role="alert">Bitter versuchen Sie es erneut!</div>';
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
    //                 $mail->isSMTP();
    //                 $mail->Host       = "mail110885.mx2f47.netcup.net";
    //                 $mail->SMTPAuth   = true;
    //                 $mail->Username   = "sender@developservice.de";
    //                 $mail->Password   = "As35673-12!";
    //                 $mail->SMTPSecure = "TLS";
    //                 $mail->Port       = 25;
    //                 $mail->protocol = 'mail';

            $mail->CharSet  = 'UTF-8';
            $mail->isHTML( true );
            $mail->ClearReplyTos();
            $mail->AddReplyTo( $replyto, '' );
            $mail->setFrom( 'kontakt@bloom-s.de', $from_name );
            $mail->addAddress($mailto);       
            $mail->Subject = "Nachricht von Webseite";
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
    
   
    private function getXMLDataAdmin( $url )
    {
        $ch = curl_init();

        $timeout = 5;
        

        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );

        $xmlData = curl_exec( $ch );

        curl_close( $ch );

        return $xmlData;
    }

}
