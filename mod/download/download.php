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
	
	if(!function_exists('cek_new')){
		function cek_new($timeupdate, $expire=2073600){
			$update = strtotime($timeupdate);
			$time	= date('Y-m-d H:i:s');
			$waktu 	= strtotime($time) - $expire;
			if($update >= $waktu){
				return '<b style="color:red"><sup>New</sup></b>';
			}else{
				return '';
			}
		}
	}
	
	
	$tengah  ='';
	
	if($_GET['action'] == ''){
		
		$tengah .='
		<h2 class="widget-title">Katalog <span class="styled1">Download</span></h2>		
		<div class="border" style="text-align:center;"><img src="mod/download/images/download.png" alt="Download" /></div>
		<div class="border breadcrumb"><a href="download.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Download</div>';
	
		
		$aa 	= $db->sql_query("SELECT * FROM `mod_download_cat`  ORDER BY `title` DESC");
		$jumlah = $db->sql_numrows($aa);
		$limit 	= 10;
				
		$a 		= new paging_s ($limit,'download','.html');

		$no 	= 0;
		$b 	= $db->sql_query("SELECT * FROM `mod_download_cat` ORDER BY `title` DESC  LIMIT $offset,$limit");
		
		$ref 	= urlencode($_SERVER['REQUEST_URI']);
		$tengah .= '
		<div class="border rb">
		<table cellspacing="10" cellpadding="0" width="100%">
			<tr>';
		while($data = $db->sql_fetchrow($b)){
			$warna 	= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';			
			$urutan = $no + 1;
			$id		= $data['id'];
			$q 		= $db->sql_query("SELECT * FROM `mod_download` WHERE `cat_id`='$id'");
			$total	= $db->sql_numrows($q);
			$cn 	= $db->sql_fetchrow($q);

			$tengah .= '
			<td style="width:33%;vertical-align:top;">
			<div style="float:left;margin-right:2px;"><img src="mod/download/images/folder.png" alt=""></div>
			<div style="padding-left:35px;"><a href="download-detail-'.$data['seftitle'].'.html" title="'.$data['title'].'"><b>'.$data['title'].'</b></a> ('.$total.') '.cek_new($cn['date'],1209600).'</div>
			<p style="padding-left:35px;">'.$data['description'].'</p>
			</td>';
			if ($urutan % 3 == 0) {
				$tengah .= '</tr><tr>';
			}
			$no++;					
		}
		$tengah .= '
			</tr>
		</table>
		</div>';
		$tengah .= $a-> getPaging($jumlah, $pg, $stg);
	}
	
	if($_GET['action'] == 'detail'){
		
		$seftitle = seo(text_filter(cleanText($_GET['seftitle'])));	
		$a 	= $db->sql_query("SELECT `id`,`title` FROM `mod_download_cat` WHERE `seftitle`='$seftitle'");
		$b 	= $db->sql_fetchrow($a);
		$id	= $b['id'];
		$a = $db->sql_query("SELECT `mod_download`.*,`mod_download_cat`.`title` AS `title_cat` FROM `mod_download` LEFT JOIN `mod_download_cat` ON (`mod_download_cat`.`id` = `mod_download`.`cat_id`) WHERE `mod_download`.`cat_id`='$id'");
		
		$tengah .='
		<h2 class="widget-title">Katalog <span class="styled1">Download</span></h2>
		<div class="border breadcrumb"><a href="download.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   '.$b['title'].'</div>
		<div class="border" style="text-align:center;"><img src="mod/download/images/download.png" alt="Download" /></div>';
		

		while($data = $db->sql_fetchrow($a)){
			$warna = empty ($warna) ? 'background-color:#f4f4f8;' : '';
			$tengah .= '
			<div class="border" style="'.$warna.'" >
			<span class="title"><strong><a target="_blank" href="download-jump-'.$data['seftitle'].'.html" title="'.$data['title'].'">'.$data['title'].'</a></strong></span><br />'.$data['description'].'Hits : '.$data['hits'].' | Date : '.datetimes($data['date'],false).' | Category : '.$data['title_cat'].'
			</div>';
		}
	}
	
	echo $tengah;