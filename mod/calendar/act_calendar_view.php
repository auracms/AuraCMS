<?php

if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
    header("HTTP/1.1 404 Not Found");
    exit;
}

$module = basename(dirname(__FILE__));
get_lang($module);

$content = '';

$script_include[]= '
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/thickbox-compressed.js"></script>
<link rel="stylesheet" type="text/css" href="css/thickbox.css" />';
$seldate 	= (int)int_filter ($_GET['sel_date']);
//$content .= (int)int_filter ('1325510565.html');	
$t 			= getdate($seldate);
//print_r ($t);
$_GET['waktu_akhir'] = isset($_GET['waktu_akhir']) ? $_GET['waktu_akhir'] : null;
$u 			= getdate((int)int_filter ($_GET['waktu_akhir']));
if (isset ($_GET['sel_date']) OR isset ($_GET['id']) OR isset ($_GET['seftitle'])){
	$id 	  = int_filter($_GET['id']);
	$seftitle = seo(text_filter(cleanText($_GET['seftitle'])));
	$JUDULCAL = array ();
	$TMPpesan = array() ;
	$awalbulandengannol 	= $t['mon'] >= 10 ? $t['mon'] : '0'.$t['mon'];
	$varwaktucalender 		= $t['year'] . '-' . $awalbulandengannol . '-' . $t['mday']; 
	$awalbulandengannol2 	= $u['mon'] >= 10 ? $u['mon'] : '0'.$u['mon'];
	$varwaktucalender2 		= $u['year'] . '-' . $awalbulandengannol2 . '-' . $u['mday'];
	  
	
	   
	$cekdate = mysql_query ("SELECT * FROM `tbl_kalender` WHERE `waktu_mulai` = '$varwaktucalender' OR `waktu_akhir` = '$varwaktucalender2' OR `id`='$id' OR `seftitle`='$seftitle' ORDER BY `waktu_mulai`");
	$getdate = mysql_fetch_assoc($cekdate);
		//print_r($getdate);
		$WKTMULAI = $getdate['waktu_mulai'];
		$WKTAKHIR = $getdate['waktu_akhir'];
		$GTTGL = (int)substr($WKTMULAI, -2, 2);
		$TGLMULAI[$GTTGL] = $GTTGL; // 
		$JUDULCAL[$GTTGL] = $getdate['judul'._EN];
		
		$lokasi	= ($getdate['gambar'] == '') ? $getdate['lokasi'] : '<a href="mod/calendar/images/normal/'.$getdate['gambar'].'" class="thickbox" rel="gallery"  title="'.$getdate['judul'._EN].'">'.$getdate['lokasi'].'</a>';
    
		 
		$content .= '<h4 class="bg">'.$getdate['judul'._EN].'</h4>';
		$content .= '<div class="border">Oleh '.$getdate['pengirim'].' ('.datetimes($getdate['tanggal']).' WIB)</div>
		<div class="border">
		<table border="0">
			<tbody>
			<tr>
			<td>Hari / Tanggal</td>
			<td style="padding-left: 15px">:</td>
			<td>'.converttgl ($WKTMULAI).' s.d '.converttgl ($WKTAKHIR).'</td>
			</tr>
			<tr>
			<td>Waktu</td>
			<td style="padding-left: 15px">:</td>
			<td>'.$getdate['waktu'].'</td>
			</tr>
			<tr>
			<td>Lokasi</td>
			<td style="padding-left: 15px">:</td>
			<td>'.$lokasi.'</td>
			</tr>
			</tbody>
		</table>'.$getdate['isi'._EN].'</div>';
}

echo $content;

?>

