<?php

/**
*	AuraCMS v.3.0
* 	Oktober 1, 2013 05:12:10 AM 
*	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
*/

	isset($_REQUEST['img']) or die('NO IMAGE');	
	include 'includes/resize.php';

	if($_REQUEST['t'] == 'yes'){
		$gambar	= 'images/thumb/'.$_REQUEST['img'];
	}else{
		$gambar	= 'images/normal/'.$_REQUEST['img'];
	}
	$width 	= $_REQUEST['w']; // lebar maksimal untuk setiap image adalah 80px
	$height = $_REQUEST['h'];
	if(!empty($height)){
		thumb($gambar,$width,$height);	// generate thumbnail image
	}else{
		image_fly($gambar,$width); // generate thumbnail image
	}
?>