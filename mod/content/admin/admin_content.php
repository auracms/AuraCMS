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
	
	$ps_array = array(
			   '1' => 'YA',
			   '0' => 'TIDAK'
			   );
			   
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

	if($_GET['action'] ==''){
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Content <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=content" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Content Manager</div>
		</div>
		<div class="border">
			<form method="post" action="" enctype ="multipart/form-data"><table style="font-family:Verdana;font-size:12px;"><tr><td style="padding-right:10px;width:70px;">Pencarian</td><td> :</td><td style="padding-left:5px;padding-top:1px;"><input type="text" name="search" size="50" placeholder="Masukkan Judul Artikel atau Beritanya" /></td><td><button type="submit" class="primary"><span class="icon plus"></span>Search</button></td></tr></table></form>
		</div>		
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Home</a></li>
					<li><a href="#tabs-2">Add Content</a></li>					
					<li><a href="#tabs-4">Pages</a></li>
					<li><a href="#tabs-5">Add Pages</a></li>
					<li><a href="#tabs-3">Topic</a></li>
					<li><a href="#tabs-6">Artikel Masuk</a></li>
 				</ul>
				<div id="tabs-1">';
				
				if (isset($_POST['search'])){
					$search = cleantext($_POST['search']);
					$QUERY =  "WHERE `type`='news' AND `published`='1' AND `title` LIKE '%$search%'";
				}else{
					$QUERY =  "WHERE `type`='news' AND `published`='1'";
				}
					
				$jqa 		= $db->sql_query("SELECT * FROM `mod_content` $QUERY ORDER BY `date` DESC");
				$jumlah = $db->sql_numrows($jqa);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 		= $db->sql_query("SELECT * FROM `mod_content` $QUERY ORDER BY `date` DESC LIMIT $offset,$limit");
				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Article Title</td>
			            <td style="text-align: center;width:80px;">Published</td>
			            <td style="text-align: center;width:80px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				while($data = $db->sql_fetchrow($b)){
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$published = ($data['published'] == 1) ? '<a class="enable" href="?mod=content&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=content&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$data['title'].'</td>
			            <td style="text-align: center;">'.$published.'</td>
			            <td style="text-align: center;"><a class="edit" href="admin.php?mod=content&amp;action=edit&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=content&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
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
				<div id="tabs-6">';
				$a 		= $db->sql_query("SELECT * FROM `mod_content` WHERE `type`='news' AND `published`='0' ORDER BY `date` DESC");
				$jumlah = $db->sql_numrows($a);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 		= $db->sql_query("SELECT * FROM `mod_content` WHERE `type`='news' AND `published`='0' ORDER BY `date` DESC LIMIT $offset,$limit");
				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Article Title</td>
			            <td style="text-align: center;width:80px;">Published</td>
			            <td style="text-align: center;width:80px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				while($data = $db->sql_fetchrow($b)){
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$published = ($data['published'] == 1) ? '<a class="enable" href="?mod=content&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=content&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$data['title'].'</td>
			            <td style="text-align: center;">'.$published.'</td>
			            <td style="text-align: center;"><a class="edit" href="admin.php?mod=content&amp;action=edit&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=content&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
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
						$topic_id 		= int_filter($_POST['topic_id']);
						$headline 		= int_filter($_POST['headline']);
						$content 		= $_POST['content'];
						$tags 			= text_filter($_POST['tags']);
						$date 			= date('Y-m-d H:i:s');
						$seftitle		= seo($title);
						$username		= $_SESSION['username'];
						$caption		= text_filter($_POST['caption']);
						$error			= '';
						
						if (!$title)  		$error .= "Error: Please input title.<br />";
						if (!$topic_id)		$error .= "Error: Please select the topic.<br />";
						if (!$content)  	$error .= "Error: Please input content.<br />";
						
						if (!empty ($_FILES['images']['name'])){
							if (!$caption)  	$error .= "Error: Please Input Caption for Images.<br />";
						}
						

						
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
									$image->resizeToWidth(450);
				   					$image->save(normal.$finame);
				   					$image->resize(50,50);
									$image->save(thumb.$finame);
								}
							}else{
								$finame = '';
							}	
							$content 	= escape($_POST['content']);
							$tags 		= text_filter($_POST['tags']);
							$success = $db->sql_query("INSERT INTO `mod_content` (`title`,`content`,`topic_id`,`date`,`hits`,`tags`,`username`,`image`,`caption`,`published`,`seftitle`,`headline`) VALUES ('$title','$content','$topic_id','$date','0','$tags','$username','$finame','$caption','1','$seftitle','$headline')");
							if($success){
								$tengah .= '<div class="success">Content Has Been Added in Database</div>';
								$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=content" />';
								unset($title);
								unset($topic_id);
								unset($content);
								unset($caption);
								unset($tags);
								unlink(tmp . $finame);
							}else{
								$tengah .= '<div class="error">'.mysql_error().'</div>';
								unlink(tmp . $finame);
								unlink(normal . $finame);
								unlink(thumb . $finame);
							}
						}
						
					}
					$title 			= !isset($title) ? '' : $title;
					$topic_id 		= !isset($topic_id) ? '' : $topic_id;
					$content 		= !isset($content) ? '' : $content;
					$tags 			= !isset($tags) ? '' : $tags;
					$caption		= !isset($caption) ? '' : $caption;
					$headline		= !isset($headline) ? '0' : $headline;
					
					$tengah .= '
					<div class="border rb">
						<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
						<table border="0" cellspacing="0" cellpadding="0" id="table1">
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Title</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="title" size="50" value="'.$title.'" /></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Jadikan Headline</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px">'.select('headline',$headline,$ps_array).'</td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px" valign="top">Category</td>
								<td style="padding-top: 5px" valign="top">:</td>
								<td style="padding-left: 5px; padding-top: 5px">
								<select name="topic_id" id="f4"><option value="">-- No Parent --</option>';
								$a = $db->sql_query("SELECT * FROM `mod_topic` ORDER BY `id` DESC");
								while ($b =  $db->sql_fetchrow ($a)){
									$pilihan = ($b['id'] == $topic_id)?'selected':'';
									$tengah .= '<option value="'.$b['id'].'" '.$pilihan.'>'.$b['topic'].'</option>';
								}
								$tengah .= '
		                       	</select>
								</td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Content</td>
								<td style="padding-top: 5px;vertical-align:top;">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="content" cols="40">'.$content.'</textarea></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Tags</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="tags" size="40" value="'.$tags.'"></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Images</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="images" size="30"></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Caption Images</td>
								<td style="padding-top: 5px">:</td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="caption" size="40" value="'.$caption.'"></td>
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
				<div id="tabs-3">';
				
					$nt = 1;
						
					$bt 		= $db->sql_query("SELECT * FROM `mod_topic` ORDER BY `id` DESC");
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$tengah .= '
					<div class="border rb" style="margin-bottom:15px;">
					<table class="list">
						<thead>
					    	<tr class="head">
					        	<td style="text-align: center;width:30px;">No</td>
					           	<td class="left">Title of Topic</td>
								<td style="text-align: center;width:80px;">Action</td>
							</tr>
						</thead>
						<tbody>';
						while($data = $db->sql_fetchrow($bt)){
							$warna  = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
		
							$tengah .= '
							<tr'.$warna.'>
					            <td style="text-align: center;">'.$nt.'</td>
					            <td class="left">'.$data['topic'].'</td>
					            <td style="text-align: center;"><a href="admin.php?mod=content&amp;action=edittopic&amp;id='.$data['id'].'" class="edit" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=content&amp;action=deletetopic&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
							</tr>';
							$nt++;					
						}
					$tengah .= '
						</tbody>
					</table>
					</div>';
					
					if(isset($_POST['submittopic'])){
	
						$topic 		= text_filter($_POST['topic']);
						$seftitle	= seo($topic);
						$error			= '';
						
						if (!$topic)		$error .= "Error: Please input the topic.<br />";
						
						if($error){
							$tengah .= '<div class="error rt" style="margin-bottom:15px;padding-left:50px;">'.$error.'</div>';
						}else{	
							$success = $db->sql_query("INSERT INTO `mod_topic` (`topic`,`seftitle`) VALUES ('$topic','$seftitle')");
							if($success){
								$tengah .= '<div class="success rt" style="margin-bottom:15px;padding-left:50px;">Topic Has Been Added in Database</div>';
								header("location: admin.php?mod=content");
								exit;
								unset($topic);
							}else{
								$tengah .= '<div class="error rt" style="margin-bottom:15px;padding-left:50px;">'.mysql_error().'</div>';
							}
						}
						
					}
			
					$topic 		= !isset($topic) ? '' : $topic;
				
					$tengah .= '
					<div class="border rb">
			        <form action="" method="post">
			        <table border="0" cellspacing="0" cellpadding="0" id="table1">
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Topic </td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="topic" size="50" value="'.$topic.'" /></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
							<td style="padding-top: 15px">&nbsp;</td>
							<td style="padding-left: 5px; padding-top: 15px"><button name="submittopic" class="primary"><span class="icon plus"></span>Submit</button></td>
						</tr>
					</table>    	                 
			        </form>
					</div>					
				</div>
				<div id="tabs-4">';
				
					
					$np = 1;					
						
					$bp 		= $db->sql_query("SELECT * FROM `mod_content` WHERE `type`='pages' ORDER BY `date` DESC");
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$tengah .= '
					<div class="border rb">
					<table class="list">
						<thead>
						  <tr class="head">
					    	<td style="text-align: center;width:30px;">No</td>
					        <td class="left">Pages Title</td>
					    	<td style="text-align: center;width:80px;">Published</td>
					    	<td style="text-align: center;width:80px;">Action</td>
					      </tr>
						</thead>
					    <tbody>';
						while($data = $db->sql_fetchrow($bp)){
							$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
							$published = ($data['published'] == 1) ? '<a class="enable" href="?mod=content&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=content&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';
		
							$tengah .= '
							<tr'.$warna.'>
					            <td style="text-align: center;">'.$np.'</td>
					            <td class="left">'.$data['title'].'</td>
					            <td style="text-align: center;">'.$published.'</td>
					            <td style="text-align: center;"><a class="edit" href="admin.php?mod=content&amp;action=editpages&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=content&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
							</tr>';
							$np++;					
						}
					$tengah .= '
						</tbody>
					</table>
					</div>				
				</div>
				<div id="tabs-5">';
				
					if(isset($_POST['submitpages'])){
						$title 			= text_filter($_POST['title']);
						$content 		= escape($_POST['content']);
						$date 			= date('Y-m-d H:i:s');
						$seftitle		= seo($title);
						$username		= $_SESSION['username'];
						$error			= '';
						
						if (!$title)  		$error .= "Error: Please input title.<br />";
						if (!$content)  	$error .= "Error: Please input content.<br />";
			
						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{	
									
							$success = $db->sql_query("INSERT INTO `mod_content` (`title`, `content`,`type`,`date`,`published`,`username`,`seftitle`) VALUES ('$title','$content','pages','$date','1','$username','$seftitle')");
							if($success){
								$tengah .= '<div class="success">Content Has Been Added in Database</div>';
								$style_include[] = '<meta http-equiv="refresh" content="1; url=admin.php?mod=content" />';
								unset($title);
								unset($content);
							}else{
								$tengah .= '<div class="error">'.mysql_error().'</div>';
							}
						}
						
					}
					$title 			= !isset($title) ? '' : $title;
					$content 		= !isset($content) ? '' : $content;
			
					$tengah .= '
					<div class="border rb">
					<form method="post" action="" enctype ="multipart/form-data">
					<table border="0" cellspacing="0" cellpadding="0" id="table1">
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Title</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="title" size="50" value="'.$title.'" /></td>
						</tr>	
						<tr>
							<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Content</td>
							<td style="padding-top: 5px;vertical-align:top;">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="content" cols="40">'.$content.'</textarea></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
							<td style="padding-top: 15px">&nbsp;</td>
							<td style="padding-left: 5px; padding-top: 15px"><button name="submitpages" class="primary"><span class="icon plus"></span>Submit</button></td>
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
		<h2 class="widget-title">Edit <span class="styled1">Content</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=content" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit Content</div>
		</div>';
		if(isset($_POST['submit'])){
			$title 			= text_filter($_POST['title']);
			$topic_id 		= int_filter($_POST['topic_id']);
			$content 		= $_POST['content'];
			$tags 			= text_filter($_POST['tags']);
			$date 			= date('Y-m-d H:i:s');
			$seftitle		= seo($title);
			$username		= $_SESSION['username'];
			$gambarlama		= text_filter($_POST['gambarlama']);
			$caption		= text_filter($_POST['caption']);
			$error			= '';
			
			if (!$title)  		$error .= "Error: Please input title.<br />";
			if (!$topic_id)		$error .= "Error: Please select the topic.<br />";
			if (!$content)  	$error .= "Error: Please input content.<br />";


			
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
						$image->resizeToWidth(450);
	   					$image->save(normal.$finame);
	   					$image->resize(50,50);
						$image->save(thumb.$finame);
					}
					$success = $db->sql_query("UPDATE `mod_content` SET `title`='$title', `content`='$content',`topic_id`='$topic_id',`date`='$date',`tags`='$tags',`image`='$finame',`caption`='$caption',`headline`='$headline',`published`='1',`seftitle`='$seftitle' WHERE `id`='$id'");
					if($success){
						$tengah .= '<div class="success">Content Has Been Update With Images</div>';
						unlink(tmp . $finame);
						unlink(thumb . $gambarlama);
						unlink(normal . $gambarlama);
						$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=content" />';
					}else{
						$tengah .= '<div class="error">'.mysql_error().'</div>';
					}
				}else{
					$success = $db->sql_query("UPDATE `mod_content` SET `title`='$title', `content`='$content',`topic_id`='$topic_id',`date`='$date',`tags`='$tags',`caption`='$caption',`headline`='$headline',`published`='1',`seftitle`='$seftitle' WHERE `id`='$id'");
					if($success){
						$tengah .= '<div class="success">Content Has Been Update No Images</div>';
						$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=content" />';						
					}else{
						$tengah .= '<div class="error">'.mysql_error().'</div>';
					}
				}					
			}
			
		}
		$data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_content` WHERE `id`='$id'"));
		$title 			= $data['title'];
		$topic_id 		= $data['topic_id'];
		$content 		= $data['content'];
		$tags 			= $data['tags'];
		$gambarlama 	= $data['image'];
		$caption 		= $data['caption'];
		$headline 		= $data['headline'];
		$tengah .= '
		<div class="border rb">
			<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
			<table border="0" cellspacing="0" cellpadding="0" id="table1">
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Title</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="title" size="70" value="'.$title.'" /></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Jadikan Headline</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px">'.select('headline',$headline,$ps_array).'</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px" valign="top">Category</td>
					<td style="padding-top: 5px" valign="top">:</td>
					<td style="padding-left: 5px; padding-top: 5px">
					<select name="topic_id" id="f4"><option value="">-- Select --</option>';
					$a = $db->sql_query("SELECT * FROM `mod_topic` ORDER BY `id` DESC");
					while ($b =  $db->sql_fetchrow ($a)){
						$id		 = $b['id'];
						$pilihan = ($b['id'] == $topic_id)?'selected':'';
						$tengah .= '<option style="color: #ff00ff;" value="'.$b['id'].'" '.$pilihan.'>'.$b['topic'].'</option>';
					}
					$tengah .= '
		            </select>
					</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Content</td>
					<td style="padding-top: 5px;vertical-align:top;">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="content" cols="40">'.$content.'</textarea></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Tags</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="tags" size="40" value="'.$tags.'"></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px"></td>
					<td style="padding-top: 5px"></td>
					<td style="padding-left: 5px; padding-top: 5px"><img src="'.thumb.$data['image'].'" alt="'.$caption.'" border="0" /></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Images</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="file" name="images" size="30"><input type="hidden" name="gambarlama" size="30" value="'.$gambarlama.'"></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Caption Images</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="caption" size="40" value="'.$caption.'"></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
					<td style="padding-top: 15px">&nbsp;</td>
					<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon reload"></span>Update</button></td>
				</tr>
			</table>                         
			</form>
		</div>';
	}
	
	
	if($_GET['action'] =='edittopic'){
		$id = int_filter($_GET['id']);
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Edit <span class="styled1">Topic</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=content" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit Topic</div>
		</div>';
		
		if(isset($_POST['submit'])){
	
			$topic 		= text_filter($_POST['topic']);
			$seftitle	= seo($topic);
			$error			= '';
				
			if (!$topic)		$error .= "Error: Please input the topic.<br />";
				
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	
				$success = $db->sql_query("UPDATE `mod_topic` SET `topic`='$topic',`seftitle`='$seftitle' WHERE `id`='$id'");
				if($success){
					$tengah .= '<div class="success">Topic Has Been Update</div>';
					$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=content" />';
				}else{
					$tengah .= '<div class="error">'.mysql_error().'</div>';
				}
			}
				
		}
	
		$data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_topic` WHERE `id`='$id'"));
		$topic 			= $data['topic'];
		
		$tengah .= '
		<div class="border rb">
		<form action="" method="post">
		<table border="0" cellspacing="0" cellpadding="0" id="table1">
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Topic </td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="topic" size="50" value="'.$topic.'" /></td>
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
	

	if($_GET['action'] =='editpages'){
		$id = int_filter($_GET['id']);
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Edit <span class="styled1">Pages</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=content" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit Pages</div>
		</div>';

		
		if(isset($_POST['submit'])){
			$title 			= text_filter($_POST['title']);
			$content 		= escape($_POST['content']);
			$date 			= date('Y-m-d H:i:s');
			$seftitle		= seo($title);
			$error			= '';
			
			if (!$title)  		$error .= "Error: Please input title.<br />";
			if (!$content)  	$error .= "Error: Please input content.<br />";

			
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	
				$success = $db->sql_query("UPDATE `mod_content` SET `title`='$title', `content`='$content',`date`='$date',`seftitle`='$seftitle' WHERE `id`='$id' AND `type`='pages'");
				if($success){
					$tengah .= '<div class="success">Content Has Been Update</div>';
					header("location: admin.php?mod=content");
					exit;
				}else{
					$tengah .= '<div class="error">'.mysql_error().'</div>';
				}
			}
			
		}
		$data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_content` WHERE `type`='pages' AND `id`='$id'"));
		$title 			= $data['title'];
		$content 		= $data['content'];

		$tengah .= '
		<div class="border rb">
		<form method="post" action="" enctype ="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" id="table1">
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Title</td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="title" size="50" value="'.$title.'" /></td>
			</tr>	
			<tr>
				<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Content</td>
				<td style="padding-top: 5px;vertical-align:top;">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="content" cols="40">'.$content.'</textarea></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
				<td style="padding-top: 15px">&nbsp;</td>
				<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon reload"></span>Update</button></td>
			</tr>
		</table>	                   
		</form>
		</div>
		';
	}
	
	if ($_GET['action'] == 'pub'){	
		if ($_GET['pub'] == 'no'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_content` SET `published`='0' WHERE `id`='$id'");		
		}	
		
		if ($_GET['pub'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_content` SET `published`='1' WHERE `id`='$id'");		
		}	
		$referer = $_GET['referer'];
		header("location: $referer");
		exit;
	}
	
	if($_GET['action'] == 'delete'){
		$id 	= int_filter($_GET['id']);
		$data 	= $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_content` WHERE `id`='$id'"));
		$images	= $data['image'];
		unlink(thumb.$images);
		unlink(normal.$images);
		$delete = mysql_query("DELETE FROM `mod_content` WHERE `id` = '$id'");
		if ($delete) {
			$referer = $_GET['referer'];
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
	if($_GET['action'] == 'deletetopic'){
		$id 		= int_filter($_GET['id']);
		$username	= $_SESSION['username'];
		$query		= $db->sql_query("SELECT * FROM `mod_content` WHERE `topic_id`='$id'");
		while($data 	= $db->sql_fetchrow($query)){
			$images	= $data['image'];
			unlink(thumb.$images);
			unlink(normal.$images);
		}
		$delete  = mysql_query("DELETE FROM `mod_topic` WHERE `id` = '$id'");
		$delete .= mysql_query("DELETE FROM `mod_content` WHERE `topic_id` = '$id'");
		if ($delete) {
			$referer = $_GET['referer'];
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
echo $tengah;
?>