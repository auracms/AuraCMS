<?php

/**
 *	AuraCMS v.3.0
 * 	October 21, 2012  , 10:40:23 AM  
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */
 

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}

	function get_setting( $param ){
		global $db;
		$data 	= $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_setting` WHERE `id`='1'"));
		return $data[$param];
	}
	
	$upload['images'] = array('image/pjpeg','image/jpeg','image/jpg');
	define('tmp','images/temp/');
	define('normal','images/normal/');
	define('thumb','images/thumb/');
	define('slide','images/slide/');	
	define('themes',get_setting('themes'));
	define('keyword',get_setting('keyword'));
	define('description',get_setting('description'));
	define('title',get_setting('title'));
	define('status',get_setting('status'));
	define('url',get_setting('url'));
	define('slogan',get_setting('slogan'));
	define('email',get_setting('email'));
	define('name_blocker',get_setting('name_blocker'));
	define('email_blocker',get_setting('email_blocker'));
	define('admin_themes',get_setting('admin_themes'));
	
	
	