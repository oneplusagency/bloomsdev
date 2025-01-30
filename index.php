<?php

/**
 * @file: index.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener
 * @created:    Tue Feb 04 2020
 * @author:     oppo, 1plus-agency.com
 * @version:    1.0.0
 * @modified:   Friday January 10th 2020 2:06:10 pm
 * @copyright   (c) 2008-2020 1plus-agency.com GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// composer autoloader for required packages and dependencies
// require_once('vendor/autoload.php');

// If this file is called directly, abort.
if (!defined('BLOOMSINC')) {
   define('BLOOMSINC', true);
}

date_default_timezone_set('Europe/Berlin');
$set = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
if ($set === false) {
   setlocale(LC_TIME, 'de_DE.utf8');
}

// echo strftime("%A %e %B %Y", mktime(0, 0, 0, 12, 22, 1978));

//@deprecated @since 23.04.2020 TEST !!
define(
   'PAGECON_ONWERK',
   'https://api.bloom-s.de:780/api/'
);

// $cacheblooms->store($code, json_encode($salons_array, true), 3600); // 3600 => 'hour',
// 60 seconds = 1 minute * 5
define('TIME_CACHEBLOOMS','60');

//https://api.bloom-s.de:780 OLD
//https://api.bloom-s.de:780/api/salon/all

//  !!!!!!!!!!!   @deprecated @since 23.04.2020   !!!!!!!
// define('PAGECON_ONWERK_V2', "https://api.bloom-s.de:780/api/");
// define('PAGECON_ONWERK_V2', "https://api.bloom-s.de:780/api/");

/** @FIX by oppo , @Date: 2020-06-22 11:10:24
 * @Desc:  return new api test.bloom-s
 */
//define('PAGECON_ONWERK_V2', 'https://api.bloom-s.de:780/api/');

/** @FIX by oppo , @Date: 2020-06-10 11:12:02
 * @Desc: return old api api.bloom-s.de
 */
//define('PAGECON_ONWERK_V2', 'https://test.bloom-s.de:780/api/');
// fix 23.06.2020
//define('PAGECON_ONWERK_V2', 'https://api.blooms.de:780/api/');
// fix 23.06.2020 16:38
define('PAGECON_ONWERK_V2', 'https://api.bloom-s.de:780/api/');

// SUB constant
// app\models\preisesModel.php 116
define(
   'PAGECON_ONWERK_EMPLOYEE_IMAGE',
   PAGECON_ONWERK_V2 . 'employee/image?employeeId='
);

define('PAGECON_ONWERK_SALON', PAGECON_ONWERK_V2 . 'salon/');
define('PAGECON_ONWERK_SALON_ALL', PAGECON_ONWERK_SALON . 'all');

// define('PAGECON_ONWERK_PING', PAGECON_ONWERK_V2 . 'ping');
// define('PAGECON_ONWERK_CUSTOMER', PAGECON_ONWERK_V2 . 'customer/');
// define('PAGECON_ONWERK_APPOINTMENT', PAGECON_ONWERK_V2 . 'appointment/');
// define('PAGECON_ONWERK_COUPON_PRICE', PAGECON_ONWERK_V2 . 'coupon/create?price=');
// define('PAGECON_ONWERK_SERVICE_PACKAGE', PAGECON_ONWERK_V2 . 'servicepackage/all');
// https://ru.coredump.biz/questions/48192705/notice-unknown-file-created-in-the-systems-temporary-directory-in-unknown-on-line-0


if (isset($_FILES['image'])) {
   error_reporting(0);
   // array (
   //    'image' =>
   //    array (
   //      'name' => 'pivo.jpg',
   //      'type' => 'image/jpeg',
   //      'tmp_name' => '/tmp/phpnWcmrX',
   //      'error' => 0,
   //      'size' => 141748,
   //    ),
   //  )

}

// set_include_path(__DIR__);
// ini_set( "error_reporting", 2047 );
// ini_set( "display_errors", 1 );
// var_dump( tempnam( "/etc", "wtf-" ) );

$f3 = require_once (__DIR__ . '/lib/base.php');



if ((float) PCRE_VERSION < 7.9) {
   trigger_error('PCRE version is out of date');
}

