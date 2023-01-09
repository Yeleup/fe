<?php
//$request_ip = $_SERVER['REMOTE_ADDR'];
//
//if (file_exists('./system/config/fe_blockip.txt.regex')) {
//    $file_blockip_contents = file_get_contents('./system/config/fe_blockip.txt.regex');
//    $blacklist_ip_range = preg_split('/[\s]+/', $file_blockip_contents);
//}
//
//$block = false;
//
//foreach( $blacklist_ip_range as $ip ) {
//    if( $ip && preg_match( $ip, $request_ip ) ) {
//		$block = true;
//	}
//}
//
//if ($block) {
//	header('Location: /block.php');
//	exit;
//}

/* LastModified */
// $LastModified_unix = strtotime(date("D, d M Y H:i:s", filectime($_SERVER['SCRIPT_FILENAME'])));
// $LastModified = gmdate("D, d M Y H:i:s", $LastModified_unix) . " GMT";
// $IfModifiedSince = false;
// if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE']) {
//     $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
// } elseif (isset($_ENV['HTTP_IF_MODIFIED_SINCE']) && $_ENV['HTTP_IF_MODIFIED_SINCE']) {
//     $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
// }
// if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
//     header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified', true, 304);
//     exit;
// }
// header('Last-Modified: ' . $LastModified);
/* end of LastModified */

// Version
define('VERSION', '3.0.3.8');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
/*if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}*/

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');
