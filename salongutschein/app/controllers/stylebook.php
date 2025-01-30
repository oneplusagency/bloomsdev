<?php

// use \Gumlet\ImageResize;

class stylebook extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // $cacheblooms = $this->f3->get('CacheBlooms');
        // $cacheblooms->eraseAll();
    }

    /**
     * @return int
     */
    public function index()
    {

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Stylebook");
        $this->f3->set('view', 'stylebook.html');
        $this->f3->set('classfoot', 'stylebook');
        // $addscripts[] = 'js/layout/jquery.chainedvefore.js';

        // $addscripts[] = 'js/jquery.filterizr.min.js';
        // $addscripts[] = 'js/isotope.pkgd.js';
        // $addscripts[] = 'js/packery-mode.pkgd.js';

        $addscripts[] = 'js/lazysizes.min.js';
        $addscripts[] = 'js/jquery.mixitup.min.js';
        $addscripts[] = 'js/layout/stylebook.js';

        $this->f3->set('addscripts', $addscripts);
        //The result will be in seconds and milliseconds.
        // $executionTime = new ExecutionTimeblooms();
        // $executionTime->start();
        // $t = new ExecutionTimeblooms();
        // $t->go(); // if it's the case

        $arraySalons = $this->getSalonIds(false);


        $titleSalons = array_map(function ($salon) {
            //     25 =>
            //     array (
            //       'Address' => 'Elisabethenstr. 8, Darmstadt ',
            //       'CategoryDisplayName' => '',
            //       'Description' => 'Großer Citysalon mitten in der Innenstadt gegenüber Saturn.
            //   Wir freuen uns auf Sie!',
            //       'DisplayName' => 'Darmstadt, Elisabethenstraße',
            //       'GooglePlacesUrl' => 'https://goo.gl/maps/7QEBG2grQT31xwsN6',
            //       'Id' => 25,
            //       'ImageUrl' => 'salonbild_da1.jpg',
            //       'Name' => 'Darmstadt 1',
            //       'OnlineAppointmentsEnabled' => true,
            //       'OpeningHours' => 'Montag - Mittwoch: 10:00 - 19:00 Uhr
            //   Donnerstag: 10:00 - 20:00 Uhr
            //   Freitag: 9:00 - 20:30 Uhr
            //   Samstag: 9:00 - 18:00 Uhr ',
            //       'Phone' => '06151 5044480',
            //     ),

            return trim($salon['DisplayName']);
            // return trim($salon['Address']);
        }, $arraySalons);

        /** @FIX by oppo (webiprog.de), @Date: 2020-12-03 16:14:04
         * @Desc: the salon are not sort alphabetical (like on termine)
         * disable  uasort
         */
        // uasort( $titleSalons, function ( $a, $b ) {
        //     // if equal, don't do much
        //     if ( $a == $b ) {
        //         return 0;
        //     }
        //     $explodedA     = explode( ',', $a );
        //     $explodedB     = explode( ',', $b );
        //     $explodedPartA = trim( $explodedA[count( $explodedA ) - 1] );
        //     $explodedPartB = trim( $explodedB[count( $explodedB ) - 1] );
        //     $explodedPartA = preg_replace( '#[0-9 ]*#', '', $explodedPartA );
        //     $explodedPartB = preg_replace( '#[0-9 ]*#', '', $explodedPartB );

        //     if ( $explodedPartA == $explodedPartB ) {
        //         // compare full string
        //         return ( $a < $b ) ? -1 : 1;
        //     }
        //     return ( $explodedPartA < $explodedPartB ) ? -1 : 1;
        // } );

        // ksort($titleSalons);
        // $titleSalons = array("all" => 'Alle') + $titleSalons;
        // $titleSalons = array("all" => 'Alle') + $titleSalons;

        $this->f3->set('TITLESALONS', $titleSalons);

        $scrabSalonsImg = [];
        $arr            = (array) $this->getStylebookAllImg();
        //        if(isset($_GET['testing'])){
        //            /*Go to line 443*/
        //            echo "<pre>"; print_r($arr); echo "</pre>"; die();
        //        }


        if (count($arr)) {
            $scrabSalonsImg = $arr;
        }
        $this->f3->set('SCRAB_SALONS_IMG', $scrabSalonsImg);

        // array (
        //     25 =>
        //     array (
        //       1090 =>
        //       array (
        //         'Id' => 1090,
        //         'Name' => 'Henriette Schütz',
        //         'SALONID' => 25,
        //         'ALT' => 'Henriette_Schuetz',
        //         'PICTURE' => '/f3-url-shortener/upload/stylebookpictures/25/1090/1_henriette_schuetz.jpg',
        //         'TH_PICTURE' => '/f3-url-shortener/upload/stylebookpictures/25/1090/thumb/1_henriette_schuetz.jpg',
        //       ),
        //       996 =>
        //       array (
        //         'Id' => 996,
        //         'Name' => 'Melanie Scharff',
        //         'SALONID' => 25,
        //         'ALT' => 'Melanie_Scharff',
        //         'PICTURE' => '/f3-url-shortener/upload/stylebookpictures/25/996/1_melanie_scharff.jpg',
        //         'TH_PICTURE' => '/f3-url-shortener/upload/stylebookpictures/25/996/thumb/1_melanie_scharff.jpg',
        //       ),

        // $this->f3->set('STYLEBOOK_PICTURE', $arr);
        // code
        // $executionTime->end();
        // echo $executionTime;
        // echo "<br />Took ".$t->time()." to execute this code.";
        // exit;
    }

    public function salon()
    {
        // PARAMS
        // array (
        //     0 => '/stylebook/salon/4/mitarbeiter/996',
        //     'action' => 'salon',
        //     'controller' => 'stylebook',
        //     'empid' => '996',
        //     'id' => '4',
        //     'mitarbeiter' => 'mitarbeiter',
        //   )

        if ($this->f3->exists('PARAMS.id')) {
            $salonId = $this->f3->get('PARAMS.id');
            $this->f3->set('salonId', $salonId);
        } else {
            // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
            $this->f3->set('SESSION.error', 'Stylebook existiert nicht');
            $this->f3->reroute('/stylebook.html');
        }

        $employeeId = null;
        if ($this->f3->exists('PARAMS.mitarbeiter')) {
            $employeeId = (int) $this->f3->get('PARAMS.empid');
        }

        $this->f3->set('isHomePage', false);
        // $addscripts[] = 'js/layout/jquery.chainedvefore.js';
        $addscripts[] = 'js/layout/stylebook.js';
        $this->f3->set('addscripts', $addscripts);
        // if GET DATA BY SALON
        if (!$employeeId) {
            $this->f3->set('title', "Stylebook Mitarbeiter");
            $this->f3->set('view', 'stylebook-salon.html');
            $this->f3->set('classfoot', 'stylebook stylebook-salon');
            $arr = $this->getStylebookAllImg($salonId);

            $this->f3->set('STYLEBOOK_PICTURE', $arr);
            $this->f3->set('ESCAPE', false);
        }
        // if GET DATA BY SALON && BY $EMPLOYEEID
        else {

            // $this->deleteDir();

            $this->f3->set('title', "Stylebook Mitarbeiter");
            $this->f3->set('view', 'stylebook-mitarbeiter.html');
            $this->f3->set('classfoot', 'stylebook stylebook-mitarbeiter');

            $salons_ctrl = new salons();
            // $salons      = $salons_ctrl->getSalonsController($salonId);
            $all_salonteam = (array) $salons_ctrl->getSalonTeamController($salonId);
            $salonteam     = bloomArrayHelper::getValueJoom($all_salonteam, $employeeId, null, 'ARRAY');
            // array (
            //     'FirstName' => 'Henriette',
            //     'Id' => 1090,
            //     'LastModified' => '2019-11-19T07:36:55.673',
            //     'LastName' => 'Schütz',
            //     'Name' => 'Henriette Schütz',
            //     'PricingRailId' => 1,
            //   )
            // $salonteamName = bloomArrayHelper::getValueJoom($salonteam, 'Name', null, 'STRING');
            $salonteamName = bloomArrayHelper::getValueJoom($salonteam, 'FirstName', null, 'STRING');

            $title_emploee = '';
            if ($salonteamName) {
                $title_emploee = 'Stylebook für ' . $salonteamName;
            }
            $this->f3->set('TITLE_EMPLOEE', $title_emploee);

            // READ FILES FROM THE GALLERY FOLDER
            // '/assets/images/banner/'
            //https://www.php.net/manual/ru/function.glob.php

            $stylebook_picture = $this->getStylebookMitarbeiterImg($salonteamName, $salonId, $employeeId, true);
            // $stylebook_picture = $this->getStylebookAllImg($salonId, $employeeId);

            // array (
            //     0 =>
            //     array (
            //       'Id' => 1106,
            //       'Name' => 'Anna',
            //       'ALT' => 'Anna',
            //       'UMLAUTNAME' => 'anna',
            //       'SALONID' => '17',
            //       'PICTURE' => '/f3-blooms/upload/stylebookpictures/17/1106/1_anna_albrecht_1593527384.jpg',
            //       'TH_PICTURE' => '/f3-blooms/upload/stylebookpictures/17/1106/thumb/1_anna_albrecht_1593527384.jpg',
            //       'key_index' => 0,
            //     ),
            //     1 =>

            $this->f3->set('STYLEBOOK_PICTURE', $stylebook_picture);
            $this->f3->set('ESCAPE', false);
            // $source = UPLOAD_STYLEBOOK_ABS_DIR;
            // $dest   = UPLOAD_STYLEBOOK_ABS_DIR;
            // helperblooms::recurse_copy( $source, $dest );
        }
    }

    // ajax salon-subpage.js carousel
    public function stylistSlider()
    {
        $salonteamName     = '';
        $employeeId        = (int) helperblooms::inGet('employeeid', 0);
        $salonId           = (int) helperblooms::inGet('salonid', 0);
        $salonteamName     = (string) helperblooms::inGet('title_employee', '');
        $stylebook_picture = $this->getStylebookMitarbeiterImg($salonteamName, $salonId, $employeeId, false);
        header('Content-Type: application/json');
        print json_encode($stylebook_picture, JSON_UNESCAPED_UNICODE);

        exit;
    }

    /**
     * @param $salonteamName
     * @param $salonId
     * @param $employeeId
     * @param $only_one
     * @return mixed
     */
    public function getStylebookMitarbeiterImg($salonteamName, $salonId = 0, $employeeId = 0, $redirect = false)
    {

        $stylebookModel = new stylebookModel();
        $images         = $stylebookModel->getCasheStylebookEmployeeImage($employeeId, $redirect);
        $stylebook_picture = [];
        $key_index = 0;

        foreach ($images as $one_team) {

            $one_pic_url = bloomArrayHelper::getValueJoom($one_team, 'Url', null, 'STRING');

            if (!$one_pic_url) {
                continue;
            }

            $new_url = $stylebookModel->urlStylebookImgResize($one_pic_url, $salonId, $employeeId, false);
            // array (
            //     'full' => '/f3-blooms/upload/stylebookpictures/17/1106/8_18118782_1948617428758186_6016199437378247040_n.jpg',
            //     'thumb' => '/f3-blooms/upload/stylebookpictures/17/1106/thumb/8_18118782_1948617428758186_6016199437378247040_n.jpg',
            //   )

            $original = bloomArrayHelper::getValueJoom($new_url, 'full', $one_pic_url, 'STRING');
            $thumb    = bloomArrayHelper::getValueJoom($new_url, 'thumb', $one_pic_url, 'STRING');

            // array (
            //     'original' => '/f3-url-shortener/assets/images/stylebookpictures/2/953/1_tamara_kanow.jpg',
            //     'thumb' => '/f3-url-shortener/assets/images/stylebookpictures/2/953/thumb/1_tamara_kanow.jpg',
            //   ),
            $alt          = helperblooms::umlautName($salonteamName);
            $umlautname   = strtolower($alt);
            $arrSalonteam = array(
                'Id'         => $employeeId,
                'Name'       => $salonteamName,
                'ALT'        => $alt,
                'UMLAUTNAME' => $umlautname,
                'SALONID'    => $salonId,
                'PICTURE'    => $original,
                'TH_PICTURE' => $thumb,
                'key_index'  => $key_index
            );

            $key_index++;

            // if ( $only_one ) {

            //     return $arrSalonteam;
            //     break;
            // }

            array_push($stylebook_picture, $arrSalonteam);
        }

        return $stylebook_picture;
    }

    /**
     * @param  $salonId
     * @return mixed
     */
    public function getStylebookAllImg($vLsalonId = 0, $mitarbeiter = 0)
    {


        // helperblooms::recursivelyRemoveDirectory(UPLOAD_STYLEBOOK_ABS_DIR, array('emp_image.jpg'));

        // salons Controller
        $salons_ctrl = new salons();

        $salonfinder = new salonsModel(null);

        $stylebookModel = new stylebookModel();

        $cashestylebookallimage = (array) $stylebookModel->getCasheStylebookAllImage();
        /** @FIX by oppo (webiprog.de), @Date: 2020-12-02 18:07:03
         * @Desc: test dev
         */
        // $tmp = ONEPLUS_DIR_PATH . DIRECTORY_SEPARATOR . 'dev';
        // file_put_contents( $tmp.'/stylebookallimage.json' , var_export( $cashestylebookallimage , true),  LOCK_EX );

        if ($vLsalonId > 0) {
            $ids_salons[$vLsalonId] = $cashestylebookallimage[$vLsalonId];
        } else {

            $ids_salons = $cashestylebookallimage;
        }
        //echo "<pre>"; print_r($ids_salons); echo "</pre>"; die();
        $test_time  = 0;
        $empl_count = 0;

        $arr_salon = [];
        if (!empty($ids_salons) && is_array($ids_salons)) {

            $ip    = $this->f3->get('IP');
            $local = ($ip == '127.0.0.1' ? true : false);

            if ($test_time) {

                $t = new ExecutionTimeblooms();
                $t->go(); // if it's the case
            }

            $key_index = 0;

            foreach ($ids_salons as $salonId => $stylimg) {

                if ($test_time) {

                    $ss = new ExecutionTimeblooms();
                    $ss->go(); // if it's the case
                    // echo "********* Salon: (" . $salonId. ") *********<br />";
                }

                $arr_team = [];

                //getCashe SalonTeam
                $salonteam = (array) $salons_ctrl->getSalonTeamController($salonId);

                $value = null;

                foreach ($stylimg as $employeeId => $st) {

                    if ($test_time) {

                        $em = new ExecutionTimeblooms();
                        $em->go(); // if it's the case
                    }

                    $one_team = array_shift($st);
                    unset($st);
                    $one_pic_url = bloomArrayHelper::getValueJoom($one_team, 'Url', null, 'STRING');

                    if (!$one_pic_url) {
                        continue;
                    }

                    // array (
                    //     'EmployeeId' => 1106,
                    //     'Order' => 0,
                    //     'PictureName' => '18118782_1948617428758186_6016199437378247040_n',
                    //     'SalonId' => 17,
                    //     'Url' => 'https://api.bloom-s.de:1690/8_18118782_1948617428758186_6016199437378247040_n.jpg',
                    //   )

                    $value = $salonteam[$employeeId];
                    if (!isset($value['Id'])) {
                        continue;
                    }
                    $value['Id'] = $employeeId;

                    // array (
                    //     'FirstName' => 'Anna',
                    //     'Groups' =>
                    //     array (
                    //       0 => 'SeniorStylist',
                    //       1 => 'Expert',
                    //       2 => 'Kommunikationstrainer',
                    //     ),
                    //     'GroupValues' =>
                    //     array (
                    //       0 => 1,
                    //       1 => 2,
                    //       2 => 11,
                    //     ),
                    //     'Id' => 1106,
                    //     'LastModified' => '2020-08-16T12:10:24.85',
                    //     'LastName' => 'Albrecht',
                    //     'Name' => 'Anna Albrecht',
                    //     'PricingRailId' => 1,
                    //     'Description' => 'Friseurin aus Leidenschaft!
                    //   Spezialisiert auf Balayage und Strähnen.
                    //   Viele Jahre Berufserfahrung.',
                    //   )

                    $empl_count++;
                    // unset($value['LastModified'], $value['FirstName'], $value['LastName'], $value['PricingRailId']);

                    $value['SALONID'] = $salonId;
                    $value['ALT']     = helperblooms::umlautName($value['Name']);
                    //$imgArr[] ='';

                    /** @FIX by oppo (webiprog.de), @Date: 2021-09-09 10:30:30
                     * @Desc: Everything can remain so, but first the most fresh pictures of the cabin should be shown.
                     */
                    if (!empty($ids_salons[$salonId][$employeeId]) && is_array($ids_salons[$salonId][$employeeId])) {
                        usort($ids_salons[$salonId][$employeeId], function ($a, $b) {
                            return $b['Order'] <=> $a['Order'];
                        });
                    }

                    // echo "<br> key index ". $key_index;
                    $imgArr = array();
                    foreach ($ids_salons[$salonId][$employeeId] as $imgAll) {
                        $imgArr[] = $imgAll['Url'];
                    }

                    $value['allimg'] = $imgArr;

                    $value['key_index'] = $key_index;
                    $key_index++;

                    $new_url = $stylebookModel->urlStylebookImgResize($one_pic_url, $salonId, $employeeId, $local);
                    // array (
                    //     'full' => '/f3-blooms/upload/stylebookpictures/17/1106/8_18118782_1948617428758186_6016199437378247040_n.jpg',
                    //     'thumb' => '/f3-blooms/upload/stylebookpictures/17/1106/thumb/8_18118782_1948617428758186_6016199437378247040_n.jpg',
                    //   )

                    $value['PICTURE']    = bloomArrayHelper::getValueJoom($new_url, 'full', $one_pic_url, 'STRING');
                    $value['TH_PICTURE'] = bloomArrayHelper::getValueJoom($new_url, 'thumb', $one_pic_url, 'STRING');

                    $salonteamName       = bloomArrayHelper::getValueJoom($value, 'FirstName', null, 'STRING');
                    $value['Name']       = $salonteamName;
                    $value['UMLAUTNAME'] = strtolower($value['ALT']);

                    $value['webimages'] = $salonfinder->getEmployeeWebimagesFilename($value, $salonId, $employeeId, $local);

                    unset($value['Groups'], $value['GroupValues'], $value['LastName'], $value['PricingRailId']);
                    // echo '<pre style="color:green">';
                    // var_export($value);
                    // echo '</pre>';
                    // exit;

                    $arr_team[$employeeId] = $value;
                }

                unset($salonteam);

                if ($test_time) {

                    echo "<br />********* Salon: (" . $salonId . ") took = " . $ss->time() . " End <hr />";
                }

                if (!empty($arr_team)) {
                    $arr_salon[$salonId] = $arr_team;
                    unset($arr_team);
                }
            } // end foreacjh salon
            if ($test_time) {
                echo "<br />All salon " . $t->time() . " to execute this code.";
                echo "<br />----------------------<br />Employee count: " . $empl_count . "";

                // All salon 24s 538,33413124084ms to execute this code.
                // Employee count: 232
                // All salon 34s 308,30597877502ms to execute this code.-
                // Employee count: 232
            }
        }

        return $arr_salon;
    }

    /**
     * @var mixed
     */
    protected static $salonids = null;
    /**
     * @param $ids
     */
    protected function getSalonIds($ids = true, $salonId = 0)
    {

        if (self::$salonids === null) {
            // salons Controller
            $salons_ctrl = new salons();
            $salons      = $salons_ctrl->getSalonsController();
            if ($salonId) {
                self::$salonids = $salons[$salonId];
            } else {
                self::$salonids = $salons;
            }
        }
        if ($ids == true) {

            return array_keys(self::$salonids);
        }
        return self::$salonids;
    }

    /**
     * @param $salonteamName
     * @param $salonId
     * @param $employeeId
     * @param $only_one
     * @return mixed
     */
    public function OLDgetStylebookMitarbeiterImg($salonteamName, $salonId = 0, $employeeId = 0, $only_one = false)
    {
        // READ FILES FROM THE GALLERY FOLDER
        // '/assets/images/banner/'
        //https://www.php.net/manual/ru/function.glob.php

        if ($salonId && $employeeId) {

            $dir       = (UPLOAD_STYLEBOOK_ABS_DIR . $salonId . DIRECTORY_SEPARATOR . $employeeId . DIRECTORY_SEPARATOR);
            $folderUrl = UPLOAD_STYLEBOOK_DIR . $salonId . '/' . $employeeId . '/';
        } elseif ($salonId) {
            $dir       = (UPLOAD_STYLEBOOK_ABS_DIR . $salonId . DIRECTORY_SEPARATOR);
            $folderUrl = UPLOAD_STYLEBOOK_DIR . $salonId . '/';
        } else {
            $dir       = (UPLOAD_STYLEBOOK_ABS_DIR);
            $folderUrl = UPLOAD_STYLEBOOK_DIR;
        }

        $images = glob($dir . "*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE | GLOB_NOSORT);

        $key_index = 0;

        $stylebook_picture = [];
        if (!empty($images) && is_array($images)) {

            if ($only_one) {
                $current_images = current($images);
                unset($images);
                $images[] = $current_images;
            }

            foreach ($images as $key => $img) {

                $img_name = basename($img);
                if (substr($img_name, 0, 3) == 'th_') {
                    continue;
                }
                $original = $folderUrl . $img_name;
                if (is_file($dir . 'thumb/' . $img_name)) {
                    $thumb = $folderUrl . 'thumb/' . $img_name;
                } else {
                    $thumb = $original;
                }
                // array (
                //     'original' => '/f3-url-shortener/assets/images/stylebookpictures/2/953/1_tamara_kanow.jpg',
                //     'thumb' => '/f3-url-shortener/assets/images/stylebookpictures/2/953/thumb/1_tamara_kanow.jpg',
                //   ),
                $alt          = helperblooms::umlautName($salonteamName);
                $umlautname   = strtolower($alt);
                $arrSalonteam = array(
                    'Id'         => $employeeId,
                    'Name'       => $salonteamName,
                    'ALT'        => $alt,
                    'UMLAUTNAME' => $umlautname,
                    'SALONID'    => $salonId,
                    'PICTURE'    => $original,
                    'TH_PICTURE' => $thumb,
                    'key_index'  => $key_index
                );

                $key_index++;

                if ($only_one) {

                    return $arrSalonteam;
                    break;
                }

                array_push($stylebook_picture, $arrSalonteam);
            }
        }

        return $stylebook_picture;
    }

    /**
     * @param $offset
     * @param $len
     */
    public function arraySliceSalon($offset, $len = -1)
    {

        $array = $this->getSalonIds(true);
        //$lines = array_slice($file, 5, count($file)-6); should be $lines = array_slice($lines, 5, count($lines)-6);
        return bloomArrayHelper::array_slice_key($array, $offset, $len = -1);
    }

    public function importStylebookimg()
    {

        //https://developservice.de/kunden/blooms/1plus/stylebook/importStylebookimg
        $arraySalons = $this->getSalonIds(true);
        // sort($array);

        // Setting up pagination
        $pagination = array(
            'length'      => isset($_GET['length']) ? (int) $_GET['length'] : 3,
            'total'       => sizeof($arraySalons),
            'currentPage' => isset($_GET['page']) ? (int) $_GET['page'] : 1
        );

        if ($pagination['currentPage'] == 1) {
            echo '<pre style="color:#000">';
            echo '<h3>Start upload images</h3><p>Bitte Navigieren Sie nicht zu einer anderen Seite, es sei denn, es wird eine Abschluss- oder Fehlermeldung angezeigt.</p>';
            echo '</pre>';
            // $this->deleteDir();
            $this->f3->clear('SESSION.slise_stylebook');
        }

        $pagination['nbPages'] = ceil($pagination['total'] / $pagination['length']);
        $pagination['offset']  = ($pagination['currentPage'] * $pagination['length']) - $pagination['length'];

        // Paginated array
        $paginated = array_slice($arraySalons, $pagination['offset'], $pagination['length'], true);

        $ret = $this->cronImgStylebook($paginated, false);

        $slise_stylebook = $this->f3->get('SESSION.slise_stylebook');
        $popo            = $slise_stylebook . ' <hr />' . $pagination['currentPage'] . ')  ' . $ret;
        $this->f3->set('SESSION.slise_stylebook', $popo);
        // array (
        //     'length' => 3,
        //     'total' => 16,
        //     'currentPage' => 1,
        //     'nbPages' => 6.0,
        //     'offset' => 0,
        //   )

        // echo '<pre style="color:#fff">';
        //     var_export($pagination);
        // echo '</pre>';
        // exit;

        if (($pagination['currentPage']) < (int) $pagination['nbPages']) {

            header('Location: https://developservice.de/kunden/blooms/1plus/stylebook/importStylebookimg?page=' . ($pagination['currentPage'] + 1));
            exit();
        } else {

            $slise_stylebook = $this->f3->get('SESSION.slise_stylebook');
            echo '<pre style="color:#000">';
            var_export($slise_stylebook);
            echo '</pre>';
            exit();
        }

        // $len =
        // return bloomArrayHelper::array_sl4;ice_key($array, $offset, $len = -1);

    }

    public function deleteDir()
    {
        helperblooms::recursivelyRemoveDirectory(UPLOAD_STYLEBOOK_ABS_DIR, array('emp_image.jpg'));
    }

    /**
     * Error handler
     *
     * @param  integer $errno   Error level
     * @param  string  $errstr  Error message
     * @param  string  $errfile Error file
     * @param  integer $errline Error line
     * @return void
     */
    public function imgError($errno, $errstr, $errfile, $errline)
    {
        $error = array(
            'Number'  => $errno,
            'Message' => $errstr,
            'File'    => $errfile,
            'Line'    => $errline
        );
        $logger = new \Log($this->f3->get('LOGS') . 'Stylebook_pictures_error.log');
        $logger->write(implode(', ', $error));
    }

    /**
     * Shutdown handler
     *
     * @return void
     */
    public function imgShutdown()
    {
        if (($error = error_get_last())) {
            $logger = new \Log($this->f3->get('LOGS') . 'Stylebook_pictures_error.log');
            $logger->write($error);
        }
    }

    /**
     * @param  $salonId
     * @return mixed
     */
    public function cronImgStylebook($ids_salons, $json = false)
    {

        return;
        // Set whether a client disconnect should abort script execution, run script in background
        @ignore_user_abort(true);

        // Set maximum execution time, run script forever
        @set_time_limit(0);

        // Set maximum time in seconds a script is allowed to parse input data
        @ini_set('max_input_time', '-1');

        // Set maximum backtracking steps
        @ini_set('pcre.backtrack_limit', PHP_INT_MAX);

        @ini_set('max_execution_time', 600); //300 seconds = 5 minutes

        @ini_set('memory_limit', '512M');

        // Set binary safe encoding
        // if ( @function_exists( 'mb_internal_encoding' ) && ( @ini_get( 'mbstring.func_overload' ) & 2 ) ) {
        //     @mb_internal_encoding( 'ISO-8859-1' );
        // }

        // Clean (erase) the output buffer and turn off output buffering
        if (@ob_get_length()) {
            @ob_end_clean();
        }

        // Set error handler
        // @set_error_handler('stylebook::error');
        @set_error_handler(array($this, 'imgError'));

        // Set shutdown handler 'stylebook::shutdown'
        @register_shutdown_function(array($this, 'imgShutdown'));

        // !!!!! ******* delete  \assets\images\upload_stylebook**************
        // $this->deleteDir();

        if ($json != true) {
            //The result will be in seconds and milliseconds.
            $executionTime = new ExecutionTimeblooms();
            $executionTime->start();
        }
        $pic_count = 1;

        // salons Controller
        $salons_ctrl = new salons();

        // echo '<pre>';
        //     var_export($ids_salons);
        // echo '</pre>';
        // // exit;
        $stylebookModel = new stylebookModel();

        $arr_salon = [];
        if (!empty($ids_salons) && is_array($ids_salons)) {

            $base                     = $this->f3->get('BASE');
            $assets                   = $this->f3->get('ASSETS');
            $upload_stylebook_abs_dir = UPLOAD_STYLEBOOK_ABS_DIR;
            $ip                       = $this->f3->get('IP');
            $local                    = ($ip == '127.0.0.1' ? true : false);

            foreach ($ids_salons as $salonId) {

                //getCashe SalonTeam
                $salonteam = (array) $salons_ctrl->getSalonTeamController($salonId);
                // array (
                //     1090 =>
                //     array (
                //       'FirstName' => 'Henriette',
                //       'Id' => 1090,
                //       'LastModified' => '2019-11-19T07:36:55.673',
                //       'LastName' => 'Schütz',
                //       'Name' => 'Henriette Schütz',
                //       'PricingRailId' => 1,
                //     ),
                //     996 =>   array (     'FirstName' => 'Melanie',     'Id' => 996,     'LastModified' => '2019-11-19T07:36:55.673',     'LastName' => 'Scharff',     'Name' => 'Melanie Scharff',     'PricingRailId' => 1,   ),
                foreach ($salonteam as $employeeId => $value) {

                    // unset($value['LastModified'], $value['FirstName'], $value['LastName'], $value['PricingRailId']);
                    // unset($value['LastModified'], $value['PricingRailId']);
                    $target_abs = $upload_stylebook_abs_dir . $salonId . DIRECTORY_SEPARATOR . $employeeId;

                    helperblooms::op_mkdir($target_abs);

                    $alt    = helperblooms::umlautName($value['Name'], true);
                    $target = $salonId . '/' . $employeeId;

                    $stylebookImgArr = (array) $stylebookModel->getStylebookEmployeeImage($employeeId, $all = true);

                    $numer = 1;
                    foreach ($stylebookImgArr as $ord => $pic) {
                        // array (
                        //     'EmployeeId' => 912,
                        //     'Order' => 0,
                        //     'Picture' => '/9j/4A
                        // $value['avatar'] = $style
                        // if (isset($pic['Order'])) {
                        //     $name=$pic['Order']+1;
                        // }else {
                        //     $name=$numer;
                        // }
                        $name = $numer . '_' . strtolower($alt);

                        $user_time_stamp = strtotime($value['LastModified']);
                        $name            = $numer . '_' . $alt . '_' . $user_time_stamp;

                        $numer++;

                        $employee_image = (string) $pic['Picture'];
                        $filename       = $target . '/' . $name . '.jpg';
                        $data           = null;

                        if (!file_exists(UPLOAD_STYLEBOOK_ABS_DIR . $filename)) {

                            if ($employee_image) {
                                $pic_count++;
                                $data = base64_decode($employee_image);
                                try {
                                    file_put_contents(UPLOAD_STYLEBOOK_ABS_DIR . $filename, $data);

                                    if (file_exists(UPLOAD_STYLEBOOK_ABS_DIR . $filename)) {
                                        // $newPathImage = UPLOAD_STYLEBOOK_ABS_DIR . $target . '/th_' . $name . '.jpg';
                                        helperblooms::op_mkdir(UPLOAD_STYLEBOOK_ABS_DIR . $target . '/thumb/');
                                        $newPathImage = UPLOAD_STYLEBOOK_ABS_DIR . $target . '/thumb/' . $name . '.jpg';
                                        $newimage     = new GumletImageResize(UPLOAD_STYLEBOOK_ABS_DIR . $filename);
                                        //Dimensions    1080 x 1080 px (scaled to 255 x 325 px)
                                        $h = 325;
                                        $w = 255;
                                        // // $newimage->crop($w, $h, GumletImageResize ::CROPCENTER)->save($newPathImage);
                                        $newimage->resizeToHeight($h)->save($newPathImage);
                                        // $newimage->resizeToBestFit(800, 600)->save($newPathImage);
                                    }
                                    // $time = strtotime($value['LastModified']);
                                    // touch(UPLOAD_STYLEBOOK_ABS_DIR . $filename, $time);
                                } catch (\Exception $e) {
                                    $logger = new \Log($this->f3->get('LOGS') . 'Stylebook_pictures_error.log');
                                    $logger->write('Error :' . $e->getMessage() . '');
                                }
                            }
                        }
                    }

                    unset($stylebookImgArr, $pic, $value, $data);
                }
            }
        }

        @ignore_user_abort(false);

        if ($json != true) {
            $executionTime->end();
            $ret = '<br />=========================<br />';
            $ret .= '' . $executionTime;
            $ret .= '<br />=========================<br />';
            $ret .= 'Upload ' . $pic_count . ' images';
            return $ret;
        } else {
            // exit;
        }
    }
} // end class
