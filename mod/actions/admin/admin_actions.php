<?php
	
	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;


	function select($nama,$selected,$value 	= array()) {
		
	
		$a  = '<select name="'.$nama.'" size="1">'; 
		$a .= '<option value="">-- Pilih --</option>';
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
	
	if(!function_exists('dir_modul')){
		function dir_modul($modul="") {
			global $content;
			$allowed = array('menu', 'actions', 'setting','files','optimize');
			$dir = opendir("mod");
			$content = '<option value="">-- Pilih Modul --</option>';
			while ($file = readdir($dir)) {
				if (!preg_match("/\./", $file)) {
					$selected = ($modul == $file) ? "selected" : "";
					if(in_array($file, $allowed)){
					}else{						
						$content .= '<option value="'.$file.'" '.$selected.'>'.$file.'</option>';
					}
				}
			}
			closedir($dir);
			return $content;
		}
	}
	
	$position_array = array(
			   '1' => 'Sidebar Kanan',
			   '0' => 'Sidebar Kiri'
			   );
			   
	$qa 	 = $db->sql_query("SELECT * FROM `mod_modul` ORDER BY `ordering`");
	$handler = array();

	while($data = $db->sql_fetchrow($qa)) {
		$published = $data['published'] ? 'publish' : 'no publish';
		$handler[$data['id']] = $data['modul'].' - '.$published;
	}
	

	$tengah  = '';
	
	
	if($_GET['action'] ==''){

		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Actions <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=actions" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Action Manager</div>
		</div>
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#users-1">Home</a></li>
					<li><a href="#users-2">Add Actions</a></li>
				</ul>
				<div id="users-1">
					<div class="border rb">
					<table class="list">
					<thead>
						<tr class="head">
							<td style="text-align: center;">No.</td>
							<td style="text-align: left;">Nama Modul Action</td>
							<td style="text-align: center;">View</td>
							<td style="text-align: center;">Action</td>
						</tr>
					</thead>
					<tbody>';
					
					$no = 1;
									
					$ref   = urlencode($_SERVER['REQUEST_URI']);
					$query = $db->sql_query("SELECT * FROM `mod_actions` GROUP BY `modul`");
					while($data = mysql_fetch_assoc($query)) {
						$warna 	 = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
						$tengah .= '
						<tr'.$warna.'>
							<td class="center">'.$no.'</td>
							<td class="left">'.$data['modul'].'</td>
							<td style="text-align: center;"><a href="admin.php?mod=actions&amp;action=view&amp;modul='.$data['modul'].'">View</a></td>
							<td style="text-align:center;"><a class="edit" href="admin.php?mod=actions&amp;action=edit&amp;id='.$data['id'].'&amp;referer='.$ref.'">Edit</a> <a class="delete" href="admin.php?mod=actions&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" onclick="return confirm(\'Apakah anda yakin ?\')">Delete</a></td></tr>';
						$no++;
					}
					$tengah .= '
					</tbody>
					</table>
					</div>';
				$tengah .= '
				</div>
				<div id="users-2">';
         	
				if(isset($_POST['submit'])){
					$modul 		= text_filter($_POST['modul']);
					$position 	= text_filter($_POST['position']);
					$modul_id	= text_filter($_POST['modul_id']);
					$error		= '';
					
					if (!$modul) 	$error .= "Error: Please input Name Of Modul.<br />";
					if (!$modul_id) $error .= "Error: Please input Select Block / Modul<.<br />";
					
					if($error){
						$tengah .= '<div class="error">'.$error.'</div>';
					}else{	

						$qc 	= $db->sql_query("SELECT (MAX(`order`) + 1) AS `order` FROM `mod_actions` WHERE `position` = '$position' AND `modul` = '$modul'");
						$dqc 	= $db->sql_fetchrow($qc);
						$order 	= $dqc['order'];
						$success = $db->sql_query("INSERT INTO `mod_actions` (`modul`,`position`,`order`,`modul_id`) VALUES ('$modul','$position','$order','$modul_id')");
						if($success){
							$tengah .= '<div class="success">Actions Been Added in Database</div>';
							
							unset($modul);
							unset($position);
							unset($modul_id);
							$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=actions" />';
						}else{
							$tengah .= '<div class="error">'.mysql_error().'</div>';
						}					
					}
					
				}
				$modul 		= !isset($modul) ? '' : $modul;
				$position 	= !isset($position) ? '' : $position;
				$modul_id	= !isset($modul_id) ? '' : $modul_id;
		
				$tengah .= '
				<div class="border rb">
				<form method="post" action="" enctype ="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="0" id="table1">
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Nama Modul</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><select name="modul" size="1">'.dir_modul($modul).'</select></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Pilih Block / Modul</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px">'.select('modul_id',$modul_id,$handler).'</td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Posisi Block / Modul</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px">'.select('position',$position,$position_array).'</td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
							<td style="padding-top: 15px">&nbsp;</td>
							<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Submit</button></td>
						</tr>
				</table>		         	                       
		        </form>
		        </div>
		        </div>
			</div>
		</div>';

	}
	
	if($_GET['action'] =='view'){
	
		$modul = mysql_real_escape_string(strip_tags($_GET['modul']));

		if (isset($_GET['delete'])) {
			$id = intval($_GET['id']);
			$db->sql_query("DELETE FROM `mod_actions` WHERE `id` = '$id'");
		}


		if (isset($_POST['submit'])) {
			if (is_array($_POST['order'])) {
				foreach($_POST['order'] as $key=>$val) {
					$posisi = $_POST['position'][$key];
					$order 	= $_POST['order'][$key];
					$db->sql_query("UPDATE `mod_actions` SET `position` = '$posisi',`order` = '$order' WHERE `id` = '$key'");
				}
			}
		}
	
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Actions <span class="styled1">'.ucwords($modul).'</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=actions" id="home">Home</a>   &nbsp;&raquo;&nbsp;   View Action '.ucwords($modul).'</div>
		</div>';
		
		$tengah .= '
		<h2>Left Sidebar</h2>
		<form method="post" action="">
		<div class="border">
		<table class="list">
			<thead>
				<tr class="head">
					<td style="text-align: center;">No.</td>
					<td style="text-align: left;">Nama Block / Modul</td>
					<td style="text-align: center;">Position</td>
					<td style="text-align: center;">Ordering</td>
					<td style="text-align: center;">Action</td>
				</tr>
			</thead>
			<tbody>';
					
			$no = 1;
									
			$ref   = urlencode($_SERVER['REQUEST_URI']);
			$query = $db->sql_query("SELECT `mod_actions`.*,`mod_modul`.`modul` FROM `mod_actions` LEFT JOIN `mod_modul` ON (`mod_modul`.`id` = `mod_actions`.`modul_id`) WHERE `mod_actions`.`modul` = '$modul' AND `mod_actions`.`position` = '0' ORDER BY `mod_actions`.`order`");

			while($data = mysql_fetch_assoc($query)) {
				$warna 	 = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
				$position= $data['position'];
				$pos	 = 'position['.$data['id'].']';
				$tengah .= '
				<tr'.$warna.'>
					<td class="center">'.$no.'</td>
					<td class="left">'.$data['modul'].'</td>
					<td style="text-align: center;">'.select($pos,$position,$position_array).'</td>
					<td style="text-align: center;"><input type="text" name="order['.$data['id'].']" value="'.$data['order'].'" size="3" /></td>
					<td style="text-align:center;"><a class="delete" href="admin.php?mod=actions&amp;action=view&amp;modul='.$modul.'&amp;id='.$data['id'].'&amp;delete=1&amp;referer='.$ref.'" onclick="return confirm(\'Apakah anda yakin ?\')">Delete</a></td></tr>';
				$no++;
			}
		$tengah .= '
			</tbody>
		</table>
		</div>
		<h2>Right Sidebar</h2>
		<div class="border">
		<table class="list">
			<thead>
				<tr class="head">
					<td style="text-align: center;">No.</td>
					<td style="text-align: left;">Nama Block / Modul</td>
					<td style="text-align: center;">Position</td>
					<td style="text-align: center;">Ordering</td>
					<td style="text-align: center;">Action</td>
				</tr>
			</thead>
			<tbody>';
					
			$no = 1;
									
			$ref   = urlencode($_SERVER['REQUEST_URI']);
			$query = $db->sql_query("SELECT `mod_actions`.*,`mod_modul`.`modul` FROM `mod_actions` LEFT JOIN `mod_modul` ON (`mod_modul`.`id` = `mod_actions`.`modul_id`) WHERE `mod_actions`.`modul` = '$modul' AND `mod_actions`.`position` = '1' ORDER BY `mod_actions`.`order`");

			while($data = mysql_fetch_assoc($query)) {
				$warna 	 = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
				$position= $data['position'];
				$pos	 = 'position['.$data['id'].']';
				$tengah .= '
				<tr'.$warna.'>
					<td class="center">'.$no.'</td>
					<td class="left">'.$data['modul'].'</td>
					<td style="text-align: center;">'.select($pos,$position,$position_array).'</td>
					<td style="text-align: center;"><input type="text" name="order['.$data['id'].']" value="'.$data['order'].'" size="3" /></td>
					<td style="text-align:center;"><a class="delete" href="admin.php?mod=actions&amp;action=view&amp;modul='.$modul.'&amp;id='.$data['id'].'&amp;delete=1&amp;referer='.$ref.'" onclick="return confirm(\'Apakah anda yakin ?\')">Delete</a></td></tr>';
				$no++;
			}
		$tengah .= '
			</tbody>
		</table>
		</div>
		<button name="submit" class="primary"><span class="icon reload"></span>Update</button></form>';
	}


echo $tengah;
?>