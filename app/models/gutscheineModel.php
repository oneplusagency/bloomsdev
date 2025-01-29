<?php

/**
 * @file: gutscheineModel.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\models
 * @created:    Thu Mar 12 2020
 * @author:     oppo
 * @version:    1.0.0
 * @modified:   Thursday March 12th 2020 3:06:06 pm
 */

class gutscheineModel
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
     * @var mixed
     */
    public $gutscheine_mapper;
    /**
     * @var mixed
     */
    protected $db;

    // const PAGECON_ONWERK_V2 = 'https://api.bloom-s.de:780/api/';
    const PAGECON_ONWERK_V2 = 'https://api.bloom-s.de:780/api/';
	

    public static $apiurl;

    /**
     * @param DB\SQL $db
     */
    public function __construct()
    {
        // parent::__construct($db, 'stylebook');
        $f3       = \Base::instance();
        $this->f3 = $f3;

        $this->base      = $this->f3->get('BASE');
        $this->page_host =
            (isset($_SERVER['HTTPS']) ? 'https' : 'http') .
            "://$_SERVER[HTTP_HOST]";

        $this->db         = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
        $this->gutscheine_mapper = new \DB\Jig\Mapper($this->db, 'gutscheine.json');
    }

    public static function getApiUrl()
    {
        if (!defined('PAGECON_ONWERK_V2')) {
            define('PAGECON_ONWERK_V2', self::PAGECON_ONWERK_V2);
        }

        self::$apiurl = PAGECON_ONWERK_V2;
        return self::$apiurl;
    }



    public static function serviceAvailable()
    {
        //https://api.bloom-s.de:780/api/ping
        return helperblooms::serviceAvailable();
    }

    /**
     * @param $url
     * @param $body
     * @return mixed
     */
    public static function getApiData($url, $body = '')
    {
        $ch = curl_init();

        $timeout = 5;

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        curl_setopt($ch, CURLOPT_POST, 1);

        if ($body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $xmlData = curl_exec($ch);

        curl_close($ch);

        return $xmlData;
    }

    /**
     * @param $query
     * @param $parent
     */
    public static function url_build_query($query, $parent = null)
    {
        $query_array = array();
        foreach ($query as $key => $value) {
            $_key = empty($parent) ? $key : $parent . '[' . $key . ']';
            if (is_array($value)) {
                $query_array[] = self::url_build_query($value, $_key);
            } else {
                $value         = preg_replace('/ /', '%20', $value);
                $query_array[] = $_key . '=' . trim($value);
            }
        }
        return implode('&', $query_array);
    }

    /**
     * Output span with progress.
     *
     * @param $current integer Current progress out of total
     * @param $total   integer Total steps required to complete
     */
    public static function outputProgress($current, $total)
    {
        echo '<div style="position: absolute;z-index:$current;background:#FFF;top: 50%;left: 50%;transform: translate(-50%, -50%);">PDF-Datei erstellen, bitte warten.. ' .
            round(($current / $total) * 100) .
            '% </div>';
        self::myFlush();
        // sleep(1);
    }

    /**
     * Flush output buffer
     */
    public static function myFlush()
    {
        echo str_repeat(' ', 256);
        if (@ob_get_contents()) {
            @ob_end_flush();
        }
        flush();
    }

    /**
     * @param  $employeeId
     * @return mixed
     */
    public function getGutscheineCode($content, $delivery)
    {
        $res = false;
        if (self::serviceAvailable() && !empty($content)) {



            // $content = array_filter($content);
		
            $add_url_params = '?' . http_build_query($content);
            $url            = 'coupon/createAnd'. $delivery . $add_url_params;

           /*  $url = preg_replace('/ /', '%20', $url);
            $url = preg_replace('/&/', '&amp;', $url);
		 */
            // $url            = self::PAGECON_ONWERK_V2.$url;
            $apiurl = self::getApiUrl();
            $url = $apiurl . $url;

	
            // $jsondata = $ftp->post($url, http_build_query($content));
            $arr = self::getApiData($url, $content);
		
            // file_put_contents(ONEPLUS_DIR_PATH . "/GutscheineCode_model.txt", var_export($arr, true), LOCK_EX);

            // stdClass::__set_state(array(
            //     'GiftCouponCode' => 'BWYH85',
            //     'GiftCouponPdfFileUri' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf?/blooms_gutschein_014_vc93P1VGl0mLmjP9lNOGrw.pdf',
            //     'InvoicePdfFileUri' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf?/blooms_rechnung_014_vc93P1VGl0mLmjP9lNOGrw.pdf',
            //     'SendMailSucceeded' => true,
            //     'AdditionalInfo' => NULL,
            //     'ReturnCodeValue' => 0,
            //     'ReturnValueText' => 'AllFine',
            //  ))
        }

        if (!$arr) {

             // disable for non local host 24.04.2020 14:36
            $ip                        = $this->f3->get('IP');
            $local                     = ($ip == '127.0.0.1' ? true : false);

            if ($ip == '127.0.0.1') {

                ob_start();
                if (
                    is_file(
                        ONEPLUS_DIR_PATH_APP .
                            'helper/json/coupon_createandinvoice_giftcouponvalue.json'
                    )
                ) {
                    include_once ONEPLUS_DIR_PATH_APP .
                        'helper/json/coupon_createandinvoice_giftcouponvalue.json';
                    $arr = ob_get_clean(); // (string)"Hello World"
                }
            }
        }
		if($delivery == 'InvoicePostal'){
			$obj = json_decode($arr);
			unset($obj->GiftCouponPdfFileUri);
			$arr = json_encode($obj);
		
			
		
		}
	
        if ($arr && ($GutscheineCode = helperblooms::jsJson($arr, true))) {
            return $GutscheineCode;
        }

        return $res;
    }
}
