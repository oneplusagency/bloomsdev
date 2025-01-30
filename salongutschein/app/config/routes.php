<?php
// http://jobec.ru/blog/fat-free-frejmvork-polnoe-opisanie/
// https://hotexamples.com/examples/-/F3/route/php-f3-route-method-examples.html
$f3->route('GET|POST /', '\home->index');
// $f3->route('GET|POST /r/@hash', '\url->r');

$f3->route('GET|POST /@controller', '\@controller->index');
$f3->route('GET|POST /@controller.html', '\@controller->index');

$f3->route('GET|POST|PUT /@controller/@action/@id', '\@controller->@action');
$f3->route('GET|POST|PUT /@controller/@action/@id/@mitarbeiter/@empid', '\@controller->@action');
$f3->route('GET|POST|PUT /@controller/@action/@id.html', '\@controller->@action');
$f3->route('GET|POST|PUT /@controller/@action', '\@controller->@action');

$f3->route('GET /@controller/@action/@id/@palazka-@cid', '\@controller->@action');


$f3->route('GET|POST /admin/@action','\AdminGet->@action');

$f3->route('GET|POST /admin', '\AdminGet->index');

$f3->route('GET|POST /admin/@action/@id','\AdminGet->@action');
// /admin/settings/config/edit
$f3->route('GET|POST /admin/@action/@id/edit','\AdminGet->@id');

//GET|POST /settings/config/edit = Controller\Config->write


// $f3->route('POST /admin/@action','AdminPost->@action');

// \F3::route("GET /", 'Control\\App->indexer');
// \F3::route("GET @blogread:     /baca/@id-xyml", "Control\\Blog->Tampil");
// \F3::route("GET @virtualasset: /img/@link/@size-@id-@type", "Control\\Imager->akses", 3600 * 24 * 7); // cache seminggu :3

// /*
//    ADMIN PANEL SETTINGS~ :* :*
// */
// \Middleware::instance()->before('GET|POST|PUT|DELETE /admin*',function(\Base $f3, $param) {
//    //cek apa dia login apa kagak, dan layak apa kagak. lel
//    $access = \Access::instance();
//    $access->policy('deny');
//    $access->allow('/admin/*', 'admin');
//    $access->allow('GET|POST /admin/Auth*');
//    $access->allow('GET|POST /admin/auth*');
//    if(!$f3->exists('SESSION.user_type') && !$f3->exists('COOKIE.user')) $f3->set('SESSION.user_type', 'guest');
//    $access->authorize($f3->get('SESSION.user_type'), function($route, $subject) {
//        \F3::reroute('@admin_pack(@pack=Auth)');
//    });

//    /*
//        Default settings for template #1
//    */
//    if($f3->exists("COOKIE.user") or $f3->exists("SESSION.user")) {
//        $userz = \User::createUser(\kksd\Sesi::$DB);
//        $userz->load(array('id=?', ($f3->exists("COOKIE.user")?$f3->COOKIE['user']:$f3->SESSION['user'])));
//        $f3->set("system.user",$userz);
//    }
//    \Template::instance()->extend('php', function($args){
//        $html = (isset($args[0])) ? $args[0] : '';
//



// function parse_xml_into_array($xml_string, $options = array())
// { /* DESCRIPTION: - parse an XML string into an array INPUT: - $xml_string - $options : associative array with any of these keys: - 'flatten_cdata' : set to true to flatten CDATA elements - 'use_objects' : set to true to parse into objects instead of associative arrays - 'convert_booleans' : set to true to cast string values 'true' and 'false' into booleans OUTPUT: - associative array */
// 	// Remove namespaces by replacing ":" with "_"
// 	if (preg_match_all("|</([\\w\\-]+):([\\w\\-]+)>|", $xml_string, $matches, PREG_SET_ORDER)) {
// 		foreach ($matches as $match) {
// 			$xml_string = str_replace('<' . $match[1] . ':' . $match[2], '<' . $match[1] . '_' . $match[2], $xml_string);
// 			$xml_string = str_replace('</' . $match[1] . ':' . $match[2], '</' . $match[1] . '_' . $match[2], $xml_string);
// 		}
// 	}
// 	$output = json_decode(json_encode(@simplexml_load_string($xml_string, 'SimpleXMLElement', ($options['flatten_cdata'] ? LIBXML_NOCDATA : 0))), ($options['use_objects'] ? false : true));
// 	// Cast string values "true" and "false" to booleans
// 	if ($options['convert_booleans']) {
// 		$bool = function (&$item, $key) {
// 			if (in_array($item, array('true', 'TRUE', 'True'), true)) {
// 				$item = true;
// 			} elseif (in_array($item, array('false', 'FALSE', 'False'), true)) {
// 				$item = false;
// 			}
// 		};
// 		array_walk_recursive($output, $bool);
// 	}
// 	return $output;
// }
