<?php

// namespace Model;
/**
 * @file: servicepackageModel.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\models
 * @created:    Fri Jan 24 2020
 * @author:     oppo, 1plus-agency.com
 * @version:    1.0.0
 * @modified:   Friday January 24th 2020 5:30:29 pm
 * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// $this->user = new \Model\User($this->casted['uid']);
class servicepackageModel extends DB\SQL\Mapper
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

    public function __construct(DB\SQL $db = null)
    {

        $f3       = \Base::instance();
        $this->f3 = $f3;

        $this->base      = $this->f3->get('BASE');
        $this->page_host = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        // parent::__construct($db, 'servicepackage');
    }

    public static function serviceAvailable()
    {
        //https://api.bloom-s.de:780/api/ping
        return helperblooms::serviceAvailable();
    }

    /**
     * @return mixed
     */
    public function getCategorieServicePackage($onlyAkadamie = false)
    {
        // var_dump($onlyAkadamie);
        // die;
        $servicepackage = false;
        if (self::serviceAvailable()) {
            $http           = new Bugzilla();
            $u = 'categorizedservicepackage';
            if ($onlyAkadamie) {
                $u .= '?isOnlineVisibleAcademyOnly=true';
            }
            $servicepackage = $http->get($u);
        }

        if (!$servicepackage) {
            include_once ONEPLUS_DIR_PATH_APP . 'helper/local/categorizedservicepackage-json.php';
            $servicepackage = $categorizedservicepackage_local;
        }

        if ($servicepackage) {
            return $servicepackage;
        }

        return $servicepackage;
    }

    /**
     * Cashe for CategorieService
     * @param $id
     * @return mixed
     */
    public function getCasheCategorieService($onlyAkadamie = false, $redirect = true)
    {

        $CatServ_arr = [];
        $cacheblooms = $this->f3->get('CacheBlooms');
        $code        = 'CategorieService';

        $result = $cacheblooms->retrieve($code, true);
        if ($result) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $CatServ_arr = json_decode($result, true);
        } else {
            // $spackModel       = new servicepackageModel();
            $CategorieService = $this->getCategorieServicePackage($onlyAkadamie);

            if ($CategorieService && ($CatServ_arr = helperblooms::jsJson($CategorieService, true))) {

                /** @FIX by oppo , @Date: 2020-04-28 11:25:31
                 * @Desc: fix cacheblooms - 5 min | constant in index - TIME_CACHEBLOOMS
                 */
                $cacheblooms->store($code, json_encode($CatServ_arr, true), TIME_CACHEBLOOMS); // 3600 => 'hour',
            } elseif ($redirect) {
                // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
                $this->f3->set('SESSION.error', 'Categorie Service existiert nicht');
                $this->f3->reroute('/');
            }
        }
        return $CatServ_arr;
    }

    /**
     * servicepackage/bysalon?salonId={25} (Darmstadt, Elisabethenstraße)
     * @param $salonId
     * @return mixed
     */
    public function getServicepackBysalon($salonId, $isOnlineVisibleAcademyOnly = false)
    {
        $arr = false;
        if (self::serviceAvailable()) {
            // salon/'
            $http = new Bugzilla();

            $u = 'servicepackage/bysalon?salonId=' . $salonId;
            if ($isOnlineVisibleAcademyOnly) {
                $u .= '&isOnlineVisibleAcademyOnly=true';
            }
            $arr  = $http->get($u);
        }

        if (!$arr && is_file(ONEPLUS_DIR_PATH_APP . 'helper/local/bysalonalons-json.php')) {
            include_once ONEPLUS_DIR_PATH_APP . 'helper/local/bysalonalons-json.php';
            $arr = $bysalonalons_local;
        }

        return $arr;
    }

    /**
     * servicepackage?employeeId={912} (Lisa Täubl)
     * @param $employeeId
     * @return mixed
     */
    public function getServicepackByEmployeeid($employeeId)
    {
        $arr = false;
        if (self::serviceAvailable()) {
            // salon/'
            $http = new Bugzilla();
            $arr  = $http->get('servicepackage?employeeId=' . $employeeId);
        }

        if (!$arr && is_file(ONEPLUS_DIR_PATH_APP . 'helper/local/helper/local/employeeid-json.php')) {
            include_once ONEPLUS_DIR_PATH_APP . 'helper/local/employeeid-json.php';
            $arr = $employeeid_local;
        }

        return $arr;
    }

    //prices/pricingrailsforsalon?salonId=25

    /**
     * @param $salonId
     * @return mixed
     */
    public function getPricingRailsForSalon($salonId)
    {
        $res = false;
        if (self::serviceAvailable()) {
            $http = new Bugzilla();
            $arr  = $http->get('prices/pricingrailsforsalon?salonId=' . (int) $salonId);
        }

        if (!$arr && is_file(ONEPLUS_DIR_PATH_APP . 'helper/json/prices_pricingrailsforsalon_salonid.json')) {

            ob_start();
            include_once ONEPLUS_DIR_PATH_APP . 'helper/json/prices_pricingrailsforsalon_salonid.json';
            $arr = ob_get_clean(); // (string)"Hello World"
        }

        // stdClass::__set_state(array(
        //     'Message' => '',
        //     'Result' =>
        //    array (
        //      0 =>
        //      stdClass::__set_state(array(
        //         'Id' => 1,
        //         'Name' => 'Standard',
        //      )),
        //    ),
        //     'Success' => true,
        //  ))

        if ($arr && ($prices_pricingrailsforsalon = helperblooms::jsJson($arr, true))) {

            if (isset($prices_pricingrailsforsalon['Success']) && $prices_pricingrailsforsalon['Success'] == true) {
                // $res =  bloomArrayHelper::getColumn($prices_pricingrailsforsalon, 'Result');
                $res = $prices_pricingrailsforsalon['Result'];
                $res = helperblooms::parseXmlToArraysalons($res, 'Id');
                // array (
                //     1 =>
                //     array (
                //       'Id' => 1,
                //       'Name' => 'Standard',
                //     ),
                //   )

            }
        }
        return $res;
    }

    //prices/allprices

    /**
     * @return mixed
     */
    public function getAllPrices()
    {
        $res = false;
        if (self::serviceAvailable()) {
            $http = new Bugzilla();
            $arr  = $http->get('prices/allprices');
        }

        if (!$arr && is_file(ONEPLUS_DIR_PATH_APP . 'helper/json/prices_allprices.json')) {

            ob_start();
            include_once ONEPLUS_DIR_PATH_APP . 'helper/json/prices_allprices.json';
            $arr = ob_get_clean(); // (string)"Hello World"

            // !!!!!! https://github.com/ikkez/f3-fal/tree/8d950271e2d7e44e23d7e10bf8b1e4f6ab2b3971

            // $fal = \FAL::instance();
            // $fal->load($this->page_host.$this->base.'/json/local/prices_allprices.json');
            // $arr  = $fal->getFileStream();

            // Call a controller inside a template and another controller in Fatfree framework
            // https://stackoverflow.com/questions/16255894/call-a-controller-inside-a-template-and-another-controller-in-fatfree-framework
            // $url = $this->page_host.$this->base.'/json/local/prices_allprices.json';
            // $this->f3->get($url);
            // $this->f3->route('GET /', 'json->local->prices_allprices.json');
            // $this->f3->mock('GET '.$url);
            // $arr  = file_get_contents($url);

        }

        // stdClass::__set_state(array(
        //     'Message' => '',
        //     'Result' =>
        //    array (
        //      0 =>
        //      stdClass::__set_state(array(
        //         'Price' => 30.0,
        //         'PricingRailId' => 1,
        //         'ServicePackageId' => 2,
        //      )),

        if ($arr && ($AllPrices = helperblooms::jsJson($arr, true))) {

            if (isset($AllPrices['Success']) && $AllPrices['Success'] == true) {
                // $res =  bloomArrayHelper::getColumn($AllPrices, 'Result');
                $res       = $AllPrices['Result'];
                $resprices = helperblooms::parseXmlToArraysalons($res, 'ServicePackageId');
                // 2 =>
                // array (
                //   'Price' => 30.0,
                //   'PricingRailId' => 1,
                //   'ServicePackageId' => 2,
                // ),
                // 3 =>
                // array (
                //   'Price' => 17.0,
                //   'PricingRailId' => 1,
                //   'ServicePackageId' => 3,
                // ),
                return $resprices;
            }
        }
        return $res;
    }

    /**
     * @param $redirect
     * @return mixed
     */
    public function getCasheAllPrices($redirect = true)
    {

        $AllPrices_arr = [];

        $cacheblooms = $this->f3->get('CacheBlooms');
        $code        = 'AllPrices';
        $result      = $cacheblooms->retrieve($code, true);

        if ($result) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $AllPrices_arr = json_decode($result, true);
        } else {
            // $spackModel       = new servicepackageModel();
            $AllPrices = $this->getAllPrices();
            if (!empty($AllPrices) && is_array($AllPrices)) {
                $AllPrices_arr = $AllPrices;
                /** @FIX by oppo , @Date: 2020-04-28 11:25:31
                 * @Desc: fix cacheblooms - 5 min | constant in index - TIME_CACHEBLOOMS
                 */
                $cacheblooms->store($code, json_encode($AllPrices_arr, true), TIME_CACHEBLOOMS); // 3600 => 'hour',

            } elseif ($redirect) {
                // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
                $this->f3->set('SESSION.error', 'Preise existiert nicht');
                $this->f3->reroute('/');
            }
        }
        return $AllPrices_arr;
    }

    /**
     * getServicepackageAll servicepackage/all
     *
     * BaseServicePackageId' => NULL,
     *'Description' => '• Nachschnitt der Ponykontur• für Stammkunden mit Gutschein kostenfrei!',
     * 'DisplayName' => 'Ponyservice'
     * 'Name' => 'Ponyservice',
     * ServicePackageId' => 69,
     *  servicepackageModel->getServicepackageAll()
     * @return void
     */
    public function getServicepackageAll()
    {
        $res = false;
        if (self::serviceAvailable()) {
            $http = new Bugzilla();
            $arr  = $http->get('servicepackage/all');
        }

        if (!$arr && is_file(ONEPLUS_DIR_PATH_APP . 'helper/json/servicepackage_all.json')) {
            ob_start();
            include_once ONEPLUS_DIR_PATH_APP . 'helper/json/servicepackage_all.json';
            $arr = ob_get_clean(); // (string)"Hello World"
        }

        // array (
        //     0 =>
        //     stdClass::__set_state(array(
        //        'BaseServicePackageId' => NULL,
        //        'Description' => '• Nachschnitt der Ponykontur
        //   • für Stammkunden mit Gutschein kostenfrei!',
        //        'DisplayName' => 'Ponyservice',
        //        'Name' => 'Ponyservice',
        //        'ServicePackageId' => 69,
        //     )),

        if ($arr && ($ServicepackageAll = helperblooms::jsJson($arr, true))) {

            $res = helperblooms::parseXmlToArraysalons($ServicepackageAll, 'ServicePackageId');
        }
        return $res;
    }


    public function getCasheServicepackageAll($redirect = false, $clearCashe = false)
    {

        $ServicepackageAll_arr = [];

        $cacheblooms = $this->f3->get('CacheBlooms');
        $code        = 'ServicepackageAll';
        $result      = $cacheblooms->retrieve($code, $clearCashe);

        if ($result) {
            //wenn es einen cache eintrag gibt diesen verwenden
            $ServicepackageAll_arr = json_decode($result, true);
        } else {
            // $spackModel       = new servicepackageModel();
            $ServicepackageAll = $this->getServicepackageAll();
            if (!empty($ServicepackageAll) && is_array($ServicepackageAll)) {
                $ServicepackageAll_arr = $ServicepackageAll;
                /** @FIX by oppo , @Date: 2020-04-28 11:25:31
                 * @Desc: fix cacheblooms - 5 min | constant in index - TIME_CACHEBLOOMS
                 */
                $cacheblooms->store($code, json_encode($ServicepackageAll_arr, true), TIME_CACHEBLOOMS + 300); // 3600 => 'hour',

            } elseif ($redirect) {
                // $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
                $this->f3->set('SESSION.error', 'Preise existiert nicht');
                $this->f3->reroute('/');
            }
        }
        return $ServicepackageAll_arr;
    }
}
