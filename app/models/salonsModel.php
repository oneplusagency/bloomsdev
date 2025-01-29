<?php

// namespace Model;
date_default_timezone_set( 'Europe/Berlin' );

/**
 * @file: Salons.php
 * @created:    Tue Jan 14 2020
 * @version:    1.0.0
 * @modified:   Tuesday January 14th 2020 1:10:03 pm
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\models
 * @author:     oppo
 * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// $this->user = new \Model\User($this->casted['uid']);
class salonsModel extends DB\SQL\Mapper
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
        // parent::__construct($db, 'salons');
        $f3       = \Base::instance();
        $this->f3 = $f3;

        $this->base      = $this->f3->get( 'BASE' );
        $this->page_host = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" )."://$_SERVER[HTTP_HOST]";
    }

    public static function serviceAvailable()
    {
        //https://api.bloom-s.de:780/api/ping
        return helperblooms::serviceAvailable();
    }

    /**
     * @return mixed
     */
    public function getSalons()
    {
        $salons = $def_salons = false;
        if ( self::serviceAvailable() ) {
            $http   = new Bugzilla();
            $salons = $http->get( 'salon/all' );
        }

        // var_dump($salons, self::serviceAvailable());

        if ( !$salons && is_file( ONEPLUS_DIR_PATH_APP.'helper/local/salons-json.php' ) ) {
            // var_dump("asda");
            include_once ONEPLUS_DIR_PATH_APP.'helper/local/salons-json.php';
            $salons = $salons_local;
        }

        if ( $salons ) {
            return $salons;
        }

        $serverIP = $_SERVER['REMOTE_ADDR'];
        if ( $serverIP != '127.0.0.1' ) {
            helperblooms::Error( 'fail Salons: salon/all' );
        }

        return $def_salons;
    }

    /**
     * @param  $salonId
     * @return mixed
     */
    public function getSalonTeam( $salonId )
    {
        // '[{"FirstName":"Henriette","Id":1090,"LastModified":"2019-11-19T07:36:55.673","LastName":"Schütz","Name":"Henriette Schütz","PricingRailId":1}
        $salonTeam = false;
        if ( self::serviceAvailable() ) {
            // salon/'
			
            $http      = new Bugzilla();
            $salonTeam = $http->get( 'salon/team?salonId='.(int) $salonId );
            if ( $salonTeam ) {
                // file_put_contents (ONEPLUS_DIR_PATH_APP . 'helper/local/_local/salon_team_salonid_'..'.json' , $salonTeam , FILE_APPEND | LOCK_EX );
            }
        }

        if ( !$salonTeam ) {
            $serverIP = $_SERVER['REMOTE_ADDR'];
            if ( $serverIP != '127.0.0.1' ) {
                helperblooms::Error( 'fail SalonTeam:'.$salonId );
            }
            // e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\helper\local\team\28-salonteam.json
            if ( is_file( ONEPLUS_DIR_PATH_APP.'helper/local/team/'.$salonId.'-salonteam.json' ) ) {

                ob_start();
                include ONEPLUS_DIR_PATH_APP.'helper/local/team/'.$salonId.'-salonteam.json';
                $arr = ob_get_clean();
                // $salonTeam = json_encode( $arr, true );
                $team_local = $arr;
            } elseif ( is_file( ONEPLUS_DIR_PATH_APP.'helper/local/team-json.php' ) ) {
                include ONEPLUS_DIR_PATH_APP.'helper/local/team-json.php';
            } else {

                $salonTeam = false;
            }

            $salonTeam = $team_local;

            // echo '<pre>';
            //     var_export($salonTeam);
            // echo '</pre>';
            // exit;

        }

        return $salonTeam;
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function getCasheSalonTeam( $id = null, $redirect = true )
    {
        $id          = intval( $id );
        $this->db    = null;
        $cacheblooms = $this->f3->get( 'CacheBlooms' );
        $code        = 'SalonTeam'.$id;

        $result = $cacheblooms->retrieve( $code, true );

        $salon_team_arr = [];
        if ( $result ) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $salon_team_arr = json_decode( $result, true );
        } else {
            $salonfinder      = new salonsModel( $this->db );
            $salon_team_model = $salonfinder->getSalonTeam( $id );

            if ( $salons_array = helperblooms::jsJson( $salon_team_model, true ) ) {
                $salon_team_arr = helperblooms::parseXmlToArraysalons( $salons_array, 'Id' );
                /* @FIX by oppo , @Date: 2020-04-28 11:25:31
                 * @Desc: fix cacheblooms - 5 min | constant in index - TIME_CACHEBLOOMS
                 */
                $cacheblooms->store( $code, json_encode( $salon_team_arr, true ), TIME_CACHEBLOOMS ); // 3600 => 'hour',
            } elseif ( $redirect ) {
                // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
                $this->f3->set( 'SESSION.error', 'Salon Team existiert nicht' );
                $this->f3->reroute( '/salons.html' );
            }
        }
        return $salon_team_arr;
    }

    /**
     * @param  $employeeId
     * @return mixed
     */
    public function getEmployeeImage( $employeeId )
    {
        $webimages = $employeeImage = false;
        if ( self::serviceAvailable() ) {
            $http = new Bugzilla();

            $webimages = $http->get( 'employee/image?employeeId='.$employeeId );
            // $webimages = $http->get( 'employee/webimages?employeeIds='.$employeeId );
        } else {
            // $f3 = \Base::instance();
            // $logger = new \Log($f3->get('LOGS') . 'service_available.log');
            // $logger->write('salonsModel::getEmployeeImage error :' . $employeeId . '');
        }

        if ( $webimages ) {
            return $webimages;
        }
        //$data = base64_decode($img[0]->Image);
        return $employeeImage;
    }

    /**
     * @param $value
     * @param $salonId
     * @param $employeeId
     * @param $local
     */
    public function getMitarbeiterFilename( $value, $salonId, $employeeId, $local = false )
    {

        $employeeId = intval( $employeeId );
        $salonId    = intval( $salonId );
        $test       = 0;

        if ( $employeeId == 0 || $salonId == 0 ) {
            return EMPLOYEEIMAGE_DIR.'emp_image.jpg';
        }

        $alt             = helperblooms::umlautName( $value['Name'], true );
        $user_time_stamp = strtotime( $value['LastModified'] );
        $filename        = $salonId.'/'.$employeeId.'_'.$alt.'_'.$salonId.'_'.$user_time_stamp.'.jpg';

        // $filename = $salonId . '/' . $employeeId . '.jpg';

        // skip hard get for local Address
        if ( $local ) {

            if (
                file_exists( EMPLOYEEIMAGE_ABS_DIR.$filename ) ) {
                $avatar = EMPLOYEEIMAGE_DIR.$filename;
            }else {
                $avatar = EMPLOYEEIMAGE_DIR.'emp_image.jpg';
            }

            return $avatar;
        }

        if ( !file_exists( EMPLOYEEIMAGE_ABS_DIR.$filename ) ) {

            $employee_image = null;
            $salon_team_arr = $this->getEmployeeImage( $employeeId );
            if ( $team_img = helperblooms::jsJson( $salon_team_arr, true ) ) {
                // array (
                //     'EmployeeId' => 913,
                //     'Image' => 'iVBORw0KGgoAAAANSUhEUgAAAKoAAAD8CAIAAABow6g/AAAABGdBTUEAALGPCi',
                //     'ImageType' => 0,
                //   )

                if ( isset( $team_img['Image'] ) ) {
                    $employee_image = (string) $team_img['Image'];
                }
            }

            if ( $employee_image ) {
                $data = base64_decode( $employee_image );
                try {

                   // clear old images

                    $dir = EMPLOYEEIMAGE_ABS_DIR.$salonId.'/';

                    $images = glob( $dir."*.jpg", GLOB_BRACE | GLOB_NOSORT );

                    foreach ( $images as $file ) {

                        $nf = basename( $file );

                        // $expl = explode( '_', $nf );

                        $file_chk_name = $employeeId.'_'.$alt.'_'.$salonId;

                        $chk = substr( $nf, 0, strlen( $file_chk_name ) );
                        if ( $chk == $file_chk_name ) {

                            // echo '<pre>';
                            //     var_export($file_chk_name);
                            // echo '</pre>';
                            // // exit;
                            unlink( $file );
                        }

                        // elseif (count($expl) < 2) {
                        //     unlink($file);
                        // }
                    }

                    file_put_contents( EMPLOYEEIMAGE_ABS_DIR.$filename, $data );
                    $time = strtotime( $value['LastModified'] );
                    touch( EMPLOYEEIMAGE_ABS_DIR.$filename, $time, $user_time_stamp );

                    if (
                        file_exists( EMPLOYEEIMAGE_ABS_DIR.$filename ) ) {
                        $avatar = EMPLOYEEIMAGE_DIR.$filename;
                    }else {
                        $avatar = EMPLOYEEIMAGE_DIR.'emp_image.jpg';
                    }


                } catch ( \Exception $e ) {
                    $logger = new \Log( $this->f3->get( 'LOGS' ).'employee_image_error.log' );
                    $logger->write( 'Error :'.$e->getMessage().'' );
                    $avatar = EMPLOYEEIMAGE_DIR.'emp_image.jpg';
                }


            } else {
                $avatar = EMPLOYEEIMAGE_DIR.'emp_image.jpg';
            }
        } else {

            $avatar = EMPLOYEEIMAGE_DIR.$filename;

            // false &&

            if ( false && $salonId == 25 ) {
                // if ($test > 0 && ($value['Id'] == 1370 || $value['Id'] == 1353 || $value['Id'] == 198)) {
                // clearstatcache();
                echo '<pre style="color:#fff">';
                echo '<hr/><b>'.( $value['Name'] ).'</b>';

                echo '<br/>params[LastModified] : '.( $value['LastModified'] ).'<br/>';
                echo '</pre>';
            }

            // if ($value['Id'] == 1370 || $value['Id'] == 856) {  }
            // \clearstatcache();
            // echo '<pre>';
            // var_export($value['Name']);
            // echo 'access: ' . \date("Y-m-d H:i:s", \fileatime(EMPLOYEEIMAGE_ABS_DIR . $filename)) . '<br/>';
            // echo '<br/>file modified: ' . \date("Y-m-d H:i:s", $file_time_touch) . '<br/>';
            // echo 'user modified: ' . \date("Y-m-d H:i:s", $user_time_stamp) . '<br/>';
            // echo 'diff: ' . $diff . '<br/>';
            // echo '</pre>';

            // 'Lisa Brand'
            // file modified: 2019-11-19 08:36:54
            // user modified: 2019-11-19 07:36:55
            // diff: 0

            // 'Mirjeta Haxhija'
            // file modified: 2019-11-19 08:36:54
            // user modified: 2019-11-19 07:36:55
            // diff: 0

        }
        return $avatar;
    }


 /** @FIX by oppo (webiprog.de), @Date: 2020-05-22 17:04:59
  * @Desc:  add Employee Webimages
  */

    /**
     * @param  $employeeId
     * @return mixed
     * @test $jsondata = getXMLData(PAGECON_ONWERK_V2 . 'employee/webimages?employeeIds=1370&employeeIds=1353');
     */
    public function getUploadEmployeeWebimages( $employeeId )
    {
        $webimages = $employeeImage = false;
        if ( self::serviceAvailable() ) {
            $http = new Bugzilla();

            $webimages = $http->get( 'employee/webimages?employeeIds='.$employeeId );
            // $jsondata = getXMLData(PAGECON_ONWERK_V2 . 'employee/webimages?employeeIds=1370&employeeIds=1353');
        } else {
            // $f3 = \Base::instance();
            // $logger = new \Log($f3->get('LOGS') . 'service_available.log');
            // $logger->write('salonsModel::getEmployeeImage error :' . $employeeId . '');
        }

        if ( $webimages ) {
            return $webimages;
        }
        //$data = base64_decode($img[0]->Image);
        return $employeeImage;
    }

    public function getEmployeeWebimagesFilename( $value, $salonId, $employeeId, $local = false )
    {

        $employeeId = intval( $employeeId );
        $salonId    = intval( $salonId );
        $test       = 0;

        if ( $employeeId == 0 || $salonId == 0 ) {
            return UPLOAD_EMPLOYEE_WEBIMAGES_DIR.'emp_image.jpg';
        }

        $alt             = helperblooms::umlautName( $value['Name'], true );
        $user_time_stamp = strtotime( $value['LastModified'] );
        $filename        = $salonId.'/'.$employeeId.'_'.$alt.'_'.$salonId.'_'.$user_time_stamp.'.jpg';

        // skip hard get for local Address
        if ( $local ) {
            if (
                file_exists( UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR.$filename ) ) {
                $avatar = UPLOAD_EMPLOYEE_WEBIMAGES_DIR.$filename;
            }else {
                $avatar = UPLOAD_EMPLOYEE_WEBIMAGES_DIR.'emp_image.jpg';
            }
            return $avatar;
        }

        if ( !file_exists( UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR.$filename ) ) {

            $employee_image = null;
            $salon_team_arr = $this->getUploadEmployeeWebimages( $employeeId );

            // if ($employeeId == 1497 ) {
            // echo '<pre>';
            //     var_export($salon_team_arr);
            // echo '</pre>';
            // // exit;
            // }
            if ( $team_img = helperblooms::jsJson( $salon_team_arr, true ) ) {
                // array (
                //     'EmployeeId' => 1370
                //     'Image' => 'iVBORw0KGgoAAAANSUhEUgAAAKoAAAD8CAIAAABow6g/AAAABGdBTUEAALGPCi',
                //     'ImageType' => 1,
                //   )


                if ( isset( $team_img[0]['Image'] ) ) {
                    $employee_image = (string) $team_img[0]['Image'];
                }
            }

            if ( $employee_image ) {
                $data = base64_decode( $employee_image );
                try {

                   // clear old images

                    $dir = UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR.$salonId.'/';

                    $images = glob( $dir."*.jpg", GLOB_BRACE | GLOB_NOSORT );

                    foreach ( $images as $file ) {

                        $nf = basename( $file );

                        // $expl = explode( '_', $nf );

                        $file_chk_name = $employeeId.'_'.$alt.'_'.$salonId;

                        $chk = substr( $nf, 0, strlen( $file_chk_name ) );
                        if ( $chk == $file_chk_name ) {

                            unlink( $file );
                        }

                        // elseif (count($expl) < 2) {
                        //     unlink($file);
                        // }
                    }

                    file_put_contents( UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR.$filename, $data );
                    $time = strtotime( $value['LastModified'] );
                    touch( UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR.$filename, $time, $user_time_stamp );

                    if (
                        file_exists( UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR.$filename ) ) {
                        $avatar = UPLOAD_EMPLOYEE_WEBIMAGES_DIR.$filename;
                    }else {
                        $avatar = UPLOAD_EMPLOYEE_WEBIMAGES_DIR.'emp_image.jpg';
                    }


                } catch ( \Exception $e ) {
                    $logger = new \Log( $this->f3->get( 'LOGS' ).'employee_image_error.log' );
                    $logger->write( 'Error :'.$e->getMessage().'' );
                    $avatar = UPLOAD_EMPLOYEE_WEBIMAGES_DIR.'emp_image.jpg';
                }


            } else {
                $avatar = UPLOAD_EMPLOYEE_WEBIMAGES_DIR.'emp_image.jpg';
            }
        } else {

            $avatar = UPLOAD_EMPLOYEE_WEBIMAGES_DIR.$filename;

            // false &&

            if ( false && $salonId == 25 ) {
                // if ($test > 0 && ($value['Id'] == 1370 || $value['Id'] == 1353 || $value['Id'] == 198)) {
                // clearstatcache();
                echo '<pre style="color:#fff">';
                echo '<hr/><b>'.( $value['Name'] ).'</b>';

                echo '<br/>params[LastModified] : '.( $value['LastModified'] ).'<br/>';
                echo '</pre>';
            }

        }
        return $avatar;
    }

    /**
     * =============       OLD      ================
     */

    /**
     * Возвращает список цен на пакеты услуг, сгруппированные по категориям. Цены специфичны для салона.
     * Эта функция также доступна через категоризированныйserviceпакет/салон (Только V1),
     * который был введен для постоянного наименования функций.
     *
     *
     * salon/categorizedPrices (NUR V1) Funktion: <BASE>/salon/categorizedPrices
     * Aufruf: <BASE>/salon/categorizedPrices?salonId={SALONID}
     *
     * Beschreibung: Liefert eine Liste der Preise für die Dienstleistungspakete gruppiert nach Kategorie zurück. Preise sind salonspezifisch.
     *
     * Diese Funktion ist auch erreichbar über categorizedservicepackage/salon (NUR V1),
     * die eingeführt wurde für eine konstante Benennung der Funktionen.
     * @param  $salonId
     * @return mixed
     */

    /**
     * Возвращает список цен на пакеты услуг. Цены специфичны для салона.
     *
     * @Aufruf: <BASE>/salon/prices?salonId={SALONID}
     *
     * {SALONID} Номер идентификатор салона, на который будут определены цены
     * Список объектов ServicePackagePrice
     *
     * @param  salon/prices (NUR V1) Funktion: <BASE>/salon/prices
     * @param  $salonId
     * @return mixed
     */

    /**
     * @param  $url
     * @return mixed
     */
    private function getXMLData( $url )
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
    
    public function getKontactFilename( $value, $employeeId, $type='admin', $local = false )
    {
        
        if($type == 'admin'){
            $baseAbs = EMPLOYEEIMAGE_ADMIN_ABS_DIR;
            $baseDir = EMPLOYEEIMAGE_ADMIN_DIR;
        }elseif($type == "staff"){
            $baseAbs = EMPLOYEEIMAGE_STAFF_ABS_DIR;
            $baseDir = EMPLOYEEIMAGE_STAFF_DIR;
        }
       
        $employeeId = intval( $employeeId );
        $test       = 0;

        if ( $employeeId == 0  ) {
            return $baseDir.'emp_image.jpg';
        }

        $alt             = helperblooms::umlautName( $value['Name'], true );
        $user_time_stamp = strtotime( $value['LastModified'] );
        $filename        = $employeeId.'_'.$alt.'_'.$user_time_stamp.'.jpg';

       
        
        // skip hard get for local Address
        if ( $local ) {

            if (
                file_exists( $baseAbs.$filename ) ) {
                $avatar = $baseDir.$filename;
            }else {
                $avatar = $baseDir.'emp_image.jpg';
            }

            return $avatar;
        }

        if ( !file_exists( $baseAbs.$filename ) ) {
            
            $employee_image = null;
            $salon_team_arr = $this->getEmployeeImage( $employeeId );
           
            if ( $team_img = helperblooms::jsJson( $salon_team_arr, true ) ) {
                // array (
                //     'EmployeeId' => 913,
                //     'Image' => 'iVBORw0KGgoAAAANSUhEUgAAAKoAAAD8CAIAAABow6g/AAAABGdBTUEAALGPCi',
                //     'ImageType' => 0,
                //   )

                if ( isset( $team_img['Image'] ) ) {
                    $employee_image = (string) $team_img['Image'];
                }
            }

            if ( $employee_image ) {
                $data = base64_decode( $employee_image );
                
                try {

                   // clear old images

                    $dir = $baseAbs.'/';

                    $images = glob( $dir."*.jpg", GLOB_BRACE | GLOB_NOSORT );
                  
                    if(!empty($images)){
                        
                        foreach ( $images as $file ) {

                            $nf = basename( $file );

                            // $expl = explode( '_', $nf );

                            $file_chk_name = $employeeId.'_'.$alt;

                            $chk = substr( $nf, 0, strlen( $file_chk_name ) );
                            if ( $chk == $file_chk_name ) {

                                // echo '<pre>';
                                //     var_export($file_chk_name);
                                // echo '</pre>';
                                // // exit;
                                unlink( $file );
                            }

                            // elseif (count($expl) < 2) {
                            //     unlink($file);
                            // }
                        }
                    }
                    
                    

                    file_put_contents( $baseAbs.$filename, $data );
                    $time = strtotime( $value['LastModified'] );
                    touch( $baseAbs.$filename, $time, $user_time_stamp );

                    if (
                        file_exists( $baseAbs.$filename ) ) {
                        $avatar = $baseDir.$filename;
                    }else {
                        $avatar = $baseDir.'emp_image.jpg';
                    }


                } catch ( \Exception $e ) {
                    $logger = new \Log( $this->f3->get( 'LOGS' ).'employee_image_error.log' );
                    $logger->write( 'Error :'.$e->getMessage().'' );
                    $avatar = $baseDir.'emp_image.jpg';
                }


            } else {
                $avatar = $baseDir.'emp_image.jpg';
            }
        } else {
            $avatar = $baseDir.$filename;
        }
        return $avatar;
    }
    
    public function getKontactWebImageFilename( $value, $employeeId, $type='admin', $local = false )
    {
        $webPath ='webimage/';
        if($type == 'admin'){
            $baseAbs = EMPLOYEEIMAGE_ADMIN_ABS_DIR.$webPath;
            $baseDir = EMPLOYEEIMAGE_ADMIN_DIR.$webPath;
        }elseif($type == "staff"){
            $baseAbs = EMPLOYEEIMAGE_STAFF_ABS_DIR.$webPath;
            $baseDir = EMPLOYEEIMAGE_STAFF_DIR.$webPath;
        }
       
        $employeeId = intval( $employeeId );
        $test       = 0;

        if ( $employeeId == 0  ) {
            return $baseDir.'emp_image.jpg';
        }

        $alt             = helperblooms::umlautName( $value['Name'], true );
        $user_time_stamp = strtotime( $value['LastModified'] );
        $filename        = $employeeId.'_'.$alt.'_'.$user_time_stamp.'.jpg';

       
        
        // skip hard get for local Address
        if ( $local ) {

            if (
                file_exists( $baseAbs.$filename ) ) {
                $avatar = $baseDir.$filename;
            }else {
                $avatar = $baseDir.'emp_image.jpg';
            }

            return $avatar;
        }

        if ( !file_exists( $baseAbs.$filename ) ) {
            
            $employee_image = null;
            $salon_team_arr = $this->getUploadEmployeeWebimages( $employeeId );
            
           
           
            if ( $team_img = helperblooms::jsJson( $salon_team_arr, true ) ) {
               
                if ( isset( $team_img[0]['Image'] ) ) {
                    $employee_image = (string) $team_img[0]['Image'];
                }
            }
          
            if ( $employee_image ) {
                $data = base64_decode( $employee_image );
                
                try {

                   // clear old images

                    $dir = $baseAbs.'/';

                    $images = glob( $dir."*.jpg", GLOB_BRACE | GLOB_NOSORT );
                  
                    if(!empty($images)){
                        
                        foreach ( $images as $file ) {

                            $nf = basename( $file );

                            // $expl = explode( '_', $nf );

                            $file_chk_name = $employeeId.'_'.$alt;

                            $chk = substr( $nf, 0, strlen( $file_chk_name ) );
                            if ( $chk == $file_chk_name ) {

                                // echo '<pre>';
                                //     var_export($file_chk_name);
                                // echo '</pre>';
                                // // exit;
                                unlink( $file );
                            }

                            // elseif (count($expl) < 2) {
                            //     unlink($file);
                            // }
                        }
                    }
                    
                    

                    file_put_contents( $baseAbs.$filename, $data );
                    $time = strtotime( $value['LastModified'] );
                    touch( $baseAbs.$filename, $time, $user_time_stamp );

                    if (
                        file_exists( $baseAbs.$filename ) ) {
                        $avatar = $baseDir.$filename;
                    }else {
                        $avatar = $baseDir.'emp_image.jpg';
                    }


                } catch ( \Exception $e ) {
                    $logger = new \Log( $this->f3->get( 'LOGS' ).'employee_image_error.log' );
                    $logger->write( 'Error :'.$e->getMessage().'' );
                    $avatar = $baseDir.'emp_image.jpg';
                }


            } else {
                $avatar = $baseDir.'emp_image.jpg';
            }
        } else {
            $avatar = $baseDir.$filename;
        }
        return $avatar;
    }
}
