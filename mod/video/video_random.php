<?php

if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
    header("HTTP/1.1 404 Not Found");
    exit;
}

global $koneksi_db;

$query 	= mysql_query("SELECT * FROM `mod_video` ORDER BY RAND() DESC LIMIT 0,1");
$data	= mysql_fetch_array($query);
$video	= $url_situs.'/mod/video/video/'.$data['video'];
$gambar	= $url_situs.'/mod/video/images/thumb/'.$data['gambar'];
if(isset($_GET['pilih']) AND $_GET['pilih']=='video'){
	$randomvideo = '<iframe src="mod/gallery/300x250.php" marginwidth="1" marginheight="1" height="250" scrolling="no" name="slide" border="0" frameborder="0" style="padding: 0"></iframe>';
}else{
$randomvideo = <<<Iwan
	<!-- START OF THE PLAYER EMBEDDING TO COPY-PASTE -->
	<div id="mediaplayer">JW Player goes here 1</div>
	
	<script type="text/javascript" src="jwplayer.js"></script>
	<script type="text/javascript">
		jwplayer("mediaplayer").setup({
			flashplayer: "player.swf",
			autostart : "false",
			//file :"playlists_metro.php",
			file : "$video",
			width:"300",
			height:"250",
			repeat :"list",
			image: "$gambar"/* ,
			mode :[{type:'html5'},
				{type: 'flash',src:'player.swf'},
				{type: 'download'}
				]*/
		});
	</script>
	<!-- END OF THE PLAYER EMBEDDING -->
Iwan;
}




?>