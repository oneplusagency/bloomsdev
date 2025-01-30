<?php

// namespace Model;

/**
 * @file: stylebookModel.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\models
 * @created:    Wed Feb 05 2020
 * @author:     oppo,
 * @version:    1.0.0
 * @modified:   Wednesday February 5th 2020 6:52:57 pm
 */

// $this->user = new \Model\User($this->casted['uid']);
class stylebookModel extends DB\SQL\Mapper
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
		// parent::__construct($db, 'stylebook');
		$f3       = \Base::instance();
		$this->f3 = $f3;

		$this->base      = $this->f3->get('BASE');
		$this->page_host = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
	}

	public static function serviceAvailable()
	{
		//https://api.bloom-s.de:780/api/ping
		return helperblooms::serviceAvailable();
	}

	/**
	 * @param  $employeeId
	 * @return mixed
	 */
	public static function getStylebookEmployeeImage($employeeId, $all = true)
	{
		$arr = $res = false;
		if (self::serviceAvailable()) {
			$http = new Bugzilla();
			$arr  = $http->get('employee/stylebookpictures?employeeId=' . (int) $employeeId);
		}

		if (!$arr) {
			ob_start();
			include_once ONEPLUS_DIR_PATH_APP . 'helper/json/employee_stylebookpictures_employeeid_1106.json';
			$arr = ob_get_clean();
		}

		if ($arr && ($StylebookImg = helperblooms::jsJson($arr, true))) {

			if (isset($StylebookImg['Success']) && $StylebookImg['Success'] == true) {

					$res = $StylebookImg['Result'];
					$res = helperblooms::parseXmlToArraysalons($res, 'Order');
			}
		}
		return $res;
	}


	public function getCasheStylebookEmployeeImage($id = null, $redirect = false)
	{
		$id                    = intval($id);
		$stylebookallimage_arr = [];

		$cacheblooms = $this->f3->get('CacheBlooms');
		$code        = 'StylebookEmployeeImage' . $id;

		$result = $cacheblooms->retrieve($code, true);

		if ($result) {
			$stylebookallimage_arr = json_decode($result, true);

		} else {

			if ($stylebook_array = static::getStylebookEmployeeImage($id)) {

				$cacheblooms->store($code, json_encode($stylebook_array, true), TIME_CACHEBLOOMS + 600); // 3600 => 'hour',

				$stylebookallimage_arr = $stylebook_array;
				unset($stylebook_array);
			} elseif ($redirect) {
				// $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
				$this->f3->set('SESSION.error', 'Stylebook existiert nicht');
				$this->f3->reroute('/');
			}
		}

		return $stylebookallimage_arr;
	}
	/**
	 * @param  $employeeId
	 * @return mixed
	 */
	public static function getStylebookAllImage()
	{
		$arr = $res = false;
		if (self::serviceAvailable()) {
			$http = new Bugzilla();
			$arr  = $http->get('employee/stylebookpictures');

		// $tmp = ONEPLUS_DIR_PATH . DIRECTORY_SEPARATOR . 'dev';
        // file_put_contents( $tmp.'/server_stylebook_model_all_image.json' , var_export( $arr , true),  LOCK_EX );
		}

		if (!$arr) {
			$f3       = \Base::instance();
			$ip                        = $f3->get('IP');
			$local                     = ($ip == '127.0.0.1' ? true : false);
			if ($local) {
			ob_start();
			include_once ONEPLUS_DIR_PATH_APP . 'helper/json/employee_stylebookpictures.json';
			$arr = ob_get_clean();
			}
		}

		if ($arr && ($StylebookImg = helperblooms::jsJson($arr, true))) {

			if (isset($StylebookImg['Success']) && $StylebookImg['Success'] == true) {
				$res = $StylebookImg['Result'];

				// $tmp = ONEPLUS_DIR_PATH . DIRECTORY_SEPARATOR . 'dev';
				// file_put_contents( $tmp.'/1result_stylebook_model_all_image.json' , var_export( $res , true),  LOCK_EX );

				// $res = helperblooms::parseXmlToArraysalons($res, 'Order');

				// $tmp = ONEPLUS_DIR_PATH . DIRECTORY_SEPARATOR . 'dev';
				// file_put_contents( $tmp.'/2parse_res_stylebook_model_all_image.json' , var_export( $res , true),  LOCK_EX );
			}
		}
		return $res;
	}

	/**
	 * @param $id
	 * @param null $redirect
	 * @return mixed
	 */
	public function getCasheStylebookAllImage($id = null, $redirect = true)
	{
		$id                    = intval($id);
		$stylebookallimage_arr = [];

		$cacheblooms = $this->f3->get('CacheBlooms');
		$code        = 'StylebookAllImage' . $id;

		$result = $cacheblooms->retrieve($code, true);

		if ($result) {
			$stylebookallimage_arr = json_decode($result, true);
		} else {

			if ($stylebook_array = self::getStylebookAllImage()) {

				$cacheblooms->store($code, json_encode($stylebook_array, true), TIME_CACHEBLOOMS + 600); // 3600 => 'hour',

				$stylebookallimage_arr = $stylebook_array;

		// $tmp = ONEPLUS_DIR_PATH . DIRECTORY_SEPARATOR . 'dev';
        // file_put_contents( $tmp.'/nocache_stylebook_model_all_image.json' , var_export( $stylebookallimage_arr , true),  LOCK_EX );

				unset($stylebook_array);
			} elseif ($redirect) {
				// $this->f3->set( 'SESSION.error', 'Salon doesn\'t exist' );
				$this->f3->set('SESSION.error', 'Stylebook existiert nicht');
				$this->f3->reroute('/');
			}
		}

		$stylebookallimage_arr_TEST = array(
			0  => array(
				'EmployeeId'  => 1106,
				'Order'       => 0,
				'PictureName' => '18118782_1948617428758186_6016199437378247040_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/8_18118782_1948617428758186_6016199437378247040_n.jpg'
			),
			1  => array(
				'EmployeeId'  => 1106,
				'Order'       => 1,
				'PictureName' => '18119158_1948617285424867_9212165720728800132_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/9_18119158_1948617285424867_9212165720728800132_n.jpg'
			),
			2  => array(
				'EmployeeId'  => 1515,
				'Order'       => 2,
				'PictureName' => '18199280_1948617645424831_7015525271823909169_n',
				'SalonId'     => 25,
				'Url'         => 'https://api.bloom-s.de:1690/10_18199280_1948617645424831_7015525271823909169_n.jpg'
			),
			3  => array(
				'EmployeeId'  => 1106,
				'Order'       => 3,
				'PictureName' => '20228762_462554294122677_1345214187617559387_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/11_20228762_462554294122677_1345214187617559387_n.jpg'
			),
			4  => array(
				'EmployeeId'  => 1106,
				'Order'       => 4,
				'PictureName' => '20620772_468375626873877_3338974651915703475_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/12_20620772_468375626873877_3338974651915703475_n.jpg'
			),
			5  => array(
				'EmployeeId'  => 1106,
				'Order'       => 5,
				'PictureName' => '20664119_468616056849834_1222924722992259284_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/13_20664119_468616056849834_1222924722992259284_n.jpg'
			),
			6  => array(
				'EmployeeId'  => 1106,
				'Order'       => 6,
				'PictureName' => '20915633_475709549473818_975447756265798997_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/14_20915633_475709549473818_975447756265798997_n.jpg'
			),
			7  => array(
				'EmployeeId'  => 1106,
				'Order'       => 7,
				'PictureName' => '21271333_2019970238289571_5613213299995861761_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/15_21271333_2019970238289571_5613213299995861761_n.jpg'
			),
			8  => array(
				'EmployeeId'  => 1335,
				'Order'       => 8,
				'PictureName' => '21272493_708758749322230_620921782973198176_n',
				'SalonId'     => 25,
				'Url'         => 'https://api.bloom-s.de:1690/16_21272493_708758749322230_620921782973198176_n.jpg'
			),
			9  => array(
				'EmployeeId'  => 1106,
				'Order'       => 9,
				'PictureName' => '21371030_2020795041540424_8607359604980797168_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/17_21371030_2020795041540424_8607359604980797168_n.jpg'
			),
			10 => array(
				'EmployeeId'  => 1106,
				'Order'       => 10,
				'PictureName' => '21432836_606936809695474_1571953869948257688_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/18_21432836_606936809695474_1571953869948257688_n.jpg'
			),
			11 => array(
				'EmployeeId'  => 1466,
				'Order'       => 11,
				'PictureName' => '21462253_485101345201305_8754338363722319011_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/19_21462253_485101345201305_8754338363722319011_n.jpg'
			),
			12 => array(
				'EmployeeId'  => 1106,
				'Order'       => 12,
				'PictureName' => '21558561_2025268437759751_3130805901797682807_n',
				'SalonId'     => 17,
				'Url'         => 'https://api.bloom-s.de:1690/20_21558561_2025268437759751_3130805901797682807_n.jpg'
			)
		);

		if (count($stylebookallimage_arr)) {

			$poryadok = [];
			foreach ($stylebookallimage_arr as $key => $v) {

				// 0 =>
				// array (
				//   'EmployeeId' => 1106,
				//   'Order' => 0,
				//   'PictureName' => '18118782_1948617428758186_6016199437378247040_n',
				//   'SalonId' => 17,
				//   'Url' => 'https://api.bloom-s.de:1690/8_18118782_1948617428758186_6016199437378247040_n.jpg',
				// ),

				$poryadok[$v['SalonId']][$v['EmployeeId']][] = $v;
			}
			return $poryadok;
		}

		return [];
	}


	/**
	 * @param $url
	 * @return mixed
	 */
	public function getimg($url)
	{
		$headers[]  = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
		$headers[]  = 'Connection: Keep-Alive';
		$headers[]  = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$user_agent = 'php';
		$process    = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		// curl_setopt($process, CURLOPT_USERAGENT, $user_agent); //check here
		curl_setopt($process, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}


	public function urlStylebookImgResize($url, $salonId, $employeeId, $local = false)
	{

		$target_abs = UPLOAD_STYLEBOOK_ABS_DIR . $salonId . DIRECTORY_SEPARATOR . $employeeId;

		$target = $salonId . '/' . $employeeId;

		$imagename = basename($url);
		$imagename_local_url = $target . '/' . $imagename;
		$imagename_local_url_thumb = $target . '/thumb/' . $imagename;

		$new_url_file = [];
		$new_url_file = ['full' => $url, 'thumb' => $url];

		if (!file_exists(UPLOAD_STYLEBOOK_ABS_DIR . $imagename_local_url_thumb)) {

			$employee_image = $this->getimg($url);

			if ($employee_image) {

				helperblooms::op_mkdir($target_abs);

				try {

					file_put_contents(UPLOAD_STYLEBOOK_ABS_DIR . $imagename_local_url, $employee_image);


					if (file_exists(UPLOAD_STYLEBOOK_ABS_DIR . $imagename_local_url)) {
						// $newPathImage = UPLOAD_STYLEBOOK_ABS_DIR . $target . '/th_' . $name . '.jpg';

						// $h_full = 550;
						// $newFullimage     = new GumletImageResize(UPLOAD_STYLEBOOK_ABS_DIR . $imagename_local_url);
						// $newFullimage ->resizeToHeight($h_full)->save($newFullimage);

						helperblooms::op_mkdir(UPLOAD_STYLEBOOK_ABS_DIR . $target . '/thumb/');
						$newPathImage = UPLOAD_STYLEBOOK_ABS_DIR . $target . '/thumb/' . $imagename;
						$newimage     = new GumletImageResize(UPLOAD_STYLEBOOK_ABS_DIR . $imagename_local_url);
						//Dimensions    1080 x 1080 px (scaled to 255 x 325 px)
						// $h = 325;
						$h = 255;
						$w = 255;
						// // $newimage->crop($w, $h, GumletImageResize ::CROPCENTER)->save($newPathImage);

						// $filename,
						// $image_type = null, IMAGETYPE_JPEG IMAGETYPE_WEBP
						// $quality = null,
						// $permissions = null,
						// $exact_size = false

						// helperblooms::recursivelyRemoveDirectory(UPLOAD_STYLEBOOK_ABS_DIR, array('emp_image.jpg'));
						$newimage->resizeToHeight($h,true)->save($newPathImage, IMAGETYPE_JPEG, 80, null);

						$new_url_file = [];
						$new_url_file = ['full' => UPLOAD_STYLEBOOK_DIR . $target . '/' . $imagename, 'thumb' => UPLOAD_STYLEBOOK_DIR . $target . '/thumb/' . $imagename];

						// $newimage->resizeToBestFit(800, 600)->save($newPathImage);
					}
					// $time = strtotime($value['LastModified']);
					// touch(UPLOAD_STYLEBOOK_ABS_DIR . $imagename_local_url, $time);
				} catch (\Exception $e) {
					$logger = new \Log($this->f3->get('LOGS') . 'Stylebook_pictures_error.log');
					$logger->write('Error :' . $e->getMessage() . '');
				}
			}
		} else {
			$new_url_file = [];
			$new_url_file = ['full' => UPLOAD_STYLEBOOK_DIR . $imagename_local_url, 'thumb' => UPLOAD_STYLEBOOK_DIR . $imagename_local_url_thumb];
		}

		return $new_url_file;
	}
} // end class
