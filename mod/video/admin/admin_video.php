<?php

	if(!defined('ADMIN')) exit;
	
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
	
	if(!isset($_GET['action'])){
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Video <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=video" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Video Manager</div>
		</div>		
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Home</a></li>
					<li><a href="#tabs-2">Add Video</a></li>
					<li><a href="#tabs-3">Video Masuk</a></li>
				</ul>
				<div id="tabs-1">';
				$a 		= $db->sql_query("SELECT * FROM `mod_video` WHERE `published`='1'  ORDER BY `date` DESC");
				$jumlah = $db->sql_numrows($a);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 		= $db->sql_query("SELECT * FROM `mod_video` WHERE `published`='1'  ORDER BY `date` DESC LIMIT $offset,$limit");
				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Title</td>
			            <td style="text-align: center;width:80px;">Thumbnail</td>
						<td style="text-align: center;width:80px;">Published</td>
			            <td style="text-align: center;width:80px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				
				while($data = $db->sql_fetchrow($b)){
					$warna 		= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$published 	= ($data['published'] == 1) ? '<a class="enable" href="?mod=video&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=video&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$data['title'].'</td>
			            <td style="text-align: center;"><img src="http://i2.ytimg.com/vi/'.$data['code'].'/default.jpg" border="0" alt=""></td>
						<td style="text-align: center;">'.$published.'</td>
			            <td style="text-align: center;"><a class="edit" href="admin.php?mod=video&amp;action=edit&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=video&amp;action=delete&amp;id='.$data['id'].'" onclick="return confirm(\'Are You Sure deleted this ?\')" title="Delete">Delete</a></td>
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
				<div id="tabs-3">';
				$a 		= $db->sql_query("SELECT * FROM `mod_video` WHERE `published`='0' ORDER BY `date` DESC");
				$jumlah = $db->sql_numrows($a);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 		= $db->sql_query("SELECT * FROM `mod_video` WHERE `published`='0'  ORDER BY `date` DESC LIMIT $offset,$limit");
				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Title</td>
			            <td style="text-align: center;width:80px;">Thumbnail</td>
						<td style="text-align: center;width:80px;">Published</td>
			            <td style="text-align: center;width:80px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				
				while($data = $db->sql_fetchrow($b)){
					$warna 		= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$published 	= ($data['published'] == 1) ? '<a class="enable" href="?mod=video&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=video&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$data['title'].'</td>
			            <td style="text-align: center;"><img src="http://i2.ytimg.com/vi/'.$data['code'].'/default.jpg" border="0" alt=""></td>
						<td style="text-align: center;">'.$published.'</td>
			            <td style="text-align: center;"><a class="edit" href="admin.php?mod=video&amp;action=edit&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=video&amp;action=delete&amp;id='.$data['id'].'" onclick="return confirm(\'Are You Sure deleted this ?\')" title="Delete">Delete</a></td>
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
						$title 			= text_filter($_POST['title']);
						$content 		= $_POST['content'];
						$date 			= date('Y-m-d H:i:s');
						$seftitle		= seo($title);
						$code 			= text_filter($_POST['code']);
						$error			= '';
						
						if (!$title)  		$error .= "Error: Please input title.<br />";
						if (!$content)  	$error .= "Error: Please input description.<br />";
						if (!$code)  		$error .= "Error: Please input code of youtube video.<br />";

						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{	
								
							$content = mysql_real_escape_string($_POST['content']);
							$success = $db->sql_query("INSERT INTO `mod_video` (`title`,`description`,`code`,`date`,`published`,`seftitle`) VALUES ('$title','$content','$code','$date','1','$seftitle')");
							if($success){
								$tengah .= '<div class="success">Video Has Been Added in Database</div>';
								unset($title);
								unset($content);
								unset($code);
								$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=video" />';
							}else{
								$tengah .= '<div class="error">'.mysql_error().'</div>';
							}
						}
						
					}
					$title 			= !isset($title) ? '' : $title;
					$content 		= !isset($content) ? '' : $content;
					$code 			= !isset($code) ? '' : $code;
					
					$tengah .= '
					<div class="border rb">
						<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
						<table border="0" cellspacing="0" cellpadding="0" id="table1">
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Title</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="title" id="f1" value="'.$title.'" /></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Description</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="content" cols="40">'.$content.'</textarea></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px" valign="top">Code Youtube</td>
								<td style="padding-top: 5px" valign="top">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="code" size="40" value="'.$code.'"></td>
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
	

	if($_GET['action'] =='edit'){
		
		$id = int_filter($_GET['id']);
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Edit <span class="styled1">Video</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=video" id="home">Home</a>   &nbsp;&raquo;&nbsp;   <a>Video Manager</a></div>
		</div>';
		  
			if(isset($_POST['submit'])){
				$title 			= text_filter($_POST['title']);
				$gambarlama		= text_filter($_POST['gambarlama']);
				$content 		= $_POST['content'];
				$date 			= date('Y-m-d H:i:s');
				$seftitle		= seo($title);
				$code 			= text_filter($_POST['code']);
				$error			= '';
						
				if (!$title)  		$error .= "Error: Please input title.<br />";
				if (!$content)  	$error .= "Error: Please input description.<br />";
				if (!$code)  		$error .= "Error: Please input code of youtube video.<br />";

						
				if($error){
					$tengah .= '<div class="error">'.$error.'</div>';
				}else{	
					
					$content 	= mysql_real_escape_string($_POST['content']);
					$success 	= $db->sql_query("UPDATE `mod_video` SET `title`='$title',`description`='$content',`code`='$code',`date`='$date',`seftitle`='$seftitle' WHERE `id`='$id'");
					if($success){
						$tengah .= '<div class="success">Video Has Been Update</div>';
						$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=video" />';						
					}else{
						$tengah .= '<div class="error">'.mysql_error().'</div>';
					}					
				}						
			}
			$data    	= $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_video` WHERE `id`='$id'"));
			$title 	 	= $data['title'];
			$code 	 	= $data['code'];
			$content 	= $data['description'];
					
			$tengah .= '
			<div class="border rb">
				<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="0" id="table1">
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Title</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="title" id="f1" value="'.$title.'" /></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Description</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="content" cols="40">'.$content.'</textarea></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px" valign="top">Code Youtube</td>
						<td style="padding-top: 5px" valign="top">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="code" size="40" value="'.$code.'"></td>
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
	
	if($_GET['action'] == 'delete'){
		$id 		= int_filter($_GET['id']);
		$delete = mysql_query("DELETE FROM `mod_video` WHERE `id` = '$id'");
		if ($delete) {
			header("location: admin.php?mod=video");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
	if ($_GET['action'] == 'pub'){	
		global $db;
		if ($_GET['pub'] == 'no'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_video` SET `published`='0' WHERE `id`='$id'");		
		}	
		
		if ($_GET['pub'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_video` SET `published`='1' WHERE `id`='$id'");		
		}	
		$referer = $_GET['referer'];
		header("location: $referer");
		exit;
	}



echo $tengah;

?>