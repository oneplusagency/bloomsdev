<?php

class preise extends Controller
{
    //preise.php

    /**
     * @var mixed
     */
    protected static $services = null;

    /**
     * @return mixed
     */
    private function banners()
    {

        $banners = [];

        $bannerAdmin = new bannerPriceAdmin();
        $images = (array) $bannerAdmin->loadBysort();

        $query = array(
            // 'playlist'        => $video_id,
            'enablejsapi' => 1,
            'iv_load_policy' => 3,
            'disablekb' => 1,
            'autoplay' => 1,
            'modestbranding' => 1,
            // Показывает�?�? меню плеера перед началом проигровани�?. �?е нужно показывать какие-либо �?имволы плеера. 25.05.2020 16:19
            'controls' => 0,
            'showinfo' => 0,
            'rel' => 0,
            'loop' => 0,
            'mute' => 1,
            'wmode' => 'transparent',
            'color' => 'white',
            'theme' => 'dark'
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
                $banners[] = ['type' => 'img', 'src' => PRICE_BANNER_DIR . $img['src'], 'interval' => $img['carousel_interval']];
                $yt++;
            } elseif (!empty($img['video_url'])) {

                if ($yt > 0) {
                    // $query['autoplay'] = 0;
                }
                /** @FIX by oppo (webiprog.de), @Date: 2020-03-18 11:34:55
                 * @Desc: LOOP
                 * https://sergeychunkevich.com/dlya-web-mastera/youtube-parametry/#param13
                 */
                // 'playlist'		=> $video_id,
                $video_id = str_replace('https://www.youtube.com/embed/', '', $img['video_url']);
                if ($video_id) {
                    $query['playlist'] = $video_id;
                }

                $youtube_addon_url = '?' . http_build_query($query, '&');

                $youtube_url = rtrim($img['video_url'], '?');
                $youtube_url = $youtube_url . $youtube_addon_url;
                $banners[] = ['type' => 'youtube', 'src' => $youtube_url, 'interval' => $img['carousel_interval']];
                $yt++;
            }
        }

