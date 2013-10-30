<?php

/**
 *	
 * 	March 02, 2012  , 09:32:55 PM   
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */

if (!defined('INDEX')) {
    Header("Location: ../index.php");
    exit;
}


	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;
	
	$index_hal = 1;

	$tengah  = '';
	
	function jumlah_photo($id){
		global $db;
		$q 		= $db->sql_query("SELECT * FROM `mod_gallery` WHERE `album_id`='$id' AND `published`='1'");
		$jumlah = $db->sql_numrows($q);
		return $jumlah;
	}
	$style_include[] = <<<js
	<style type="text/css">

	.pretty {
	    background: -moz-linear-gradient(center bottom , #F2F2F2 0px, #FFFFFF 100%) repeat scroll 0 0 transparent;
	    border: 1px solid #DDDDDD;
	    border-radius: 5px 5px 5px 5px;
	    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
	    padding: 4px;
	}
	</style>
js;

if(!isset($_GET['action'])){
	
	$tengah .= '
	<h2>Gallery Photo</h2>
	<div class="border" style="text-align:center;"><img src="mod/gallery/images/gallery.png" alt="Gallery" /></div>
	<div class="border"><a href="gallery.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Gallery Photo</div>';
	
	$q 		= $db->sql_query("SELECT * FROM `mod_gallery_album` WHERE `published`='1'");
	$jumlah = $db->sql_numrows($q);
	$limit 	= 15;
	
	$a 		= new paging_s ($limit,'gallery','.html');
	
	$tengah .= '<div class="border"><table  width="100%" border="0" ><tr>';

	$query 	= $db->sql_query ("SELECT * FROM `mod_gallery_album` WHERE `published`='1' ORDER BY `id` DESC LIMIT $offset,$limit");
	$i 		= 1;
	while($data = $db->sql_fetchrow($query)){
		
		$id = $data['id'];
		$d 	= $db->sql_fetchrow($db->sql_query("SELECT `images` FROM `mod_gallery` WHERE `album_id`='$id' AND `published`='1' ORDER BY rand()"));
	
		$tengah .= '<td width="33%" valign="top"><div style="text-align:center;margin-bottom:10px;"><a href="gallery-detail-'.$data['seftitle'].'.html" title="'.$data['album'].'" /><strong>'.$data['album'].'</strong></a><br /><a href="gallery-detail-'.$data['seftitle'].'.html" title="'.$data['album'].'" /><img class="pretty" src="thumb.php?img='.$d['images'].'&amp;w=128&amp;t=yes" alt="" border="0" style="opacity:1;" /></a><br />'.datetimes($data['tanggal'],false).'</br>Jumlah Photo : '.jumlah_photo($id).'</div></td>';
		
		if ( $i % 3 == 0 ) {
		    $tengah .= '</tr><tr>';
		}
		$i++;
	}
	$tengah .= '</table></div>';	
	
	$tengah .= $a-> getPaging($jumlah, $pg, $stg);
		    
}

if($_GET['action'] == 'detail'){
	

	$seftitle 	= text_filter(cleanText($_GET['seftitle']));
	$d 			= $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_gallery_album` WHERE `seftitle`='$seftitle' AND `published`='1'"));
	$id			= $d['id'];
	
	$script_include[] = <<<js
	<script type="text/javascript" src="plugin/jquery-lightbox-0.5/js/jquery.lightbox-0.5.js"></script>
	<link rel="stylesheet" type="text/css" href="plugin/jquery-lightbox-0.5/css/jquery.lightbox-0.5.css" media="screen" />
	<script type="text/javascript">
	$(function() {
		$('#gallery .lightbox').lightBox({fixedNavigation:true});
	});
	</script>
js;

		
	$tengah .= '
	<h2>Gallery Photo</h2>
	<div class="border" style="text-align:center;"><img src="mod/gallery/images/gallery.png" alt="Gallery" /></div>
	<div class="border"><a href="gallery.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   '.$d['album'].'</div>';
	
	$q 		= $db->sql_query("SELECT * FROM `mod_gallery` WHERE `album_id`='$id' AND `published`='1'");
	$jumlah = $db->sql_numrows($q);
	$limit 	= 16;
	
	$a 		= new paging_s ($limit,'gallery-detail-'.$seftitle,'.html');
	
	$tengah .= '<div class="border" id="gallery"><table  width="100%" border="0" ><tr>';

	$query 	= $db->sql_query ("SELECT * FROM `mod_gallery` WHERE `album_id`='$id'AND `published`='1' ORDER BY `tanggal` DESC LIMIT $offset,$limit");
	$i 		= 1;
	while($data = $db->sql_fetchrow($query)){
		

		$tengah .= '<td width="25%" valign="top"><div style="text-align:center;margin-bottom:15px;"><a class="lightbox" title="'.$data['caption'].'" href="'.normal.$data['images'].'"><img class="pretty" src="thumb.php?img='.$data['images'].'&amp;w=128&amp;t=yes" alt="" border="0" style="opacity:1;" /></a></div></td>';
		
		if ( $i % 4 == 0 ) {
		    $tengah .= '</tr><tr>';
		}
		$i++;
	}
	$tengah .= '</table></div>';	
	
	$tengah .= $a-> getPaging($jumlah, $pg, $stg);
	

	
	

}

echo $tengah;

?>