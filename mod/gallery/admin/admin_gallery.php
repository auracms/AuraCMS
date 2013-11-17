<?php
	
	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;
	

	$tengah  = '';

	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;
	
	function escape ($value){
		$b = mysql_real_escape_string($value);
		return $b;
	}
	
	$jp 		 	= $db->sql_query("SELECT * FROM `mod_gallery_album` WHERE `published`='1' ORDER BY `album` ASC");
	$album_array 	= array();

	while($data = $db->sql_fetchrow($jp)) {
		$album_array[$data['id']] = $data['album'];
	}
	
	

	function select($gallery,$selected,$value 	= array()) {
		
	
		$a  = '<select name="'.$gallery.'" size="1">'; 
		$a .= '<option value="">-- Pilih '.ucwords($gallery).' --</option>';
		if (is_array ($value)){
			foreach ($value as $k => $v) {
					
				if ($k == $selected){
					$a .= '<option value="'.$k.'" selected>'.$v.'</option>';
				}else {
					$a .= '<option value="'.$k.'">'.$v.'</option>';
				}		
			}		
		}  
		   
		$a .= '</select>';
		
		return $a;
	}
	
	$script_include[] = <<<js
	<script type="text/javascript" src="plugin/jquery-lightbox-0.5/js/jquery.lightbox-0.5.js"></script>
	<link rel="stylesheet" type="text/css" href="plugin/jquery-lightbox-0.5/css/jquery.lightbox-0.5.css" media="screen" />
	<script type="text/javascript">
	$(function() {
		$('#gallery .lightbox').lightBox({fixedNavigation:true});
	});
	</script>
