<?php

// namespace Model;

/**
 * @file: Preises.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\models
 * @created:    Tue Jan 14 2020
 * @author:     oppo
 * @version:    1.0.0
 * @modified:   Tuesday January 14th 2020 1:10:03 pm
 * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */


// $this->user = new \Model\User($this->casted['uid']);
class preisesModel extends DB\SQL\Mapper
{
	/**
	 * @param DB\SQL $db
	 */

	public function __construct(DB\SQL $db = null)
	{
		// parent::__construct($db, 'preises');
	}

	public static function serviceAvailable()
	{
		//https://api.bloom-s.de:780/api/ping
		return helperblooms::serviceAvailable();
	}

	/**
	 * @return mixed
	 */
	public function getPreiseCat()
	{

		$preises =  false;
		if (self::serviceAvailable()) {
			// $preises = new SimpleXMLElement( $xml, null, false );
			$simpleXml = helperblooms::parseXmlServer(PAGECON_ONWERK_SALON_ALL);
		}

		if (!$simpleXml) {
			include_once ONEPLUS_DIR_PATH_APP . 'helper/local/categorizedprices.php';
			$fileContents = $categorizedprices_local;
			$simpleXml    = simplexml_load_string($fileContents, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
		}

		if ($simpleXml) {
			$preises = $simpleXml->xpath('//ServicePackageCategory');
			return $preises;
		}

		return $preises;
	}

	/**
	 * @param string $content
	 *
	 * @return array
	 */
	public function parseXmlToArraypreises($items)
	{

		// '@attributes' =>
		// array (
		//   'Id' => '1',
		//   'Name' => 'Frauen',
		// ),

		$result = [];
		foreach ($items as $item) {
			$preiseId = (int) $item[0]->attributes()->Id;
			$preiseName = (string) $item[0]->attributes()->Name;
			// $preiseName = bin2hex($preiseName);
			$preiseAlias = helperblooms::safe_filename($preiseName);
			$item[$preiseAlias] = $preiseName;
			$result[$preiseId][$preiseAlias] = (array) $item;
		}
		return $result;
	}

	/**
	 * @param $preiseId
	 * @return mixed
	 */
	public function getPreiseTeam($preiseId)
	{
		$preiseTeam = false;
		if (self::serviceAvailable()) {
			//team_local
			$simpleXml = helperblooms::parseXmlServer(PAGECON_ONWERK_SALON . 'team?preiseId=' . $preiseId);
		}

		if (!$simpleXml) {
			include_once ONEPLUS_DIR_PATH_APP . 'helper/local/team.php';
			$fileContents = $team_local;
			$simpleXml    = simplexml_load_string($fileContents, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
		}
		if ($simpleXml) {
			// $preiseTeam = $simpleXml->xpath('//Preise');
			return $simpleXml;
		}

		return $preiseTeam;
	}

	public function getEmployeeImage($employeeId)
	{
		$employeeImage = false;
		if (self::serviceAvailable()) {
			//team_local
			$simpleXml = helperblooms::parseXmlServer(PAGECON_ONWERK_EMPLOYEE_IMAGE . '' . $employeeId);
		} else {
			$f3 = \Base::instance();
			$logger = new \Log($f3->get('LOGS') . 'service_available.log');
			$logger->write('preisesModel::getEmployeeImage error :' . $employeeId . "");
		}

		// if (!$simpleXml) {
		// 	include_once ONEPLUS_DIR_PATH_APP . 'helper/local/employeeimage.php';
		// 	$fileContents = trim($employee_image);
		// 	$simpleXml   = simplexml_load_string($fileContents, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

		// 	$f3 = Base::instance();
		// 	$logger = new \Log($f3->get('LOGS') . 'error_model.log');
		// 	$logger->write('preisesModel::getEmployeeImage error :'.$employeeId."");
		// }
		if ($simpleXml) {
			// $employeeImage = $simpleXml->xpath('//Preise');
			return $simpleXml;
		}
		//$data = base64_decode($img[0]->Image);
		return $employeeImage;
	}


	/**
	 * Возвращает список цен на пакеты услуг, сгруппированные по категориям. Цены специфичны для салона.
	 * Эта функция также доступна через категоризированныйserviceпакет/салон (Только V1),
	 * который был введен для постоянного наименования функций.
	 *
	 * @param $preiseId
	 * @return mixed
	 *
	 * preise/categorizedPrices (NUR V1) Funktion: <BASE>/preise/categorizedPrices
	 * Aufruf: <BASE>/preise/categorizedPrices?preiseId={SALONID}
	 *
	 * Beschreibung: Liefert eine Liste der Preise für die Dienstleistungspakete gruppiert nach Kategorie zurück. Preise sind preisespezifisch.
	 *
	 * Diese Funktion ist auch erreichbar über categorizedservicepackage/preise (NUR V1),
	 * die eingeführt wurde für eine konstante Benennung der Funktionen.

	 *
	 */

		/**
	 * Возвращает список цен на пакеты услуг. Цены специфичны для салона.
	 *
	 * @param preise/prices (NUR V1) Funktion: <BASE>/preise/prices
	 * @Aufruf: <BASE>/preise/prices?preiseId={SALONID}
	 *
	 * {SALONID} Номер идентификатор салона, на который будут определены цены
	 * Список объектов ServicePackagePrice
	 *
	 * @param $preiseId
	 * @return mixed
	 */



	/**
	 * @param $url
	 * @return mixed
	 */
	private function getXMLData($url)
	{

		$ch = curl_init();

		$timeout = 5;

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$xmlData = curl_exec($ch);

		curl_close($ch);

		return $xmlData;
	}
}
