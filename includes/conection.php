<?php

/**
 *	sCMS v.1.0
 * 	February 22, 2012 , 10:40:23 AM  
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}*/
	
	/*  Pengaturan Error Warning
		0 = false
		E_ALL = true
	*/
	error_reporting(E_ALL);
	
	define('FUNCTION', true);
	
	$mysql_user 	= 'root';
	$mysql_password = '';
	$mysql_database = 'auracmsv3';
	$mysql_host 	= 'localhost';