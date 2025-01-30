<?php
/*
 * BugzillaPHP - PHP class interface to Bugzilla (version 3.2 and above).
 * Copyright 2009 Scott Teglasi <steglasi@subpacket.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Bugzilla Connector class.
 *
 * @author Scott Teglasi <steglasi@subpacket.com>
 * @version 0.1
 * @copyright 2009 Scott Teglasi <steglasi@subpacket.com>
 */
class Bugzilla
{
    // const PAGECON_ONWERK_V2 = "https://api.bloom-s.de:780/api/";
    const PAGECON_ONWERK_V2 = "https://api.bloom-s.de:780/api/";
    const PAGECON_ONWERK    = "https://api.bloom-s.de:780/api/";

    public static $apiurl;


    /**
     * @var mixed
     */
    private $bugzillaUrl;
    /**
     * @var string
     */

    public $cookies = array();

    /**
     * @var array
     */
    public $last_headers = array();

    /**
     * @var array
     */
    public $options = array(
        'http'  => array(
            'max_redirects' => 1,
            'ignore_errors' => 1,
            'method'        => "GET"
        ),
        'other' => array(
            'follow_redirects' => true
        ),
        'ssl'                => array(
            'peer_name'          => 'generic-server',
            'verify_peer'        => FALSE,
            'verify_peer_name'   => FALSE,
            'allow_self_signed'  => TRUE
             )
    );

    // $header = "Accept: application/json";
    /**
     * @var array
     */
    public $default_headers = array(
        #'Host'             => 'www.facebook.com',
        'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
        // 'Accept'           => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept'          => 'Accept: application/json',
        'Accept-Language' => 'en-us,en;q=0.5',
        // 'Accept-Charset'   => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
        'Content-Type'    => 'application/json; charset=UTF-8',
        'Pragma'          => 'no-cache',
        'Cache-Control'   => 'no-cache'
    );

    /**
     * @param $options
     * @param array $cookies
     */
    public function __construct($options = null, $cookies = array())
    {
        if (isset($options['proxy'])) {
            $this->options = helperblooms::merge($this->options, array(
                'http' => array(
                    'proxy'           => $options['proxy'],
                    'request_fulluri' => true
                )
            ));
        }

        if ($options) {
            $this->options['other'] = helperblooms::merge($this->options['other'], $options);
        }
        if (isset($options['headers'])) {
            $this->default_headers = helperblooms::merge($this->default_headers, $options['headers']);
        }
    }


    public static function getApiUrl()
    {
        if (!defined('PAGECON_ONWERK_V2')) {
            define('PAGECON_ONWERK_V2', self::PAGECON_ONWERK_V2);
        }

        self::$apiurl = PAGECON_ONWERK_V2;
        return self::$apiurl;
    }



    /**
     * @param $url
     * @return mixed
     */
    public function get($url)
    {
        return $this->do_request($url, $this->options);
    }

    /**
     * @param $url
     * @param $content
     * @return mixed
     */
    public function post($url, $content = '')
    {
        $options = helperblooms::merge($this->options, array(
            'http' => array(
                'method'  => "POST",
                'content' => $content
            )
        ));
        return $this->do_request($url, $options, $content);
    }

    /**
     * @param $content
     */
    public function build_headers($content = null)
    {
        $headers = $this->default_headers;

        if ($this->cookies) {
            $headers['Cookie'] = $this->build_cookies();
        }

        if ($content) {
            $headers['Content-Length'] = strlen($content);
        }

        foreach ($headers as $name => $value) {
            $head[] = "{$name}: {$value}";
        }

        return trim(implode("\r\n", $head));
    }

    /**
     * Send a request to bugzilla.
     *
     * @param string $url
     * @param string $requestType GET or POST
     * @param string $body
     * @param array $postvars
     * @return string
     */
    /**
     * @param $headers
     * @return mixed
     */
    /**
     * @param $url
     */
    private function do_request($url, $options, $content = null)
    {

        $apiurl = self::getApiUrl();
        $url = $apiurl . $url;

        // $header = "Accept: application/json";

        // if ($this->cookies) {
        //     $header .= "\r\n" . $this->cookiesToHeader();
        // }

        // if ($postvars) {
        //     $header .= "\r\nContent-type: application/x-www-form-urlencoded";
        // } else {
        //     $header .= "\r\nContent-Type: text/html; charset=utf-8";
        // }

        // $options['http']['header'] = $this->build_headers($content);
        $options['http']['header'] = $this->build_headers();
        stream_context_get_default($options);

        // if (!empty($postvars)) {
        //     // Process them.
        //     $body = http_build_query($postvars);
        // }

        $response = file_get_contents($url);
        // preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#", $http_response_header[0], $match );
        // $statusCode = intval( $match[1] );
        $header_arrgs = $this->parseHeaders($http_response_header);

        // echo '<pre>';
        //     var_export($header_arrgs);
        // echo '</pre>';
        // // exit;

        // array (
        //     0 => 'HTTP/1.1 403 Ip Forbidden',
        //     'reponse_code' => 403,
        //     'Content-Type' => 'text/html',
        //     'Server' => 'Microsoft-IIS/10.0',
        //     'Date' => 'Thu, 23 Jan 2020 15:40:03 GMT',
        //     'Connection' => 'close',
        //     'Content-Length' => '2345',
        //   )

        if (isset($header_arrgs['reponse_code']) && $header_arrgs['reponse_code'] != '200') {
            return false;
        }
        // Grab any cookies that were sent in         this request and stash 'em in the session.
        $cookieList = array();
        foreach ($http_response_header as $item) {
            if (substr($item, 0, 11) == 'Set-Cookie:') {
                // Got a cookie.  Save it!
                $cookieList[] = substr($item, 12);
            }
        }
        // Save cookies.
        if (count($cookieList) > 0) {
            $this->saveCookies($cookieList);
        }

        return $response;
    }


