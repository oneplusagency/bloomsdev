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


function sendRequest($url, $requestType = 'GET', $body = '', $postvars = '')
{
    $header = "Accept: application/json";

    if ($postvars) {
       $header .= "\r\nContent-type: application/x-www-form-urlencoded";
    } else {
       $header .= "\r\nContent-Type: text/html; charset=utf-8";
    }

    if (!empty($postvars)) {
        // Process them.
        $body = http_build_query($postvars);
    }
//'header'=>    array( "Cookie: foo="bar"l ),
    $context = stream_context_create(array('http' => array(
        'method' => $requestType,
        'header' => $header,
        //'body' => $body,
		'content' => $body
    )));

    $response = file_get_contents($url, false, $context);

    if ($http_response_header) {
     /*
     array (
  0 => 'HTTP/1.1 200 OK',
  1 => 'Cache-Control: no-cache',
  2 => 'Pragma: no-cache',
  3 => 'Content-Length: 66',
  4 => 'Content-Type: application/json; charset=utf-8',
  5 => 'Expires: -1',
  6 => 'Server: Microsoft-IIS/10.0',
  7 => 'X-AspNet-Version: 4.0.30319',
  8 => 'X-Powered-By: ASP.NET',
  9 => 'Set-Cookie: ARRAffinity=38603d278206bcfb4269154b3d6ef8bcf481de50b927653b45022d957e1e2109;Path=/;HttpOnly;Domain=api.bloom-s.de:780',
  10 => 'Date: Wed, 22 Jan 2020 11:47:46 GMT',
  11 => 'Connection: close',
)
     */

    }

    return $response;
}


date_default_timezone_set("Europe/Berlin"); //https://secure.php.net/manual/en/timezones.europe.php

define('PAGECON_ONWERK_V2', 'https://api.bloom-s.de:780/api/');

$test_date =  date("Y-m-d", strtotime("+2 week"));


// $arr  = $http->get('employee/stylebookpictures?employeeId=' . (int) $employeeId);
// $arr  = $http->get('employee/stylebookpictures');

echo  "<h2>Stylebook von Melisa</h2>";

echo  "\n\r";
$jsondata = getXMLData(PAGECON_ONWERK_V2 . 'employee/stylebookpictures');
// $jsondata = getXMLData(PAGECON_ONWERK_V2 . 'employee/stylebookpictures?employeeId=1253');
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