<?php
/**
 *	aTravel v.1.0
 * 	December 20, 2011 05:12:10 AM 
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	/*  Pengaturan Error Warning
		0 = false
		E_ALL = true
	*/
	error_reporting(E_ALL);
	
	define('FUNCTION', true);
	
	$mysql_user 	= 'root';
	$mysql_password = '';
	$mysql_database = 'eiti';
	$mysql_host 	= 'localhost';
	
	if (file_exists('includes/fungsi.php')){
		include 'includes/fungsi.php';
	}
	$attachment['photo'] = array('image/pjpeg','image/jpeg','image/jpg');
	$photo['width'] = 600;
	$photo['height'] = 400;
	$photo['ratio'] = '4:5';
	$GLOBALS['timeplus'] = 3600;
	define('tmp','images/temp/');
	define('normal','images/normal/');
	define('thumb','images/thumb/');
	define('slide','images/slide/');

?>