<?php

if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
    header("HTTP/1.1 404 Not Found");
    exit;
}

global $koneksi_db;

$s = mysql_query ("SELECT * FROM `mod_video` ORDER BY `id` DESC LIMIT 0,4");
$videonormal = '';
$urutan =  1;
while($data = mysql_fetch_array($s)){
	$nama 		= $data['judul'._EN];
	$keterangan	= $data['keterangan'._EN];
	$id 		= $data['id'];
	$img		= $data['gambar'];
	$gambar 	= empty ($img) ? '<img style="background: #F1F8ED;border: 1px solid #D1EAC3;padding: 6px;" src="mod/video/images/computer.png" alt="" />': '<img style="background: #F1F8ED;border: 1px solid #D1EAC3;padding: 6px;" src="mod/video/images/thumb/'.$img.'" alt="" />';
	if($urutan % 2 == 0){
		$float = 'right';
	}else{
		$float = 'left';
	}	
	$videonormal .= '
	<div class="border" style="text-align:center;width:135px;float:'.$float.';margin:5px 0 0 0;min-height:115px;">
		<a href="video-view-'.$data['seftitle'].'.html" title="'.$data['seftitle'].'"><img style="background: #F1F8ED;border: 1px solid #D1EAC3;padding: 6px;" src="mod/video/images/thumb/'.$img.'" alt="" /><br /><span style="text-shadow: 0px 1px 1px rgb(255, 255, 255);font: bold 12px/20px arial;color: #000;">'.$nama.'</span></a>
	</div>';
	$urutan++;
}

?>