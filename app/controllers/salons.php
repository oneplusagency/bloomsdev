<?php
// namespace Salons;
class salons extends Controller
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
    //getSalonTeam($salonId) salonteam

    public function __construct()
    {
        parent::__construct();

        // $cacheblooms = $this->f3->get('CacheBlooms');
        // $cacheblooms->eraseAll();
    }


    protected static $services = null;

    /**
     * @return mixed
     */
    private function banners($salonId)
    {

        $banners = [];

        $bannerAdmin = new bannerSalonsAdmin();
        $images      = (array) $bannerAdmin->loadBysort($salonId);

        $query = array(
            // 'playlist'        => $video_id,
            'enablejsapi'    => 1,
            'iv_load_policy' => 3,
            'disablekb'      => 1,
            'autoplay'       => 1,
            'modestbranding'        => 1,
            // Показывается меню плеера перед началом проигрования. Не нужно показывать какие-либо символы плеера. 25.05.2020 16:19
            'controls'        => 0,
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
                $banners[] = ['type' => 'img', 'src' => BANNER_PARENT_URL_DIR . '/salons-banner/' . $img['src'], 'interval' => $img['carousel_interval']];
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
                $banners[]   = ['type' => 'youtube', 'src' => $youtube_url, 'interval' => $img['carousel_interval']];
                $yt++;
            }
        }

        return $banners;
    }


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
        // file_put_contents ( ONEPLUS_DIR_PATH."/team.txt" , var_export( $salon_team_arr , true),  LOCK_EX );

        // array (
        //     17 =>
        //     array (
        //       'FirstName' => 'Marios',
        //       'Id' => 17,
        //       'LastModified' => '2020-02-05T16:13:39.837',
        //       'LastName' => 'Stratigis',
        //       'Name' => 'Marios Stratigis',
        //       'PricingRailId' => 1,
        //     ),
        //     1019 =>
        //     array (
        //       'FirstName' => 'Alev',
        //       'Id' => 1019,
        //       'LastModified' => '2020-02-05T16:13:39.837',
        //       'LastName' => 'Tilki',
        //       'Name' => 'Alev Tilki',
        //       'PricingRailId' => 1,
        //     ),

        /* @FIX by oppo , @Date: 2020-05-07 14:28:38
         * @Desc: Вибір працівника повинен бути в алфавітному порядку
         */
        if (!empty($salon_team_arr)) {

            // if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            //     uasort($salon_team_arr, function ($a, $b) {
            //         return $a['FirstName'] <=> $b['FirstName'];
            //     });
            // } else {
            //     uasort($salon_team_arr, function ($a, $b) {
            //         return strnatcmp($a['FirstName'], $b['FirstName']);
            //     });
            // }

            // file_put_contents ( ONEPLUS_DIR_PATH."/team.txt" , var_export( $salon_team_arr , true),  LOCK_EX );

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


        // clear cashe !! test
        // $cacheblooms->isCached( $code  ) ;  1) check isCached(
        // $cacheblooms->erase( $code );      2) than -  erase
        // $cacheblooms->eraseAll();

        $result = $cacheblooms->retrieve($code, true);

        if ($result) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $salons_array = json_decode($result, true);
        } else {
            $salonfinder  = new salonsModel($this->db);
            $salons_model = $salonfinder->getSalons();
            // echo "<pre>";
            // var_dump( $this->db, $salons_model);
            // die;

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

    /**
     * Renders the main salons page by dynamically building and displaying
     * a list of salon cities based on the cached salon data. The method
     * organizes the city names into columns for layout purposes and generates
     * clickable links for each city leading to their respective salon pages.
     *
     * It also sets various framework variables necessary for the view,
     * including homepage status, title, template view, and additional
     * HTML content for the salon cities section.
     *
     * @return void
     */
    public function index()
    {
        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Salons');
        $this->f3->set('view', 'salons.html');
        $this->f3->set('classfoot', 'salons');

        $salons_array = $this->getCasheSalons();
        $salon_cities_html = [];
        $salons_list_html = [];

        if (isset($salons_array) && is_array($salons_array)) {
            $salonsList = [];
            $salons = $salons_array; // 16
            $cities = [];
            foreach ($salons as $key => $salon) {
                if (strtoupper(trim($salon['DisplayName'])) == 'MANNHEIM, N7, AKADEMIE') continue;
                if (!empty($salon['DisplayName'])) {
                    $city = explode(',', $salon['DisplayName'])[0];
                    if (!key_exists($city, $cities)) {
                        $cities[strtolower($city)] = $city;
                        $salonsList[$key] = $salon['DisplayName'];
                    }
                }
            }
            $base = $this->f3->get('BASE');
            $rows = array_chunk($cities, ceil(count($cities) / 3), true); // 3 = column count;
            $salonsRows = array_chunk($salonsList, ceil(count($salonsList) / 3), true); // 3 = column count;

            foreach ($rows as $columns) {
                $salon_cities_html[] = '<div class="layout-salon-col">';
                foreach ($columns as $key => $city) {
                    $url           = $base . '/salons/city/' . $key;
                    $salon_cities_html[] = '<p><a href="' . $url . '">' . trim($city) . '</a></p>';
                }
                $salon_cities_html[] = '</div>';
            }
            foreach ($salonsRows as $salonsColumns) {
                $salons_list_html[] = '<div class="layout-salon-col">';
                foreach ($salonsColumns as $sId => $salon) {
                    $url           = $base . '/salons/details/' . $sId;
                    $salons_list_html[] = '<p><a href="' . $url . '">' . trim($salon) . '</a></p>';
                }
                $salons_list_html[] = '</div>';
            }
        }

        $html = implode(PHP_EOL, $salon_cities_html);
        $salonsListHtml = implode(PHP_EOL, $salons_list_html);
        // $html = implode("\n", $salons_html);

        // $tpl = new Template;
        // $rendered_content = $tpl->resolve($tpl->parse($html));
        $this->f3->set('SALONCITIES', $html);
        $this->f3->set('SALONSLIST', $salonsListHtml);
        $this->f3->set('ESCAPE', false);

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
    }

    /**
     * Handles the city-specific salon page rendering, setting the appropriate title,
     * meta description, and view for the requested city. It also fetches and displays
     * salons based on the city selected, providing relevant details such as address,
     * phone, opening hours, and reviews.
     *
     * The method dynamically sets content attributes depending on the city parameter
     * provided in the URL. If the city is not found, the user is redirected to the
     * generic salons page.
     *
     * @return void
     */
    public function city()
    {
        $cityName = '';
        $title = 'Salons';
        $metaDescription = 'bloom´s Friseur! Ihr Top Friseur für die besten Frisurentrends und den besten Haarschnitt.  Ihr Friseur für Langhaarfrisuren und Kurzhaarfrisuren. Friseurtermine online buchen!';
        if ($this->f3->exists('PARAMS.id')) {
            $cityName = $this->f3->get('PARAMS.id');
            $this->f3->set('cityNamePath', 'SalonCity/' . $cityName . '.html');
        } else {
            $this->f3->set('SESSION.error', 'Die Stadt der Salons existiert nicht');
            $this->f3->reroute('/salons.html');
        }
        $this->f3->set('isHomePage', false);
        $this->f3->set('salonCityClass', ' salon-city-active');
        switch ($cityName) {
            case 'darmstadt':
                $title = 'bloom´s Friseur in Darmstadt | Premium-Styling in der Elisabethenstraße';
                $metaDescription = 'Erleben Sie exklusives Styling bei bloom´s in Darmstadt. Haarschnitte, Balayage und mehr. Jetzt online Termin buchen und verwöhnen lassen!';
                break;
            case 'heidelberg':
                $title = 'bloom´s Friseur in Heidelberg | Exklusives Styling in der Altstadt';
                $metaDescription = 'Entdecken Sie bloom´s in Heidelberg. Kreative Haarschnitte, Balayage und mehr. Buchen Sie Ihren Friseur-Termin online und erleben Sie den Unterschied!';
                break;
            case 'karlsruhe':
                $title = 'bloom´s Friseur in Karlsruhe | Premium-Frisuren in der Innenstadt';
                $metaDescription = 'Lassen Sie sich verwöhnen bei bloom´s in Karlsruhe. Haarschnitte, Balayage und mehr. Jetzt online Termin buchen – an zwei zentralen Standorten!';
                break;
            case 'landau':
                $title = 'bloom´s in Landau | Ihr Premium-Friseur in der Altstadt';
                $metaDescription = 'Stilvolles Styling bei bloom´s in Landau. Haarschnitte für Damen & Herren. Buchen Sie jetzt online und besuchen Sie uns in der Kramstraße.';
                break;
            case 'ludwigshafen':
                $title = 'bloom´s Friseur in Ludwigshafen | Exklusives Styling in der Rhein-Galerie';
                $metaDescription = 'Individuelle Beratung und professionelles Styling bei bloom´s in Ludwigshafen. Jetzt online Termin buchen und verwöhnen lassen!';
                break;
            case 'mainz':
                $title = 'Friseursalons in der Mainzer Innenstadt | bloom´s';
                $metaDescription = 'bloom´s Friseursalons Mainz Innenstadt: Trend-Haarschnitte für Damen & Herren✔ individuelle Beratung✔ exklusives Ambiente✔ Jetzt online Termin buchen!';
                break;
            case 'mannheim':
                $title = 'bloom´s Friseur in Mannheim | Stilvolles Styling in der Quadratestadt';
                $metaDescription = 'Exklusives Styling für Damen und Herren bei bloom´s in Mannheim. Buchen Sie jetzt Ihren Termin online!';
                break;
            case 'neustadt':
                $title = 'bloom´s Friseur in Neustadt | Ihr exklusives Salon in der Altstadt';
                $metaDescription = 'Stilvolles Styling bei bloom´s in Neustadt. Haarschnitte, Balayage und mehr. Jetzt online Termin in der Friedrichstraße buchen.';
                break;
            case 'speyer':
                $title = 'bloom´s Friseur in Speyer | Ihr exklusives Salon in der Innenstadt';
                $metaDescription = 'Besuchen Sie bloom´s Friseur in Speyer für Haarschnitte, Balayage und mehr. Jetzt online Termin buchen und Ihre Traumfrisur verwirklichen!';
                break;
            case 'wiesbaden':
                $title = 'bloom´s Friseur in Wiesbaden | Premium-Frisuren in zentraler Lage';
                $metaDescription = 'Entdecken Sie bloom´s in Wiesbaden. Exklusive Frisuren und mehr. Jetzt online Termin in der Langgasse oder Schwalbacher Straße buchen.';
                break;
            case 'worms':
                $title = 'bloom´s Friseur in Worms | Exklusives Styling in der Kaiserpassage';
                $metaDescription = 'Professionelle Frisuren bei bloom´s in Worms. Haarschnitte, Balayage und mehr. Jetzt online Termin in der Kaiserpassage buchen!';
                break;

        }
        $this->f3->set('site', $title);
        $this->f3->set('metaDescription', $metaDescription);
        $this->f3->set('view', 'salons.html');
        $this->f3->set('classfoot', 'salon-city');

        $salons_array = $this->getCasheSalons();
        $salons_html = [];
        $salonsRating = [
            25 => ['ratingValue' => 4.1, 'reviewCount' => 205],
            27 => ['ratingValue' => 4.6, 'reviewCount' => 239],
            30 => ['ratingValue' => 4.6, 'reviewCount' => 201],
            34 => ['ratingValue' => 4.6, 'reviewCount' => 97],
            17 => ['ratingValue' => 4.3, 'reviewCount' => 221],
            16 => ['ratingValue' => 4.4, 'reviewCount' => 206],
            5 => ['ratingValue' => 4.3, 'reviewCount' => 203],
            18 => ['ratingValue' => 4.5, 'reviewCount' => 216],
            8 => ['ratingValue' => 4.6, 'reviewCount' => 193],
            4 => ['ratingValue' => 4.5, 'reviewCount' => 201],
            3 => ['ratingValue' => 4.5, 'reviewCount' => 213],
            15 => ['ratingValue' => 4.7, 'reviewCount' => 188],
            31 => ['ratingValue' => 4.7, 'reviewCount' => 188],
            29 => ['ratingValue' => 4.7, 'reviewCount' => 81],
            32 => ['ratingValue' => 4.5, 'reviewCount' => 220],
            1 => ['ratingValue' => 4.5, 'reviewCount' => 220],
            6 => ['ratingValue' => 4.4, 'reviewCount' => 124],
            22 => ['ratingValue' => 4.3, 'reviewCount' => 155],
            28 => ['ratingValue' => 4.6, 'reviewCount' => 133],
        ];
        if (isset($salons_array) && is_array($salons_array)) {
            $salons = $salons_array; // 16
            $finalSalonsList = [];
            foreach ($salons as $salonKey => $salon) {
                if (strtoupper(trim($salon['DisplayName'])) == 'MANNHEIM, N7, AKADEMIE') continue;
                if (!empty($salon['DisplayName'])) {
                    $city = explode(',', $salon['DisplayName'])[0];
                    if (strtolower($city) === $cityName) {
                        $finalSalonsList[$salonKey] = $salon;
                    }
                }
            }

            $base = $this->f3->get('BASE');
            $rows = array_chunk($finalSalonsList, ceil(count($finalSalonsList) / 3), true); // 3 = column count;

            foreach ($rows as $columns) {
                $salons_html[] = '<div class="column-inner-row"><div class="row" itemscope itemtype="https://schema.org/HairSalon">';
                foreach ($columns as $sId => $salon) {
                    $banners = $this->banners($sId);
                    if (empty($banners) || !isset($banners[0]['src'])) {
                        $image = '/dev/assets/images/salons/salonbild_wi1.jpg';
                    } else {
                        $image = trim($banners[0]['src']);
                    }
                    $url           = $base . '/salons/details/' . $sId;
                    $urlTermin     = $base . '/termine/salon/' . $sId;
                    
                    $salons_html[] = '<div class="col-md-7 col-sm-12 col-xs-12"><div class="column-inner-img"><img src="' . $image . '" alt=""></div></div>';

                    $salons_html[] .= '<div class="col-md-5 col-sm-12 col-xs-12 marginAuto"><div class="column-inner-info">';
                    $salons_html[] .= '<h3 itemprop="name">' . trim($salon['DisplayName']) . '</h3>';
                    $salons_html[] .= '<p><strong>Adresse: </strong><span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">' . trim($salon['Address']) . '</span></p>';
                    $salons_html[] .= '<p><strong>Termin:</strong> <a href="' . $urlTermin . '" itemprop="termin">Jetzt buchen</a></p>';
                    $salons_html[] .= '<p>Telefon: <a href="tel:' . trim($salon['Phone']) . '" itemprop="telephone">' . trim($salon['Phone']) . '</a></p>';
                    $salons_html[] .= '<p><strong>Öffnungszeiten:</strong> <span itemprop="openingHours">' . trim($salon['OpeningHours']) . '</span></p>';
                    $salons_html[] .= '<p><strong>Website:</strong> <a href="' . $url . '" itemprop="url">Zum Salon</a></p>';
                    // if (array_key_exists($sId, $salonsRating)) {
                    //     $salons_html[] .= '<p><strong>Bewertung:</strong> <span itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"><span itemprop="ratingValue">' . $salonsRating[$sId]['ratingValue'] . '</span> von 5 Sternen (basierend auf <span itemprop="reviewCount">' . $salonsRating[$sId]['reviewCount'] . '</span> Bewertungen)</span></p>';
                    // }
                    $salons_html[] .= '</div></div>';
                    unset($banners);
                }
                $salons_html[] = '</div></div>';
            }
        }

        $html = implode(PHP_EOL, $salons_html);
        $this->f3->set('SALONS', $html);
        $this->f3->set('ESCAPE', false);
    }

    public function details()
    {
        if ($this->f3->exists('PARAMS.id')) {
            $salonId = $this->f3->get('PARAMS.id');
            $this->f3->set('salonId', $salonId);
        } else {
            // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
            $this->f3->set('SESSION.error', 'Salon ID existiert nicht');
            // $this->f3->reroute('/salons.html');
            $this->f3->reroute('/');
        }

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', 'Salon');
        $this->f3->set('view', 'salon-subpage.html');
        $this->f3->set('classfoot', 'salon');
        $this->f3->set('SALONS', null);
        // ADD JS
        $addscripts = 'js/layout/salon-subpage.js';
        $this->f3->set('addscripts', array($addscripts));

        $salons_html  = [];
        $salons_array = (array) $this->getCasheSalons($salonId);
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

        if (empty($salons_array)) {
            $this->f3->set('SESSION.error', 'Salon existiert nicht');
            // $this->f3->reroute('/salons.html');
            $this->f3->reroute('/');
        } else {
            // $salons_array =  array_map('trim',$salons_array);

            $this->f3->set('SALON_ADDRESS', trim($salons_array['Address']));
            $this->f3->set('SALON_DESCRIPTION', trim($salons_array['Description']));
            $this->f3->set('SALON_DISPLAYNAME', trim($salons_array['DisplayName']));

            $this->f3->set('SALON_GOOGLEPLACESURL', trim($salons_array['GooglePlacesUrl']));
            // google_map_url($address, $zoom = 13)
            $this->f3->set('SALON_GOOGLE_MAP_URL', $this->google_map_url(trim($salons_array['Address'])));

            /* @FIX by oppo (1plus.de), @Date: 2020-04-27 14:53:39
             * @Desc: fix no imaGE NEW Salon https://developservice.de/kunden/blooms/1plus/salons/details/34
             */
            if (empty($salons_array['ImageUrl'])) {
                $salons_array['ImageUrl'] = 'salonbild_wi1.jpg';
            }

            $this->f3->set('SALON_IMAGEURL', trim($salons_array['ImageUrl']));
            $this->f3->set('SALON_NAME', trim($salons_array['Name']));
            // OpeningHours
            $OpeningHours = '';
            if (!empty($salons_array['OpeningHours'])) {
                $OpeningHours = preg_split('~\R~', $salons_array['OpeningHours']);
                $OpeningHours = array_map('trim', array_filter($OpeningHours));
                // $OpeningHours = implode(', ', $OpeningHours);
                /* @FIX by oppo (1plus-agency.com), @Date: 2020-01-20 14:18:11
                 * @Desc: Customer asks to place phone number and working time under each other
                 */
                $OpeningHours = implode('<br />', $OpeningHours);
            }
            $this->f3->set('SALON_OPENINGHOURS', $OpeningHours);

            $this->f3->set('SALON_PHONE', $salons_array['Phone']);

            //e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\assets\images\employeeimage\
            $salonteam = (array) $this->getCasheSalonTeam($salonId);

            // if ($salonId == 15) {
            //     echo '<pre>';
            //     var_export($salonteam);
            //     echo '</pre>';
            //     exit;
            // }

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
            $salonfinder = new salonsModel(null);
            $base        = $this->f3->get('BASE');
            $assets      = $this->f3->get('ASSETS');

            $employeeimage_abs_dir = EMPLOYEEIMAGE_ABS_DIR;
            $target                = $employeeimage_abs_dir . $salonId;
            helperblooms::op_mkdir($target);
            /* @FIX by oppo (1plus.de), @Date: 2020-05-22 17:14:56
             * @Desc: add Employee Webimages
             */
            $webimages_abs_dir = UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR;
            $target                = $webimages_abs_dir . $salonId;
            helperblooms::op_mkdir($target);

            $ip    = $this->f3->get('IP');
            $local = ($ip == '127.0.0.1' ? true : false);

            $e = 0;

            foreach ($salonteam as $employeeId => $value) {
                //{{@ASSETS}}images/severine_emp.jpg"
                // skip hard get for local Address
                $value['avatar'] = $salonfinder->getMitarbeiterFilename($value, $salonId, $employeeId, $local);

                /* @FIX by oppo (1plus.de), @Date: 2020-05-22 17:14:56
                 * @Desc: add Employee Webimages
                 */
                $value['webimages'] = $salonfinder->getEmployeeWebimagesFilename($value, $salonId, $employeeId, $local);

                $value['salonId'] = $salonId;
                /** @FIX by oppo (1plus.de), @Date: 2020-05-26 18:36:43
                 * @Desc: add slider employee (key index)
                 */
                $value['key_index'] = $e;

                $description            = bloomArrayHelper::getValueJoom($value, 'Description', '', 'STRING');

                //     $description            = '<ul class="rrrr">
                //     <li>Ausführlicher            Beschreibungstext // Text2 /</li>

                //     <li>Salonleitung, Friseurmeisterin</li>
                //     <li>Bayalage hochstecken</li>
                //     <li>Fachtrainerin</li>
                //     <li>Erfahrung:10 Jahre bei bloom´s</li>

                //     <li>
                //         “Ich liebe mein Team und meine kunden”
                //     </li>

                //     <li>Preiskatrgorie (laden)</li>
                // </ul>';
                if ($description) {
                    // add description 27.05.2020
                    // $description = preg_replace('/\R+/', "", $description);
                    $description =  trim(strip_tags(html_entity_decode($description)));
                    $description = preg_replace('~[\r\n]+~', "\n", $description);
                    // $description = preg_replace('/\s(?=\s)/', "", $description);
                    $description = preg_replace('/([\s])\1+/', " ", $description);
                    $description =  nl2br($description);
                }

                $value['Description']   = $description;
                $value['ALT']           = helperblooms::umlautName($value['Name']);
                $salonteam[$employeeId] = $value;

                $e++;
            }

            // file_put_contents ( ONEPLUS_DIR_PATH."/salonteam.txt" , var_export( $salonteam , true),  LOCK_EX );

            $this->f3->set('SALONTEAM', $salonteam);

            /* @FIX by oppo , @Date: 24.07.2020 16:40
         * @Desc: add slider
         */
            $banners = $this->banners($salonId);
            $this->f3->set('BANNERS', $banners);

            $this->f3->set('ESCAPE', false);
        }
    }

    // https://stackoverflow.com/questions/1886740/php-remove-javascript
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

    /**
     * @param $address
     * @param $zoom
     * @return mixed
     */
    protected function google_map_url($address, $zoom = 13)
    {
        if (strlen($address) < 4) {
            return '';
        }

        // https://maps.google.com/maps?q=Elisabethenstr.%208%2C%20Darmstadt&t=&z=13&ie=UTF8&iwloc=&output=embed
        $url   = 'https://www.google.com/maps/embed/v1/place';
        $url   = 'https://maps.google.com/maps';
        $query = http_build_query(
            array(
                // 'key'      => \Config::get('map.google.api_key.browser'),
                'q'       => $address,
                't'       => '',
                'z'       => $zoom,
                'ie'      => 'UTF8',
                'iwloc'   => '',
                'output=' => 'embed'
                // 'language' => 'de',
            ),
            '',
            '&',
            PHP_QUERY_RFC3986
        );
        $url .= '?' . $query . '&output=embed';
        // return '<iframe src="' . $url . '" width="' . $this->_width . '" height="' . $this->_height . '" frameborder="0"></iframe>';
        // $url ='https://maps.google.com/maps?q=Elisabethenstr.%208%2C%20Darmstadt&t=&z=13&ie=UTF8&iwloc=&output=embed';

        // $url ="https://maps.google.com/maps?q=Elisabethenstr.+8%2C+Darmstadt&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output%3D=embed";

        return $url;
    }

    public function create()
    {
        if ($this->f3->exists('POST.create')) {
            $user = new User($this->db);
            $user->add();
            //set session
            new Session();
            $this->f3->set('SESSION.test', 'Success');
            $this->f3->reroute('/users');
        } else {
            $this->f3->set('page_head', 'Create User');
            $this->f3->set('view', 'users/create.htm');
        }
    }
}
