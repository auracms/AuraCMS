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

	if($_GET['action'] ==''){
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Download <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=download" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Download Manager</a></div>
		</div>		
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#download-1">Home</a></li>
					<li><a href="#download-2">Add Download</a></li>
					<li><a href="#download-3">Category</a></li>
				</ul>
				<div id="download-1">';
				$a 		= $db->sql_query("SELECT * FROM `mod_download` ORDER BY `id` DESC");
				$jumlah = $db->sql_numrows($a);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 		= $db->sql_query("SELECT * FROM `mod_download` ORDER BY `id` DESC LIMIT $offset,$limit");
				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Download</td>
			            <td style="text-align: center;width:80px;">Published</td>
			            <td style="text-align: center;width:80px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				while($data = $db->sql_fetchrow($b)){
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$published = ($data['published'] == 1) ? '<a class="enable" href="?mod=download&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=download&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$data['title'].'</td>
			            <td style="text-align: center;">'.$published.'</td>
			            <td style="text-align: center;"><a class="edit" href="admin.php?mod=download&amp;action=edit&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=download&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
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
				<div id="download-2">';
		  
					if(isset($_POST['submit'])){
						$title 		 = $_POST['title'];
						$cat_id 	 = $_POST['cat_id'];
						$description = $_POST['description'];
						$link 		 = $_POST['url'];
						$size 		 = $_POST['size'];
						$date 		 = date('Y-m-d H:i:s');
						$seftitle	 = seo($title);
						$username	 = $_SESSION['username'];
						$error		 = '';
						
						if (!$title)  		$error .= 'Error: Please input Title.<br />';
						if (!$cat_id)  		$error .= 'Error: Please Select Category.<br />';
						if (!$description)  $error .= "Error: Please input Description.<br />";
						if (!$link)  		$error .= 'Error: Please input Link Download.<br />';
						if (!$size)  		$error .= 'Error: Please input Size.<br />';
						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{	
							$description = escape($_POST['description']);
							$title 		 = escape(cleantext($_POST['title']));
							$url		 = escape($_POST['url']);

							$success = $db->sql_query("INSERT INTO `mod_download` (`title`,`cat_id`,`description`,`url`,`size`,`published`,`author`,`date`,`seftitle`) VALUES ('$title','$cat_id','$description','$link','$size','1','$username','$date','$seftitle')");
							if($success){
								$tengah .= '<div class="success">Download Has Been Added in Database</div>';
								unset($download);
							}else{
								$tengah .= '<div class="error">Download Can`t Added in Database</div>';
							}
						}
						
					}
					$description = !isset($description) ? '' : $description;
					$title 		 = !isset($title) ? '' : $title;
					$cat_id 	 = !isset($cat_id) ? '' : $cat_id;
					$link 		 = !isset($link) ? '' : $link;
					$size 		 = !isset($size) ? '' : $size;
					
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
								<td style="padding-right: 10px; padding-top: 5px" valign="top">Category</td>
								<td style="padding-top: 5px" valign="top">:</td>
								<td style="padding-left: 5px; padding-top: 5px">
								<select name="cat_id" id="f4"><option value="">-- No Parent --</option>';
								$a = $db->sql_query("SELECT * FROM `mod_download_cat` ORDER BY `title` ASC");
								while ($b =  $db->sql_fetchrow ($a)){
									$pilihan = ($b['id'] == $cat_id)?'selected':'';
									$tengah .= '<option value="'.$b['id'].'" '.$pilihan.'>'.$b['title'].'</option>';
								}
								$tengah .= '
		                       	</select>
								</td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Description</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="description" cols="40">'.$description.'</textarea></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Link</td>
								<td style="padding-top: 5px;vertical-align:top;">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="url" size="40" value="'.$link.'"><br />Ex : http://google.com</td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Size Item</td>
								<td style="padding-top: 5px;vertical-align:top;">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="size" size="10" value="'.$size.'"><br />Ex : 120 KB</td>
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
				<div id="download-3">';
				
					$qc 	= $db->sql_query("SELECT * FROM `mod_download_cat` ORDER BY `id` DESC");
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$tengah .= '
					<div class="border rb" style="margin-bottom:15px;">
					<table class="list">
			        	<thead>
			         	 <tr class="head">
			           	 	<td style="text-align: center;width:30px;">No</td>
			            	<td class="left">Name of Category</td>
			            	<td style="text-align: center;width:80px;">Action</td>
			          	</tr>
			        	</thead>
			        	<tbody>';
			        	$i = 1;
						while($data = $db->sql_fetchrow($b)){
						$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';

						$tengah .= '
						<tr'.$warna.'>
			            	<td style="text-align: center;">'.$i.'</td>
			            	<td class="left">'.$data['title'].'</td>
			            	<td style="text-align: center;"><a class="edit" href="admin.php?mod=download&amp;action=editcat&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=download&amp;action=deletecat&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
						</tr>';
						$i++;					
					}
					$tengah .= '
						</tbody>
					</table>
					</div>';
		  
					if(isset($_POST['submitcat'])){
						$title 		 = $_POST['title'];
						$description = $_POST['description'];
						$error			= '';
						
						if (!$title)  		$error .= 'Error: Please input Title.<br />';
						if (!$description)  $error .= 'Error: Please input Description.<br />';
						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{	
							$description = escape($_POST['description']);
							$title 		 = escape(cleantext($_POST['title']));
							$seftitle	 = seo($title);

							$success = $db->sql_query("INSERT INTO `mod_download_cat` (`description`,`title`,`seftitle`) VALUES ('$description','$title','$seftitle')");
							if($success){
								$tengah .= '<div class="success">Category Has Been Added in Database</div>';
								$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=download" />';
								unset($description);
								unset($title);
							}else{
								$tengah .= '<div class="error">Category Can`t Added in Database</div>';
							}
						}
						
					}
					$description = !isset($description) ? '' : $description;
					$title 		 = !isset($title) ? '' : $title;
					
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
								<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Description</td>
								<td style="padding-top: 5px;vertical-align:top;">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="description" cols="40">'.$description.'</textarea></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
								<td style="padding-top: 15px">&nbsp;</td>
								<td style="padding-left: 5px; padding-top: 15px"><button name="submitcat" class="primary"><span class="icon plus"></span>Submit</button></td>
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
		<h2 class="widget-title">Edit <span class="styled1">Download</span></h2>
		<div class="breadcrumb"><a href="admin.php?moddownload" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit Download</div>
		</div>';
		if(isset($_POST['submit'])){

			$title 		 = $_POST['title'];
			$cat_id 	 = $_POST['cat_id'];
			$description = $_POST['description'];
			$link 		 = $_POST['url'];
			$size 		 = $_POST['size'];
			$date 		 = date('Y-m-d H:i:s');
			
			$error		 = '';
						
			if (!$title)  		$error .= 'Error: Please input Title.<br />';
			if (!$cat_id)  		$error .= 'Error: Please Select Category.<br />';
			if (!$description)  $error .= "Error: Please input Description.<br />";
			if (!$link)  		$error .= 'Error: Please input Link Download.<br />';
			if (!$size)  		$error .= 'Error: Please input Size.<br />';
			
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	
				$description = escape($_POST['description']);
				$title 		 = escape(cleantext($_POST['title']));
				$link		 = escape($_POST['url']);
				$seftitle	 = seo($title);
				$success = $db->sql_query("UPDATE `mod_download` SET `title`='$title',`cat_id`='$cat_id',`description`='$description',`url`='$link',`size`='$size',`date`='$date' WHERE `id`='$id'");
				if($success){
					$tengah .= '<div class="success">Download Has Been Update</div>';
					$style_include[] = '<meta http-equiv="refresh" content="1; url=admin.php?mod=download" />';						
				}else{
					$tengah .= '<div class="error">download Can`t be Update</div>';
				}
			}					
		}
			
		
		$data 	 = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_download` WHERE `id`='$id'"));
		$title 		 = $data['title'];
		$cat_id 	 = $data['cat_id'];
		$description = $data['description'];
		$url 		 = $data['url'];
		$size 		 = $data['size'];

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
					<td style="padding-right: 10px; padding-top: 5px" valign="top">Category</td>
					<td style="padding-top: 5px" valign="top">:</td>
					<td style="padding-left: 5px; padding-top: 5px">
					<select name="cat_id" id="f4"><option value="">-- No Parent --</option>';
					$a = $db->sql_query("SELECT * FROM `mod_download_cat` ORDER BY `id` DESC");
					while ($b =  $db->sql_fetchrow ($a)){
						$pilihan = ($b['id'] == $cat_id)?'selected':'';
						$tengah .= '<option value="'.$b['id'].'" '.$pilihan.'>'.$b['title'].'</option>';
					}
					$tengah .= '
					</select>
					</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Description</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="description" cols="40">'.$description.'</textarea></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Link</td>
					<td style="padding-top: 5px;vertical-align:top;">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="url" size="40" value="'.$url.'"><br />Ex : http://google.com</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Size Item</td>
					<td style="padding-top: 5px;vertical-align:top;">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="size" size="10" value="'.$size.'"><br />Ex : 120 KB</td>
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
	
	
	if($_GET['action'] =='editcat'){
		$id = int_filter($_GET['id']);
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Edit <span class="styled1">Category</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=download" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit Category</div>
		</div>';
		if(isset($_POST['submit'])){

			$title 		 = $_POST['title'];
			$description = $_POST['description'];			
			$error		 = '';
						
			if (!$title)  		$error .= 'Error: Please input Title.<br />';
			if (!$description)  $error .= "Error: Please input Description.<br />";
			
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	
				$description = escape($_POST['description']);
				$title 		 = escape(cleantext($_POST['title']));
				$seftitle	 = seo($title);
				$success = $db->sql_query("UPDATE `mod_download_cat` SET `title`='$title',`description`='$description',`seftitle`='$seftitle' WHERE `id`='$id'");
				if($success){
					$tengah .= '<div class="success">Category Has Been Update</div>';
					$style_include[] = '<meta http-equiv="refresh" content="1; url=admin.php?mod=download" />';						
				}else{
					$tengah .= '<div class="error">Category Can`t be Update</div>';
				}
			}					
		}
			
		
		$data 	 = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_download_cat` WHERE `id`='$id'"));
		$title 		 = $data['title'];
		$description = $data['description'];

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
					<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="description" cols="40">'.$description.'</textarea></td>
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
		$id 	= int_filter($_GET['id']);
		$delete = $db->sql_query("DELETE FROM `mod_download` WHERE `id` = '$id'");
		if ($delete) {
			$referer = $_GET['referer'];
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
	if($_GET['action'] == 'deletecat'){
		$id 	= int_filter($_GET['id']);
		$delete = $db->sql_query("DELETE FROM `mod_download_cat` WHERE `id` = '$id'");
		if ($delete) {
			$referer = $_GET['referer'];
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}

	if ($_GET['action'] == 'pub'){	
		if ($_GET['pub'] == 'no'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_download` SET `published`='0' WHERE `id`='$id'");		
		}	
		
		if ($_GET['pub'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_download` SET `published`='1' WHERE `id`='$id'");		
		}	
		$referer = $_GET['referer'];
		header("location: $referer");
		exit;
	}
	
	echo $tengah;