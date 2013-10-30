<?php

	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;
	
	$script_include[] = <<<js
	<!-- TinyMCE -->
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/init.js"></script>
	<!-- /TinyMCE -->
js;
		

	$tengah  = '
	<div class="box">
	<h2 class="widget-title">Guestbook <span class="styled1">Manager</span></h2>
	<div class="breadcrumb"><a href="admin.php?mod=guestbook" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Guestbook Manager</div>
	</div>';
	if($_GET['action'] ==''){
		
		$tengah .= '
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Home</a></li>
					<li><a href="#tabs-2">Setting</a></li>
				</ul>
				<div id="tabs-1">';
					$a 		= $db->sql_query("SELECT * FROM `mod_guestbook` ORDER BY `date` DESC");
					$jumlah = $db->sql_numrows($a);
					$limit 	= 15;
					
					$a 		= new paging ($limit);
	
					if(isset($offset)){
						$no = $offset + 1;
					}else{
						$no = 1;
					}
					
					$b 		= $db->sql_query("SELECT * FROM `mod_guestbook` ORDER BY `date` DESC LIMIT $offset,$limit");
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$tengah .= '
					<div class="border rb">
					<table class="list">
				        <thead>
				          <tr class="head">
				            <td style="text-align: center;width:30px;">No</td>
				            <td class="left" style="width:200px;">Name &amp; Information</td>
				            <td class="left">Comment</td>
				            <td style="text-align: center;width:80px;">Action</td>
				          </tr>
				        </thead>
				        <tbody>';
					while($data = $db->sql_fetchrow($b)){
						$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
						if(!empty($data['answers'])){
							$answers = '<br /><div style="width:80px;float:left;padding-top:10px;"><strong>Answers : </strong></div><div style="border:1px;padding-top:10px;padding-left:80px;"><i>'.$data['answers'].'</i></div> ';
						}else{
							$answers = '';
						}
						$tengah .= '
						<tr'.$warna.'>
				            <td style="text-align: center;">'.$no.'</td>
				            <td class="left">'.$data['name'].' - '.$data['city'].'<br />Email : '.$data['email'].'<br />IP : '.$data['ip'].'<br />'.datetimes($data['date']).'</td>
				            <td class="left">'.$data['comment'].' '.$answers.'</td>
				            <td style="text-align: center;"><a href="admin.php?mod=guestbook&amp;action=answer&amp;id='.$data['id'].'" class="edit" title="Answers Comment">Answers</a> <a class="delete" href="admin.php?mod=guestbook&amp;action=delete&amp;id='.$data['id'].'"  onclick="return confirm(\'Are You Sure deleted this ?\')" title="Delete">Delete</a></td>
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
			
						$max_limit 	= int_filter($_POST['max_limit']);
						$char 		= int_filter($_POST['char']);
						$error			= '';
						
						if (!$max_limit)	$error .= "Error: Please input the Max Limit per Page.<br />";
						if (!$char)		$error .= "Error: Please input the Max Character for Posted.<br />";

						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{	
							
							$success = $db->sql_query("UPDATE `mod_guestbook_config` SET `max_limit`='$max_limit',`char`='$char' WHERE `id`='1'");
							if($success){
								$tengah .= '<div class="success">Database Setting Updated</div>';
							}else{
								$tengah .= '<div class="error">Databse Setting can`t Updated</div>';
							}
						}
						
					}
			
					$data 		= $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_guestbook_config` WHERE `id`='1'"));
					$max_limit 	= $data['max_limit'];
					$char 		= $data['char'];
					
					$tengah .= '
					<div class="border rb">
					<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
			        <table border="0" cellspacing="0" cellpadding="0" id="table1">
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Max Limit per Pages</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input class="text" type="text" name="max_limit" value="'.$max_limit.'" /></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Max Character</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input class="text" type="text" name="char" value="'.$char.'" /></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">&nbsp;</td>
							<td style="padding-top: 5px">&nbsp;</td>
							<td style="padding-left: 5px; padding-top: 5px"><button name="submit" class="primary"><span class="icon plus"></span>Update</button></td>
						</tr>
					</table>                         
			        </form>
			        </div>
				</div>
			</div>
		</div>';


	}
	
	if($_GET['action'] == 'delete'){
		$id 		= int_filter($_GET['id']);
		$delete = mysql_query("DELETE FROM `mod_guestbook` WHERE `id` = '$id'");
		if ($delete) {
			header("location: admin.php?mod=guestbook");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
	if($_GET['action'] =='answer'){
		$id 	= int_filter($_GET['id']);

					
		$a 		= $db->sql_query("SELECT * FROM `mod_guestbook` WHERE `id`='$id'");
		$ref 	= urlencode($_SERVER['REQUEST_URI']);
		$tengah .= '
		<div class="border">
			<table class="list">
			<thead>
				<tr class="head">
				    <td class="left" style="width:200px;">Name &amp; Information</td>
				    <td class="left">Comment</td>
				</tr>
			</thead>
			<tbody>';
			$data = $db->sql_fetchrow($a);
			$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
			if(!empty($data['answers'])){
				$answers = '<br /><div style="width:80px;float:left;padding-top:10px;"><strong>Answers : </strong></div><div style="border:1px;padding-top:10px;padding-left:80px;"><i>'.$data['answers'].'</i></div> ';
			}else{
				$answers = '';
			}
			$tengah .= '
			<tr'.$warna.'>
				<td class="left">'.$data['name'].' - '.$data['city'].'<br />Email : '.$data['email'].'<br />IP : '.$data['ip'].'<br />'.datetimes($data['date']).'</td>
				<td class="left">'.$data['comment'].' '.$answers.'</td>
			</tr>			
			</tbody>
			</table>
		</div>';
		
		if(isset($_POST['submit'])){
			$answer = text_filter($_POST['answers']);
			$update = $db->sql_query ("UPDATE `mod_guestbook` SET `answers`='$answer' WHERE `id`='$id'");
			if($update){
				header("location: admin.php?mod=guestbook");
				exit;
			}			
		}
		
		$answer 			= !isset($answer) ? '' : $answer;
		$tengah .= '
		<div class="border rb">
		<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" id="table1">
			<tr>
				<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Answers Comment</td>
				<td style="padding-top: 5px;vertical-align:top;">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="answers" cols="40">'.$answer.'</textarea></td>
			</tr>

			<tr>
				<td style="padding-right: 10px; padding-top: 5px">&nbsp;</td>
				<td style="padding-top: 5px">&nbsp;</td>
				<td style="padding-left: 5px; padding-top: 5px"><button name="submit" class="primary"><span class="icon plus"></span>Answer</button></td>
			</tr>
		</table>                         
		</form>
		</div>';
	}
echo $tengah;