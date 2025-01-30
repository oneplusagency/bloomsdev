<?php
// https://stackoverflow.com/questions/19178083/convert-simplexml-to-json-php
// XML::toArray
// Curl::__curl(array $headers, $url, $method, $body='', $config=[])
class Curl
{

    public static function __curl(array $headers, $url, $method, $body='', $config=[])
    {
        $ch = curl_init();
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $default_config = [
            'connect_timeout' => 3,
            'dns_cache_timeout' => 3,
            'full_timeout' => 30,
            'UA' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
        ];
        $config = array_merge($default_config, $config);

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $config['UA']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $config['connect_timeout']);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, $config['dns_cache_timeout']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['full_timeout']);
        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
	$errors = curl_error($ch);
        curl_close($ch);
        $headers_raw = explode("\r\n", $header);
	$status_row = array_shift($headers_raw);
	$status = explode(' ', $status_row)[1];
        $headers = [];
        foreach ($headers_raw as $header) {
            @list($k, $v) = explode(': ', $header);
            $headers[$k] = $v;
        }
        return [$errors, $status, $headers, $body];
    }

}


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

$pageconOnwerkApis = [
    '20xx' => 'ma24247.plusserver.de:81', //@deprecated @since 21.01.2019
    '201901' => 'api.bloom-s.de:780' //PM (21.01.2019) current api; 779 is a custom port that had to be opened for this purpose
];
define('PAGECON_ONWERK', "https://{$pageconOnwerkApis['201901']}/api/");
//define('PAGECON_ONWERK_V2', "https://api.bloom-s.de:780/api/");

define('PAGECON_ONWERK_V2', 'https://api.bloom-s.de:780/api/');

$test_date =  date("Y-m-d", strtotime("+2 week"));

// appointment/iCalendar
// Funktion: <BASE>/appointment/iCalendar
// Aufruf: <BASE>/appointment/iCalendar?appointmentId={APPOINTMENTID}
// Beschreibung: Liefert einen im iCalendar-Format formatierten String zurück. Wichtig zu beachten ist
// bei der Erstellung einer „*.ics“-Datei, dass diese mit dem Encoding „UTF8 without BOM“ erstellt wird,
// da ansonsten Google und Android Probleme mit der Verarbeitung der Datei haben.


echo  "<h2>Funktion: <BASE>/appointment/iCalendar</h2>";
echo  "<h3>Aufruf: <BASE>/appointment/iCalendar?appointmentId={APPOINTMENTID}</h3>";
echo  "\n\r";
$jsondata = getXMLData(PAGECON_ONWERK_V2 . 'appointment/iCalendar?appointmentId=ccee68f6-1d79-46b8-a78e-b80d3ba27e9a');
$jsondata = getXMLData(PAGECON_ONWERK_V2 . 'appointment/iCalendar?appointmentId=3fb71d2a-e751-46c7-819e-248f56cd4654');
//var_export( $jsondata , true)
// file_put_contents (__DIR__ ."/_local/iCalendar.json" , $jsondata , LOCK_EX );
file_put_contents (__DIR__ ."/iCalendar.json" , $jsondata , LOCK_EX );
/*
$jsondata = str_replace(PHP_EOL, "", $jsondata);
$jsondata = str_replace("&#xD;", "\n", $jsondata);
*/
echo  "<pre>";
// var_export(json_decode($jsondata));
echo  nl2br(json_decode($jsondata));
echo  "</pre>";

exit;

?>