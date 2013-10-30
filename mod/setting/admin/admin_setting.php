<?php

	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;

	$tengah  = '';
	if(!function_exists('dir_themes')){
		function dir_themes($modul="") {
			global $content;
			$dir = opendir("themes");
			while ($file = readdir($dir)) {
				if (!preg_match("/\./", $file)) {
					$selected = ($modul == $file) ? "selected" : "";
					if($file == 'duniamaya'){
					}else{
						$content .= "<option value=\"$file\" $selected>".$file."</option>";
					}
				}
			}
			closedir($dir);
			return $content;
		}
	}
	if(!function_exists('select')){
		function select($nama,$selected,$value 	= array(),$att = 'size="1"') {
			
		
			$a  = '<select name="'.$nama.'" '.$att.'">'; 
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
	}

    $script_include[] = <<<js
    <script>
        $(function() {
            $( "#tabssetting" ).tabs({
    			cookie: {
    				expires: 1
    			}
		    });
        });
    </script>
js;


	$status_array  = array(
					'1'=>'1',
					'0'=>'0'
					);
					
	$mail_array  = array(
					'mail'=>'PHP Mail()',
					'smtp'=>'SMPTP'
					);
					
	$ssl_array  = array(
					''=>'None',
					'ssl'=>'SSL',
					'tls'=>'TLS'
					);

	if($_GET['action'] ==''){
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Settings <span class="styled1">Website</span></h2>
		</div>

        <div id="tabssetting">
		    <ul>
			    <li><a href="#setting-1">Setting Website</a></li>
                <li><a href="#setting-2">Email Setting</a></li>
			</ul>
            <div id="setting-1">';

			if(isset($_POST['submit'])){
				$title 			= text_filter($_POST['title']);
				$keyword 		= text_filter($_POST['keyword']);
				$description 	= text_filter($_POST['description']);
				$slogan			= text_filter($_POST['slogan']);
				$status 		= int_filter($_POST['status']);
				$email 			= text_filter($_POST['email']);
				$themes 		= text_filter($_POST['themes']);
				$url 			= text_filter($_POST['url']);
				$name_blocker 	= text_filter($_POST['name_blocker']);
				$email_blocker 	= text_filter($_POST['email_blocker']);

				$error			= '';

				if (!$title)  		$error .= "Error: Please input title.<br />";
				if (!$keyword)		$error .= "Error: Please input keyword.<br />";
				if (!$description)	$error .= "Error: Please input description.<br />";
				if (!$slogan)  		$error .= "Error: Please input slogan.<br />";
				if (!$email)  		$error .= "Error: Please input email.<br />";
				if (!$themes)  		$error .= "Error: Please input name of themes.<br />";
				if (!$url)  		$error .= "Error: Please input url website.<br />";
				if (!$email_blocker)$error .= "Error: Please input email blocker.<br />";
				if (!$name_blocker) $error .= "Error: Please input name blocker.<br />";


				if($error){
					$tengah .= '<div class="error">'.$error.'</div>';
				}else{

					$success = $db->sql_query("UPDATE `mod_setting` SET `title`='$title', `keyword`='$keyword',`description`='$description',`slogan`='$slogan',`status`='$status',`email`='$email',`themes`='$themes',`email_blocker`='$email_blocker',`url`='$url',`name_blocker`='$name_blocker' WHERE `id`='1'");
					if($success){
						$tengah .= '<div class="success">Content Has Been Update</div>';
						$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=setting" />';
					}else{
						$tengah .= '<div class="error">'.mysql_error().'</div>';
					}
				}

			}
			$data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_setting` WHERE `id`='1'"));
			$title 			= $data['title'];
			$keyword 		= $data['keyword'];
			$description 	= $data['description'];
			$slogan			= $data['slogan'];
			$status 		= $data['status'];
			$email	 		= $data['email'];
			$themes 		= $data['themes'];
			$url 			= $data['url'];
			$admin_themes	= $data['admin_themes'];
			$name_blocker	= $data['name_blocker'];
			$email_blocker 	= $data['email_blocker'];
			$tengah .= '
			<div class="border rb">
				<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="0" id="table1">
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Title</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="title" size="40" value="'.$title.'" /></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Keyword</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="keyword" size="40" value="'.$keyword.'" /></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px" valign="top">Description</td>
						<td style="padding-top: 5px" valign="top">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="description" cols="60">'.$description.'</textarea></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Slogan</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="slogan" size="40" value="'.$slogan.'" /></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Status</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px">'.select('status',$status,$status_array).'</td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Url Website</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="url" size="40" value="'.$url.'" /></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Email</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="email" size="40" value="'.$email.'" /></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Themes</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><select name="themes" size="1">'.dir_themes($themes).'</select></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Name Blocker</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="name_blocker" size="40" value="'.$email_blocker.'"></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Email Blocker</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="email_blocker" size="40" value="'.$email_blocker.'"></td>
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

            <div id="setting-2">';
			if(isset($_POST['submitemail'])){
				$mailtype 				= text_filter($_POST['mailtype']);
				$smtpport 				= text_filter($_POST['smtpport']);
				$smtphost 				= text_filter($_POST['smtphost']);
				$smtpusername			= text_filter($_POST['smtpusername']);
				$smtppassword 			= text_filter($_POST['smtppassword']);
				$smtpssl 				= text_filter($_POST['smtpssl']);
				$signature 				= text_filter($_POST['signature']);
				$systememailsfromname 	= text_filter($_POST['systememailsfromname']);
				$systememailsfromemail 	= text_filter($_POST['systememailsfromemail']);

				$error			= '';

				if (!$mailtype)  		$error .= "Error: Please input mailtype.<br />";
				if (!$signature)  		$error .= "Error: Please input name of Signature.<br />";
				if (!$systememailsfromname)  		$error .= "Error: Please input System Emails From Name.<br />";
				if (!$systememailsfromemail) $error .= "Error: Please input System Emails From Email.<br />";


				if($error){
					$tengah .= '<div class="error">'.$error.'</div>';
				}else{

					$success = $db->sql_query("UPDATE `mod_setting` SET `mailtype`='$mailtype', `smtpport`='$smtpport',`smtphost`='$smtphost',`smtpusername`='$smtpusername',`smtppassword`='$smtppassword',`smtpssl`='$smtpssl',`signature`='$signature',`systememailsfromname`='$systememailsfromname',`systememailsfromemail`='$systememailsfromemail' WHERE `id`='1'");
					if($success){
						$tengah .= '<div class="success">Content Has Been Update</div>';
						$style_include[] = '<meta http-equiv="refresh" content="0; url=admin.php?mod=setting" />';
					}else{
						$tengah .= '<div class="error">'.mysql_error().'/div>';
					}
				}

			}
			$mailtype 				= $data['mailtype'];
			$smtpport 				= $data['smtpport'];
			$smtphost 				= $data['smtphost'];
			$smtpusername			= $data['smtpusername'];
			$smtppassword 			= $data['smtppassword'];
			$smtpssl 				= $data['smtpssl'];
			$signature	 			= $data['signature'];
			$systememailsfromname 	= $data['systememailsfromname'];
			$systememailsfromemail 	= $data['systememailsfromemail'];

			$tengah .= '
			<div class="border rb">
				<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="0" id="table1">
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Mail Type</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px">'.select('mailtype',$mailtype,$mail_array).'</td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">SMTP Port</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="smtpport" size="5" value="'.$smtpport.'" /> The port your mail server uses</td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px" valign="top">SMTP Host</td>
						<td style="padding-top: 5px" valign="top">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="smtphost" size="40" value="'.$smtphost.'"></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">SMTP Username</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="smtpusername" size="35" value="'.$smtpusername.'"></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">SMTP Password</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="password" name="smtppassword" size="20" value="'.$smtppassword.'"></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">SMTP SSL Type</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px">'.select('smtpssl',$smtpssl,$ssl_array).'</td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px;vertical-align:top">Global Email Signature</td>
						<td style="padding-top: 5px;vertical-align:top">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><textarea name="signature" rows="4" cols="60">'.$signature.'</textarea></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">System Emails From Name</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="systememailsfromname" size="35" value="'.$systememailsfromname.'"></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">System Emails From Email</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="systememailsfromemail" size="50" value="'.$systememailsfromemail.'"></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
						<td style="padding-top: 15px">&nbsp;</td>
						<td style="padding-left: 5px; padding-top: 15px"><button name="submitemail" class="primary"><span class="icon plus"></span>Save Change</button></td>
					</tr>
				</table>
				</form>
			</div>

            </div>
        </div>';
			

	}

echo $tengah;
?>