<?php


	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;
	

	$script_include[] = <<<js
	<!-- TinyMCE -->
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/init.js"></script>
	<!-- /TinyMCE -->
js;

	
	
	function form_select($name,$value = array(),$att = 'size="1"',$request = 'post') {
		$content = '<select name="'.$name.'" '.$att.'>';
		$_request = ($request == 'post') ? @$_POST : @$_GET;
		if(is_array($value)) {
			foreach($value as $key=>$val){
				if (@$_request[$name] == $key) {
					$content .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';
				}else {
					$content .= '<option value="'.$key.'">'.$val.'</option>';
				}
			}
		}
		$content .= '</select>';		
		return $content;
	}
	
	$tengah  = '
	<div class="box">
		<h2 class="widget-title">Modul <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=modul" id="home">Home</a>   &nbsp;&raquo;&nbsp;   <a href="admin.php?mod=modul&amp;action=addmodul">Add Block Modul</a>   &nbsp;&raquo;&nbsp;   <a href="admin.php?mod=modul&amp;action=addblock">Add Block Content</a></div>
	</div>';
	
	if(!isset($_GET['action'])){
	
		if (isset($_POST['submit'])) {
		if (is_array($_POST['order'])) {
				foreach($_POST['order'] as $key=>$val) {
						$publish = $_POST['publish'][$key];
						$position = $_POST['position'][$key];
						$update = mysql_query("UPDATE `mod_modul` SET `ordering` = '$val',`published` = '$publish',`position` = '$position' WHERE `id` = '$key'");
					}
			}
		}
		$tengah .= '
		<div id="tabs">
			<ul>
				<li><a href="#tabs-2">Right Sidebar</a></li>
				<li><a href="#tabs-1">Left Sidebar</a></li>
			</ul>
			<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
			<div id="tabs-1">
				<div class="border rb">
				<table class="list">
				<thead>
					<tr class="head">
						<td style="text-align: center;">Title</td>
						<td style="text-align: center;">Published</td>
						<td style="text-align: center;">Position</td>
						<td style="text-align: center;">Order</td>
						<td style="text-align: center;">Type</td>
						<td style="text-align: center;">Action</td>
					</tr>
				</thead>
				<tbody>';
				$query = mysql_query("SELECT * FROM `mod_modul` WHERE `position` = '0' ORDER BY `ordering`");
				while($data = mysql_fetch_assoc($query)) {
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$select = '<select name="publish['.$data['id'].']">';
					if ($data['published'] == 1) {
						$select .= '<option value="1" selected="selected">yes</option>';
						$select .= '<option value="0">no</option>';
					}else {
						$select .= '<option value="1">yes</option>';
						$select .= '<option value="0" selected="selected">no</option>';
					}
					
					$select .= '</select>';
					
					$select1 = '<select name="position['.$data['id'].']">';
					if ($data['position'] == 0) {
						$select1 .= '<option value="0" selected="selected">kiri</option>';
						$select1 .= '<option value="1">kanan</option>';
					}else {
						$select1 .= '<option value="0">kiri</option>';
						$select1 .= '<option value="1" selected="selected">kanan</option>';
					}				
					$select1 .= '</select>';
					
					$tengah .= '<tr'.$warna.'><td class="left">'.limittxt($data['modul'],15).'</td><td style="text-align: center;">'.$select.'</td><td style="text-align: center;">'.$select1.'</td><td style="text-align: center;"><input type="text" name="order['.$data['id'].']" value="'.$data['ordering'].'" size="3" /></td><td style="text-align: center;">'.$data['type'].'</td><td style="text-align:center;"><a class="edit" href="admin.php?mod=modul&amp;action=edit&amp;id='.$data['id'].'">Edit</a> <a class="delete" href="admin.php?mod=modul&amp;action=delete&amp;id='.$data['id'].'" onclick="return confirm(\'Apakah anda yakin ?\')">Delete</a></td></tr>';
					
				}
				$tengah .= '
				</tbody>
				</table>
				<button name="submit" class="primary" style="margin-top:10px;"><span class="icon plus"></span>Update</button>
				</div>
			</div>
			<div id="tabs-2">
				<div class="border rb">
				<table class="list">
				<thead>
					<tr class="head">
						<td style="text-align: center;">Title</td>
						<td style="text-align: center;">Published</td>
						<td style="text-align: center;">Position</td>
						<td style="text-align: center;">Order</td>
						<td style="text-align: center;">Type</td>
						<td style="text-align: center;">Action</td>
					</tr>
				</thead>
				<tbody>';
				$query = mysql_query("SELECT * FROM `mod_modul` WHERE `position` = '1' ORDER BY `ordering`");
				while($data = mysql_fetch_assoc($query)) {
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$select = '<select name="publish['.$data['id'].']">';
					if ($data['published'] == 1) {
						$select .= '<option value="1" selected="selected">yes</option>';
						$select .= '<option value="0">no</option>';
					}else {
						$select .= '<option value="1">yes</option>';
						$select .= '<option value="0" selected="selected">no</option>';
					}
					
					$select .= '</select>';
					
					$select1 = '<select name="position['.$data['id'].']">';
					if ($data['position'] == 0) {
						$select1 .= '<option value="0" selected="selected">kiri</option>';
						$select1 .= '<option value="1">kanan</option>';
					}else {
						$select1 .= '<option value="0">kiri</option>';
						$select1 .= '<option value="1" selected="selected">kanan</option>';
					}				
					$select1 .= '</select>';
					
					$tengah .= '<tr'.$warna.'><td class="left">'.limittxt($data['modul'],15).'</td><td style="text-align: center;">'.$select.'</td><td style="text-align: center;">'.$select1.'</td><td style="text-align: center;"><input type="text" name="order['.$data['id'].']" value="'.$data['ordering'].'" size="3" /></td><td style="text-align: center;">'.$data['type'].'</td><td style="text-align: center;"><a class="edit" href="admin.php?mod=modul&amp;action=edit&amp;id='.$data['id'].'">Edit</a> <a class="delete" href="admin.php?mod=modul&amp;action=delete&amp;id='.$data['id'].'" onclick="return confirm(\'Apakah anda yakin ?\')">Delete</a></td></tr>';
					
				}
				$tengah .= '
				</tbody>
				</table>
				<button name="submit" class="primary" style="margin-top:10px;"><span class="icon plus"></span>Update</button>
				</div>
			</div>
			</form>
		</div>';
	
	}
	
	if($_GET['action'] == 'addblock'){
		if (isset($_POST['submit'])) {
			$error = null;
			$title 	= text_filter($_POST['title']);
			$modul 	= $_POST['modul'];
			
			if (!$title)  	$error .= "Error: Please input title.<br />";
			if (!$modul)  	$error .= "Error: Please input content of modul.<br />";
	
			
			if ($error != '') {
				$tengah .= '<div class="error">'.$error.'</div>';
			}else {
				$modul 		= $_POST['modul'];
				$position 	= trim(strip_tags($_POST['position']));
				$spesial 	= trim(strip_tags($_POST['spesial']));
				$cek 		= mysql_query("SELECT MAX(`ordering`) + 1 AS `ordering` FROM `mod_modul` WHERE `position` = '$position'");
				$data 		= mysql_fetch_assoc($cek);
				$ordering 	= $data['ordering'];
				$insert = mysql_query("INSERT INTO `mod_modul` (`modul`,`content`,`position`,`ordering`,`type`,`spesial`) VALUES ('$title','$modul','$position','$ordering','block','$spesial')");
				if ($insert) {
					header("location: admin.php?mod=modul");
					exit;	
				}else {
					$tengah .= '<div class="error">'.mysql_error().'</div>';	
				}
			}		
		}
		$title 	= !isset($title) ? '' : $title;
		$modul 	= !isset($modul) ? '' : $modul;
		$tengah .= '
		<div class="border rb">
		<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
		<table>
			<tr>
				<td>Block Title</td>
				<td style="padding:5px;">:</td>
				<td><input type="text" name="title" value="'.$title.'" size="40" /></td>
			</tr>
			<tr>
				<td>Show Title ?</td>
				<td style="padding:5px;">:</td>
				<td>'.form_select('spesial',array('no'=>'Yes','yes'=>'No')).'</td>
			</tr>
			<tr>
				<td valign="top" style="padding-top:5px;">Content Block</td>
				<td style="padding:5px;" valign="top">:</td>
				<td><textarea name="modul" cols="40" rows="5">'.$modul.'</textarea></td>
			</tr>
			<tr>
				<td>Position</td>
				<td style="padding:5px;">:</td>
				<td>'.form_select('position',array('0'=>'Left','1'=>'Right')).'</td>
			</tr>
			<tr>
				<td>&nbsp;</td><td style="padding:5px;">&nbsp;</td><td><button name="submit" class="primary" style="margin-top:10px;"><span class="icon plus"></span>Submit</button></td>
			</tr>
		</table>
		</form>
		</div>';
		
	}
	
	if($_GET['action'] == 'addmodul'){
		if (isset($_POST['submit'])) {
			$error = null;
			$title 	= text_filter($_POST['title']);
			$modul 	= text_filter($_POST['modul']);
			
			if (!$title)  	$error .= "Error: Please input title.<br />";
			if (!$modul)  	$error .= "Error: Please input modul file.<br />";
			
			if ($error != '') {
				$tengah .= '<div class="error">'.$error.'</div>';
			}else {
				$spesial 	= trim(strip_tags($_POST['spesial']));
				$position	 	= trim(strip_tags($_POST['position']));
				$cek 		= mysql_query("SELECT MAX(`ordering`) + 1 AS `ordering` FROM `mod_modul` WHERE `position` = '$position'");
				$data 		= mysql_fetch_assoc($cek);
				$ordering 	= $data['ordering'];
				$insert 	= mysql_query("INSERT INTO `mod_modul` (`modul`,`content`,`position`,`ordering`,`type`,`spesial`) VALUES ('$title','$modul','$position','$ordering','module','$spesial')");
				if ($insert) {
				header("location: admin.php?mod=modul");
						exit;	
				}else {
					$tengah .= '<div class="error">'.mysql_error().'</div>';	
				}
			}		
		}
		$title 	= !isset($title) ? '' : $title;
		$modul 	= !isset($modul) ? '' : $modul;
		$tengah .= '
		<div class="border rb">
		<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
		<table>
			<tr>
				<td>Modul Title</td>
				<td style="padding:5px;">:</td>
				<td><input type="text" name="title" value="'.$title.'" size="40" /></td>
			</tr>
			<tr>
				<td>Show Title ?</td>
				<td style="padding:5px;">:</td>
				<td>'.form_select('spesial',array('no'=>'Yes','yes'=>'No')).'</td>
			</tr>
			<tr>
				<td>Modul File (*.php)</td>
				<td style="padding:5px;">:</td>
				<td><input type="text" name="modul" value="'.$modul.'" size="40" /></td>
			</tr>
			<tr>
				<td>Position</td>
				<td style="padding:5px;">:</td>
				<td>'.form_select('position',array('0'=>'Left','1'=>'Right')).'</td>
			</tr>
			<tr>
				<td>&nbsp;</td><td style="padding:5px;">&nbsp;</td><td><button name="submit" class="primary" style="margin-top:10px;"><span class="icon plus"></span>Submit</button></td>
			</tr>
		</table>
		</form>
		</div>';
		
	}
	
	if($_GET['action'] == 'delete'){
		$id = int_filter($_GET['id']);
		$delete = mysql_query("DELETE FROM `mod_modul` WHERE `id` = '$id'");
		if ($delete) {
			header("location: admin.php?mod=modul");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
	if($_GET['action'] == 'edit'){
		$id = int_filter($_GET['id']);
		if (isset($_POST['submit'])) {
			
			$title 	 = text_filter($_POST['title']);
			$modul 	 = $_POST['modul'];
			$spesial = text_filter($_POST['spesial']);
			$error	 = '';
			
			if (!$title)  	$error .= "Error: Please input title.<br />";
			if (!$modul)  	$error .= "Error: Please input content of modul or modul file<br />";
			
			if ($error) {
				$tengah .= '<div class="error">'.$error.'</div>';
			}else {
		
				$cek = mysql_num_rows(mysql_query("SELECT `type` FROM `mod_modul` WHERE `id` = '$id' AND `type` = 'module'"));
				if ($cek) {
					$modul 	= text_filter($_POST['modul']);
				}else {
					$modul 		= $_POST['modul'];
				}
				$update = mysql_query("UPDATE `mod_modul` SET `modul` = '$title',`content` = '$modul',`spesial`='$spesial' WHERE `id` = '$id'");
				if ($update) {
					header("location: admin.php?mod=modul");
					exit;
				}else {
					$tengah .= '<div class="error">'.mysql_error().'</div>';
				}
			}
		}
		
		$query 	= mysql_query("SELECT * FROM `mod_modul` WHERE `id` = '$id'");
		$data 	= mysql_fetch_assoc($query);

		$_POST['spesial'] = $data['spesial'];
		$title	 = $data['modul'];
		$modul	 = $data['content'];
		
		if ($data['type'] == 'module') {
			$tengah .= '
			<div class="border rb">
			<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
			<table>
				<tr>
					<td>Modul Title</td>
					<td style="padding:5px;">:</td>
					<td><input type="text" name="title" value="'.$title.'" size="40" /></td>
				</tr>
				<tr>
					<td>Show Title ?</td>
					<td style="padding:5px;">:</td>
					<td>'.form_select('spesial',array('no'=>'Yes','yes'=>'No')).'</td>
				</tr>
				<tr>
					<td>Modul File (*.php)</td>
					<td style="padding:5px;">:</td>
					<td><input type="text" name="modul" value="'.$modul.'" size="40" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td><td style="padding:5px;">&nbsp;</td><td><button name="submit" class="primary" style="margin-top:10px;"><span class="icon plus"></span>Submit</button></td>
				</tr>
			</table>
			</form>
			</div>';
		}else {
			$tengah .= '
			<div class="border rb">
			<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
			<table>
				<tr>
					<td>Block Title</td>
					<td style="padding:5px;">:</td>
					<td><input type="text" name="title" value="'.$title.'" size="40" /></td>
				</tr>
				<tr>
					<td>Show Title ?</td>
					<td style="padding:5px;">:</td>
					<td>'.form_select('spesial',array('no'=>'Yes','yes'=>'No')).'</td>
				</tr>
				<tr>
					<td valign="top" style="padding-top:5px;">Content Block</td>
					<td style="padding:5px;" valign="top">:</td>
					<td><textarea name="modul" cols="40" rows="5">'.$modul.'</textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td><td style="padding:5px;">&nbsp;</td><td><button name="submit" class="primary" style="margin-top:10px;"><span class="icon plus"></span>Submit</button></td>
				</tr>
			</table>
			</form>
			</div>';		
		}
		
	}

echo $tengah;