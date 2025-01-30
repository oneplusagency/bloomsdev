<?php
// https://stackoverflow.com/questions/19178083/convert-simplexml-to-json-php
// XML::toArray
// Curl::__curl(array $headers, $url, $method, $body='', $config=[])



function getXMLData($url) {

		$ch = curl_init();

		$timeout = 5;

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Accept: application/json"));

        //curl_setopt($ch, CURLOPT_POST, 1);
		$body = '[1]';
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

		$xmlData = curl_exec($ch);

		curl_close($ch);

		return $xmlData;

	}

date_default_timezone_set("Europe/Berlin"); //https://secure.php.net/manual/en/timezones.europe.php

define('PAGECON_ONWERK_V2', 'https://api.bloom-s.de:780/api/');

$test_date =  date("Y-m-d", strtotime("+2 week"));


// $arr  = $http->get('employee/stylebookpictures?employeeId=' . (int) $employeeId);
// $arr  = $http->get('employee/stylebookpictures');

echo  "<h2>Stylebook für Sabine</h2>";

echo  "\n\r";
// $jsondata = getXMLData(PAGECON_ONWERK_V2 . 'employee/stylebookpictures');
$jsondata = getXMLData(PAGECON_ONWERK_V2 . 'employee/stylebookpictures?employeeId=1010');
//var_export( $jsondata , true)
// file_put_contents (__DIR__ ."/_local/iCalendar.json" , $jsondata , LOCK_EX );
// file_put_contents (__DIR__ ."/stylebook_melisa.json" , $jsondata , LOCK_EX );
/*
$jsondata = str_replace(PHP_EOL, "", $jsondata);
$jsondata = str_replace("&#xD;", "\n", $jsondata);
*/
echo  "<pre>";
var_export(json_decode($jsondata));
// echo  (json_decode($jsondata));
echo  "</pre>";

exit;

?>