        return $banners;
    }

    public function index()
    {
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Preise');
        $this->f3->set('view', 'preise.html');
        $this->f3->set('classfoot', 'preise');

        // ADD JS
        // $addscripts[] = 'js/layout/jquery.chained.remote.js';
        $addscripts[] = 'js/layout/jquery.chainedvefore.js';
        $addscripts[] = 'js/layout/preise.js';
        $this->f3->set('addscripts', $addscripts);

        $option_salon = self::select_salon();
        $this->f3->set('OPTION_SALON', $option_salon);

        /* @FIX by oppo , @Date: 2020-03-05 19:46:28
         * @Desc: add slider
         */
        // $banners = $this->banners();
        // $this->f3->set( 'BANNERS', $banners );
        /** @FIX by oppo (webiprog.de), @Date: 2021-01-12 16:47:30
         * @Desc:  disable banners
         */

        $this->f3->set('BANNERS', []);

        $this->f3->set('ESCAPE', false);
    }

    public function salon()
    {
        if ($this->f3->exists('PARAMS.id')) {
            $salonId = $this->f3->get('PARAMS.id');
            $this->f3->set('salonId', $salonId);
        } else {
            // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
            $this->f3->set('SESSION.error', 'Preise existiert nicht');
            $this->f3->reroute('/salons.html');
        }
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Preise');
        $this->f3->set('view', 'preise.html');
        $this->f3->set('classfoot', 'preise');

        // ADD JS
        // $addscripts[] = 'js/layout/jquery.chained.remote.js';
        $addscripts[] = 'js/layout/jquery.chainedvefore.js';
        $addscripts[] = 'js/layout/preise.js';
        $this->f3->set('addscripts', $addscripts);

        $option_salon = self::select_salon($salonId);
        $this->f3->set('OPTION_SALON', $option_salon);

        // https://stackoverflow.com/questions/28041682/ajax-populate-selectlist-with-option-groups
        // https://stackoverflow.com/questions/8578814/groupby-in-javascript-to-group-json-data-and-populate-on-optgroup
        // http://jsfiddle.net/FG9Lg/1/
        // http://jsfiddle.net/FG9Lg/
        // https://stackoverflow.com/questions/35326594/populate-select-with-optgroup-using-jquery
        // http://jsfiddle.net/39xkzcck/
        // https://codepen.io/salomalo/pen/qBELbaO

        // $spackModel = new servicepackageModel();
        // $PricingRailsForSalon = $spackModel->getPricingRailsForSalon($salonId);
        // $prsrails_key = array_keys($PricingRailsForSalon);
        // array (
        //     0 => 1,
        //   )

        // $AllPrices = $spackModel-> getAllPrices();

        if (false) {
        }

        /* @FIX by oppo @Date: 2020-03-05 19:46:28
         * @Desc: add slider
         */
        $banners = $this->banners();
        $this->f3->set('BANNERS', $banners);

        $this->f3->set('ESCAPE', false);

        //     2 =>
        //     array (
        //       '@attributes' =>
        //       array (
        //         'Id' => '2',
        //       ),
        //       'Address' => 'Bismarckstraße 52, Ludwigshafen',
        //       'CategoryDisplayName' =>
        //       array (
        //       ),
        //       'Description' => 'Großer Citysalon unübersehbar in Top-Lage!
        //   ',
        //       'DisplayName' => 'Ludwigshafen, Bismarckstraße ',
        //       'GooglePlacesUrl' => 'https://goo.gl/maps/7QEBG2grQT31xwsN6',
        //       'ImageUrl' => 'salonbild_lu2.jpg',
        //       'Name' => 'Ludwigshafen 2',
        //       'OnlineAppointmentsEnabled' => 'true',
        //       'OpeningHours' => 'Montag - Freitag: 10:00 - 19:30 Uhr
        //   Samstag: 9:00 - 18:00 Uhr ',
        //       'Phone' => '0621 520708',
        //     ),

        // $this->f3->set('SALONS', null);
    }

    /**
     * @param $salonId
     * @return mixed
     */
    protected static function select_salon($salonId = null, $text = 'Bitte Salon auswahlen')
    {
        // Bitte Salon auswahlen
        // - Wählen Sie einen Salon aus -

        // salons Controller
        $salons_ctrl = new salons();
        $salons = $salons_ctrl->getSalonsController();
        // <option value="Ludwigshafen Rhein-Galerie">Ludwigshafen Rhein-Galerie</option>
        $option_salon[] = '<option value="">' . $text . '</option>';
        if (!empty($salons) && is_array($salons)) {
            foreach ($salons as $skey => $sv) {
                $selected = '';
                if (strtoupper(trim($sv['DisplayName'])) == 'MANNHEIM, N7, AKADEMIE')
                    continue;
                if ($salonId == $skey) {
                    $selected = 'selected="selected"';
                }
                $option_salon[] = '<option value="' . $skey . '" ' . $selected . '>' . trim($sv['DisplayName']) . '</option>';
            }
        }
        return $option_salon;
    }

    /**
     * @param $salonId
     */
    protected static function getStaticService($salonId = null)
    {
        if ($salonId) {
            $kliz_sel = [];
            if (self::$services === null) {
                $spackModel = new servicepackageModel();

                $diff = $spackModel->getServicepackBysalon($salonId);


                // echo '<pre>';
                // echo json_encode($diff);
                // echo '</pre>';
                // exit;

                if ($diff && ($serv_array = helperblooms::jsJson($diff, true))) {
                    $serv_array = array_unique($serv_array);
                    $Cat_array = $spackModel->getCasheCategorieService();

                    if ($Cat_array) {
                        foreach ($Cat_array as $c => $sp) {
                            $services = [];
                            foreach ($sp['ServicePackages'] as $s => $pp) {
                                $BaseServicePackageId = $pp['BaseServicePackageId'];
                                $ServicePackageId = $pp['ServicePackageId'];

                                // if (in_array($ServicePackageId, $serv_array)) {
                                $services[$ServicePackageId] = [
                                    'Name' => $pp['Name'],
                                    'DisplayName' => $pp['DisplayName'],
                                    'Description' => $pp['Description'],
                                    'ServicePackageId' => $ServicePackageId,
                                    'BaseServicePackageId' => $BaseServicePackageId,
                                    'WebSortOrder' => $pp['WebSortOrder']
                                ];
                                // }
                            }
                            if (!empty($services) && is_array($services) && count($services)) {
                                $kliz_sel[] = [
                                    'title' => $sp['Name'],
                                    'displayname' => $sp['DisplayName'],
                                    'id' => $sp['Id'],
                                    'services' => $services
                                ];
                                // $kliz_sel[$sp['Id']] = $sp['Name'];
                            }

                            unset($services);
                        }
                    }
                }

                self::$services = $kliz_sel;
            }

            return self::$services;
        }
        return false;
    }

    public function servicesShoAjax()
    {
        // https://vike.io/ru/548778/
        $kliz_sel = [];
        // <option disabled="" selected="">Auswahl Dienstleistungskategorie</option>

        if ($this->f3->get('AJAX')) {
            header('Content-Type: application/json');

            // $salonId     = $this->f3->get('GET.salonId');
            $salonId = (int) $this->f3->get('GET._value');

            if ($salonId) {
                $Cat_array = self::getStaticService($salonId);

                // echo '<pre>';
                // var_export($Cat_array);
                // echo '</pre>';
                // exit;
                if ($Cat_array) {
                    // first val
                    // $kliz_sel[] = [
                    //     'title' => $sp['Name'],
                    //     'id' => $sp['Id'],
                    //     'services' => $services
                    // ];

                    $kliz_sel = [0 => 'Auswahl Dienstleistungskategorie'];
                    foreach ($Cat_array as $key => $sp) {
                        $kliz_sel[$sp['id']] = $sp['title'];
                    }

                    // $kliz_sel = [0 => 'Auswahl Dienstleistungskategorie'] + $Cat_array;

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
                }
            } else {
                $kliz_sel[''] = 'Bitte zuerst Salon auswählen';
            }

            print json_encode($kliz_sel);

            exit();
        }
    }

    public function getPriceAjax()
    {
        $kliz_sel = ['error' => true, 'html' => ''];

        // $logger = new \Log($this->f3->get('LOGS') . 'all_price.log');
        // $logger->write('' . var_export($_REQUEST, true) . '');

        // if (true) {
        if ($this->f3->get('AJAX')) {
            header('Content-Type: application/json');

            $salonId = $this->f3->get('GET.salonId');
            // $salonId = 25;

            if ($salonId) {

                $html = [];
                $spackModel = new servicepackageModel();

                $AllPrices = $spackModel->getCasheAllPrices();

                $Cat_array = self::getStaticService($salonId);

                // echo json_encode($Cat_array);
                // die;

                $finalSs = [];

                array_map(function ($category) use (&$finalSs) {

                    $categoryCopy = $category;
                    // var_dump($category);
                    // die;
                    unset($categoryCopy['services']);
                    $categoryCopy['services'] = [];
                    $notSortedService = [];

                    foreach ($category['services'] as $_ss) {
                        if ($_ss['WebSortOrder'] == null) {
                            $notSortedService[] = $_ss;
                        } else {
                            $categoryCopy['services'][$_ss['WebSortOrder']] = $_ss;
                        }
                    }

                    ksort($categoryCopy['services']);

                    // echo  "Max : " . max(array_keys($categoryCopy['services'])) . ' > ' . json_encode($categoryCopy['services']);
                    // die;

                    $highestSortingValue = count(array_keys($categoryCopy['services'])) > 0 ? max(array_keys($categoryCopy['services'])) : 0;



                    foreach ($notSortedService as $nss) {
                        $highestSortingValue += 1;
                        $categoryCopy['services'][$highestSortingValue] = $nss;
                    }

                    // echo  "highestSortingValue : $highestSortingValue : " . json_encode($categoryCopy['services']);
                    // die;

                    $finalSs[] = $categoryCopy;
                }, $Cat_array);


                // $categoryToServicePackage = $spackModel->getCategorieServicePackage();
                // $categoryToServicePackageArray = helperblooms::jsJson($categoryToServicePackage, true);

                // echo json_encode($categoryToServicePackageArray);

                $Cat_array = $finalSs;

                // echo "Final SS Array :" . json_encode($finalSs);
                // exit;

                if ($Cat_array) {

                    // first val
                    // $kliz_sel[] = [
                    //     'title' => $sp['Name'],
                    //     'id' => $sp['Id'],
                    //     'services' => $services
                    // ];

                    foreach ($Cat_array as $key => $sp) {

                        $html[] = '<table id="' . $sp['id'] . '-tbprice" class="table table-borderless table-price">
                        <thead>
                            <tr>
                                <th colspan="2" scope="col-m"><h3>' . $sp['title'] . '</h3></th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach ($sp['services'] as $s => $pp) {
                            // 'Name' => $pp['Name'],
                            // 'ServicePackageId' => $ServicePackageId,
                            // 'BaseServicePackageId' => $BaseServicePackageId

                            $Description = '';
                            if (!empty($pp['Description'])) {
                                $Description = preg_split('~\R~', $pp['Description']);
                                $Description = array_map('trim', array_filter($Description));
                                // $Description = implode( "</br>", $Description );
                                $Description = implode("\n", $Description);
                                $id = $pp['ServicePackageId'];
                                $Description = '<i data-toggle="tooltip" data-html="true" data-placement="top" class="fa fa-info-circle bloomstooltip" id="bloomstip' . $id . '" title="' . $Description . '"></i>';

                                // $Description .= '<div class="ui-tooltip ui-widget ui-widget-content bloomstip bloomstip'.$id.'" role="tooltip">'.$Description.'</div>';
                            }

                            $price = '';
                            $ServicePackageId = $pp['ServicePackageId'];
                            if (isset($AllPrices[$ServicePackageId]) && $cl_price = $AllPrices[$ServicePackageId]['Price']) {

                                $price = helperblooms::formaNumber($cl_price);

                                if ($AllPrices[$ServicePackageId]['IsFixPrice'] == false) {
                                    $price = '<span style="font-size:0.6rem" >ab </span>' . $price;
                                }
                            }

                            // 3 =>
                            // array (
                            //   'Price' => 17.0,
                            //   'PricingRailId' => 1,
                            //   'ServicePackageId' => 3,
                            // ),


                            // <td scope="row" class="w-50 groshi-name">'.$pp['DisplayName'].' '.$Description.'</td>
                            // or
                            // <td scope="row" class="w-50 groshi-name">'.$pp['Name'].' '.$Description.'</td>

                            $html[] = '<tr>
                            <td scope="row" class="w-75 groshi-name">' . $pp['DisplayName'] . ' ' . $Description . '</td>
                            <td class="td-gold groshi-price">' . $price . '</td>
                            </tr>';
                        }

                        $html[] = '</tbody>
                        </table>';
                    }
                }

                $html_res = implode("\n", $html);
                $kliz_sel = ['error' => false, 'html' => $html_res];
            }
        }

        print json_encode($kliz_sel);
        exit();
    }

    public function cat()
    {
        // $logger = new \Log( $this->f3->get( 'LOGS' ).'price.log' );
        // $logger->write( 'price:'.var_export($_REQUEST, true)."" );

        // <option disabled selected> Auswahl Dienstleistungskategorie</option>
        // <optgroup label="Frauen">
        //     <option value="2">Schnitt (Cut &amp; Go)</option>
        //     <option value="4">Schnitt + Finish</option>
        //     <option value="69">Ponyservice</option>
        // </optgroup>
        // <optgroup label="Männer">
        //     <option value="6">Schnitt + Finish</option>
        //     <option value="70">Konturenservice</option>
        // </optgroup>

        // print  "{
        //     'data' : {
        //         'text': 'Mountain Time Zone',
        //         'children': [
        //           {
        //             'id': 'CA',
        //             'text': 'California'
        //           },
        //           {
        //             'id': 'CO',
        //             'text': 'Colorado'
        //           }
        //         ]
        //     }
        // }";

        $all = [
            // First Group
            [
                'id' => 10,
                'text' => 'Group_One',
                'children' => [['id' => 11, 'text' => 'Field_1_Group_One'], ['id' => 12, 'text' => 'Field_2_Group_One']]
            ],
            // Second Group
            [
                'id' => 20,
                'text' => 'Group_Two',
                'children' => [['id' => 21, 'text' => 'Field_1_Group_Two'], ['id' => 22, 'text' => 'Field_2_Group_Two']]
            ]
        ];

        $Group = [
            'Category 1' => [[11 => 'Field_1_Group_One'], [12 => 'Field_12'], [13 => 'Field_13'], [14 => 'Field_14']],
            'Category 2' => [[115 => 'Field_11'], [125 => 'Field_12'], [135 => 'Field_13'], [145 => 'Field_14']],
            'Category 3' => [[118 => 'Field_11']]
        ];

        print json_encode($Group);
        exit();
    }
}