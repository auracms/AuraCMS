<?php
	
	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;

	$script_include[] = <<<js
	<!-- TinyMCE -->
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/init.js"></script>
	<!-- /TinyMCE -->
js;
	$tengah  = '';
	
	$tengah .= <<<scr
	<script type="text/javascript">
	/*<![CDATA[*/
		ubahbackground=function(obj,ini){
			var Obj = document.getElementById(obj);
			try{
				Obj.style.background = ini.value;
			}catch(e){
				alert('Tabel Warna Invalid');
			}	
		};
		
		ubahcolor=function(obj,ini){
			var Obj = document.getElementById(obj);
			try{
				Obj.style.color = ini.value;
			}catch(e){
				alert('Tabel Warna Invalid');
			}		
		};
	/*]]>*/
	</script>
scr;

	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;
	
	function escape ($value){
		$b = mysql_real_escape_string($value);
		return $b;
	}
	
	if($_GET['action'] ==''){
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Agenda <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=calendar" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Agenda Manager</div>
		</div>		
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Home</a></li>
					<li><a href="#tabs-2">Tambah Agenda</a></li>
				</ul>
				<div id="tabs-1">';
				$a 		= $db->sql_query("SELECT * FROM `tbl_kalender` ORDER BY `judul` DESC");
				$jumlah = $db->sql_numrows($a);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 		= $db->sql_query("SELECT * FROM `tbl_kalender` ORDER BY `judul` DESC LIMIT $offset,$limit");
				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Name Kegiatan</td>
			            <td class="left">Tanggal Kegiatan</td>
			            <td style="text-align: center;width:100px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				while($data = $db->sql_fetchrow($b)){
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$data['judul'].'</td>
			            <td class="left">Tanggal : '.datetimes($data['waktu_mulai'],false).'<br />Waktu : '.$data['waktu'].'<br />Lokasi : '.$data['lokasi'].'</td>
			            <td style="text-align: center;"><a class="perbaiki" href="admin.php?mod=calendar&amp;action=edit&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Edit">Edit</a> <a class="hapus" href="admin.php?mod=calendar&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
					</tr>';
					$no++;					
				}
				$tengah .= '
					</tbody>
				</table>
				</div>';
				$tengah .= $a-> getPaging($jumlah, $pg, $stg);
				
				$tengah .= '
				</div>
				<div id="tabs-2">';
		  
					if(isset($_POST['submit'])){
						$judul 			= cleantext($_POST['judul']);	
						$waktu_mulai 	= cleantext($_POST['waktu_mulai']);	
						$waktu_akhir 	= cleantext($_POST['waktu_akhir']);	
						$keterangan		= $_POST['keterangan'];
						$waktu			= cleantext($_POST['waktu']);	
						$lokasi			= cleantext($_POST['lokasi']);
						$background 	= cleantext($_POST['background']);	
						$color 			= cleantext($_POST['color']);
						$tanggal		= date('Y-m-d H:i:s');
						$seftitle		= seo($judul);
						$pengirim		= $_SESSION['username'];
						$error			= '';
						
						if (!$judul)     	$error .= "Error: Silahkan Isi Judul nya!<br />";
						if (!$lokasi)     	$error .= "Error: Silahkan Isi Lokasinya nya!<br />";
						if (!$waktu_mulai)  $error .= "Error: Silahkan Isi tanggal waktu mulainya nya!<br />";
						if (!$keterangan)   $error .= "Error: Silahkan Isi Keterangan nya!<br />";
						if (!$waktu)     	$error .= "Error: Silahkan Isi Waktunya nya!<br />";
						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{								
	
							$keterangan = escape($keterangan);
							$judul		= escape($judul);
							if(empty($waktu_akhir)){
								$waktu_akhir = $waktu_mulai;
							}
							$hasil 		= $db->sql_query("INSERT INTO `tbl_kalender` (`judul`,`tanggal`,`waktu_mulai`,`waktu_akhir`,`waktu`,`isi`,`lokasi`,`pengirim`,`background`,`color`,`seftitle`) VALUES ('$judul','$tanggal','$waktu_mulai','$waktu_akhir','$waktu','$keterangan','$lokasi','$pengirim','$background','$color','$seftitle')");
				
							if($hasil){
								$tengah .= '<div class="success">Agenda Kegiatan Berhasil dimasukkan ke database</div>';
								unset($judul);
								unset($keterangan);
								$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=calendar" />';
							}else{
								$tengah .= '<div class="error">'.mysql_error().'</div>';
							}
						}
						
					}
					$judul 		   = !isset($judul) ? '' : $judul;
					$keterangan	   = !isset($keterangan) ? '' : $keterangan;
					$waktu_mulai   = !isset($waktu_mulai) ? '' : $waktu_mulai;
					$waktu_akhir   = !isset($waktu_akhir) ? '' : $waktu_akhir;
					$waktu 		   = !isset($waktu) ? '' : $waktu;
					$background    = !isset($background) ? '' : $background;
					$color 		   = !isset($color) ? '' : $color;
					$lokasi		   = !isset($lokasi) ? '' : $lokasi;
					
					
					$tengah .= '
					<div class="border rb">
						<form method="post" action="" enctype ="multipart/form-data">
						<table border="0" cellspacing="0" cellpadding="0" id="table1">
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Nama Kegiatan</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="judul" size="20" value="'.$judul.'"></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Tanggal Kegiatan</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" id="datepicker1" name="waktu_mulai" size="10" value="'.$waktu_mulai.'"> s.d <input type="text" id="datepicker2" name="waktu_akhir" size="10" value="'.$waktu_akhir.'"></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Waktu Kegiatan</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="waktu" size="20" value="'.$waktu.'"> Contoh : Pukul 08.00 WIB</td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Background Color</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="background" size="5" value="'.$background.'" onblur="return ubahbackground(\'ubahwarna\',this);"> <span id="ubahwarna" style="background:#efefef">&nbsp;&nbsp;<span id="tulisanwarna">16</span>&nbsp;&nbsp;</span></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Warna Tulisan</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="color" size="5" value="'.$color.'" onblur="return ubahcolor(\'tulisanwarna\',this);"></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">Keterangan</td>
								<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><textarea rows="11" name="keterangan" cols="43">'.$keterangan.'</textarea></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Lokasi</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="lokasi" size="20" value="'.$lokasi.'"></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 15px">&nbsp;</td>
								<td style="padding-left: 5px; padding-top: 15px">&nbsp;</td>
								<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Submit</button></td>
							</tr>
						</table>
						</form>
					</div>
				</div>
			</div>
		</div>';
		
		
	}
	
	if($_GET['action'] =='edit'){
		$id 	 = int_filter($_GET['id']);
		$referer = $_GET['referer'];
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Edit <span class="styled1">Agenda Kegiatan</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=guru" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Agenda Kegiatan</div>
		</div>';
		
		if(isset($_POST['submit'])){
			$judul 			= cleantext($_POST['judul']);	
			$waktu_mulai 	= cleantext($_POST['waktu_mulai']);	
			$waktu_akhir 	= cleantext($_POST['waktu_akhir']);	
			$keterangan		= $_POST['keterangan'];
			$waktu			= cleantext($_POST['waktu']);	
			$lokasi			= cleantext($_POST['lokasi']);
			$background 	= cleantext($_POST['background']);	
			$color 			= cleantext($_POST['color']);
			$tanggal		= date('Y-m-d H:i:s');
			$seftitle		= seo($judul);
			$pengirim		= $_SESSION['username'];
			$error			= '';
						
			if (!$judul)     	$error .= "Error: Silahkan Isi Judul nya!<br />";
			if (!$lokasi)     	$error .= "Error: Silahkan Isi Lokasinya nya!<br />";
			if (!$waktu_mulai)  $error .= "Error: Silahkan Isi tanggal waktu mulainya nya!<br />";
			if (!$keterangan)     		$error .= "Error: Silahkan Isi Keterangan nya!<br />";
			if (!$waktu)     	$error .= "Error: Silahkan Isi Waktunya nya!<br />";
						
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{		
				$keterangan = escape($keterangan);
				$judul		= escape($judul);
				$success = $db->sql_query("UPDATE `tbl_kalender` SET `judul`='$judul',`waktu`='$waktu',`waktu_mulai`='$waktu_mulai',`waktu_akhir`='$waktu_akhir',`isi`='$keterangan',`lokasi`='$lokasi',`background`='$background',`color`='$color',`pengirim`='$pengirim',`tanggal`='$tanggal',`seftitle`='$seftitle' WHERE `id`='$id'");
				if($success){
					$tengah .= '<div class="success">Agenda Has Been Update</div>';
					$style_include[] = '<meta http-equiv="refresh" content="0; url='.$referer.'" />';
				}else{
					$tengah .= '<div class="error">'.mysql_error().'</div>';								
				}
							
			}						
		}
		$data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `tbl_kalender` WHERE `id`='$id'"));
		$judul 			= $data['judul'];
		$waktu 			= $data['waktu'];
		$waktu_mulai 	= $data['waktu_mulai'];
		$waktu_akhir 	= $data['waktu_akhir'];
		$keterangan		= $data['isi'];
		$lokasi 		= $data['lokasi'];
		$background 	= $data['background'];
		$color 			= $data['color'];

		$tengah .= '
		<div class="border rb">
			<form method="post" action="" enctype ="multipart/form-data">
			<table border="0" cellspacing="0" cellpadding="0" id="table1">
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Nama Kegiatan</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="judul" size="20" value="'.$judul.'"></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Tanggal Kegiatan</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" id="datepicker1" name="waktu_mulai" size="10" value="'.$waktu_mulai.'"> s.d <input type="text" id="datepicker2" name="waktu_akhir" size="10" value="'.$waktu_akhir.'"></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Waktu Kegiatan</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="waktu" size="20" value="'.$waktu.'"> Contoh : Pukul 08.00 WIB</td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Background Color</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="background" size="5" value="'.$background.'" onblur="return ubahbackground(\'ubahwarna\',this);"> <span id="ubahwarna" style="background:#efefef">&nbsp;&nbsp;<span id="tulisanwarna">16</span>&nbsp;&nbsp;</span></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Warna Tulisan</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="color" size="5" value="'.$color.'" onblur="return ubahcolor(\'tulisanwarna\',this);"></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">Keterangan</td>
					<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><textarea rows="11" name="keterangan" cols="43">'.$keterangan.'</textarea></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Lokasi</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="lokasi" size="20" value="'.$lokasi.'"></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 15px">&nbsp;</td>
					<td style="padding-left: 5px; padding-top: 15px">&nbsp;</td>
					<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Update</button></td>
				</tr>
			</table>
			</form>
		</div>';
		
	}

	if($_GET['action'] =='delete'){
		$id = int_filter($_GET['id']);
			
		$delete = $db->sql_query("DELETE FROM `tbl_kalender` WHERE `id` = '$id'");
		if ($delete) {
			$referer = $_GET['referer'];
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}


	echo $tengah;