/**
 * @var \Base $f3
 */
$f3 = \Base::instance();

$f3->BITMASK = ENT_COMPAT|ENT_SUBSTITUTE;

$base = $f3->get('BASE');

// https://fatfreeframework.com/3.7/quick-reference
// !Pass variables from controller to javascript function in Fat-Free Framework
// https://stackoverflow.com/questions/53249563/pass-variables-from-controller-to-javascript-function-in-fat-free-framework
//!custom filters:
// https://stackoverflow.com/questions/42706866/how-to-format-date-in-fatfree-template
//https://fatfreeframework.com/3.6/preview#filter
// !Data Sanitation
// https://fatfreeframework.com/3.6/views-and-templates#Extendingfiltersandcustomtags
// $f3->set('ESCAPE',FALSE);

define('ONEPLUS_DIR_PATH', __DIR__);
define(
   'ONEPLUS_DIR_PATH_APP',
   ONEPLUS_DIR_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR
);
// 'E:\\openserver7\\OpenServer\\domains\\localhost\\woooo.spiegelid.wp\\wp-content\\plugins\\goldrechner'
define('ONEPLUS_DIR_URL', $base);

// $employeeimage_abs_dir = ONEPLUS_DIR_PATH . '/assets/images/employeeimage/';
// define('EMPLOYEEIMAGE_ABS_DIR', $employeeimage_abs_dir);
// $employeeimage_dir = $base . '/assets/images/employeeimage/';
// define('EMPLOYEEIMAGE_DIR', $employeeimage_dir);

$employeeimage_abs_dir = ONEPLUS_DIR_PATH . '/upload/employeeimage/';
define('EMPLOYEEIMAGE_ABS_DIR', $employeeimage_abs_dir);
$employeeimage_dir = $base . '/upload/employeeimage/';
define('EMPLOYEEIMAGE_DIR', $employeeimage_dir);


// upload\employee_webimages
$upload_employee_webimages_abs_dir = ONEPLUS_DIR_PATH . '/upload/employee_webimages/';
define('UPLOAD_EMPLOYEE_WEBIMAGES_ABS_DIR', $upload_employee_webimages_abs_dir);
$upload_employee_webimages_dir = $base . '/upload/employee_webimages/';
define('UPLOAD_EMPLOYEE_WEBIMAGES_DIR', $upload_employee_webimages_dir);

// upload\upload_stylebook
$upload_stylebook_abs_dir = ONEPLUS_DIR_PATH . '/upload/stylebookpictures/';
define('UPLOAD_STYLEBOOK_ABS_DIR', $upload_stylebook_abs_dir);
$upload_stylebook_dir = $base . '/upload/stylebookpictures/';
define('UPLOAD_STYLEBOOK_DIR', $upload_stylebook_dir);

/** @FIX by oppo (webiprog.de), @Date: 2020-07-24 13:04:06
 * @Desc:  BANERS
 *///

$banner_parent_abs_dir = ONEPLUS_DIR_PATH . '/upload/baners-blooms';
define('BANNER_PARENT_ABS_DIR', $banner_parent_abs_dir);
$banner_parent_url_dir = $base . '/upload/baners-blooms';
define('BANNER_PARENT_URL_DIR', $banner_parent_url_dir);


$banner_abs_dir = BANNER_PARENT_ABS_DIR . '/banner/';
define('BANNER_ABS_DIR', $banner_abs_dir);
$banner_dir = BANNER_PARENT_URL_DIR . '/banner/';
define('BANNER_DIR', $banner_dir);

$price_banner_abs_dir = BANNER_PARENT_ABS_DIR . '/price-banner/';
define('PRICE_BANNER_ABS_DIR', $price_banner_abs_dir);
$price_banner_dir = BANNER_PARENT_URL_DIR . '/price-banner/';
define('PRICE_BANNER_DIR', $price_banner_dir);


/*Admin staff picture*/

$employeeimageAdmin_dir = $base . '/upload/employeeimageadmin/';
define('EMPLOYEEIMAGE_ADMIN_DIR', $employeeimageAdmin_dir);
$upload_employee_admin_abs_dir = ONEPLUS_DIR_PATH . '/upload/employeeimageadmin/';
define('EMPLOYEEIMAGE_ADMIN_ABS_DIR', $upload_employee_admin_abs_dir);


