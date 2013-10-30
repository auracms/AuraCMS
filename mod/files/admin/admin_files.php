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

	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;
	
	function escape ($value){
		$b = mysql_real_escape_string($value);
		return $b;
	}
	
	if (!function_exists('fsize')){
		function fsize($zahl) {
			//Creates Filesize-Info
			//number_format($_FILES["wcsfile"]["size"] / 1024, 0, ',', '.')." kB)
			if($zahl < 1000) {
				$zahl = $zahl."";
			} else {
				if($zahl < 1048576) {
					$zahl = number_format($zahl/1024, 1, '.', '.')."&nbsp;Kb";
				} else {
					$zahl = number_format($zahl/1048576, 1, '.', '.')."&nbsp;Mb";
				}
			}
			return $zahl;
		}
	}
	
	define('files','files/');
	
	if (!function_exists('format_size')){
		function format_size($file){
			$get_file_size = filesize(files.$file);
			$get_file_size = number_format($get_file_size / 1024,1);
			return $get_file_size.' kb';
		}
	}

	
	if($_GET['action'] ==''){
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Files <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=files" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Files Manager</div>
		</div>		
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Home</a></li>
					<li><a href="#tabs-2">Add Files</a></li>
				</ul>
				<div id="tabs-1">';
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Name File</td>
			            <td class="left">Ukuran File</td>
			            <td style="text-align: center;width:100px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
			        
			        $rep = opendir(files);
					$no = 1;
					while ($file = readdir($rep)) {
						if($file != '..' && $file !='.' && $file !='' && format_size($file)!='0.0 kb'){
							if (is_dir($file)){
								continue;
							}else {
								if ($file !='index.php'){
								$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
								$tengah .= '
								<tr '.$warna.'>
									<td style="text-align: center;">'.$no.'</td>
									<td class="left">'.$file.'</td>
									<td class="center">'.format_size($file).'</td>
									<td style="text-align: center;"><a href="admin.php?mod=files&amp;action=delete&amp;file='.$file.'">Hapus</a></td>
								</tr>';
								$no++;
								}
							}
						}
					
					}
					closedir($rep);
					clearstatcache();
				$tengah .= '
					</tbody>
				</table>
				</div>';
				
				$tengah .= '
				</div>
				<div id="tabs-2">';
		  
					if(isset($_POST['submit'])){
						$_FILES['gambar']['name'] = is_array(@$_FILES['gambar']['name']) ? @$_FILES['gambar']['name'] : array ();	
						for ($e=0; $e<=count(@$_FILES['gambar']['name']); $e++){
							if (!empty ($_FILES['gambar']['name'][$e])){												
								$tmp 	= $_FILES['gambar']['tmp_name'][$e];
								$finame = $_FILES['gambar']['name'][$e];
								$fileType = $_FILES['gambar']['type'][$e];
								$allowed = array('image/gif', 'image/jpeg', 'image/jpg','image/png');
								if (in_array($fileType, $allowed)) {
						
								if (move_uploaded_file($tmp, files . $finame)) {
									$tengah .= '<div class="success">File berhasil di Upload</div>';
									$style_include[] ='<meta http-equiv="refresh" content="1; url=admin.php?mod=files" />';
								}	
								}else{
									$tengah .= '<div class="error">File Type ini tidak di izinkan</div>';
								}							
																	
							}
						}						
					}
				
					
					$tengah .= '
					<div class="border rb">
						<form method="post" action="" enctype ="multipart/form-data">
						<table border="0" cellspacing="0" cellpadding="0" id="table1">
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">File 1</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="gambar[]" size="20" /></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">File 2</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="gambar[]" size="20" /></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">File 3</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="gambar[]" size="20" /></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">File 4</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="gambar[]" size="20" /></td>
							</tr>
							<tr>
								<td style="padding-left: 5px; padding-top: 5px">File 5</td>
								<td style="padding-left: 5px; padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="gambar[]" size="20" /></td>
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
	
	
	if($_GET['action'] =='delete'){
		$file = text_filter ($_GET['file']);		
		if($file) {
			unlink(files.$file);
			$tengah .= '<div class="success">File <strong>'.$file.'</strong> Berhasil Dihapus</div>';	
			$style_include[] ='<meta http-equiv="refresh" content="0; url=admin.php?mod=files" />';		
		}
		header("location: admin.php?mod=files");
	}
	
echo $tengah;