js;
	
	if($_GET['action'] ==''){
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Gallery <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=gallery" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Gallery Manager</div>
		</div>		
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#tabsgallery-1">Home</a></li>
					<li><a href="#tabsgallery-2">Upload Photo</a></li>
					<li><a href="#tabsgallery-3">Album Gallery</a></li>
					<li><a href="#tabsgallery-4">Album Masuk</a></li>
					<li><a href="#tabsgallery-5">Photo Masuk</a></li>
				</ul>
				<div id="tabsgallery-1">';
				$ab 	= $db->sql_query("SELECT `mod_gallery`.*,`mod_gallery_album`.`album` FROM `mod_gallery` LEFT JOIN `mod_gallery_album` ON (`mod_gallery_album`.`id` = `mod_gallery`.`album_id`) WHERE `mod_gallery`.`published`='1' ORDER BY `tanggal` DESC");

				$jumlah = $db->sql_numrows($ab);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 	= $db->sql_query("SELECT `mod_gallery`.*,`mod_gallery_album`.`album` FROM `mod_gallery` LEFT JOIN `mod_gallery_album` ON (`mod_gallery_album`.`id` = `mod_gallery`.`album_id`) WHERE `mod_gallery`.`published`='1' ORDER BY `tanggal` DESC LIMIT $offset,$limit");

				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb" id="gallery">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="center">Thumbnail</td>
			            <td class="center">Created</td>
			            <td class="center">Album</td>
			            <td class="center">Published</td>
			            <td style="text-align: center;width:60px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				while($data = $db->sql_fetchrow($b)){
					$warna 		= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$published  = ($data['published'] == 1) ? '<a class="enable" href="?mod=gallery&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=gallery&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="center"><a class="lightbox" title="'.$data['caption'].'" href="'.normal.$data['images'].'"><img src="images/thumb/'.$data['images'].'" alt="'.$data['caption'].'" border="0" /></td>
			            <td class="center">'.datetimes($data['tanggal'],false).'</td>
			            <td class="center">'.$data['album'].'</td>
			            <td class="center">'.$published.'</td>
			            <td style="text-align: center;"><a class="delete" href="admin.php?mod=gallery&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
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
				<div id="tabsgallery-2">';
		  
					if(isset($_POST['submit'])){

						$album_id	= int_filter($_POST['album']);
						$keterangan	= escape($_POST['keterangan']);
						$tanggal	= date('Y-m-d H:i:s');
						
						
						$error		= '';
						
						if (!$album_id)  		$error .= "Error: Please input Select Nama Album nya.<br />";
						if (!$keterangan)		$error .= 'Error: Please input Input Keterangan nya.<br />';
						if (empty ($_FILES['images']['name'])){
							$error .= 'Error: Please Browse Images.<br />';
						}
						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{	
							
							
							include 'includes/resize.php';	
							$tmp 	= $_FILES['images']['tmp_name'];
							$finame = $_FILES['images']['name'];
							$finame = str_replace (' ', '-', $finame);
							$finame = time() . '-' . $finame;	
								
							if (move_uploaded_file($tmp, tmp . $finame)) {
								$image = new SimpleImage();
								$image->load(tmp . $finame);									
								$image->resizeToWidth(500);
				   				$image->save(normal.$finame);
				   				$image->resize(128,96);
								$image->save(thumb.$finame);
							}
						
							$success = $db->sql_query("INSERT INTO `mod_gallery` (`images`,`album_id`,`caption`,`tanggal`,`published`) VALUES ('$finame','$album_id','$keterangan','$tanggal','1')");
							if($success){
								$tengah .= '<div class="success">Gallery Photo Has Been Added in Database</div>';
								$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=gallery" />';
								unset($album_id);
								unset($keterangan);
								unlink(tmp . $finame);
						
							}else{
								$tengah .= '<div class="error">'.mysql_error().'</div>';
								unlink(tmp . $finame);
								unlink(thumb . $finame);
								unlink(normal . $finame);
							}
						}
						
					}
					$album_id		= !isset($album_id) ? '' : $album_id;
					$keterangan		= !isset($keterangan) ? '' : $keterangan;
					
					
					$tengah .= '
					<div class="border rb">
						<form method="post" action="" enctype ="multipart/form-data">
						<table border="0" cellspacing="0" cellpadding="0" id="table1">
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Nama Album</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px">'.select('album',$album_id,$album_array).'</td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">Photo</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="images" size="20"></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">Keterangan</td>
								<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><textarea rows="3" name="keterangan" cols="50">'.$keterangan.'</textarea></td>
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
				<div id="tabsgallery-3">';
					$nj = 1;
						
					$bj 		= $db->sql_query("SELECT * FROM `mod_gallery_album` WHERE `published`='1' ORDER BY `album` ASC");
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$tengah .= '
					<div class="border rb" style="margin-bottom:15px;">
					<table class="list">
						<thead>
					    	<tr class="head">
					        	<td style="text-align: center;width:30px;">No</td>
					           	<td class="left">Nama Album</td>
					           	<td class="center">Created On</td>
					           	<td class="center">Published</td>
								<td style="text-align: center;width:80px;">Action</td>
							</tr>
						</thead>
						<tbody>';
						while($data = $db->sql_fetchrow($bj)){
							$warna  	= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
							$published  = ($data['published'] == 1) ? '<a class="enable" href="?mod=gallery&amp;action=pub&amp;pubalbum=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=gallery&amp;action=pub&amp;pubalbum=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';
							$tengah .= '
							<tr'.$warna.'>
					            <td style="text-align: center;">'.$nj.'</td>
					            <td class="left">'.$data['album'].'</td>
					            <td class="center">'.datetimes($data['tanggal'],false).'</td>
					            <td class="center">'.$published.'</td>
					            <td style="text-align: center;"><a href="admin.php?mod=gallery&amp;action=editalbum&amp;id='.$data['id'].'&amp;referer='.$ref.'" class="edit" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=gallery&amp;action=deletealbum&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
							</tr>';
							$nj++;					
						}
					$tengah .= '
						</tbody>
					</table>
					</div>';
					
					if(isset($_POST['submitalbum'])){
	
						$album 		= text_filter($_POST['album']);
						$seftitle	= seo($album);
						$tanggal	= date('Y-m-d H:i:s');
						$error	= '';
						
						if (!$album)		$error .= "Error: Please input Nama Album.<br />";
						
						if($error){
							$tengah .= '<div class="error rt" style="margin-bottom:15px;padding-left:50px;">'.$error.'</div>';
						}else{	
							$success = $db->sql_query("INSERT INTO `mod_gallery_album` (`album`,`tanggal`,`seftitle`,`published`) VALUES ('$album','$tanggal','$seftitle','1')");
							if($success){
								$tengah .= '<div class="success rt" style="margin-bottom:15px;padding-left:50px;">Nama Album Has Been Added in Database</div>';
								header("location: admin.php?mod=gallery");
								exit;
								unset($album);
							}else{
								$tengah .= '<div class="error rt" style="margin-bottom:15px;padding-left:50px;">'.mysql_error().'</div>';
							}
						}
						
					}
			
					$album 		= !isset($album) ? '' : $album;
				
					$tengah .= '
					<div class="border rb">
			        <form action="" method="post">
			        <table border="0" cellspacing="0" cellpadding="0" id="table1">
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Nama Album</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="album" size="35" value="'.$album.'" /></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
							<td style="padding-top: 15px">&nbsp;</td>
							<td style="padding-left: 5px; padding-top: 15px"><button name="submitalbum" class="primary"><span class="icon plus"></span>Submit</button></td>
						</tr>
					</table>    	                 
			        </form>
					</div>
				</div>
				<div id="tabsgallery-4">';
					$nj = 1;
						
					$bj 		= $db->sql_query("SELECT * FROM `mod_gallery_album` WHERE `published`='0' ORDER BY `album` ASC");
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$tengah .= '
					<div class="border rb" style="margin-bottom:15px;">
					<table class="list">
						<thead>
					    	<tr class="head">
					        	<td style="text-align: center;width:30px;">No</td>
					           	<td class="left">Nama Album</td>
					           	<td class="center">Created On</td>
					           	<td class="center">Published</td>
								<td style="text-align: center;width:80px;">Action</td>
							</tr>
						</thead>
						<tbody>';
						while($data = $db->sql_fetchrow($bj)){
							$warna  = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
							$published  = ($data['published'] == 1) ? '' : '<a class="disable" href="?mod=gallery&amp;action=pub&amp;pubalbum=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';
							
							$tengah .= '
							<tr'.$warna.'>
					            <td style="text-align: center;">'.$nj.'</td>
					            <td class="left">'.$data['album'].'</td>
					            <td class="center">'.datetimes($data['tanggal'],false).'</td>
					            <td class="center">'.$published.'</td>
					            <td style="text-align: center;"><a href="admin.php?mod=gallery&amp;action=editalbum&amp;id='.$data['id'].'&amp;referer='.$ref.'" class="edit" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=gallery&amp;action=deletealbum&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
							</tr>';
							$nj++;					
						}
					$tengah .= '
						</tbody>
					</table>
					</div>					
				</div>
				<div id="tabsgallery-5">';
					$ab 	= $db->sql_query("SELECT `mod_gallery`.*,`mod_gallery_album`.`album` FROM `mod_gallery` LEFT JOIN `mod_gallery_album` ON (`mod_gallery_album`.`id` = `mod_gallery`.`album_id`) WHERE `mod_gallery`.`published`='0' ORDER BY `tanggal` DESC");
	
					$jumlah = $db->sql_numrows($ab);
					$limit 	= 15;
					
					$a 		= new paging ($limit);
	
					if(isset($offset)){
						$no = $offset + 1;
					}else{
						$no = 1;
					}
					
					$b 	= $db->sql_query("SELECT `mod_gallery`.*,`mod_gallery_album`.`album` FROM `mod_gallery` LEFT JOIN `mod_gallery_album` ON (`mod_gallery_album`.`id` = `mod_gallery`.`album_id`) WHERE `mod_gallery`.`published`='0' ORDER BY `tanggal` DESC LIMIT $offset,$limit");
	
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$tengah .= '
					<div class="border rb" id="gallery">
					<table class="list">
				        <thead>
				          <tr class="head">
				            <td style="text-align: center;width:30px;">No</td>
				            <td class="center">Thumbnail</td>
				            <td class="center">Created</td>
				            <td class="center">Album</td>
				            <td class="center">Published</td>
				            <td style="text-align: center;width:60px;">Action</td>
				          </tr>
				        </thead>
				        <tbody>';
					while($data = $db->sql_fetchrow($b)){
						$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
						$published = ($data['published'] == 1) ? '<a class="enable" href="?mod=gallery&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=gallery&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

						$tengah .= '
						<tr'.$warna.'>
				            <td style="text-align: center;">'.$no.'</td>
				            <td class="center"><a class="lightbox" title="'.$data['caption'].'" href="'.normal.$data['images'].'"><img src="thumb.php?img='.$data['images'].'&w=96&t=yes" alt="'.$data['caption'].'" border="0" /></td>
				            <td class="center">'.datetimes($data['tanggal'],false).'</td>
				            <td class="center">'.$data['album'].'</td>
				            <td class="center">'.$published.'</td>
				            <td style="text-align: center;"><a class="delete" href="admin.php?mod=gallery&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
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
			</div>
		</div>';
		
		
	}
	
		
	if($_GET['action'] =='edit'){
		$id 	 = int_filter($_GET['id']);
		$referer = $_GET['referer'];
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Edit <span class="styled1">Gallery</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=gallery" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Gallery Manager</div>
		</div>';
		
		if(isset($_POST['submit'])){
			$gallery 	= text_filter($_POST['gallery']);
			$peringkat 	= text_filter($_POST['peringkat']);
			$siswa		= text_filter($_POST['siswa']);
			$album_id	= int_filter($_POST['album']);
			$keterangan	= escape($_POST['keterangan']);
			$tanggal	= text_filter($_POST['tanggal']);
			$gambarlama	= $_POST['gambarlama'];
			$seftitle	= seo($peringkat.' '.$siswa.' '.$tanggal);
						
			$error		= '';
						
			if (!$gallery)  		$error .= "Error: Please input Nama Gallery nya.<br />";
			if (!$siswa)			$error .= "Error: Please input Nama Siswa nya.<br />";
			if (!$peringkat)		$error .= "Error: Please input Peringkat nya.<br />";
			if (!$album_id)  		$error .= "Error: Please input Select Nama Album nya.<br />";
			if (!$tanggal)			$error .= "Error: Please input Tanggal nya.<br />";
			if (!$keterangan)		$error .= 'Error: Please input Input Keterangan nya.<br />';
						
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	
				
				if (!empty ($_FILES['images']['name'])){
					include 'includes/resize.php';	
					$tmp 	= $_FILES['images']['tmp_name'];
					$finame = $_FILES['images']['name'];
					$finame = str_replace (' ', '-', $finame);
					$finame = time() . '-' . $finame;	
								
					if (move_uploaded_file($tmp, tmp . $finame)) {
						$image = new SimpleImage();
						$image->load(tmp . $finame);									
						$image->resizeToWidth(500);
				   		$image->save(normal.$finame);
				   		$image->resize(128,96);
						$image->save(thumb.$finame);
					}
				}else{
					$finame = '';
				}
				
				if (!empty ($_FILES['images']['name'])){			
					$success = $db->sql_query("UPDATE `mod_gallery` SET `gallery`='$gallery',`siswa`='$siswa',`album_id`='$album_id',`peringkat`='$peringkat',`tanggal`='$tanggal',`keterangan`='$keterangan',`seftitle`='$seftitle',`images`='$finame' WHERE `id`='$id'");
					if($success){
						$tengah .= '<div class="success">Gallery Has Been Update</div>';
						unlink(tmp . $finame);
						unlink(thumb . $gambarlama);
						unlink(thumb . $gambarlama);
						header("location: $referer");
						exit;
					}else{
						$tengah .= '<div class="error">'.mysql_error().'</div>';	
						unlink(tmp . $finame);							
					}
				}else{
					$success = $db->sql_query("UPDATE `mod_gallery` SET `gallery`='$gallery',`siswa`='$siswa',`album_id`='$album_id',`peringkat`='$peringkat',`tanggal`='$tanggal',`keterangan`='$keterangan',`seftitle`='$seftitle' WHERE `id`='$id'");
					if($success){
						$tengah .= '<div class="success">Gallery Has Been Update</div>';
						header("location: $referer");
						exit;
					}else{
						$tengah .= '<div class="error">'.mysql_error().'</div>';								
					}
				}
			}
						
		}
		$data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_gallery` WHERE `id`='$id'"));
		$gallery 		= $data['gallery'];
		$siswa 			= $data['siswa'];
		$peringkat 		= $data['peringkat'];
		$album_id		= $data['album_id'];
		$tanggal		= $data['tanggal'];
		$keterangan		= $data['keterangan'];
		$gambarlama		= $data['images'];
		$images 		= ($data['images'] == '') ? '<img src="images/nophoto.png" alt="'.$data['siswa'].'" border="0" style="padding: 5px; width:150px;height:200px;border: 1px solid #d1eac3; background: #f1f8ed;margin-right:15px;" />' : '<img src="images/thumb/'.$data['images'].'" border="0" alt="'.$data['siswa'].'" style="padding: 5px; width:150px;height:200px;border: 1px solid #d1eac3; background: #f1f8ed;margin-right:15px;" />';
		

		$tengah .= '
		<div class="border rb">
			<form method="post" action="" enctype ="multipart/form-data">
			<table border="0" cellspacing="0" cellpadding="0" id="table1">
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Nama Siswa</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="siswa" size="40" value="'.$siswa.'"></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px"></td>
					<td style="padding-top: 5px"></td>
					<td style="padding-left: 5px; padding-top: 5px">'.$images.'</td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Photo Siswa</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="images" size="20"><input type="hidden" name="gambarlama" size="20" value="'.$gambarlama.'" /></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Nama Gallery</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="gallery" size="40" value="'.$gallery.'"></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Peringkat Gallery</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="peringkat" size="20" value="'.$peringkat.'"></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Nama Album</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px">'.select('album',$album_id,$album_array).'</td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px">Tanggal Gallery</td>
					<td style="padding-left: 5px; padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input id="tanggal_lahir" type="text" name="tanggal" size="8" value="'.$tanggal.'"></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">Keterangan</td>
					<td style="padding-left: 5px; padding-top: 5px;vertical-align:top;">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><textarea rows="5" name="keterangan" cols="50">'.$keterangan.'</textarea></td>
				</tr>
				<tr>
					<td style="padding-left: 5px; padding-top: 15px">&nbsp;</td>
					<td style="padding-left: 5px; padding-top: 15px">&nbsp;</td>
					<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Submit</button></td>
				</tr>
			</table>
			</form>
		</div>';
	}
	
	if($_GET['action'] =='editalbum'){
		$id 	 = int_filter($_GET['id']);
		$referer = $_GET['referer'];
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-gallery">Edit <span class="styled1">Nama Album</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=gallery" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit Nama Album</div>
		</div>';
		
		if(isset($_POST['submit'])){
	
			$album 	= text_filter($_POST['album']);
			$error		= '';
				
			if (!$album)		$error .= "Error: Please input Nama Album nya.<br />";
				
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	
				$success = $db->sql_query("UPDATE `mod_gallery_album` SET `album`='$album' WHERE `id`='$id'");
				if($success){
					$tengah .= '<div class="success">Nama Album Has Been Update</div>';
					header("location: $referer");
					exit;
				}else{
					$tengah .= '<div class="error">'.mysql_error().'</div>';
				}
			}
				
		}
	
		$data 		= $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_gallery_album` WHERE `id`='$id'"));
		$album 	= $data['album'];
		
		$tengah .= '
		<div class="border rb">
		<form action="" method="post">
		<table border="0" cellspacing="0" cellpadding="0" id="table1">
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Nama Album </td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="album" value="'.$album.'" /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
				<td style="padding-top: 15px">&nbsp;</td>
				<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Update</button></td>
			</tr>
		</table>    	                 
	    </form>
		</div>';
		
	}
	
	if ($_GET['action'] == 'pub'){	
		if ($_GET['pub'] == 'no'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_gallery` SET `published`='0' WHERE `id`='$id'");		
		}	
		
		if ($_GET['pub'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_gallery` SET `published`='1' WHERE `id`='$id'");		
		}	
		
		if ($_GET['pubalbum'] == 'no'){	
			$id = int_filter ($_GET['id']);	
			$a = $db->sql_query ("UPDATE `mod_gallery_album` SET `published`='0' WHERE `id`='$id'");
		}	
		
		if ($_GET['pubalbum'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_gallery_album` SET `published`='1' WHERE `id`='$id'");				
		}
		
		$referer = $_GET['referer'];
		header("location: $referer");
		exit;
	}
	
	if($_GET['action'] == 'delete'){
		$id 	= int_filter($_GET['id']);
		$query	= $db->sql_query("SELECT * FROM `mod_gallery` WHERE `id`='$id'");
		while($data 	= $db->sql_fetchrow($query)){
			$images	= $data['images'];
			unlink(thumb.$images);
			unlink(normal.$images);
		}
		$delete = mysql_query("DELETE FROM `mod_gallery` WHERE `id` = '$id'");
		if ($delete) {
			$referer = $_GET['referer'];
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
	if($_GET['action'] == 'deletealbum'){
		$id 	= int_filter($_GET['id']);
		$query	= $db->sql_query("SELECT * FROM `mod_gallery` WHERE `album_id`='$id'");
		while($data 	= $db->sql_fetchrow($query)){
			$images	= $data['images'];
			unlink(thumb.$images);
			unlink(normal.$images);
		}
		$delete  = mysql_query("DELETE FROM `mod_gallery_album` WHERE `id` = '$id'");
		$delete .= mysql_query("DELETE FROM `mod_gallery` WHERE `album_id`='$id'");
		if ($delete) {
			$referer = $_GET['referer'];
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}


echo $tengah;