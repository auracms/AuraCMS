<?php

	if(!defined('INDEX')) exit;

	error_reporting(0);
	$tengah = '';
	$script_include[]= '
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/thickbox-compressed.js"></script>
	<link rel="stylesheet" type="text/css" href="css/thickbox.css" />';
	
	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;
	
	
	if($_GET['action'] == ''){
		
		$tengah .= '
		<h2>Agenda Kegiatan</h2>
		<div class="border" style="text-align:center;"><img src="mod/calendar/images/kaldik.jpeg" alt="Agenda Kegiatan" /></div>			
		<div class="border">
			<div class="breadcrumb"><a href="agenda.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Daftar Agenda Kegiatan</div>
		</div>';
		
		$tengah .= '			
		<div class="border rb">
		<table class="list">
			<thead>
			<tr class="head">
				<td style="text-align: center;width:30px;">No</td>
				<td class="left">Agenda</td>
				<td style="text-align: center;">Tanggal</td>
			</tr>
			</thead>
			<tbody>';
			$jq 		= $db->sql_query("SELECT * FROM `tbl_kalender` ORDER BY `waktu_mulai` DESC");
			$jumlah = $db->sql_numrows($jq);
			$limit 	= 15;
				
			$a 		= new paging_s ($limit,'agenda','.html');

			if(isset($offset)){
				$no = $offset + 1;
			}else{
				$no = 1;
			}
				
			$query 		= $db->sql_query("SELECT * FROM `tbl_kalender` ORDER BY `waktu_mulai` DESC LIMIT $offset,$limit");
				
			while($data = $db->sql_fetchrow($query)){
				$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';

				$tengah .= '
				<tr'.$warna.'>
			    	<td style="text-align: center;">'.$no.'</td>
			   		<td class="left"><a href="agenda-'.$data['seftitle'].'.html" title="'.$data['judul'].'" />'.$data['judul'].'</a></td>
			     	<td style="text-align: center;">'.datetimes($data['waktu_mulai'],false).'</td>
				</tr>';
				$no++;					
			}
			$tengah .= '
			</tbody>
		</table>
		</div>';
		$tengah .= $a-> getPaging($jumlah, $pg, $stg);
		
	}
	if($_GET['action'] == 'view'){
		
		
		
		if (isset ($_GET['sel_date']) OR isset ($_GET['seftitle']) OR isset ($_GET['waktu_akhir'])){
			
			$t 		  = getdate(int_filter ($_GET['sel_date']));
			$u 		  = getdate(int_filter ($_GET['waktu_akhir']));
			$seftitle = seo(text_filter(cleanText($_GET['seftitle'])));
			
			$JUDULCAL = array ();
			$TMPpesan = array() ;
			$awalbulandengannol 	= $t['mon'] >= 10 ? $t['mon'] : '0'.$t['mon'];
			$varwaktucalender 		= $t['year'] . '-' . $awalbulandengannol . '-' . $t['mday']; 
			$awalbulandengannol2 	= $u['mon'] >= 10 ? $u['mon'] : '0'.$u['mon'];
			$varwaktucalender2 		= $u['year'] . '-' . $awalbulandengannol2 . '-' . $u['mday'];
	  
	
	   
			$cekdate = mysql_query ("SELECT * FROM `tbl_kalender` WHERE `waktu_mulai` = '$varwaktucalender' OR `waktu_akhir` = '$varwaktucalender2' OR `seftitle`='$seftitle' ORDER BY `waktu_mulai`");
			$getdate = mysql_fetch_assoc($cekdate);
			$WKTMULAI 	= $getdate['waktu_mulai'];
			$WKTAKHIR 	= $getdate['waktu_akhir'];
			$GTTGL 		= (int)substr($WKTMULAI, -2, 2);
			$TGLMULAI[$GTTGL] = $GTTGL; // 
			$JUDULCAL[$GTTGL] = $getdate['judul'];
			$ID			= $getdate['id'];			
			
			$lokasi	= $getdate['lokasi'];
	    
			 
			$tengah .= '<h2>Agenda Kegiatan</h2>';
			$tengah .= '
			<div class="border" style="text-align:center;"><img src="mod/calendar/images/kaldik.jpeg" alt="Agenda Kegiatan" /></div>			
			<div class="border">
			<div class="breadcrumb"><a href="agenda.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   '.$getdate['judul'].'</div>
			</div>
			<div class="border">
			<table border="0" cellspacing="0" cellpadding="0" id="table1">
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;width:90px;"><strong>Judul Agenda</strong></td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 10px; padding-top: 5px"><strong>'.$getdate['judul'].'</strong></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;width:90px;"><strong>Tanggal</strong></td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 10px; padding-top: 5px">'.datetimes ($WKTMULAI,false).' s.d '.datetimes ($WKTAKHIR,false).'</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;width:90px;"><strong>Waktu</strong></td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 10px; padding-top: 5px">'.$getdate['waktu'].'</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;width:90px;vertical-align:top;"><strong>Agenda</strong></td>
					<td style="padding-top: 5px;vertical-align:top;">:</td>
					<td style="padding-left: 10px; padding-top: 5px">'.$getdate['isi'].'</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;width:90px;"><strong>Lokasi</strong></td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 10px; padding-top: 5px">'.$lokasi.'</td>
				</tr>
			</table>
			</div>';
			$tengah .= '			
			<h2>Agenda Kegiatan Lainnya</h2>
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Agenda</td>
			            <td style="text-align: center;">Tanggal</td>
			          </tr>
			        </thead>
			        <tbody>';
			        
				$no = 1;
				
				$query 		= $db->sql_query("SELECT * FROM `tbl_kalender` WHERE `id`!='$ID' ORDER BY `waktu_mulai` DESC");
				
				while($data = $db->sql_fetchrow($query)){
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left"><a href="agenda-'.$data['seftitle'].'.html" title="'.$data['judul'].'" />'.$data['judul'].'</a></td>
			            <td style="text-align: center;">'.datetimes($data['waktu_mulai'],false).'</td>
					</tr>';
					$no++;					
				}
				$tengah .= '
					</tbody>
				</table>
				</div>';
			
		}
		
		
		
	}
echo $tengah;

?>

