<?php

/**
 * @file: helperblooms.php
 * @created:    Tue Jan 14 2020
 * @version:    1.0.0
 * @modified:   Tuesday January 14th 2020 12:13:04 pm
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\helper
 * @author:     oppo
 * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// helperblooms::do_curl($url)
class helperblooms extends \Prefab
{
    /**
     * @var string
     */
    private $_cachepath = 'cache/';

    // const PAGECON_ONWERK_V2 = 'https://api.bloom-s.de:780/api/';


    /**
     * @var mixed
     */
    public static $apiurl;

    public static function getApiUrl()
    {
        if ( !defined( 'PAGECON_ONWERK_V2' ) ) {
            define( 'PAGECON_ONWERK_V2', self::PAGECON_ONWERK_V2 );
        }

        self::$apiurl = 'https://api.bloom-s.de:780/api/';
        return self::$apiurl;
    }

    /**
     * @return mixed
     */
    public static function serviceAvailable()
    {
        $http     = new Bugzilla();
        $vidpovid = $http->url_valid( 'ping' );

        if ( $vidpovid == false ) {
            // $serverIP = $_SERVER['SERVER_ADDR'];
            $serverIP = $_SERVER['REMOTE_ADDR'];
            if ( $serverIP != '127.0.0.1' ) {
                self::Error( 'Service no Available' );
            }
        }
        return $vidpovid;
    }

    /**
     * @param  $url
     * @param  $toarray
     * @return mixed
     */
    /**
     * @param  $url
     * @param  $requestType
     * @param  $body
     * @param  $postvars
     * @return mixed
     */
    public static function parseXmlServer( $url, $toarray = false )
    {
        $json = false;
        // $fileContents = self::do_curl($url);
        $fileContents = self::doCall( $url );
        if ( $fileContents ) {
            // $simpleXml    = new SimpleXMLElement($simpleXml, null, false);
            if ( $toarray ) {
                $fileContents = str_replace( array( "\n", "\r", "\t" ), '', $fileContents );
                $fileContents = trim( str_replace( '"', "'", $fileContents ) );
                $fileContents = str_replace( PHP_EOL, '', $fileContents );
                $fileContents = str_replace( '&#xD;', "\n", $fileContents );
                $simpleXml    = simplexml_load_string( $fileContents, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS );
                // to do
                // $simpleXml = self::toArray($simpleXml);

                //$simpleXml = TypeConverter::xmlToArray($simpleXml, TypeConverter::XML_MERGE);
                //  https://github.com/milesj/type-converter
                // XML_NONE  - Disregard XML attributes and only return the value.
                // XML_MERGE - Merge attributes and the value into a single dimension; the values key will be "value".
                // XML_GROUP - Group the attributes into a key of "attributes" and the value into a key of "value".
                // XML_OVERWRITE - Attributes will only be returned.
                $json = json_encode( $simpleXml, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT );
            } else {
                $fileContents = str_replace( PHP_EOL, '', $fileContents );
                $fileContents = str_replace( '&#xD;', "\n", $fileContents );
                $simpleXml    = simplexml_load_string( $fileContents, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS );
                $json         = $simpleXml;
            }
        }

        return $json;
    }

    /**
     * @param  $url
     * @param  $requestType
     * @param  $body
     * @param  $postvars
     * @return mixed
     */
    public function sendRequest( $url, $requestType = 'GET', $body = '', $postvars = '' )
    {
        $header = 'Accept: application/json';

        if ( $postvars ) {
            $header .= "\r\nContent-type: application/x-www-form-urlencoded";
        } else {
            $header .= "\r\nContent-Type: text/html; charset=utf-8";
        }

        if ( !empty( $postvars ) ) {
            // Process them.
            $body = http_build_query( $postvars );
        }
        //'header'=>    array( "Cookie: foo="bar"l ),
        $context = stream_context_create( array(
            'http' => array(
                'method'  => $requestType,
                'header'  => $header,
                //'body' => $body,
                'content' => $body
            )
        ) );

        $response = file_get_contents( $url, false, $context );

        if ( $http_response_header ) {
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

    /**
     * @param $path
     * @param $post
     * @param $cookie
     * @param $cookiejar
     * @param $referer
     * @param $connecttimeout
     * @param $timeout
     */
    public static function doCall( $path, $post = '', $cookie = '', $cookiejar = '', $referer = '', $connecttimeout = 3, $timeout = 30 )
    {
        // try to get remote file data using cURL
        $crs = curl_init();
        curl_setopt( $crs, CURLOPT_URL, $path );
        curl_setopt( $crs, CURLOPT_BINARYTRANSFER, true );
        curl_setopt( $crs, CURLOPT_FAILONERROR, true );

        curl_setopt( $crs, CURLOPT_CONNECTTIMEOUT, $connecttimeout );
        curl_setopt( $crs, CURLOPT_TIMEOUT, $timeout );

        curl_setopt( $crs, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36' );

        if ( $referer ) {
            curl_setopt( $crs, CURLOPT_REFERER, $referer );
        } else {
            curl_setopt( $crs, CURLOPT_AUTOREFERER, 1 );
        }

        if ( $post ) {
            curl_setopt( $crs, CURLOPT_POST, 1 );
            curl_setopt( $crs, CURLOPT_POSTFIELDS, $post );
        }

        if ( $cookie ) {
            curl_setopt( $crs, CURLOPT_COOKIE, $cookie );
        }
        if ( $cookiejar ) {
            $cookiepath = getcwd().'./'.$cookiejar;
            curl_setopt( $crs, CURLOPT_COOKIEJAR, $cookiepath );
            curl_setopt( $crs, CURLOPT_COOKIEFILE, $cookiepath );
        }

        // Дозволити доступ до переходів
        if ( ini_get( 'open_basedir' ) == '' && !ini_get( 'safe_mode' ) ) {
            curl_setopt( $crs, CURLOPT_FOLLOWLOCATION, true );
        }

        curl_setopt( $crs, CURLOPT_HEADER, 0 );
        curl_setopt( $crs, CURLOPT_RETURNTRANSFER, true );
        // curl_setopt($crs, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8","Content-Length:".strlen($post)));

        curl_setopt( $crs, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $crs, CURLOPT_SSL_VERIFYHOST, false );

        curl_setopt( $crs, CURLOPT_FAILONERROR, true ); // Required for HTTP error codes to be reported via our call to curl_error($ch)

        $response     = curl_exec( $crs );
        $curl_error   = false;
        $responseCode = curl_getinfo( $crs, CURLINFO_HTTP_CODE );
        if ( $response === false ) {
            $curl_error = curl_error( $crs );
            curl_close( $crs );
            return array( null, $responseCode, $curl_error );
        }
        // $headerSize = curl_getinfo($crs, CURLINFO_HEADER_SIZE);
        $responseBody = $response;

        curl_close( $crs );
        return array( $responseBody, $responseCode, $curl_error );
    }

    /**
     * Helper public static function for retrieving data from url
     * @param  $url
     * @param  $connecttimeout
     * @return mixed
     */
    public static function do_curl( $url, $connecttimeout = 5 )
    {
        if ( function_exists( 'curl_init' ) ) {
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $connecttimeout );

            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
            $content = curl_exec( $ch );
            curl_close( $ch );
            return $content;
        } else {
            return file_get_contents( $url );
        }
    }

    /**
     * @param $message
     * @param $file
     * @param $class
     * @param $function
     * @param $line
     */
    public static function Error( $message, $file = __FILE__, $class = __CLASS__, $function = __FUNCTION__, $line = __LINE__ )
    {
        $array_data = array( $message, $file, $class, $function, $line );
        self::write( 'ERROR', $array_data );
    }

    /**
     * @param $error_type
     * @param $array_data
     */
    private static function write( $error_type, $array_data )
    {
        $date     = date( 'Y-m-d H:i:s' );
        $dateFile = date( 'Y-m-d' );

        $message = "[{$date}] [{$error_type}] [{$array_data[1]}->{$array_data[2]}::{$array_data[3]}:{$array_data[4]}] $array_data[0]".PHP_EOL;

        try {
            // file_put_contents($this->path.'/'.$dateFile.'.log', $message, FILE_APPEND);

            $f3     = Base::instance();
            $logger = new \Log( $f3->get( 'LOGS' ).'helper_error.log' );
            $logger->write( 'Error :'.$message.'' );
        } catch ( Exception $e ) {
            // error_log('[ERROR] [Log::write()] -> ' . $e, 0);
            // header("HTTP/1.0 500 Internal Server Error");
            // exit();
        }
    }

    /**
     * @param $filename
     */
    public static function umlautName( $filename, $strtolower = false )
    {
        $search  = array( '/ß/', '/ä/', '/Ä/', '/ö/', '/Ö/', '/ü/', '/Ü/', '([^[:alnum:]._])' );
        $replace = array( 'ss', 'ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', '_' );

        if ( $strtolower ) {
            return strtolower( preg_replace( $search, $replace, $filename ) );
        }

        return trim( preg_replace( $search, $replace, $filename ) );
    }

    /**
     *  Перетворити XML в масив
     * @param  $xml
     * @return mixed
     */
    public static function xmlToArray( $xml, $no_unicode = false )
    {
        // $json = json_encode($simpleXml, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
        if ( $no_unicode ) {
            $array_data = json_decode( json_encode( simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        } else {
            $array_data = json_decode( json_encode( simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ), JSON_UNESCAPED_UNICODE ), true );
        }

        return $array_data;
    }

    /**
     * Перетворити масив у XML
     * @param  $arr
     * @return mixed
     */
    public static function arrayToXml( $arr )
    {
        $xml = '<xml>';
        foreach ( $arr as $key => $val ) {
            if ( is_numeric( $val ) ) {
                $xml .= '<'.$key.'>'.$val.'</'.$key.'>';
            } else {
                $xml .= '<'.$key.'><![CDATA['.$val.']]></'.$key.'>';
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * Converts an XML node to an array
     *
     *
     *
     * @note Does not handle namespaces
     * @param  \SimpleXMLElement $node XML node
     * @return array
     */
    public static function toArray( \SimpleXMLElement $node )
    {
        $output = array();

        $attributes = array();
        foreach ( $node->attributes() as $key => $val ) {
            $attributes[$key] = (string) $val;
        }

        $text = trim( (string) $node );

        if ( !empty( $attributes ) ) {
            $output['@attributes'] = $attributes;
        }

        if ( !empty( $text ) ) {
            $output['_'] = $text;
        }

        $children = array();
        foreach ( $node->children() as $childName => $childNode ) {
            $children[$childName][] = self::toArray( $childNode );
        }

        foreach ( $children as $childName => $childList ) {
            if ( count( $childList ) === 1 ) {
                $output[$childName] = $childList[0];
            } else {
                $output[$childName] = $childList;
            }
        }

        return $output;
    }

    /**
     * @param  \SimpleXMLElement $node
     * @return mixed
     */
    public static function toObject( \SimpleXMLElement $node )
    {
        $obj = new \stdClass();
        self::toObject_R( $obj, self::toArray( $node ) );

        return $obj;
    }

    /**
     * @param  $obj
     * @param  $item
     * @return null
     */
    private static function toObject_R( $obj, $item )
    {
        if ( !is_array( $item ) ) {
            return;
        }

        foreach ( $item as $key => $val ) {
            if ( is_array( $val ) ) {
                if ( array_keys( $val ) === range( 0, count( $val ) - 1 ) ) {
                    $q = array();
                    foreach ( $val as $i => $v ) {
                        $q[$i] = new \stdClass();
                        self::toObject_R( $q[$i], $v );
                    }
                    $obj->$key = $q;
                } else {
                    $obj->$key = new \stdClass();
                    self::toObject_R( $obj->$key, $val );
                }
            } else {
                $obj->$key = $val;
            }
        }
    }

    /**
     * Recursive directory creation based on full path.
     *
     * Will attempt to set permissions on folders.
     *
     *
     * @since 2.0.1
     *
     * @param  string $target Full path to attempt to create.
     * @return bool   Whether the path was created. True if path already exists.
     */
    public static function op_mkdir( $target )
    {
        $wrapper = null;

        // Strip the protocol.
        if ( self::op_is_stream( $target ) ) {
            list( $wrapper, $target ) = explode( '://', $target, 2 );
        }

        // From php.net/mkdir user contributed notes.
        $target = str_replace( '//', '/', $target );

        // Put the wrapper back on the target.
        if ( $wrapper !== null ) {
            $target = $wrapper.'://'.$target;
        }

        /*
         * Safe mode fails with a trailing slash under certain PHP versions.
         * Use rtrim() instead of untrailingslashit to avoid formatting.php dependency.
         */
        $target = rtrim( $target, '/' );
        if ( empty( $target ) ) {
            $target = '/';
        }

        if ( file_exists( $target ) ) {
            return @is_dir( $target );
        }

        // Do not allow path traversals.
        if ( false !== strpos( $target, '../' ) || false !== strpos( $target, '..'.DIRECTORY_SEPARATOR ) ) {
            return false;
        }

        // We need to find the permissions of the parent folder that exists and inherit that.
        $target_parent = dirname( $target );
        while ( '.' != $target_parent && !is_dir( $target_parent ) && dirname( $target_parent ) !== $target_parent ) {
            $target_parent = dirname( $target_parent );
        }

        // Get the permission bits.
        if ( $stat = @stat( $target_parent ) ) {
            $dir_perms = $stat['mode'] & 0007777;
        } else {
            $dir_perms = 0777;
        }

        if ( @mkdir( $target, $dir_perms, true ) ) {
            /*
             * If a umask is set that modifies $dir_perms, we'll have to re-set
             * the $dir_perms correctly with chmod()
             */
            if ( $dir_perms != ( $dir_perms & ~umask() ) ) {
                $folder_parts = explode( '/', substr( $target, strlen( $target_parent ) + 1 ) );
                for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i++ ) {
                    @chmod( $target_parent.'/'.implode( '/', array_slice( $folder_parts, 0, $i ) ), $dir_perms );
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Test if a given path is a stream URL
     *
     *
     * @since 3.5.0
     *
     * @param  string $path The resource path or URL.
     * @return bool   True if the path is a stream URL.
     */
    public static function op_is_stream( $path )
    {
        $scheme_separator = strpos( $path, '://' );

        if ( false === $scheme_separator ) {
            // $path isn't a stream
            return false;
        }

        $stream = substr( $path, 0, $scheme_separator );

        return in_array( $stream, stream_get_wrappers(), true );
    }

    /**
     * Unserialize value only if it was serialized.
     *
     *
     * @since 2.0.0
     *
     * @param  string $original    Maybe unserialized original, if is needed.
     * @return mixed  Unserialized data can be any type.
     */
    public static function maybe_unserialize( $original )
    {
        if ( self::is_serialized( $original ) ) {
            // don't attempt to unserialize data that wasn't serialized going in
            return @unserialize( $original );
        }
        return $original;
    }

    /**
     * Check value to find if it was serialized.
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     *
     * @since 2.0.5
     *
     * @param  string $data   Value to check to see if was serialized.
     * @param  bool   $strict Optional. Whether to be strict about the end of the string. Default true.
     * @return bool   False if not serialized and true if it was.
     */
    public static function is_serialized( $data, $strict = true )
    {
        // if it isn't a string, it isn't serialized.
        if ( !is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' == $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace ) {
                return false;
            }
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 ) {
                return false;
            }
            if ( false !== $brace && $brace < 4 ) {
                return false;
            }
        }
        $token = $data[0];
        switch ( $token ) {
            case 's':
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
            // or else fall through
            case 'a':
            case 'O':
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
        }
        return false;
    }

    /**
     * Check whether serialized data is of string type.
     *
     *
     * @since 2.0.5
     *
     * @param  string $data Serialized data.
     * @return bool   False if not a serialized string, true if it is.
     */
    public static function is_serialized_string( $data )
    {
        // if it isn't a string, it isn't a serialized string.
        if ( !is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( strlen( $data ) < 4 ) {
            return false;
        } elseif ( ':' !== $data[1] ) {
            return false;
        } elseif ( ';' !== substr( $data, -1 ) ) {
            return false;
        } elseif ( $data[0] !== 's' ) {
            return false;
        } elseif ( '"' !== substr( $data, -2, 1 ) ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Serialize data, if needed.
     *
     *
     * @since 2.0.5
     *
     * @param  string|array|object $data Data that might be serialized.
     * @return mixed               A scalar data
     */
    public static function maybe_serialize( $data )
    {
        if ( is_array( $data ) || is_object( $data ) ) {
            return serialize( $data );
        }

        // Double serialization is required for backward compatibility.
        // See https://core.trac.wordpress.org/ticket/12930
        // Also the world will end. See WP 3.6.1.
        if ( self::is_serialized( $data, false ) ) {
            return serialize( $data );
        }

        return $data;
    }

    /**
     * @param  $path
     * @return mixed
     */
    public static function op_normalize_path( $path )
    {
        $wrapper = '';
        if ( false ) {
            list( $wrapper, $path ) = explode( '://', $path, 2 );
            $wrapper .= '://';
        }

        // Standardise all paths to use /
        $path = str_replace( '\\', '/', $path );

        // Replace multiple slashes down to a singular, allowing for network shares having two slashes.
        $path = preg_replace( '|(?<=.)/+|', '/', $path );

        // Windows paths should uppercase the drive letter
        if ( ':' === substr( $path, 1, 1 ) ) {
            $path = ucfirst( $path );
        }

        return $wrapper.$path;
    }

    /**
     * Helper public static function to validate filenames
     * @param $filename
     */
    public static function safe_filename( $filename )
    {
        $search  = array( '/ß/', '/ä/', '/Ä/', '/ö/', '/Ö/', '/ü/', '/Ü/', '([^[:alnum:]._])' );
        $replace = array( 'ss', 'ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', '_' );

        $filename = preg_replace( $search, $replace, $filename );
        //\.\_
        return preg_replace( '/[^0-9a-z\-]/i', '', strtolower( $filename ) );
    }

    // https://fatfreeframework.com/3.6/preview#filter
    // \Preview::instance()->filter('badwords','\Helper::instance()->badwords');
    // Now you can use {{ @user_comment | badwords }} to filter the user_comment var for badwords. It's also possible to combine multiple filter: {{ @user_comment | badwords, raw }}.

    // When the function is called without any parameter, it just returns an array of all registered filter names. When the function is called with a $key but without a $func parameter, it returns the registered function string.

    /**
     * @param $val
     */
    /**
     * @param  $arr1
     * @param  $arr2
     * @return mixed
     */
    public static function badwords( $val )
    {
        $bad_words         = array( 'badword', 'jerk', 'damn' );
        $replacement_words = array( '@#$@#', 'j&*%', 'da*@' );
        return str_ireplace( $bad_words, $replacement_words, $val );
    }

    /**
     * Recursivaly merge two arrays.
     * @param  $arr1
     * @param  $arr2
     * @return mixed
     */
    public static function merge( $arr1, $arr2 = null )
    {
        $args = func_get_args();
        $r    = (array) current( $args );
        while (  ( $arg = next( $args ) ) !== false ) {
            foreach ( (array) $arg as $key => $val ) {
                if ( is_array( $val ) && isset( $r[$key] ) && is_array( $r[$key] ) ) {
                    $r[$key] = self::merge( $r[$key], $val );
                } elseif ( is_int( $key ) ) {
                    $r[] = $val;
                } else {
                    $r[$key] = $val;
                }
            }
        }
        return $r;
    }

    /**
     * Make a random UUID
     *
     * @return void
     */
    public static function makeUUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff )
        );
    }

    /**
     * If $value is a JSON encoded object or array it will be decoded
     * and returned.
     * If $value is not JSON format, then it will be returned unmodified.
     * https://www.php.net/manual/ru/json.constants.php
     */
    public static function jsJson( $value, $arr = true )
    {
        if ( !is_string( $value ) ) {
            return false;
        }
        if ( strlen( $value ) < 2 ) {
            return false;
        }
        if ( '{' != $value[0] && '[' != $value[0] ) {
            return false;
        }
        $json_data = json_decode( $value, $arr );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return false;
        }
        return $json_data;
    }

    /**
     *
     * @param  string  $content
     * @return array
     */
    public static function parseXmlToArraysalons( $items, $key = 'Id' )
    {
        $result = [];
        foreach ( $items as $item ) {
            $salonId          = (string) $item[$key];
            $result[$salonId] = (array) $item;
        }
        return $result;
    }

    /**
     * Undocumented function
     *
     * @param  [type] $title
     * @return void
     */
    public static function human_urlencode( $title )
    {
        //create slug (look up WP's way of doing this)

        $title = strip_tags( $title );
        $title = mb_strtolower( $title, 'UTF-8' );
        $title = preg_replace( '/&.+?;_/', ' ', $title );
        $title = preg_replace( '/[^%a-z0-9 -]/', '', $title );
        $title = preg_replace( '/\s+/', '-', $title );
        $title = preg_replace( '|-+|', '-', $title );
        $title = str_replace( '%', 'perc', $title );
        $title = trim( $title, '-' );

        return $title;
    }

    /**
     * @param $str
     */
    public static function fix_white_spaces( $str )
    {
        return preg_replace( '/\s\s+/', ' ', preg_replace( '/\s\"/', ' "', preg_replace( '/\s\'/', ' \'', $str ) ) );
    }

    /**
     * Format number following the component configuration.
     *
     * @param  float|int $price
     * @return unknown
     */
    public static function formaNumber( $number = 0, $decimals = 2, $currency_symbol = '&euro;' )
    {
        $number              = (float) $number;
        $decimal_separator   = ',';
        $thousands_separator = ' ';
        $decimals            = (int) $number == $number ? 0 : $decimals;
        return number_format( $number, $decimals, $decimal_separator, $thousands_separator ).' <span class="currency-symbol">'.$currency_symbol.'</span>';
    }

    /**
     * Check and return an array key
     *
     * @change  2.0.2
     *
     * @since   2.0.2
     *
     * @param  array  $array Array with values.
     * @param  string $key   Name of the key.
     * @return mixed  Value of the requested key.
     */
    public static function get_key( $array, $key )
    {
        if ( empty( $array ) || empty( $key ) || !isset( $array[$key] ) ) {
            return null;
        }

        return $array[$key];
    }

    /**
     * Валідація глобальних змінних $_COOKIE[]
     *
     * @param  string $name (строка $name)
     * @return string $name (строка $name)
     */
    public static function inCOOKIE( $name, $return = null )
    {
        if ( !filter_has_var( INPUT_COOKIE, $name ) ) {
            return $return;
        }
        if ( filter_input( INPUT_COOKIE, $name ) == true ) {
            return isset( $_COOKIE[$name] ) ? $_COOKIE[$name] : $return;
        }
    }

    /**
     * Return the current users IP addr.
     */
    public static function getIP( $ret = null )
    {
        if ( filter_has_var( INPUT_SERVER, 'REMOTE_ADDR' ) ) {
            return $_SERVER['REMOTE_ADDR'];
        }
        // If 'REMOTE_ADDR' exists, the function will return before this last return.
        //"not detected"
        return $ret;
    }

    /**
     * Валідація глобальних змінних $_POST[]
     *
     * @test $name = helperblooms::inPOST($name);
     *
     * @param  array|string $name (масив або рядок $name)
     * @return array|string $name (масив або рядок $name)
     */
    public static function inPOST( $name, $return = null )
    {
        if ( !filter_has_var( INPUT_POST, $name ) ) {
            return $return;
        }

        if ( filter_input( INPUT_POST, $name, FILTER_DEFAULT, FILTER_FORCE_ARRAY ) == true ) {
            return isset( $_POST[$name] ) ? $_POST[$name] : $return;
        }
    }

    /**
     * Валідація глобальних змінних $_GET[]
     *
     * @test $name = helperblooms::inGET($name);
     * @param  array|string $name (масив або рядок $name)
     * @return array|string $name (масив або рядок $name)
     */
    public static function inGET( $name, $return = null )
    {
        if ( !filter_has_var( INPUT_GET, $name ) ) {
            return $return;
        }
        if ( filter_input( INPUT_GET, $name, FILTER_DEFAULT, FILTER_FORCE_ARRAY ) == true ) {
            return isset( $_GET[$name] ) ? $_GET[$name] : $return;
        }
    }

    /**
     * Валідація глобальних змінних $_SERVER[]
     *
     * @param  string $name (строка $name)
     * @return string $name (строка $name)
     */
    public static function inSERVER( $name, $return = null )
    {
        if ( filter_input( INPUT_SERVER, $name, FILTER_DEFAULT, FILTER_FORCE_ARRAY ) == true ) {
            return isset( $_SERVER[$name] ) ? $_SERVER[$name] : $return;
        }
    }

    /**
     * Get a session variable.
     *
     * @param  String  $key
     * @param  mixed   $filter
     * @param  bool    $fillWithEmptyString
     * @return mixed
     */
    public static function inSESSION( $key = null, $filter = null, $fillWithEmptyString = false )
    {
        if ( !isset( $_SESSION ) ) {
            return null;
        }

        if ( !$key ) {
            if ( function_exists( 'filter_var_array' ) ) {
                return $filter ? filter_var_array( $_SESSION, $filter ) : $_SESSION;
            } else {
                return $_SESSION;
            }
        }
        if ( isset( $_SESSION[$key] ) ) {
            if ( function_exists( 'filter_var' ) ) {
                return $filter ? filter_var( $_SESSION[$key], $filter ) : $_SESSION[$key];
            } else {
                return $_SESSION[$key];
            }
        } elseif ( true === $fillWithEmptyString ) {
            return '';
        }
        return null;
    }

    /**
     * @param $str
     * @return mixed
     */
    public static function fixBadUnicodeForJson( $str )
    {
        $str = preg_replace_callback(
            '/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/',
            function ( $matches ) {
                return chr( hexdec( "$1" ) ).chr( hexdec( "$2" ) ).chr( hexdec( "$3" ) ).chr( hexdec( "$4" ) );
            },
            $str
        );
        $str = preg_replace_callback(
            '/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/',
            function ( $matches ) {
                return chr( hexdec( "$1" ) ).chr( hexdec( "$2" ) ).chr( hexdec( "$3" ) );
            },
            $str
        );
        $str = preg_replace_callback(
            '/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/',
            function ( $matches ) {
                return chr( hexdec( "$1" ) ).chr( hexdec( "$2" ) );
            },
            $str
        );
        $str = preg_replace_callback(
            '/\\\\u00([0-9a-f]{2})/',
            function ( $matches ) {
                return chr( hexdec( "$1" ) );
            },
            $str
        );
        return $str;
    }

    /**
     *  htmlString The original string with html entities encoded
     *  Декодировать уже закодированный Юникод в обычный текстовый код
     *
     *  Когда использовать:
     * Часть кодированного контента, не может быть найдена
         * Если вы хотите, чтобы содержание поиска, пожалуйста, расшифровать существование поля «, посвященный поиску"
     *
     *  example:
     *      "&#36935; &#21040;" to "Encounter"
     *
     *  @param unicode string
     *  @return text string
     */
    public static function unicode2Text( $unicode )
    {
        return preg_replace_callback(
            '~(&#[0-9a-f]+;)~i',
            function ( $matches ) {
                return mb_convert_encoding( $matches[0], 'UTF-8', 'HTML-ENTITIES' );
            },
            $unicode
        );
    }

    /**
     *  Text into unicode
     */
    public static function text2Unicode( $text )
    {
        return mb_convert_encoding( $text, 'HTML-ENTITIES', 'UTF-8' );
    }

    /**
     * if the session has been started yet, start it
     *
     * @return void
     */
    public static function calc_start_session()
    {
        if ( version_compare( phpversion(), '5.4.0', '>=' ) ) {
            if ( session_status() == PHP_SESSION_NONE ) {
                session_start();
            }
        } else {
            if ( !session_id() ) {
                session_start();
            }
        }
    }

    /**
     * Close the session object for new updates
     *
     * @return void
     */
    public static function calc_close_session()
    {
        if ( version_compare( phpversion(), '5.4.0', '>=' ) ) {
            if ( session_status() == PHP_SESSION_ACTIVE ) {
                session_write_close();
            }
        } else {
            if ( session_id() ) {
                session_write_close();
            }
        }
    }

    /**
     * @param  $array
     * @param  $keys
     * @return mixed
     */
    public static function array_change_keys( $array, $keys )
    {
        foreach ( array_keys( $array ) as $key ) {
            if ( array_key_exists( $key, $keys ) ) {
                $array = self::array_change_key( $array, $key, $keys[$key] );
            }
        }

        return $array;
    }

    /**
     * @param  $array
     * @param  $old_key
     * @param  $new_key
     * @return mixed
     */
    public static function array_change_key( $array, $old_key, $new_key )
    {
        if ( array_key_exists( $old_key, $array ) ) {
            $array[$new_key] = $array[$old_key];
            unset( $array[$old_key] );
        }
        return $array;
    }

    /**
     * @param  $query
     * @return mixed
     */
    public static function parse_query( $query )
    {
        $array = [];
        parse_str( $query, $array );
        return $array;
    }

    /**
     * @param $query
     */
    public static function build_query( $query )
    {
        return http_build_query( $query );
    }

    /**
     * @param $url
     */
    public static function parse_url( $url )
    {
        return parse_url( $url );
    }

    /**
     * @param $uri
     */
    public static function ga_get_domain( $uri )
    {
        $hostPattern     = '/^(http:\/\/)?([^\/]+)/i';
        $domainPatternUS = '/[^\.\/]+\.[^\.\/]+$/';
        $domainPatternUK = '/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/';

        preg_match( $hostPattern, $uri, $matches );
        $host = $matches[2];
        if ( preg_match( '/.*\..*\..*\..*$/', $host ) ) {
            preg_match( $domainPatternUK, $host, $matches );
        } else {
            preg_match( $domainPatternUS, $host, $matches );
        }

        return ['domain' => $matches[0], 'host' => $host];
    }

    /**
     * @param $text
     */
    public static function trimText( $text )
    {
        $clear = strip_tags( $text );
        $clear = html_entity_decode( $clear );
        $clear = urldecode( $clear );
        $clear = preg_replace( '/[^A-Za-z0-9]/', ' ', $clear );
        $clear = preg_replace( '/ +/', ' ', $clear );
        $clear = trim( $clear );
        $clear = str_replace( ' ', '-', $clear );
        return strtolower( $clear );
    }

    /**
     * @param  $file
     * @return mixed
     */
    public static function getExtension( $file )
    {
        $finfo = finfo_open( FILEINFO_MIME_TYPE );
        $ext   = finfo_file( $finfo, $file['tmp_name'] );
        finfo_close( $finfo );
        return $ext;
    }

    /**
     * Returns the real mime type of an image file.
     *
     * This depends on exif_imagetype() or getimagesize() to determine real mime types.
     *
     *
     * @since 4.7.1
     *
     * @param  string       $file Full path to the file.
     * @return string|false The actual mime type or false if the type cannot be determined.
     */
    public static function get_image_mime( $file )
    {
        /*
         * Use exif_imagetype() to check the mimetype if available or fall back to
         * getimagesize() if exif isn't avaialbe. If either function throws an Exception
         * we assume the file could not be validated.
         */
        try {
            if ( is_callable( 'exif_imagetype' ) ) {
                $imagetype = exif_imagetype( $file );
                $mime      = ( $imagetype ) ? image_type_to_mime_type( $imagetype ) : false;
            } elseif ( function_exists( 'getimagesize' ) ) {
                $imagesize = getimagesize( $file );
                $mime      = ( isset( $imagesize['mime'] ) ) ? $imagesize['mime'] : false;
            } else {
                $mime = false;
            }
        } catch ( \Exception $e ) {
            $mime = false;
        }
        return $mime;
    }

    /**
     * @param $value
     */
    public static function uml( $value = '' )
    {
        if ( !$value ) {
            return '';
        }
        return mb_check_encoding( $value, 'UTF-8' ) ? utf8_decode( $value ) : trim( $value );
    }

    /**
     * Coverts text to iso-8859-15 encoding.
     * @test SimBimBom::convertISO($value = '');
     * @param  string $text       utf-8 text
     * @return string ISO-8859-15 text
     */
    public static function convertISO( $text )
    {
        return mb_convert_encoding( $text, 'iso-8859-15', 'utf-8' );
    }

    /**
     * Encrypt and decrypt
     * @TEST http://qaru.site/questions/31768/php-aes-encrypt-decrypt
     * @TEST https://github.com/blocktrail/cryptojs-aes-php
     * @TEST https://hotexamples.com/examples/-/-/openssl_decrypt/php-openssl_decrypt-function-examples.html
     *
     *
     *
     * @author Nazmul Ahsan <n.mukto@gmail.com>
     *
     * @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
     *
     * @param string $string string to be encrypted/decrypted
     * @param string $action what to do with this? e for encrypt, d for decrypt
     */
    public static function _crypt( $string, $action = 'e' )
    {
        // you may change these values to your own
        $secret_key = 'UA_secret_key';
        $secret_iv  = 'PARASOLYA_secret_iv';

        $output         = false;
        $encrypt_method = 'AES-256-CBC';
        $key            = hash( 'sha256', $secret_key );
        $iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if ( 'e' == $action ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        } elseif ( 'd' == $action ) {
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }

        return $output;
    }

    /**
     * datetime locale
     * https://htmlweb.ru/php/php_date.php
     * @param  mixed     $d
     * @param  string    $format
     * @return string
     */
    public static function datetime( $d, $format = '%B %e, %Y %H:%M' )
    {
        if ( $d instanceof \DateTime ) {
            $d = $d->getTimestamp();
        }

        return strftime( $format, $d );
    }

    /**
     * datelo funcion (date with locale)
     * Credits: Sergio Abreu
     * http://sites.sitesbr.net
     * NOTE: Depend on availability of the locale in server.
     *
     * @test https://www.php.net/manual/ru/function.strftime.php
     * en_US
     */

    public static function datelo( $str, $locale = 'de_DE', $time = null )
    {
        if ( $time === null ) {
            $time = time();
        }
        //&& !preg_match('/[nz]/', $str)
        if ( preg_match( '/[DlFM]/', $str ) ) {
            setlocale( LC_ALL, $locale );
            $dict      = array();
            $dict['d'] = '%d';
            $dict['D'] = '%a';
            $dict['j'] = '%e';
            $dict['l'] = '%A';
            $dict['N'] = '%u';
            $dict['w'] = '%w';
            $dict['F'] = '%B';
            $dict['m'] = '%m';
            $dict['M'] = '%b';
            $dict['Y'] = '%G';
            $dict['g'] = '%l';
            $dict['G'] = '%k';
            $dict['h'] = '%I';
            $dict['H'] = '%H';
            $dict['i'] = '%M';
            $dict['s'] = '%S';
            $dict['S'] = ' '; //removes English sufixes th rd etc.
            $dict[' '] = ' ';
            $dict['-'] = '-';
            $dict['/'] = '/';
            $dict[':'] = ':';
            $dict[','] = ',';

            $chars = preg_split( '//', $str );
            $nstr  = '';

            foreach ( $chars as $c ) {
                if ( $c ) {
                    //skip empties
                    $nstr .= $dict[$c];
                }
            }

            return strftime( $nstr );
        } else {
            // not localized

            return date( $str, $time );
        }
    }

    /**
     *  helperblooms::recursivelyRemoveDirectory
     * @param $source
     * @param $removeOnlyChildren
     * @url https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
     */
    public static function recursivelyRemoveDirectory( $source, $excludes = array( 'emp_image.jpg' ), $removeOnlyChildren = true )
    {
        if ( empty( $source ) || file_exists( $source ) === false ) {
            throw new Exception( "File does not exist: '$source'" );
        }

        if ( is_file( $source ) || is_link( $source ) ) {
            if ( false === unlink( $source ) ) {
                throw new Exception( "Cannot delete file '$source'" );
            }
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator( $source, RecursiveDirectoryIterator::SKIP_DOTS ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ( $files as $fileInfo ) {
            /*
             * @var SplFileInfo $fileInfo
             */
            if ( $fileInfo->isDir() ) {
                if ( !in_array( $fileInfo->getFilename(), $excludes ) ) {
                    if ( self::recursivelyRemoveDirectory( $fileInfo->getRealPath() ) === false ) {
                        throw new Exception( "Failed to remove directory '{$fileInfo->getRealPath()}'" );
                    }
                    if ( false === rmdir( $fileInfo->getRealPath() ) ) {
                        throw new Exception( "Failed to remove empty directory '{$fileInfo->getRealPath()}'" );
                    }
                }
            } else {
                if ( !in_array( $fileInfo->getFilename(), $excludes ) ) {
                    if ( unlink( $fileInfo->getRealPath() ) === false ) {
                        throw new Exception( "Failed to remove file '{$fileInfo->getRealPath()}'" );
                    }
                }
            }
        }

        if ( $removeOnlyChildren === false ) {
            if ( false === rmdir( $source ) ) {
                throw new Exception( "Cannot remove directory '$source'" );
            }
        }
    }

    /**
     * @param $dirname
     * @url https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
     */
    public static function rmdir_recursive( $dirname )
    {

        /*
         * FilesystemIterator and SKIP_DOTS
         */

        if ( class_exists( 'FilesystemIterator' ) && defined( 'FilesystemIterator::SKIP_DOTS' ) ) {

            if ( !is_dir( $dirname ) ) {
                return false;
            }

            foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dirname, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST ) as $path ) {
                $path->isDir() ? rmdir( $path->getPathname() ) : unlink( $path->getRealPath() );
            }

            return rmdir( $dirname );
        }

        /*
         * RecursiveDirectoryIterator and SKIP_DOTS
         */

        if ( class_exists( 'RecursiveDirectoryIterator' ) && defined( 'RecursiveDirectoryIterator::SKIP_DOTS' ) ) {

            if ( !is_dir( $dirname ) ) {
                return false;
            }

            foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dirname, RecursiveDirectoryIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST ) as $path ) {
                $path->isDir() ? rmdir( $path->getPathname() ) : unlink( $path->getRealPath() );
            }

            return rmdir( $dirname );
        }

        /*
         * RecursiveIteratorIterator and RecursiveDirectoryIterator
         */

        if ( class_exists( 'RecursiveIteratorIterator' ) && class_exists( 'RecursiveDirectoryIterator' ) ) {

            if ( !is_dir( $dirname ) ) {
                return false;
            }

            foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dirname ), RecursiveIteratorIterator::CHILD_FIRST ) as $path ) {
                if ( in_array( $path->getFilename(), array( '.', '..' ) ) ) {
                    continue;
                }
                $path->isDir() ? rmdir( $path->getPathname() ) : unlink( $path->getRealPath() );
            }

            return rmdir( $dirname );
        }

        /*
         * Scandir Recursive
         */

        if ( !is_dir( $dirname ) ) {
            return false;
        }

        $objects = scandir( $dirname );

        foreach ( $objects as $object ) {
            if ( $object === '.' || $object === '..' ) {
                continue;
            }
            filetype( $dirname.DIRECTORY_SEPARATOR.$object ) === 'dir' ? self::rmdir_recursive( $dirname.DIRECTORY_SEPARATOR.$object ) : unlink( $dirname.DIRECTORY_SEPARATOR.$object );
        }

        reset( $objects );
        rmdir( $dirname );

        return !is_dir( $dirname );
    }

    /**
     * Zips all images in $this->$output_directory
     */
    public static function zipImages()
    {
        $zip = new ZipArchive();
        $zip->open( $this->zip_name.'-'.date( 'd-m-Y' ).'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE );

        $files = glob( $this->output_directory."*" );

        foreach ( $files as $file ) {
            $zip->addFile( $file );
        }

        $zip->close();
    }

    /**
     * Cleans the output directory by deleting all of the files inside.
     */
    public static function cleanOutputDirectory()
    {
        $files = glob( $this->output_directory.'*' );

        foreach ( $files as $file ) {
            if ( is_file( $file ) ) {
                unlink( $file );
            }
        }
    }

    /**
     * Delete all the files (specified type) in the folder
     * @param string $path - path to the folder (without a slash at the end)
     */
    public static function delete_all_files( $path, $type = "*.*" )
    {
        $files = glob( $path.'/'.$type ); // get all file names
        foreach ( $files as $file ) {
            // iterate files
            if ( is_file( $file ) ) {
                @unlink( $file ); // delete file
            }
        }
    }

    /**
     * Deleting the directory (including all files and folders in it)
     * @param string $directory - path to the directory (without a slash at the end)
     */
    public static function recursiveRemoveDirectory( $directory )
    {
        if ( is_dir( $directory ) ) {
            foreach ( glob( "{$directory}/*" ) as $file ) {
                if ( is_dir( $file ) ) {
                    self::recursiveRemoveDirectory( $file );
                } else {
                    @unlink( $file );
                }
            }
            rmdir( $directory );
        }
    }

    /**
     * @param $src
     * @param $dst
     */
    public static function recurse_copy( $src, $dst )
    {
        $dir = opendir( $src );
        @mkdir( $dst );
        while ( false !== ( $file = readdir( $dir ) ) ) {
            if (  ( $file != '.' ) && ( $file != '..' ) ) {
                if ( is_dir( $src.'/'.$file ) ) {
                    self::recurse_copy( $src.'/'.$file, $dst.'/'.$file );
                } else {
                    copy( $src.'/'.$file, $dst.'/'.$file );
                }
            }
        }
        closedir( $dir );
    }

    /**
     * @param $date
     * @param $format
     * @return mixed
     */
    public static function validateDate( $date, $format = 'Y-m-d' )
    {
        //$format = 'Y-m-d H:i:s'
        $d = DateTime::createFromFormat( $format, $date );
        return $d && $d->format( $format ) == $date;
    }

    /**
     * Returns a timestamp, first checking if value already is a timestamp.
     * @since  2.0.0
     * @param  string|int $string Possible timestamp string
     * @return int                   Time stamp
     */
    public static function make_valid_time_stamp( $string )
    {
        if ( !$string ) {
            return 0;
        }

        return $this->is_valid_time_stamp( $string )
            ? (int) $string :
        strtotime( (string) $string );
    }

    /**
     * Determine if a value is a valid timestamp
     * @since  2.0.0
     * @param  mixed  $timestamp Value to check
     * @return boolean           Whether value is a valid timestamp
     */
    public static function is_valid_time_stamp( $timestamp )
    {
        return (string) (int) $timestamp === (string) $timestamp
        && $timestamp <= PHP_INT_MAX
        && $timestamp >= ~PHP_INT_MAX;
    }

    /**
     * Checks if a value is 'empty'. Still accepts 0.
     * @since  2.0.0
     * @param  mixed $value Value to check
     * @return bool         True or false
     */
    public static function isempty( $value )
    {
        return null === $value || '' === $value || false === $value;
    }

    /**
     * Checks if a value is not 'empty'. 0 doesn't count as empty.
     * @since  2.2.2
     * @param  mixed $value Value to check
     * @return bool         True or false
     */
    public static function notempty( $value )
    {
        return null !== $value && '' !== $value && false !== $value;
    }

    /**
     * Filters out empty values (not including 0).
     * @since  2.2.2
     * @param  mixed $value Value to check
     * @return bool         True or false
     */
    public function filter_empty( $value )
    {
        return array_filter( $value, array( $this, 'notempty' ) );
    }

    /**
     * Insert a single array item inside another array at a set position
     * @since  2.0.2
     * @param  array &$array   Array to modify. Is passed by reference, and no return is needed.
     * @param  array $new      New array to insert
     * @param  int   $position Position in the main array to insert the new array
     */
    public static function array_insert( &$array, $new, $position )
    {
        $before = array_slice( $array, 0, $position - 1 );
        $after  = array_diff_key( $array, $before );
        $array  = array_merge( $before, $new, $after );
    }

    /**
     * Dump log in e_log.txt file
     * @param  string  $variable Variable to print
     * @param  boolean $clear    Clear file before priniting
     * @return null
     */
    public static function dump_log( $variable = '', $clear = false )
    {
        $file = 'e_log.txt';
        if ( !file_exists( $file ) ) {
            touch( $file );
        }

        $current = file_get_contents( $file );
        $debug   = debug_backtrace();
        if ( $clear ) {
            $current = '';
        }

        $current .= "***********************************";
        $current .= "\n* Date: ".date( 'd-m-Y - h:i:a' );
        $current .= "\n* ";
        if ( isset( $debug[0]['file'] ) ) {
            $current .= "\n* File: ".$debug[0]['file'];
        }

        if ( isset( $debug[0]['line'] ) ) {
            $current .= "\n* line: ".$debug[0]['line'];
        }

        $current .= "\n***********************************\n";

        $current .= "\n".var_export( $variable, true );
        $current .= "\n";
        $current .= "\n";
        $current .= "\n";
        file_put_contents( $file, $current );
    }

    /**
     * @param $username
     * @return mixed
     */
    public static function ns_ondomain_email( $username = 'noreply' )
    {
        $f3       = \Base::instance();
        $home_url = $f3->get( 'SCHEME' ).'://'.$f3->get( 'HOST' ).$f3->get( 'BASE' );
        // $url  = 'https://developservice.de/kunden/blooms/1plus/karriere.html';
        /**
         * @var mixed
         */
        static $ondomain_email = null;
        if ( is_null( $ondomain_email ) ) {
            $info           = parse_url( $home_url );
            $host           = $info['host'];
            $domain         = preg_replace( '/^www./', '', $host );
            $ondomain_email = $username.'@'.$domain;
        }

        return $ondomain_email;
    }

    /**
     * @param $price
     */
    public static function price_format_decimal( $price )
    {
        // $price = $price / 100;
        $price = floatval( $price );
        return number_format( $price, 2, ".", "" );
    }

    /**
     * @param $price
     * @return mixed
     */
    public static function price_database_format( $price )
    {
        $price = str_replace( ',', '.', $price );
        $price = floatval( $price );
        $price = number_format( $price, 2, '.', '' ) * 100;
        return $price;
    }


    // https://stackoverflow.com/questions/2326125/remove-multiple-whitespaces
    // echo whitespace::smart_clean($string);

    /**
     * @param $s
     * @return mixed
     */
    public static function remove_doublewhitespace( $s = null )
    {
        return $ret = preg_replace( '/([\s])\1+/', ' ', $s );
    }

    /**
     * @param $s
     * @return mixed
     */
    public static function remove_whitespace( $s = null )
    {
        return $ret = preg_replace( '/[\s]+/', '', $s );
    }

    /**
     * @param $s
     * @return mixed
     */
    public static function remove_whitespace_feed( $s = null )
    {
        return $ret = preg_replace( '/[\t\n\r\0\x0B]/', '', $s );
    }

    /**
     * @param $s
     * @return mixed
     */
    public static function smart_clean( $s = null )
    {
        return $ret = trim( self::remove_doublewhitespace( self::remove_whitespace_feed( $s ) ) );
    }

} // end class
