<?php

	/**
	 *	AuraCMS v.3.0
	 * 	Oktober 1, 2013 05:12:10 AM 
	 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
	 */
	 
	error_reporting (0);
	function bukafile($filename){
		$fp = @fopen($filename, "r");
		$sizeof = (@filesize($filename) == 0) ? 1 : filesize($filename);
		return @fread($fp, $sizeof);
		fclose($fp);
	} 

	$image_on  = 'images/Yameng.gif';
	$image_off = 'images/Yameng-off.gif';

	Header("Content-type: image/gif");
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

	$yahooid 	= $_GET['id'];
	$ch 		= curl_init('http://opi.yahoo.com/online?u='.$yahooid.'&m=t');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$status 	= curl_exec($ch);
	curl_close($ch);
	if($status == $yahooid.' is NOT ONLINE'){
		//tampilkan gambar offline
		echo bukafile ($image_off);
	} elseif ($status == $yahooid.' is ONLINE'){
		//tampilkan gambar online
		echo bukafile ($image_on);
	}