$employeeimageStaff_dir = $base . '/upload/employeeimagestaff/';
define('EMPLOYEEIMAGE_STAFF_DIR', $employeeimageStaff_dir);
$upload_employee_staff_abs_dir = ONEPLUS_DIR_PATH . '/upload/employeeimagestaff/';
define('EMPLOYEEIMAGE_STAFF_ABS_DIR', $upload_employee_staff_abs_dir);

// F3 autoloader for application business code
//$f3->set('AUTOLOAD', 'app/');
// Model\Salons
$f3->set('AUTOLOAD', [
   'app/controllers/;app/models/;app/helper/',
   function ($class) {
      return strtoupper($class);
   },
]);

// https://stackoverflow.com/questions/20752365/how-to-link-back-to-index-php-in-the-fat-free-framework


// $checksum = crc32(PAGECON_ONWERK_V2.date('m'));
$checksum = crc32(PAGECON_ONWERK_V2);
$f3->set('BLOOMS_VERSION', $checksum );

$f3->set('ASSETS', $base . '/assets/');
$f3->set('TITLEBLOOM', 'bloom\'s');
// $this->f3->set('classfoot', 'home');
$f3->set('classfoot', 'home');
$f3->set('POMILKA', null);

//UI=app/views/
$f3->set('UI', __DIR__ . '/app/views/');

// load config files
$f3->config(__DIR__ . '/app/config/config.ini');
require_once __DIR__ . '/app/config/routes.php';

// https://groups.google.com/forum/#!topic/f3-framework/CukfeyGroSg
// http://sbf-testing.byethost7.com/en/ffconfig?i=1

// set DEBUG
$f3->set('DEBUG', (int) $f3->get('site_debug'));
// set CACHE
$site_cache = (int) $f3->get('site_cache');
$f3->set('CACHE', (bool) $site_cache);

$f3->set('TEMP', __DIR__ . '/tmp/');

$f3->set('carousel_interval', (int) $f3->get('carousel_interval'));

// ini_set('display_errors', 1);

// set Cacheblooms $cacheblooms = $f3->get('CacheBlooms');
$f3->set(
   'CacheBlooms',
   new cacheblooms([
      'name' => 'blooms',
      'path' => __DIR__ . '/tmp/cache/',
      'extension' => '.cache',
   ])
);

// $f3->set('CACHE', true);
// $f3->set('DEBUG', 1);
// $f3->route('GET /enrollees/campus/@controller','\enrollees\campus\@controller->index');
// $f3->route('GET /enrollees/campus/@controller','\enrollees\campus\@controller->index');

// $f3->config('config/routes.ini');
//  if($f3->get('DEBUG') == 0)
// $f3->set('ONERROR', 'Controller->error');
// $f3->set('logger',$logger = new Log('records.log'));
// $f3->set('LOGGER', new Log(date('Y-m-d.\l\o\g')));

// set environment
// $f3->set('ENV_PROD_GOP', 0 );
// // load config files
// if ($f3->get('ENV_PROD_GOP')):
//     // $f3->config('config/setting.ini');
// else:
//     // $f3->set('DEBUG', 3);
//     $f3->set('DEBUG', 3);
//     // $f3->config('config/setting-dev.ini');
// endif;