    private static function pingCurl($url) {

		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Accept: application/json"));
		$xmlData = curl_exec($ch);
		curl_close($ch);
		return $xmlData;

	}


    public function url_valid($url)
    {

        // $url = self::PAGECON_ONWERK_V2 . $url;
        $apiurl = self::getApiUrl();
        $url = $apiurl . $url;

        $ping = self::pingCurl($url);

        if ($ping == 'true' || $ping == true) {
           return true;
        }
        return false;


        /** @FIX by oppo (webiprog.de), @Date: 2020-04-23 13:43:04
         * @Desc: перес"лало новим апи api.bloom-s.de:780  - get_headers не працює нижче
         */

        // $file_headers = @get_headers($url);
        // if ($file_headers === false) {
        //     return false;
        // }
        // // when server not found
        // foreach ($file_headers as $header) {
        //     // parse all headers:
        //     // corrects $url when 301/302 redirect(s) lead(s) to 200:
        //     if (preg_match("/^Location: (http.+)$/", $header, $m)) {
        //         $url = $m[1];
        //     }

        //     // grabs the last $header $code, in case of redirect(s):
        //     if (preg_match("/^HTTP.+\s(\d\d\d)\s/", $header, $m)) {
        //         $code = $m[1];
        //     }
        // } // End foreach...
        // if ($code == 200) {
        //     return true;
        // }
        // // $code 200 == all OK
        // else {
        //     return false;
        // }
        // All else has failed, so this must be a bad link
    }
    // End function url_exists

    /**
     * @param $headers
     * @return mixed
     */
    private function parseHeaders($headers)
    {
        $head = array();
        foreach ($headers as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1])) {
                $head[trim($t[0])] = trim($t[1]);
            } else {
                $head[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out)) {
                    $head['reponse_code'] = intval($out[1]);
                }
            }
        }
        return $head;
    }

    private function build_cookies()
    {
        foreach ($this->cookies as $name => $value) {
            $value    = $value['value'];
            $cookie[] = "{$name}={$value}";
        }
        if (count($cookie) > 0) {
            return trim(implode('; ', $cookie));
        }
    }

    /**
     * @param $header
     * @return mixed
     */
    private function parse_cookies($header)
    {
        $csplit = explode(';', $header);
        $cdata  = array();
        foreach ($csplit as $data) {
            $cinfo = explode('=', $data);

            // todo clean up and use cookie class

            $cinfo[0] = trim($cinfo[0]);
            if (in_array($cinfo[0], array('secure', 'httponly'))) {
                $cinfo[1] = true;
            }

            if ($cinfo[0] == 'expires') {
                $cinfo[1] = strtotime($cinfo[1]);
            }

            if (in_array($cinfo[0], array('domain', 'expires', 'path', 'secure', 'comment', 'httponly'))) {
                $cdata[trim($cinfo[0])] = $cinfo[1];
            } else {
                $cdata['name']  = trim($cinfo[0]);
                $cdata['value'] = trim($cinfo[1]);
            }
            $cdata['data'] = $data;
        }
        $this->cookies[$cdata['name']] = $cdata;
        return $this->cookies;
    }

    /* HTTP Helpers */
    /**
     * @param $ua
     */
    public function set_user_agent($ua)
    {
        $this->default_headers['User-Agent'] = $ua;
    }

    /**
     * @param $key
     * @param $value
     */
    public function add_header($key, $value)
    {
        $this->default_headers[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get_cookie($key)
    {
        return $this->cookies[$key]['value'];
    }

    /**
     * @return mixed
     */
    public function get_cookies()
    {
        return $this->cookies;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set_cookie($key, $value)
    {
        $this->cookies[$key] = $value;
    }

    /**
     * Sets the cookie list to be sent to bugzilla in subsequent requests.
     *
     * @param array $cookieList
     */
    public function setCookies($cookieList)
    {
        $this->cookies = $cookieList;
    }

    /**
     * Returns an array of cookies used by bugzilla.
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Converts the cookie array into a proper HTTP header.
     *
     * @return string
     */
    private function cookiesToHeader()
    {
        // Convert the cookie array into a cookie header.
        $header = false;
        if (is_array($this->cookies)) {
            $header = 'Cookie: $Version=0; ';
            foreach ($this->cookies as $cookie) {
                $header .= $cookie['name'] . '=' . $cookie['value'] . '; ';
                $header .= '$Path=' . $cookie['path'] . '; ';
            }
        }
        return $header;
    }

    /**
     * @param $cookieHeaders
     */
    private function saveCookies($cookieHeaders)
    {
        foreach ($cookieHeaders as $cookie) {
            // Get rid of Set-cookie.
            $cookie      = str_replace('Set-Cookie: ', '', $cookie);
            $cookieParts = explode(";", $cookie);
            // first one should be the cookie name and value.
            $cookieParams['name']  = substr($cookieParts[0], 0, strpos($cookieParts[0], '='));
            $cookieParams['value'] = substr($cookieParts[0], strpos($cookieParts[0], '=') + 1);

            foreach ($cookieParts as $piece) {
                $keyval = explode('=', $piece);
                switch (trim($keyval[0])) {
                    case "path":
                        $cookieParams['path'] = $keyval[1];
                        break;
                    case "expires":
                        $cookieParams['expires'] = $keyval[1];
                        break;
                }
            }
            $cookieList[] = $cookieParams;
            unset($cookieParams);
            unset($cookieParts);
            unset($piece);
            unset($keyval);
        }
        $this->cookies = $cookieList;
    }
}
