<?php

/**
 * @file: akademie.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-blooms\app\controllers
 * @created:    Mon Jul 27 2020
 * @author:     oppo, webiprog.de
 * @version:    1.0.0
 * @modified:   Tuesday January 21st 2020 6:04:56 pm
 * @copyright   (c) 2008-2020 Webiprog GmbH, UA. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

class akademie extends Controller
{


    private $isTesting = false;

    public function __construct()
    {
        parent::__construct();
        // $this->f3 = $f3;
        $this->db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
    }

    /**
     * @return mixed
     */
    private function banners()
    {

        $banners = [];

        $bannerAdmin = new bannerAkademieAdmin();
        $images      = (array) $bannerAdmin->loadBysort();

        $query = array(
            // 'playlist'        => $video_id,
            'enablejsapi'    => 1,
            'iv_load_policy' => 3,
            'disablekb'      => 1,
            'autoplay'       => 1,
            'modestbranding' => 1,
            // Показывается меню плеера перед началом проигрования. Не нужно показывать какие-либо символы плеера. 25.05.2020 16:19
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
        $def_carousel_interval = (int) $this->f3->get('carousel_interval');

        foreach ($images as $n => $img) {

            if (empty($img['carousel_interval'])) {
                $img['carousel_interval'] = $def_carousel_interval;
            }

            // false &&
            if (!empty($img['src'])) {
                $banners[] = ['type' => 'img', 'src' => BANNER_PARENT_URL_DIR . '/akademie-banner/' . $img['src'], 'interval' => $img['carousel_interval']];
                $yt++;
            } elseif (!empty($img['video_url'])) {

                if ($yt > 0) {
                    // $query['autoplay'] = 0;
                }
                /* @FIX by oppo (webiprog.de), @Date: 2020-03-18 11:34:55
                 * @Desc: LOOP
                 * https://sergeychunkevich.com/dlya-web-mastera/youtube-parametry/#param13
                 */
                // 'playlist'        => $video_id,
                $video_id = str_replace('https://www.youtube.com/embed/', '', $img['video_url']);
                if ($video_id) {
                    $query['playlist'] = $video_id;
                }

                $youtube_addon_url = '?' . http_build_query($query, '&');

                $youtube_url = rtrim($img['video_url'], '?');
                $youtube_url = $youtube_url . $youtube_addon_url;
                $banners[]   = ['type' => 'youtube', 'src' => $youtube_url, 'interval' => $img['carousel_interval']];
                $yt++;
            }
        }

        return $banners;
    }

    public function index()
    {

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Akademie");
        $this->f3->set('view', 'akademie.html');
        $this->f3->set('classfoot', 'akademie termine');
        // ADD JS
        // $addscripts = 'js/layout/akademie.js';
        $addscripts[] = 'js/layout/jquery.chainedvefore.js';
        $addscripts[] = 'js/layout/jquery.validate.min.js';
        $addscripts[] = 'js/layout/messages_de.js';
        $addscripts[] = 'js/layout/jquery.bootstrap.wizard.min.js';
        $addscripts[] = 'js/layout/APPOINTMENT_MAKER.js';
        $addscripts[] = 'js/layout/akademie-buchung.js';
        $addscripts[] = 'js/layout/akademie.js';
        // $this->f3->set('addscripts', array($addscripts));
        $this->f3->set('addscripts', $addscripts);

        $this->isTesting = isset( $_GET['test'] ) && $_GET['test'] == 'true' ;

        // var_dump($this->isTesting);die;
        

        /* @FIX by oppo @Date: 27.07.2020 17:58
         * @Desc: add slider
         */
        $banners = $this->banners();
        $this->f3->set('BANNERS', $banners);

        /* @FIX by oppo (webiprog.de), @Date: 2020-08-25 17:36:26
         * @Desc: add  seminare
         */
        $seminares = $this->getSeminares();
        $this->f3->set('SEMINARES', $seminares);

        $this->f3->set('ESCAPE', false);


        // Priese

        // $categories = $this->db->read('price-new-banner.json');

        $services = $this->getServicesWithPrices();
      
        
        // echo "<pre>";
        // print_r($services);
        // die;

        $this->f3->set(
            'createExtraInfoLabel',
            function ($item) {
                if (isset($item['extra_info']) && count($item['extra_info']) > 0) {

                    $line = array_map(function ($v) {
                        return '&bullet;' . $v;
                    }, $item['extra_info']);

                    return '<i data-toggle="tooltip" data-html="true"
                    data-placement="top"
                    class="fa fa-info-circle bloomstooltip"
                    id="bloomstip{{@KEY}}{{@KEY2}}" title=""
                    data-original-title="' . implode('<br/>', $line) . '">
                </i>';
                } else {
                    return '';
                }
            }
        );
        // $this->f3->set('categories', $categories);
        $this->f3->set('services', $services);

        // resend_code Clear session PDF
        // assets\js\layout\APPOINTMENT_MAKER.js
        if (($this->f3->exists('POST.resend_code')) && $_POST['resend_code'] == 1) {
            $this->f3->set('SESSION.pdf_sess', null);
        }

        $this->f3->set('salonId', null);
        $option_salon = self::select_salon();
        $this->f3->set('OPTION_SALON', $option_salon);
        $this->f3->set('ESCAPE', false);
    }


    private function getServicesWithPrices(){

        $spackModel = new servicepackageModel();


        $AllPrices = $spackModel->getCasheAllPrices();

        $categoryToServicePackageArray = $spackModel->getCasheCategorieService(true);

        $finalSs = [];

        array_map(function (&$sa) use (&$orderedCategories) {

            $orderedSubCategories = [];

            array_map(function ($sp) use ($sa, &$orderedSubCategories) {
                $orderedSubCategories[(int) $sp['WebSortOrder']] = $sp;
            }, $sa['ServicePackages']);

            ksort($orderedSubCategories);

            $orderedSubCategories = array_values($orderedSubCategories);

            $sa['ServicePackages'] = $orderedSubCategories;

            // var_dump($orderedSubCategories);
            // if (!empty($orderedSubCategories)) {
            //     $orderedCategories[] = $orderedSubCategories;
            // }
        }, $categoryToServicePackageArray);


        array_map(function ($sa) use (&$finalSs, $AllPrices) {
            $a = [];
            $a['Name'] = $sa['DisplayName'];
            $a['isAkedemyService'] = true;
            $a['Id'] = $sa['Id'];
            $a['ServicePackages'] = [];

            array_map(function ($sp) use (&$a, $AllPrices) {

                $price =  isset($AllPrices[ (string) $sp['ServicePackageId'] ]) ? $AllPrices[ (string) $sp['ServicePackageId'] ] : null;

                $sp['price'] = $price;
                $sp['Name'] = $sp['DisplayName'];
                $a['ServicePackages'][] = $sp;

            }, $sa['ServicePackages']);

            // $a['ServicePackages'] = $sa['ServicePackages'];
            $finalSs[] = $a;
        }, $categoryToServicePackageArray);


        foreach ($finalSs as $_s) {

            // if ($_s['Id'] == null) continue;
            $kliz_sel[] = [
                'title' => $_s['Name'],
                'id' => $_s['Id'],
                'isAkedemyService' => $_s['isAkedemyService'],
                'services' => $_s['ServicePackages']
            ];
        }

        return $kliz_sel;

    }

    static protected $seminares = null;

    public function getSeminares()
    {
        if (self::$seminares === null) {

            self::$seminares = $this->akademieSeminareImg();
        }
        return self::$seminares;
    }


    public function getPriese()
    {
        if (self::$seminares === null) {

            self::$seminares = $this->akademieSeminareImg();
        }
        return self::$seminares;
    }

    /**
     * @param $salonteamName
     */
    public function akademieSeminareImg($salonteamName = '')
    {
        // READ FILES FROM THE GALLERY FOLDER
        // '/assets/images/banner/'
        //https://www.php.net/manual/ru/function.glob.php

        // assets\images\akademie\seminare

        $base = $this->f3->get('BASE');

        $dir_seminare        = ONEPLUS_DIR_PATH . '/assets/images/akademie/seminare/';
        $base_seminare = $base . '/assets/images/akademie/seminare/';

        // $imgDir = $dir_seminare ;
        // $allowedExtensions = ['jpg', 'png', 'gif'];

        // foreach (new DirectoryIterator($imgDir) as $fileInfo) {
        //     if ($fileInfo->isDot()) continue;
        //     if (in_array($fileInfo->getExtension(), $allowedExtensions)) {
        //         echo $fileInfo->getFilename() . "<br>\n";
        //         echo '<img src="' . $folderUrl . '/' . $fileInfo->getFilename() . '"><br/>';
        //     }
        // }

        // $images = glob($dir_seminare  . "*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE | GLOB_NOSORT);
        // // (SORT_REGULAR, SORT_STRING, SORT_NUMERIC)
        // asort($images, SORT_NATURAL); // Correction as per post #5

        // $images = array_map(function ($salon) {

        //     return trim(basename($salon));
        //     // return trim($salon['Address']);
        // }, $images);


        $seminares = array(
            0 =>
            array(
                'src' => 'seminare_1.jpg',
                'title' => 'Neue Fachkräfte',
            ),
            1 =>
            array(
                'src' => 'seminare_2.jpg',
                'title' => 'Neue Azubis',
            ),
            2 =>
            array(
                'src' => 'seminare_3.jpg',
                'title' => 'Schnitt Trend Frauen',
            ),
            3 =>
            array(
                'src' => 'seminare_4.jpg',
                'title' => 'Schnitt Trend Männer',
            ),
            4 =>
            array(
                'src' => 'seminare_5.jpg',
                'title' => 'Hochstecken klassisch',
            ),
            5 =>
            array(
                'src' => 'seminare_6.jpg',
                'title' => 'Hochstecken kreativ',
            ),
            6 =>
            array(
                'src' => 'seminare_7.jpg',
                'title' => 'Farb- und Strähnentechniken',
            ),
            7 =>
            array(
                'src' => 'seminare_8.jpg',
                'title' => 'Fotoworkshop',
            ),
            8 =>
            array(
                'src' => 'seminare_9.jpg',
                'title' => 'Ausbildung zum Expert',
            ),
            9 =>
            array(
                'src' => 'seminare_10.jpg',
                'title' => 'Ausbildung zum Fachtrainer',
            ),
            10 =>
            array(
                'src' => 'seminare_11.jpg',
                'title' => 'Ausbildung zum Kommuniktaionstrainer',
            ),
            11 =>
            array(
                'src' => 'seminare_12.jpg',
                'title' => 'Expert Tagung',
            ),
            12 =>
            array(
                'src' => 'seminare_13.jpg',
                'title' => 'Fachtrainer Tagung',
            ),
            13 =>
            array(
                'src' => 'seminare_14.jpg',
                'title' => 'Kommunikationstrainer Tagung',
            ),
            14 =>
            array(
                'src' => 'seminare_15.jpg',
                'title' => 'Gute Umgangsformen',
            ),
            15 =>
            array(
                'src' => 'seminare_16.jpg',
                'title' => 'bloom´s Hair Happening',
            ),
            16 =>
            array(
                'src' => 'seminare_17.jpg',
                'title' => 'Azubis on Stage',
            ),
            17 =>
            array(
                'src' => 'seminare_18.jpg',
                'title' => 'Visagistenkurs',
            ),
        );


        $key_index = 0;

        $seminare_arr = [];

        helperblooms::op_mkdir($dir_seminare  . 'thumb/');

        foreach ($seminares as $key => $src) {

            $imagename = $src['src'];

            $imagename_local_url = $dir_seminare  . '' . $imagename;
            $imagename_local_url_thumb = $dir_seminare  . 'thumb/' . $imagename;

            $new_url_file = $new_url_file_thumb = $base_seminare  . '' . $imagename;

            if (!file_exists($imagename_local_url_thumb)) {

                try {

                    $newPathImage = $imagename_local_url_thumb;
                    $newimage     = new GumletImageResize($imagename_local_url);
                    $h = 255;
                    $w = 255;
                    $newimage->resizeToHeight($h, true)->save($newPathImage, IMAGETYPE_JPEG, 80, null);

                    $new_url_file_thumb = $base_seminare  . 'thumb/' . $imagename;
                } catch (\Exception $e) {

                    $new_url_file_thumb = $base_seminare  . '' . $imagename;
                    $logger = new \Log($this->f3->get('LOGS') . 'academ_pictures_error.log');
                    $logger->write('Error :' . $e->getMessage() . '');
                }
            } else {
                $new_url_file_thumb = $base_seminare  . 'thumb/' . $imagename;
            }

            $seminare_arr[] = array(
                'src' => $new_url_file,
                'thumb' => $new_url_file_thumb,
                'title' => $src['title'],
                'key_index' => $key_index,
            );

            $key_index++;
        }
        // end foreach
        return $seminare_arr;
    }


    /** MODELLTERMINE Steps */
    public function salon()
    {
        if ($this->f3->exists('PARAMS.id')) {
            $salonId = $this->f3->get('PARAMS.id');
            $this->f3->set('salonId', $salonId);
        } else {
            // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
            $this->f3->set('SESSION.error', 'akademie-buchung existiert nicht');
            $this->f3->reroute('/salons.html');
        }

        // resend_code Clear session PDF
        // assets\js\layout\APPOINTMENT_MAKER.js
        if (($this->f3->exists('POST.resend_code')) && $_POST['resend_code'] == 1) {
            $this->f3->set('SESSION.pdf_sess', null);
        }

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Akademie Buchung');
        $this->f3->set('view', 'akademie-buchung.html');
        $this->f3->set('classfoot', 'termine');

        // ADD JS
        // $addscripts[] = 'js/layout/jquery.chained.remote.js';
        $addscripts[] = 'js/layout/jquery.chainedvefore.js';
        $addscripts[] = 'js/layout/jquery.validate.min.js';
        $addscripts[] = 'js/layout/messages_de.js';
        $addscripts[] = 'js/layout/jquery.bootstrap.wizard.min.js';
        $addscripts[] = 'js/layout/APPOINTMENT_MAKER.js';
        $addscripts[] = 'js/layout/akademie-buchung.js';
        $this->f3->set('addscripts', $addscripts);

        $option_salon = $this->select_salon($salonId);
        $this->f3->set('OPTION_SALON', $option_salon);

        $this->f3->set('ESCAPE', false);
    }

    /**
     * @param  $salonId
     * @return mixed
     */
    public  function select_salon(
        $salonId = null,
        $text = 'Bitte Salon auswählen*'
    ) {
        // Bitte Salon auswahlen
        // - Wählen Sie einen Salon aus -

        $salons_ctrl = new salons();
        $salons = $salons_ctrl->getSalonsController();
        // <option value="Ludwigshafen Rhein-Galerie">Ludwigshafen Rhein-Galerie</option>
        $option_salon[] = '<option value="">' . $text . '</option>';
        if (!empty($salons) && is_array($salons)) {
            foreach ($salons as $skey => $sv) {
                $selected = '';
                if ($salonId == $skey) {
                    $selected = 'selected="selected"';
                }
                $option_salon[] =
                    '<option value="' .
                    $skey .
                    '" ' .
                    $selected .
                    '>' .
                    trim($sv['DisplayName']) .
                    '</option>';
            }
        }
        return $option_salon;
    }

    public function mitarbeiter()
    {
        $response = [];

        if ($this->f3->get('AJAX')) {
            header('Content-Type: application/json');

            // $logger = new \Log($this->f3->get('LOGS') . 'mitarbeiter2.log');
            // $logger->write('' . var_export($_REQUEST, true) . '');

            $option_salon = (int) $this->f3->get('GET.option_salon');
            // selectCombo
            // $option_salon = (int) $this->f3->get('GET.q');
            if (!$option_salon) {
                // assets\js\layout\jquery.chainedvefore.js
                $option_salon = (int) $this->f3->get('GET._value');
            }
            // Sun, 26 Jan 2020 07:26:55 +0100 [127.0.0.1] '_id' => 'option_salon',
            // Sun, 26 Jan 2020 07:26:55 +0100 [127.0.0.1] '_name' => 'option_salon',
            // Sun, 26 Jan 2020 07:26:55 +0100 [127.0.0.1] '_value' => '17',

            // Fri, 24 Jan 2020 13:51:12 +0100 [127.0.0.1] 'option_salon' => '2',
            if ($option_salon) {
                $salons_ctrl = new salons();
                $teams = (array) $salons_ctrl->getSalonTeamController($option_salon);

                // file_put_contents ( ONEPLUS_DIR_PATH."/team_t.txt" , var_export( $teams  , true),  LOCK_EX );

                //$response[""] = "Bitte Mitarbeiter auswählen";
                $response["0"] = "Alle Salonmitarbeiter";
                foreach ($teams as $key => $value) {

                    /** @FIX by oppo @Date: 2020-03-17 12:28:23
                     * @Desc: тут нужно показывать лишь только им�? /  Vornamen (без фамилии):
                     */
                    $response[$key] = $value['FirstName'];
                    //  $response[$key] = $value['Name'];
                }
                // $logger = new \Log($this->f3->get('LOGS') . 'teams.log');
                // $logger->write('' . var_export($teams, true) . '');

                // 1090 =>
                // array (
                // 'FirstName' => 'Henriette',
                // 'Id' => 1090,
                // 'LastModified' => '2019-11-19T07:36:55.673',
                // 'LastName' => 'Schütz',
                // 'Name' => 'Henriette Schütz',
                // 'PricingRailId' => 1,
                // ),
            } else {
                $response[""] = "Bitte zuerst Salon auswählen";
            }
        }

        //  file_put_contents ( ONEPLUS_DIR_PATH."/team_response.txt" , var_export( $response  , true),  LOCK_EX );
        //  file_put_contents ( ONEPLUS_DIR_PATH."/team_json.txt" , json_encode($response, JSON_FORCE_OBJECT),  LOCK_EX );
        echo  json_encode($response, JSON_PRETTY_PRINT);
        exit();
    }

    public function services()
    {
        // https://vike.io/ru/548778/
        $kliz_sel = [];

        $test = (int) helperblooms::inGet('test', 0);
        // if (true) {
        if ($test || $this->f3->get('AJAX')) {

            header('Content-Type: application/json');

            $employee_id = $this->f3->get('GET.Id');
            $salonId = $this->f3->get('GET.salonId');

            // $employee_id =  967;
            // $salonId = 25;
            if ($test) {
                $salonId = 25;
            }

            // Cleans query string before we run the selects below.
            // Allows A-Z, a-z, 0-9, whitespace, minus/hyphen, equals, ampersand, underscore, and period/full stop.
            $employee_id = preg_replace(
                "/[^A-Za-z0-9\s\-\=\&\_\.]/",
                "",
                $employee_id
            );

            $spackModel = new servicepackageModel();

            $diff = [];
            if ($employee_id > 0) {
                $diff = $spackModel->getServicepackByEmployeeid($employee_id);
            } else {
                // Alle Salonmitarbeiter
                $diff = $spackModel->getServicepackBysalon($salonId);
            }

            if ($diff && ($serv_array = helperblooms::jsJson($diff, true))) {

                $serv_array = array_unique($serv_array);

                // echo '<pre>';
                //     var_export($serv_array);
                // echo '</pre>';
                // exit;

                $Cat_array = $spackModel->getCasheCategorieService();

                // echo '<pre>';
                //     var_export($Cat_array);
                // echo '</pre>';
                // exit;

                if ($Cat_array) {
                    foreach ($Cat_array as $c => $sp) {
                        $services = [];
                        foreach ($sp['ServicePackages'] as $s => $pp) {
                            $BaseServicePackageId = $pp['BaseServicePackageId'];
                            $ServicePackageId = $pp['ServicePackageId'];

                            // echo '<pre>';
                            //     var_export($pp);
                            // echo '</pre>';
                            // // exit;

                            // text: option.Name,
                            // value: option.ServicePackageId,
                            // 'data-children': option.BaseServicePackageId

                            if ((int) $BaseServicePackageId === 0) {
                                if (in_array($ServicePackageId, $serv_array)) {


                                    $basicId = $this->getBasicId($ServicePackageId, $sp['ServicePackages']);

                                    $services[$ServicePackageId] = [
                                        // 'Name' => $pp['Name'],
                                        'Name' => trim($pp['DisplayName']),
                                        'ServicePackageId' => $ServicePackageId,
                                        'BaseServicePackageId' => $basicId
                                    ];
                                }
                            }
                        }
                        if (!empty($services) && is_array($services) && count($services)) {
                            $kliz_sel[] = [
                                'title' => $sp['Name'],
                                // 'title' => $sp['displayName'],
                                'id' => $sp['Id'],
                                'services' => $services
                            ];
                        }

                        unset($services);
                    }
                    //     'Description' => NULL,
                    //     'DisplayName' => NULL,
                    //     'Id' => 1,
                    //     'Name' => 'Frauen',
                    //     'ServicePackages' =>
                    //     array (
                    //       0 =>
                    //       array (
                    //         'BaseServicePackageId' => NULL,
                    //         'Description' => '• Nachschnitt der Ponykontur
                    //   • für Stammkunden mit Gutschein kostenfrei!',
                    //         'DisplayName' => 'Ponyservice',
                    //          => 'Ponyservice',
                    //         'ServicePackageId' => 69,
                    //       ),
                    //       1 =>
                    //       array (
                    //         'BaseServicePackageId' => NULL,
                    //         'Description' => '• Kompetente Beratung mit mehreren Vorschlägen
                    //   • Individuell auf Ihre Haarstruktur abgestimmter Haarschnitt
                    //   • Inkl. Waschen/Conditioner/ohne Föhnen oder Selbst-Fönen',
                    //         'DisplayName' => 'Schnitt (Cut & Go)',
                    //         'Name' => 'Schnitt (Cut & Go)',
                    //         'ServicePackageId' => 2,
                    //       ),
                    //       2 =>
                    //       array (
                    //         'BaseServicePackageId' => NULL,
                    //         'Description' => '• Beratung/Waschen/Conditioner
                    //   • professionelles Finish mit Rundbürste, Lockenstab oder Glätteisen
                    //   ',
                    //         'DisplayName' => 'Finish',
                    //         'Name' => 'Finish (Fönen)',
                    //         'ServicePackageId' => 3,
                    //       ),

                    //     ),
                }
            }

            // https://stackoverflow.com/questions/28041682/ajax-populate-selectlist-with-option-groups
            // https://stackoverflow.com/questions/8578814/groupby-in-javascript-to-group-json-data-and-populate-on-optgroup
            // http://jsfiddle.net/FG9Lg/1/
            // http://jsfiddle.net/FG9Lg/
            // https://stackoverflow.com/questions/35326594/populate-select-with-optgroup-using-jquery
            // http://jsfiddle.net/39xkzcck/
            // https://codepen.io/salomalo/pen/qBELbaO
            // http://jsfiddle.net/mzj0nuet/
        }
        print json_encode($kliz_sel);
        // print json_encode($response);
        // print json_encode([]);
        exit();
    }


    /**
     * to do
     * @param  $id
     * @param  $ServicePackages
     * @return mixed
     */
    protected function getBasicId($id, $ServicePackages)
    {

        foreach ($ServicePackages as $pp) {

            if (
                isset($pp['ServicePackageId'])  &&  isset($pp['BaseServicePackageId'])  && (int) $pp['BaseServicePackageId'] === (int) $id
            ) {
                //if((int) $id[0] === (int) $ServicePackageBasic->ServicePackage->BaseServicePackageId[0]){
                $basicId = $pp['ServicePackageId'];
                return $basicId;
            }
        }



        // array (
        //     'BaseServicePackageId' => NULL,
        //     'Description' => '• Kompetente Beratung mit mehreren Vorschlägen
        //   • Individuell auf ihre Haarstruktur abgestimmter Haarschnitt
        //   • Professionelles Finish mit Rundbürste, Lockenstab oder Glätteisen',
        //     'DisplayName' => 'Schnitt + Finish',
        //     'Name' => 'Schnitt + Fönen ',
        //     'ServicePackageId' => 4,
        //   )
        // array (
        //     'BaseServicePackageId' => 4,
        //     'Description' => '• Kompetente Beratung mit mehreren Vorschlägen
        //   • Individuell auf ihre Haarstruktur abgestimmter Haarschnitt
        //   • Professionelles Finish mit Rundbürste, Lockenstab oder Glätteisen
        //   ',
        //     'DisplayName' => 'Schnitt + Finish (lange Haare)',
        //     'Name' => 'Schnitt + Fönen Lang',
        //     'ServicePackageId' => 5,
        //   )


        //     foreach ($ServicePackages as $ServicePackageBasic) {

        //         // echo '<pre>';
        //         //     var_export($ServicePackageBasic);
        //         // echo '</pre>';
        //         // exit;
        //         if (
        //             (int) $ServicePackageBasic->ServicePackage->BaseServicePackageId[0] ===
        //             (int) $id[0]
        //         ) {
        //             //if((int) $id[0] === (int) $ServicePackageBasic->ServicePackage->BaseServicePackageId[0]){
        //             $basicId = $ServicePackageBasic->ServicePackage->Id;
        //             return $basicId;
        //         }
        //     }

    }

    /** @FIX by oppo , @Date: 2020-04-24 15:46:15
     * @Desc: they quietly, without notifying, changed the format of the return Json !!!!!
     * Was  : {"Available":false,"Time":"9:45 PM"}
     * Became {"Available":false,"Time":"21:45"}
     */

    /**
     * fix time  function
     *
     * @param [type] $time
     * @param string $toFormat
     * @return void
     */
    public static function convertTimeFormat($time, $toFormat = "24")
    {
        $convertTime = strtoupper($time);
        switch ($toFormat) {
            case "12":
                if (strpos($convertTime, 'AM') !== false || strpos($convertTime, 'PM') !== false) {
                    return $convertTime;
                } else {
                    $timeArr = explode(":", $convertTime);
                    if ($timeArr[0] < 12) {
                        return $convertTime . " AM";
                    } else {
                        $hour = $timeArr[0] - 12;
                        $min = $timeArr[1];
                        return $hour . ":" . $min . " PM";
                    }
                }
                break;
            case "24":
                if (strpos($convertTime, 'PM') !== false) {
                    $timeArr = explode(":", self::removePeriod($convertTime));
                    $hour = $timeArr[0] + 12;
                    $min = $timeArr[1];
                    return $hour . ":" . $min;
                } else if (strpos($convertTime, 'AM') !== false) {
                    return self::removePeriod($convertTime);
                } else {
                    return $convertTime;
                }
                break;
        }
        return $convertTime;
    }

    private static function removePeriod($time)
    {
        $patterns = array();
        $patterns[0] = '/am/';
        $patterns[1] = '/AM/';
        $patterns[2] = '/pm/';
        $patterns[3] = '/PM/';
        $patterns[4] = '/ /';
        $replacements = array();
        $replacements[0] = '';
        $replacements[1] = '';
        $replacements[2] = '';
        $replacements[3] = '';
        $replacements[4] = '';
        return trim(preg_replace($patterns, $replacements, $time));
    }

    public function terminFinden()
    {
        $kliz_sel = ['error' => true, 'html' => ''];
        header('Content-Type: application/json; charset=utf-8');

        date_default_timezone_set('Europe/Berlin');
        $loc = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
        // error_reporting(E_ERROR);
        // terminFinden
        // $salonId, $mitarbeiterId = "", $dienstleistungId
        // array ( 'option_salon' => '25', 'date' => 'Freitag, 31. Januar 2020', 'iso_date' => '2020-01-31', 'mitarbeiter' => '1025', 'servicePackage' => '4', )
        // $this->f3->set('ESCAPE', false);

        $test = (int) helperblooms::inGet('test', 0);
        // if (true) {
        if ($test || $this->f3->get('AJAX')) {
            // test
            // if (true) {
            if ($test || $this->f3->exists('POST.option_salon')) {
                $data = $this->f3->get('POST');
                // https://arjunphp.com/array_map-class-method-php/
                // //static functions in a class:
                // array_map(array('MyClass', 'MyFunction'), $array);

                $salonId = helperblooms::inPOST('option_salon');
                $mitarbeiterId = helperblooms::inPOST('mitarbeiter');
                // у�?луга Id service
                $dienstleistungId = helperblooms::inPOST('servicePackage');
                $datum = helperblooms::inPOST('iso_date');

                $ip = $this->f3->get('IP');
                $local = $ip == '127.0.0.1' ? true : false;

                // $test = true;
                //  Test local No access to primary server !!! �?FUK
                if ($test > 0) {
                    $salonId = 28;
                    $mitarbeiterId = '1025';
                    $mitarbeiterId = 0;
                    $dienstleistungId = '4';
                    $dienstleistungId = '2';

                    $now = new DateTime();
                    $test_date = $now->add(new DateInterval('P1W'))->format('Y-m-d');
                    $datum = $test_date;
                }
                if ($test > 0) {
                    $salonId = 14;
                    $mitarbeiterId = '721';
                    $dienstleistungId = '71';
                    $now = new DateTime();
                    $test_date = $now->add(new DateInterval('P1W'))->format('Y-m-d');
                    $datum = $test_date;
                }

                if ($test > 0) {
                    $salonId = 14;
                    $mitarbeiterId = '721';
                    $mitarbeiterId = 0;
                    $dienstleistungId = '71';

                    // $test_date =  date("Y-m-d", strtotime("+1 week"));
                    $now = new DateTime();
                    $test_date = $now->add(new DateInterval('P1W'))->format('Y-m-d');
                    $datum = $test_date;
                }


                // test on 24.04.2020 14:43

                // option_salon: 8
                // date: Donnerstag, 07. Mai 2020
                // iso_date: 2020-05-07
                // mitarbeiter: 0
                // servicePackage: 67

                if ($test > 1) {
                    $salonId = 8;
                    $mitarbeiterId = 0;
                    $dienstleistungId = '67';
                    $now = new DateTime();
                    $test_date = $now->add(new DateInterval('P1W'))->format('Y-m-d');
                    $datum = $test_date;
                }

                // here 2 people for test  == small !!
                //  https://developservice.de/kunden/blooms/1plus/salons/details/14
                //  https://developservice.de/kunden/blooms/1plus/termine/salon/14
                if ($test == 2) {
                    $salonId = 14; // 2 users
                    $salonId = 30; // 4 users
                    $salonId = 2; // 6 users
                    $salonId = 25; // 8 users
                    // $salonId = 4; // 8 users
                    $mitarbeiterId = 0;
                    $dienstleistungId = '67';
                    $now = new DateTime();
                    $test_date = $now->add(new DateInterval('P1W'))->format('Y-m-d');
                    $datum = $test_date;
                }


                $employeeimage_abs_dir = EMPLOYEEIMAGE_ABS_DIR;
                $target = $employeeimage_abs_dir . $salonId;
                helperblooms::op_mkdir($target);

                $heute = new \DateTime('NOW'); //Heute
                // $heute = new \DateTime('today midnight'); //Heute
                $term_date = new \DateTime($datum); //Abstimmung
                $diff = $term_date->diff($heute)->format("%a");
                $mdaw = clone $term_date;

                if ($diff == 0) {
                    $anfangsdatum = $mdaw->format('Y-m-d');
                } elseif ($diff >= 5) {
                    $anfangsdatum = $mdaw->modify('-5 day')->format('Y-m-d');
                } else {
                    $anfangsdatum = $mdaw->modify('-' . $diff . ' day')->format('Y-m-d');
                }

                $timeTable_dropdown = null;

                $termineModel = new termineModel(null);

                // SELECTED EMPLOER
                // ********** EACH ONLY THIS USER (EMPLOER) FROM SALON  **********
                if ($mitarbeiterId) {
                    $newTimes = [];
                    $show = 10;
                    $date_arr = [];

                    for ($i = 0; $i <= $show; $i++) {
                        $date = date(
                            "Y-m-d",
                            strtotime($anfangsdatum . " + " . $i . " day")
                        );
                        $date_arr[] = $date;
                        // $termineModel = new termineModel( null );
                        // getAllTimes($salonId, $mitarbeiterId = '', $dienstleistungId, $datum)
                        $xml = $termineModel->getAllTimes(
                            $salonId,
                            $mitarbeiterId,
                            $dienstleistungId,
                            $date
                        );

                        // 912 =>
                        // array (
                        //   'Id' => 912,
                        //   'Name' => 'Lisa Täubl',
                        //   'Slots' =>
                        //   array (
                        //     0 =>
                        //     array (
                        //       'Available' => false,
                        //       'Time' => '8:00 AM',
                        //     ),
                        //     4 =>
                        //     array (
                        //       'Available' => true,
                        //       'Time' => '9:00 AM',
                        //     ),
                        if ($xml) {
                            // $xml['Date'] = $date;
                            // array_push($xml,$date);
                            array_push($newTimes, $xml);
                        }
                    }

                    $body = '';
                    // $body .= '<form id="dateSelect" action="" method="post">';
                    //  d-flex justify-content-between
                    $body .=
                        '<div class="multiple-items" id="termine-content">';
                    $width = 0;
                    // Generate HTML: Timetable
                    $salonfinder = new salonsModel();
                    $salonteam = (array) $salonfinder->getCasheSalonTeam($salonId, false);
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

                    // $mitarbeiter      = $salonteam[$mitarbeiterId];
                    $mitarbeiter = bloomArrayHelper::getValueJoom(
                        $salonteam,
                        $mitarbeiterId,
                        null
                    );
                    if (empty($mitarbeiter)) {
                        print json_encode($kliz_sel);
                        exit();
                    }

                    $availableCounter = 0;

                    $key_index = 0;

                    foreach ($newTimes as $d => $Times) {
                        // дата в тижні �?товпчик
                        $zag_date = $date_arr[$d];



                        foreach ($Times as $t => $timeTableData) {
                            // 1 =>
                            // array (
                            //   912 =>
                            //   array (
                            //     'Id' => 912,
                            //     'Name' => 'Lisa Täubl',
                            //     'Slots' =>
                            //     array (
                            //       0 =>
                            //       array (
                            //         'Available' => false,
                            //         'Time' => '8:00 AM',
                            //       ),
                            //       1 =>

                            //Mitarbeiterbild holen

                            /* выводит: vrijdag 22 december 1978 */
                            // echo strftime("%A %d %B %Y", mktime(0, 0, 0, 12, 22, 1978));
                            // $wochentag = date("D", strtotime($zag_date));
                            // $wochentag = strftime("%A", strtotime($zag_date));
                            $wochentag = strftime("%A", strtotime($zag_date));
                            $wochentag = utf8_encode($wochentag);
                            // $wochentag = 'Donnerstag 05 Mдrz 2020';
                            // $wochentag = 'Samstag 29 Februar 2020';
                            // $wochentag = strtr($wochentag, $trans);
                            $datum_short = date("d.m.", strtotime($zag_date));

                            // $wochentag_aj = date("D, d. F Y", strtotime($zag_date));
                            $wochentag_aj = strftime("%A, %d. %B %Y", strtotime($zag_date));
                            $wochentag_aj = utf8_encode($wochentag_aj);
                            // $wochentag_aj = $wochentag;

                            $avatar = $salonfinder->getMitarbeiterFilename(
                                $mitarbeiter,
                                $salonId,
                                $mitarbeiterId,
                                $local
                            );

                            /* @FIX by oppo (1plus.de), @Date: 29.05.2020
                            * @Desc: add Employee Webimages
                            */
                            $filename = $salonfinder->getEmployeeWebimagesFilename($mitarbeiter, $salonId, $mitarbeiterId, $local);

                            $description            = trim(bloomArrayHelper::getValueJoom($mitarbeiter, 'Description', '', 'STRING'));

                            if ($description) {
                                // add description 27.05.2020
                                // $description = preg_replace('/\R+/', "", $description);
                                $description =  strip_tags(html_entity_decode($description));
                                $description = preg_replace('~[\r\n]+~', "\n", $description);
                                // $description = preg_replace('/\s(?=\s)/', "", $description);
                                $description = preg_replace('/([\s])\1+/', " ", $description);
                                $description =  nl2br($description);

                                $description = preg_replace('/<br(\s+\/)?>/', "<br />", $description);
                            }
                            // d-flex flex-column justify-content-center
                            $body .=
                                '<div class="termine-data-box"><div>';
                            // $slug=Web::instance()->slug($f3->get('POST.title'));

                            $datytip = '';
                            if ($description) {
                                $datytip = '<i class="fa fa-info-circle dll ohrecha toggle-modal-sam"></i>';
                            }


                            $body .=
                                '<div id="' . $key_index . '-prevsalo"
                                data-alluser="0"
                                data-salonid="' . $salonId . '"
                                data-mid="' . $mitarbeiter['Id'] . '"
                                data-index="' . $key_index . '"
                                data-title_employee="' . $mitarbeiter['FirstName'] . '"
                                data-img_employee="' . $filename . '"
                                data-employee_description="' . $description . '"
                                id="' . $mitarbeiter['Id'] . '"
                                class="prevsalo position-relative termine-data-hover-2">
                                <img class="d-none" src="' .
                                $avatar .
                                '" alt="' . helperblooms::umlautName($mitarbeiter['FirstName']) . '"/>
                                ' . $datytip . '
                                <p class="over-image-text termine-name-bg-black">' .
                                $mitarbeiter['FirstName'] .
                                '</p>
                                </div>';

                            $body .=
                                ' <div class="termine-data-time-slots termine-data-price my-0 bg-light text-dark p-1">
                            <p class="align-self-center m-0">' .
                                $wochentag .
                                '</p>
                            <p class="align-self-center m-0"><span class="futura-mtbt">' .
                                $datum_short .
                                '</span></p>
                            </div>';

                            $key_index++;


                            foreach ($timeTableData['Slots'] as $slot) {
                                // $slot = (object) $slot;
                                /** @FIX by oppo @Date: 2020-02-13 16:07:33
                                 * @Desc:  Time should be in the German way. No AM or PM. And the time of booking should be up to 20 hours.
                                 */

                                /** @FIX by oppo , @Date: 2020-04-24 15:52:14
                                 * @Desc: they quietly, without notifying, changed the format of the return Json
                                 */
                                // $slot['Time'] = DateTime::createFromFormat('h:i A', $slot['Time'])->format('H:i');
                                $slot['Time'] = self::convertTimeFormat($slot['Time']);

                                //  !! ***  MORE >  20:00    the time of booking should be up to 20 hours.
                                $chunks = explode(':', $slot['Time']);
                                if ($chunks[0] == '20' && intval($chunks[1]) > '0') {
                                    break;
                                }

                                // $mir_avatar_shot = str_replace(UPLOAD_EMPLOYEE_WEBIMAGES_DIR, '', $filename);
                                $mir_avatar_shot = str_replace(EMPLOYEEIMAGE_DIR, '', $avatar);

                                /**
                                 * @Date: 2020-04-27 18:03:18
                                 * @Desc: data-firstname= fix name - Lisa Täubl to Lisa
                                 */

                                if ($slot['Available'] == 'true') {
                                    $availableCounter++;
                                    $body .=
                                        '<div class="termine-data-time-slots my-0 bg-light text-dark p-3 available" title="Diesen Termin buchen"
                                    data-salonid="' .
                                        $salonId .
                                        '" data-mid="' .
                                        $mitarbeiter['Id'] .
                                        '" data-miname="' .
                                        $mitarbeiter['Name'] .
                                        '" data-firstname="' .
                                        $mitarbeiter['FirstName'] .
                                        '" data-miavatar="' .
                                        $mir_avatar_shot .
                                        '" data-time="' .
                                        $slot['Time'] .
                                        '" data-fulldate="' .
                                        $wochentag_aj .
                                        '" data-date="' .
                                        $zag_date .
                                        '"><p class="align-self-center m-0">' .
                                        $slot['Time'] .
                                        '</p></div>';
                                } else {
                                    $body .=
                                        '<div class="termine-data-time-slots my-0 bg-dark-2 p-3 notAvailable"><p class="align-self-center m-0">' .
                                        $slot['Time'] .
                                        '</p></div>';
                                }
                            }
                            $body .= '</div>';
                            $body .= '</div>';
                            // $body .= '<div class="mitarbeiter_separator">&nbsp;</div>';

                            $width += 84;
                        }
                    }
                    $body .= '</div>';

                    $timeTable_dropdown = $body;
                } else {

                    // ********** EACH ALL USER FROM SALON  **********

                    $timeTableData = $timeTable_dropdown = null;
                    // getAllTimes($salonId, $mitarbeiterId = '', $dienstleistungId, $datum)
                    $timeSalonAllMirabiter = $termineModel->getAllTimes(
                        $salonId,
                        $mitarbeiterId,
                        $dienstleistungId,
                        $datum
                    );

                    $body = '';
                    // $body .= '<form id="dateSelect" action="" method="post">';
                    $body .=
                        '<div class="multiple-items" id="termine-content">';
                    $width = 0;
                    // Generate HTML: Timetable
                    $salonfinder = new salonsModel(null);
                    $salonteam = (array) $salonfinder->getCasheSalonTeam($salonId, false);

                    if (empty($salonteam)) {
                        print json_encode($kliz_sel);
                        exit();
                    }
                    /** @FIX by oppo (1plus.de), @Date: 2020-05-27 10:46:25
                     * @Desc: - sorting employees should be alphabetically
                     */
                    // if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                    //     uasort($salonteam, function ($a, $b) {
                    //         return $a['FirstName'] <=> $b['FirstName'];
                    //     });
                    // } else {
                    //     uasort($salonteam, function ($a, $b) {
                    //         return strnatcmp($a['FirstName'], $b['FirstName']);
                    //     });
                    // }



                    if ($test >= 2) {
                        echo 'salonteam:: ';
                        echo '' . PHP_EOL . "\n";
                        var_export($salonteam);
                        echo '' . PHP_EOL . "\n";
                        echo '-------------------' . PHP_EOL . "\n";
                        // exit;
                        echo 'timeSalonAllMirabiter:: ';
                        echo '' . PHP_EOL . "\n";
                        var_export($timeSalonAllMirabiter);
                        echo '' . PHP_EOL . "\n";
                        echo '-------------------' . PHP_EOL . "\n";
                        // exit;

                        if (!$timeSalonAllMirabiter) {
                            die(' !! not timeSalonAllMirabiter');
                        }
                    }

                    // 1090 =>
                    // array (
                    //   'Id' => 1090,
                    //   'Name' => 'Henriette Schütz',
                    //   'Slots' =>
                    //   array (
                    //     0 =>
                    //     array (
                    //       'Available' => false,
                    //       'Time' => '8:00 AM',
                    //     ),
                    //     1 =>
                    //     array (
                    $availableCounter = 0;
                    $mitarbeiterId = 0;
                    $zag_date = $datum;

                    /** @FIX by oppo  @Date: 2020-04-24 16:07:21
                     * @Desc:  some salons don't give away information $timeSalonAllMirabiter (вин�?ток - 8  )
                     */
                    if (!empty($timeSalonAllMirabiter) && is_array($timeSalonAllMirabiter)) {



                        /** @FIX by oppo (1plus.de), @Date: 2020-05-27 10:46:25
                         * @Desc: - sorting employees should be alphabetically
                         */
                        // if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                        //     uasort($timeSalonAllMirabiter, function ($a, $b) {
                        //         return $a['Name'] <=> $b['Name'];
                        //     });
                        // } else {
                        //     uasort($timeSalonAllMirabiter, function ($a, $b) {
                        //         return strnatcmp($a['Name'], $b['Name']);
                        //     });
                        // }

                        $key_index = 0;

                        foreach ($timeSalonAllMirabiter as $mi => $timeTableData) {
                            $mitarbeiterIdSlot = $timeTableData['Id']; //  'Id' => 912,
                            // $mitarbeiterIdSlot = $mi ; //  'Id' => 912,
                            // getValueJoom(&$array,$name,$default = null,$type = ''
                            //'ARRAY'
                            $mitarbeiter = bloomArrayHelper::getValueJoom(
                                $salonteam,
                                $mitarbeiterIdSlot,
                                null
                            );
                            // 'FirstName' => 'Henriette',
                            // 'Id' => 1090,
                            // 'LastModified' => '2019-11-19T07:36:55.673',
                            // 'LastName' => 'Schütz',
                            // 'Name' => 'Henriette Schütz',
                            // 'PricingRailId' => 1,
                            // $mitarbeiter   = $salonteam[$mitarbeiterIdSlot];
                            if (!$mitarbeiter) {
                                continue;
                            }

                            $wochentag = strftime("%A", strtotime($zag_date));
                            // !! FIX UMLAUT 14.02.2020 06:25
                            $wochentag = utf8_encode($wochentag);
                            // $wochentag = strtr($wochentag, $trans);
                            $datumShot = date("d.m.", strtotime($zag_date));

                            // $wochentag_aj = date("D, d. F Y", strtotime($zag_date));
                            // https://stackoverflow.com/questions/14821998/translate-dated-f-y-hi-function-php
                            //echo strftime("%A %d %B %Y",time());
                            $wochentag_aj = strftime("%A, %d. %B %Y", strtotime($zag_date));
                            $wochentag_aj = utf8_encode($wochentag_aj);
                            // $wochentag_aj = $wochentag;


                            // getMitarbeiterFilename($value, $salonId, $employeeId, $local = false)
                            $avatar = $salonfinder->getMitarbeiterFilename(
                                $mitarbeiter,
                                $salonId,
                                $mitarbeiterIdSlot,
                                $local
                            );

                            /* @FIX by oppo (1plus.de), @Date: 29.05.2020
                            * @Desc: add Employee Webimages
                            */
                            $filename = $salonfinder->getEmployeeWebimagesFilename($mitarbeiter, $salonId, $mitarbeiterIdSlot, $local);

                            $description            = trim(bloomArrayHelper::getValueJoom($mitarbeiter, 'Description', '', 'STRING'));

                            if ($description) {
                                // add description 27.05.2020
                                // $description = preg_replace('/\R+/', "", $description);
                                $description =  strip_tags(html_entity_decode($description));
                                $description = preg_replace('~[\r\n]+~', "\n", $description);
                                // $description = preg_replace('/\s(?=\s)/', "", $description);
                                $description = preg_replace('/([\s])\1+/', " ", $description);
                                $description =  nl2br($description);
                                //$text = preg_replace("/<br\n\W*\/>/", "\n", $text);
                                $description = preg_replace('/<br(\s+\/)?>/', "<br />", $description);
                            }


                            // data-toggle="modal" data-target="#termineDataModal"
                            // d-flex flex-column justify-content-center

                            $body .=
                                '<div class="termine-data-box"><div>';
                            // $slug=Web::instance()->slug($f3->get('POST.title'));
                            $datytip = '';
                            if ($description) {
                                $datytip = '<i class="fa fa-info-circle dll ohrecha toggle-modal-sam"></i>';
                            }

                            $body .=
                                '<div id="' . $key_index . '-prevsalo"
                                data-alluser="1"
                                data-salonid="' . $salonId . '"
                                data-mid="' . $mitarbeiter['Id'] . '"
                                data-index="' . $key_index . '"
                                data-title_employee="' . $mitarbeiter['FirstName'] . '"
                                data-img_employee="' . $filename . '"
                                data-employee_description="' . $description . '"
                                id="' . $mitarbeiter['Id'] . '"
                                class="prevsalo position-relative termine-data-hover-2">
                                <img class="d-none" src="' .
                                $avatar .
                                '" alt="' . helperblooms::umlautName($mitarbeiter['FirstName']) . '"/>
                                ' . $datytip . '
                                <p class="over-image-text termine-name-bg-black">' .
                                $mitarbeiter['FirstName'] .
                                '</p>
                                </div>';
                            /** @FIX by oppo (webiprog.de), @Date: 2020-08-19 19:19:59
                             * @Desc: Дату при пошуку по ВСІМ �?півробітникам виводити не потрібно (hide d-none)
                             */
                            $body .=
                                '<div class="termine-data-time-slots hide d-none termine-data-price my-0 bg-light text-dark p-1">
                                <p class="align-self-center m-0">' .
                                $wochentag .
                                '</p>
                                <p class="align-self-center m-0">
                                <span class="futura-mtbt">' .
                                $datumShot .
                                '</span>
                                </p>
                                </div>';


                            $key_index++;


                            foreach ($timeTableData['Slots'] as $slot) {
                                // $slot = (object) $slot;
                                /** @FIX by oppo @Date: 2020-02-13 16:07:33
                                 * @Desc:  Time should be in the German way. No AM or PM. And the time of booking should be up to 20 hours.
                                 */


                                /** @FIX by oppo , @Date: 2020-04-24 15:52:14
                                 * @Desc: they quietly, without notifying, changed the format of the return Json
                                 */
                                // $slot['Time'] = DateTime::createFromFormat('h:i A', $slot['Time'])->format('H:i');
                                $slot['Time'] = self::convertTimeFormat($slot['Time']);

                                // 36 =>
                                // array (
                                //   'Available' => false,
                                //   'Time' => '17:00',
                                // ),

                                if ($test >= 2) {
                                    echo 'Time Slots';
                                    var_export($slot['Time']);
                                    echo '</pre>';
                                    exit;
                                }


                                //  !! ***  MORE >  20:00    the time of booking should be up to 20 hours.
                                $chunks = explode(':', $slot['Time']);
                                if ($chunks[0] == '20' && intval($chunks[1]) > '0') {
                                    break;
                                }

                                // $mir_avatar_shot = str_replace(UPLOAD_EMPLOYEE_WEBIMAGES_DIR, '', $filename);
                                $mir_avatar_shot = str_replace(EMPLOYEEIMAGE_DIR, '', $avatar);

                                /**
                                 * @Date: 2020-04-27 18:03:18
                                 * @Desc: data-firstname= fix name - Lisa Täubl to Lisa
                                 */
                                if ($slot['Available'] == 'true') {
                                    $availableCounter++;
                                    $body .=
                                        '<div class="termine-data-time-slots my-0 bg-light text-dark p-3 available" title="Diesen Termin buchen"
                                    data-salonid="' .
                                        $salonId .
                                        '" data-mid="' .
                                        $mitarbeiter['Id'] .
                                        '" data-miname="' .
                                        $mitarbeiter['Name'] .
                                        '" data-firstname="' .
                                        $mitarbeiter['FirstName'] .
                                        '" data-miavatar="' .
                                        $mir_avatar_shot .
                                        '" data-time="' .
                                        $slot['Time'] .
                                        '" data-fulldate="' .
                                        $wochentag_aj .
                                        '" data-date="' .
                                        $zag_date .
                                        '"><p class="align-self-center m-0">' .
                                        $slot['Time'] .
                                        '</p></div>';
                                } else {
                                    $body .=
                                        '<div class="termine-data-time-slots my-0 bg-dark-2 p-3 notAvailable"><p class="align-self-center m-0">' .
                                        $slot['Time'] .
                                        '</p></div>';
                                }
                            }
                            $body .= '</div>';
                            $body .= '</div>';
                        }
                    }
                    $body .= '</div>';
                    $timeTable_dropdown = $body;
                }

                unset($body);
                // $availableCounter = 0;
                if ($availableCounter == 0) {
                    $timeTable_dropdown =
                        '<div style="color: #fffff" class="mb-5" id="no-data-available"><p>Leider ist zu dieser Zeit kein Termin verf&uuml;gbar.</p><p>Bitte wählen Sie einen anderen Termin oder eine/n andere/n Mitarbeiter/in aus.</p></div>' . $timeTable_dropdown;
                } else {
                    $timeTable_dropdown = '<p id="termin_klicken" class="mb-5">Alle freien Termine werden in weiß angezeigt. Zum Buchen einfach auf den gewünschten Termin klicken!</p>' . $timeTable_dropdown;
                }

                // option_salon, 16
                // termine.js:345 date, Donnerstag, 13. Februar 2020
                // termine.js:345 iso_date, 2020-02-13
                // termine.js:345 mitarbeiter, 1082
                // termine.js:345 servicePackage, 3

                //     $salonId          = 25;
                //     $mitarbeiterId    = '1025';
                //     $dienstleistungId = '4';
                //     $datum            = '2020-02-05';

                // $kliz_sel = ['error' => false, 'html' => var_export($timeTable_dropdown, true)];
                $kliz_sel = [
                    'error' => false,
                    'html' => $timeTable_dropdown,
                    'salonId' => $salonId,
                    'mitarbeiterId' => $mitarbeiterId,
                    'dienstleistungId' => $dienstleistungId,
                    'datum' => $datum
                ];
            }
        }
        // file_put_contents(ONEPLUS_DIR_PATH . "/terminFinden_{$salonId}.log", var_export($kliz_sel, true), FILE_APPEND | LOCK_EX);
        //exit;
        // print json_encode($kliz_sel, JSON_UNESCAPED_UNICODE);

        try {

            if ($kliz_sel && is_array($kliz_sel)) {
                echo json_encode($kliz_sel, JSON_UNESCAPED_UNICODE);
                unset($timeTable_dropdown);
            } else {
                $timeTable_dropdown =
                    "<div id='no-data-available'><p>Leider ist zu dieser Zeit kein Termin verf&uuml;gbar.</p><p>Bitte wählen Sie einen anderen Termin oder eine/n andere/n Mitarbeiter/in aus.</p></div>";
                $kliz_sel = ['error' => true, 'html' => $timeTable_dropdown];
                echo json_encode($kliz_sel, JSON_UNESCAPED_UNICODE);
                //   echo json_encode( array( 'error' => 'wrong username or password' ) );
            }
        } catch (Exception $ex) {
            $kliz_sel = ['error' => true, 'html' => 'Interner Serverfehler'];
            echo json_encode($kliz_sel, JSON_UNESCAPED_UNICODE);
            // echo json_encode( array( 'error' => $ex->getMessage() ) );
            file_put_contents(ONEPLUS_DIR_PATH . "/terminFinden_{$salonId}.log", var_export($kliz_sel, true), FILE_APPEND | LOCK_EX);
        }


        exit();
    }

    public function confirmTabtwo()
    {

        // http://localhost/f3-url-shortener/termine/confirmTabtwo?test=1&option_salon=25&servicePackage=2

        $kliz_sel = ['error' => true, 'html' => ''];

        header('Content-Type: application/json');

        date_default_timezone_set('Europe/Berlin');
        $loc = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
        // error_reporting(E_ERROR);
        // terminFinden
        // $salonId, $mitarbeiterId = "", $dienstleistungId
        // array ( 'option_salon' => '25', 'date' => 'Freitag, 31. Januar 2020', 'iso_date' => '2020-01-31', 'mitarbeiter' => '1025', 'servicePackage' => '4', )
        // $this->f3->set('ESCAPE', false);

        $test = (int) helperblooms::inGet('test', 0);
        // if (true) {
        if ($test || $this->f3->get('AJAX')) {

            if ($test || $this->f3->exists('GET.option_salon')) {

                $data = $this->f3->get('GET');

                $salonId = helperblooms::inGET('option_salon');
                $mitarbeiterId = helperblooms::inGET('mitarbeiter');
                // у�?луга Id service
                $dienstleistungId = helperblooms::inGET('servicePackage');
                $datum = helperblooms::inGET('iso_date');
                // salons Controller
                $salons_ctrl = new salons();
                $salons = $salons_ctrl->getSalonsController($salonId);
                $salonAddress = trim(bloomArrayHelper::getValueJoom($salons, 'Address', null, 'STRING'));
                $salonPhone = trim(bloomArrayHelper::getValueJoom($salons, 'Phone', null, 'STRING'));
                // salonAddress

                //     14 =>
                //     stdClass::__set_state(array(
                //        'Address' => 'Hardtgasse 1, Worms ',
                //        'CategoryDisplayName' => ' ',
                //        'Description' => 'Mitten in Worms - mitten in der Fußgängerzone.
                //   ',
                //        'DisplayName' => 'Worms, Hardtgasse ',
                //        'GooglePlacesUrl' => 'https://goo.gl/maps/7QEBG2grQT31xwsN6',
                //        'Id' => 14,
                //        'ImageUrl' => 'salonbild_wo1.jpg',
                //        'Name' => 'Worms 1',
                //        'OnlineAppointmentsEnabled' => true,
                //        'OpeningHours' => 'Montag - Freitag: 9:00 - 19:30 Uhr
                //   Samstag: 8:00 - 18:00 Uhr ',
                //        'Phone' => '06241 2686996',
                //     )),
                $spackModel = new servicepackageModel();
                // add cashe 25.05.2020
                $servicepackage = $spackModel->getServicepackageAll();
                // $servicepackage = $spackModel->getCasheServicepackageAll();
                $DienstleistungArr = bloomArrayHelper::getValueJoom($servicepackage, $dienstleistungId, null);

                //     'BaseServicePackageId' => NULL,
                //     'Description' => '• Kompetente Beratung mit mehreren Vorschlägen
                //   • Individuell auf Ihre Haarstruktur abgestimmter Haarschnitt
                //   • Inkl. Waschen/Conditioner/ohne Föhnen oder Selbst-Fönen',
                //     'DisplayName' => 'Schnitt (Cut & Go)',
                //     'Name' => 'Schnitt (Cut & Go)',
                //     'ServicePackageId' => 2,

                $rD = ['Description' => '', 'DisplayName' => ''];
                if ($DienstleistungArr) {


                    $description =  bloomArrayHelper::getValueJoom($DienstleistungArr, 'Description', null);
                    // $description = str_replace('\r\n','\r\n',$description);

                    if ($description) {
                        $description = preg_split('~\R~', $description);
                        $description = array_map('trim', array_filter($description));
                        $description = implode("\n", $description);
                    }

                    $rD = [
                        'Description' => $description,
                        'DisplayName' => bloomArrayHelper::getValueJoom($DienstleistungArr, 'DisplayName', null)
                    ];
                }

                // $kliz_sel = ['error' => false, 'salonAddress' => $salonAddress, 'salonAddress' => $salonAddress, 'ServicePackage' => $rD];
                $kliz_sel = ['error' => false, 'salonAddress' => $salonAddress, 'salonPhone' => $salonPhone, 'ServicePackage' => $rD];
            }
        }
        print json_encode($kliz_sel);
        exit();
    }

    public function testupdate()
    {
        // $employee = new Employee( $this->db );
        // $user     = null;
        // if ( $this->f3->exists( 'POST.update' ) ) {
        //     $this->f3->set( 'POST.updated_by', $this->f3->get( 'SESSION.id' ) );
        //     $data  = $this->f3->get( 'POST' );
        //     $valid = Validate::is_valid( $data, array(
        //         'first_name'            => 'required|valid_name',
        //         'last_name'             => 'valid_name',
        //         'mobile'                => 'phone_number',
        //         'email'                 => 'valid_email',
        //         'permenant_address'     => 'required|street_address',
        //         'communication_address' => 'street_address',
        //         'parent_name'           => 'valid_name',
        //         'reference_name'        => 'valid_name',
        //         'reference_number'      => 'phone_number'
        //     ) );
        //     if ( $valid === true ) {
        //         $employee->edit( $this->f3->get( 'POST.id' ) );
        //         $this->f3->reroute( '/employee' );
        //     } else {
        //         $error = implode( '. ', $valid );
        //         \Flash::instance()->addMessage( $error, 'warning' );
        //         $this->f3->set( 'page_head', 'Create' );
        //         $this->f3->set( 'view', 'employee/form.htm' );
        //         echo Template::instance()->render( 'layout.htm' );
        //     }
        // } else {
        //     $employee->getByid( $this->f3->get( 'PARAMS.id' ) );
        //     $this->f3->set( 'employee', $user );
        //     $this->f3->set( 'page_head', 'Update' );
        //     $this->f3->set( 'view', 'employee/form.htm' );
        //     echo Template::instance()->render( 'layout.htm' );
        // }
    }



    public function getServicePackageDescription()
    {

        // http://localhost/f3-url-shortener/termine/confirmTabtwo?test=1&option_salon=25&servicePackage=2

        $kliz_sel = ['error' => true, 'html' => ''];

        header('Content-Type: application/json');

        date_default_timezone_set('Europe/Berlin');
        $loc = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
        // error_reporting(E_ERROR);
        // terminFinden
        // $salonId, $mitarbeiterId = "", $dienstleistungId
        // array ( 'option_salon' => '25', 'date' => 'Freitag, 31. Januar 2020', 'iso_date' => '2020-01-31', 'mitarbeiter' => '1025', 'servicePackage' => '4', )
        // $this->f3->set('ESCAPE', false);

        $test = (int) helperblooms::inGet('test', 0);
        // if (true) {
        if ($test || $this->f3->get('AJAX')) {

            if ($test || $this->f3->exists('GET.servicePackage')) {

                $data = $this->f3->get('GET');

                $dienstleistungId = helperblooms::inGET('servicePackage');
                $spackModel = new servicepackageModel();
                // $servicepackage = $spackModel->getServicepackageAll();
                $servicepackage = $spackModel->getCasheServicepackageAll();
                $DienstleistungArr = bloomArrayHelper::getValueJoom($servicepackage, $dienstleistungId, null);

                //     'BaseServicePackageId' => NULL,
                //     'Description' => '• Kompetente Beratung mit mehreren Vorschlägen
                //   • Individuell auf Ihre Haarstruktur abgestimmter Haarschnitt
                //   • Inkl. Waschen/Conditioner/ohne Föhnen oder Selbst-Fönen',
                //     'DisplayName' => 'Schnitt (Cut & Go)',
                //     'Name' => 'Schnitt (Cut & Go)',
                //     'ServicePackageId' => 2,

                $rD = ['Description' => '', 'DisplayName' => ''];
                if ($DienstleistungArr) {

                    $description =  bloomArrayHelper::getValueJoom($DienstleistungArr, 'Description', null);
                    // $description = str_replace('\r\n','\r\n',$description);

                    if ($description) {
                        $description = preg_split('~\R~', $description);
                        $description = array_map('trim', array_filter($description));
                        $description = implode("\n", $description);
                    }

                    $rD = [
                        'Description' => $description,
                        'DisplayName' => bloomArrayHelper::getValueJoom($DienstleistungArr, 'DisplayName', null)
                    ];
                }

                $kliz_sel = ['error' => false, 'ServicePackage' => $rD];
            }
        }
        print json_encode($kliz_sel);
        exit();
    }
}