// https://hotexamples.com/examples/-/F3/route/php-f3-route-method-examples.html
// https://sourceforge.net/p/fatfree/discussion/1041718/thread/782a5e10/
// set error handler
// Set up error handling
$f3->set('ONERROR', function (Base $f3) {
   $logger = new \Log($f3->get('LOGS') . 'error.log');
   $logger->write(
      sprintf('[CODE %s] %s', $f3->get('ERROR.text'), $f3->get('ERROR.code'))
   );
   // $f3->reroute('/error_404');
   // $f3->set('content', 'content.html');
   // $f3->set("view", 'home.html');
   // Error($message, $file = __FILE__, $class = __CLASS__, $function = __FUNCTION__, $line = __LINE__)
   // helperblooms::Error('500');

   //  new logger
   // https://github.com/brijeshb42/noteapp/blob/eec21be0f35ba998f400594259c425dc77e2ab48/app/note.php
   $log = new \Log($f3->get('LOGS') . 'error_trace.log');
   
   foreach ($f3->get('ERROR.trace') as $frame) {
      if (isset($frame['file'])) {
         $line = '';
         $addr = $f3->fixslashes($frame['file']) . ':' . $frame['line'];
         if (isset($frame['class'])) {
            $line .= $frame['class'] . $frame['type'];
         }

         if (isset($frame['function'])) {
            $line .= $frame['function'];
            if (!preg_match('/{.+}/', $frame['function'])) {
               $line .= '(';
               if (isset($frame['args']) && $frame['args']) {
                  $line .= $f3->csv($frame['args']);
               }

               $line .= ')';
            }
         }
         $log->write($addr . ' ' . $line);
      }
   }

   if ($f3->get('AJAX')) {
      if (!headers_sent()) {
         header('Content-type: application/json');
      }
      // echo json_encode( array(
      //     "error" => $f3->get( "ERROR.title" ),
      //     "text"  => $f3->get( "ERROR.text" )
      // ) );
   } else {
      $error = $f3->get('ERROR');
      $error_title = constant('Base::HTTP_' . $error['code']);
      $f3->set('title', "{$error['code']} {$error_title}");

      switch ($f3->get('ERROR.code')) {
         case 404:
            $f3->set('POMILKA', 404);
            // $f3->set("title", "Not Found");
            $f3->set('ESCAPE', false);
            $c = "<p>The requested URL {$_SERVER['REDIRECT_URL']} was not found on this server.</p>";

            //https://fatfreeframework.com/3.6/framework-variables
            // `ERROR.code` - the HTTP status error code (`404`, `500`, etc.)
            // `ERROR.status` - a brief description of the HTTP status code. e.g. `'Not Found'`
            // `ERROR.text` - error context
            // `ERROR.trace` - stack trace stored in an `array()`
            // `ERROR.level` - error reporting level (`E_WARNING`, `E_STRICT`, etc.)

            $f3->set('ESCAPE', false);
            $realm = $f3->get('REALM');
            if ($realm) {
               $c = "Die angeforderte URL <span class=\"url-error\">{$realm}</span> wurde auf diesem Server nicht gefunden";
               $f3->set('ERROR', ['text' => $c]);
               $f3->set('ERROR', [
                  'code' => $error['code'],
                  'text' => $c,
                  'status' => $error['status'],
                  'trace' => $error['trace'],
                  'level' => $error['level'],
               ]);
            }
            // echo \Helper\View::instance()->render("error/404.html"); // test
            echo \Template::instance()->render('error/error-404.html');
            break;
         case 403:
            $f3->set('POMILKA', 403);
            echo 'You do not have access to this page.';
            break;

         case '500':
         default:
            $f3->set('POMILKA', 500);
            if (ob_get_level()) {
               // $f3->reroute('/');
               // include __DIR__ . "/app/view/error/inline.html";
               echo \Template::instance()->render('error/error-inline.html');
            } else {
               // $f3->reroute('/');
               // include __DIR__ . "/app/view/error/500.html";
               echo \Template::instance()->render('error/500.html');
            }
      }

      // $f3->reroute('/');
   }
});

// $f3->set('ONERROR', function($f3){
//     $logger = new \Log('f3errors.log');
//     $logger->write(print_r($f3->get('ERROR'), true));
//     echo \Template::instance()->render('error.html');
// });

$f3->route('GET /clearcache', function ($f3) {
   // Clear cached profile picture data
   // $cache = \Cache::instance();
   // // $cache->clear('folder');
   // $cache->reset();
   // $f3->clear('CACHE');
   $tmp = ONEPLUS_DIR_PATH . DIRECTORY_SEPARATOR . 'tmp';
   if (file_exists($tmp)) {
      array_map('unlink', array_filter((array) glob($tmp . '/*.php')));
   }

   $cacheblooms = $f3->get('CacheBlooms');
   $cacheblooms->eraseAll();
   $f3->set('SESSION.success', 'Cache erfolgreich gelöscht');

   $f3->reroute('/');
});

$f3->run();


