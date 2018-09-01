<?php

	if(!defined('INDEX')) exit;
	
	$index_hal = 1;
	
	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;	
	
	$tengah  ='
	<h2>Guestbook</h2>
	<div class="border" style="text-align:center;"><img src="mod/guestbook/images/guestbook.jpg" alt="Bukutamu" /></div>
	<div class="border" style="vertical-align:middle;"><img style="padding-right:10px;vertical-align:middle;"src="mod/guestbook/images/home.ico" alt="" /><a href="guestbook.html">Home</a><img style="padding:0 10px 0 10px;vertical-align:middle;"src="mod/guestbook/images/write_gb.png" alt="" /><a href="guestbook-add.html">Add Guestbook</a></div>';
	
	if(!isset($_GET['action'])){
		$tengah .='	
		<div class="border rb">	
		<table class="list">
			<thead>
				<tr class="head">
					<td style="text-align: center;width:200px;"><img style="vertical-align:middle;"src="mod/guestbook/images/sender.png" alt="" /><br />Sender</td>
					<td style="text-align: center;"><img style="vertical-align:middle;"src="mod/guestbook/images/message.png" alt="" /><br />Message</td>
				</tr>			
			</thead>';
				$q 		= $db->sql_query("SELECT `id` FROM `mod_guestbook` ORDER BY `id` DESC");				
				$jumlah	= $db->sql_numrows($q);
				$r		= $db->sql_fetchrow($db->sql_query("SELECT `max_limit` FROM `mod_guestbook_config` WHERE `id`='1'")); 
				$limit	= $r['max_limit'];
				$a 		= new paging_s ($limit,'guestbook','.html');
				$query	= $db->sql_query("SELECT * FROM `mod_guestbook` ORDER BY `id` DESC LIMIT $offset,$limit");
				while($data = $db->sql_fetchrow($query)){
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;"><img src="mod/guestbook/images/user_back.png" alt="'.$data['ip'].'" /><br /><strong>'.$data['name'].'</strong><br />'.$data['city'].'<br />';
			            if(!empty($data['website'])) $tengah .='<a href="'.$data['website'].'" target=_blank title="'.$data['website'].'"><img src="mod/guestbook/images/web.png" alt="'.$data['website'].'" border="0" style="margin-right:10px;" /></a>';
			            $tengah .= '
						<a href="mailto:'.$data['email'].'" title="'.$data['email'].'"><img src="mod/guestbook/images/email.png" alt="'.$data['email'].'" border="0" /></a>
			            </td>
			            <td style="text-align: justify;"><span style="color:#e9a002;">Posted : '.datetimes($data['date']).'</span><br />'.$data['comment'];
			            if(!empty($data['answers'])) $tengah .= '<br /><div style="width:80px;float:left;padding-top:10px;"><strong>Answers : </strong></div><div style="border:1px;padding-top:10px;padding-left:80px;"><i>'.$data['answers'].'</i></div> ';
						$tengah .= '
			            </td>
					</tr>';
				}
			$tengah .= '
			<tbody>
			</tbody>
		</table></div>';
		$tengah .= $a-> getPaging($jumlah, $pg, $stg);
	}
	
	if($_GET['action'] == 'add'){
		
		if(isset($_POST['submit'])){
			$name		= xss_clean(text_filter($_POST['name']));
			$email		= xss_clean(text_filter($_POST['email']));
			$website	= xss_clean(text_filter($_POST['website']));
			$city		= xss_clean(text_filter($_POST['city']));
			$comment	= xss_clean(text_filter($_POST['comment']));
			$gfx_check	= text_filter($_POST['gfx_check']);
			$date 		= date('Y-m-d H:i:s');
			$ip 		= getIP();
			$error		= '';
			
			if (!$city)  	$error .= 'Error: Please input your city.<br />';
			if (!$comment)	$error .= 'Error: Please input your message.<br />';
			if (!$name)  	$error .= 'Error: Please input your name.<br />';
			if (!is_valid_email($email)) $error .= 'Error: E-Mail address invalid!<br />';
			if ($gfx_check != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) {$error .= 'Security Code Invalid <br />';}
			if (cek_posted('guestbook')){
				$error .= 'Anda Telah Memposting, Tunggu beberapa Saat';
			}
			
			if(!((strpos($website, 'http') === 0) && filter_var($website, FILTER_VALIDATE_URL))){
			    $error .= 'URL is invalid';
			}
			
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
				unset($gfx_check);
			}else{
				$name 	 = mysql_real_escape_string($name);
				$city 	 = mysql_real_escape_string($city);
				$comment = mysql_real_escape_string($comment);
				$insert = $db->sql_query("INSERT INTO `mod_guestbook` (`name`,`email`,`website`,`ip`,`city`,`date`,`comment`) VALUE ('$name','$email','$website','$ip','$city','$date','$comment')");
				if($insert){
					$tengah .= '<div class="sukses">Data has been Save</div>';
					posted('guestbook');
					unset($name);
					unset($email);
					unset($website);
					unset($city);
					unset($comment);
					unset($gfx_check);
					$style_include[] = '<meta http-equiv="refresh" content="1; url=guestbook.html" />';
				}else{
					$tengah .= '<div class="error">Data can`t Save</div>';
				}
			}
			
			
			
		}
		$name 		= !isset($name) ? '' : $name;
		$email 		= !isset($email) ? '' : $email;
		$website 	= !isset($website) ? '' : $website;
		$city 		= !isset($city) ? '' : $city;
		$comment 	= !isset($comment) ? '' : $comment;
		$gfx_check 	= !isset($gfx_check) ? '' : $gfx_check;
		
		$script_include[] = <<<js
		<script type="text/javascript">
			function change (el) {
				var max_len = 500;
				if (el.value.length > max_len) {
					el.value = el.value.substr(0, max_len);
				}
				document.getElementById('char_cnt').innerHTML = el.value.length;
				document.getElementById('chars_left').innerHTML = max_len - el.value.length;
				return true;
			}
		</script>
js;
		$tengah .= '
		<div class="border rb">
		<form method="post" action="" enctype ="multipart/form-data" id="myform" name="myform">
			<table border="0" cellspacing="0" cellpadding="0" id="table1">
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Name</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="name" size="40" value="'.$name.'" /></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Email</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="email" size="40" value="'.$email.'" /></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px" valign="top">Website</td>
					<td style="padding-top: 5px" valign="top">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="website" size="40" value="'.$website.'" /></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">City</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="city" size="40" value="'.$city.'" /></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Message</td>
					<td style="padding-top: 5px;vertical-align:top;">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="comment" id="message" cols="60" maxlength="500" onkeyup="change(this);">'.$comment.'</textarea><br>You\'ve typed <span id="char_cnt">0</span> character(s). You are allowed <span id="chars_left">lots</span> more.</td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Security Code</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><img src="includes/code_image.php" border="0" alt="Security Code"></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Type Code</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="gfx_check" size="6" value="'.$gfx_check.'" /></td>
				</tr>
				<tr>
					<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
					<td style="padding-top: 15px">&nbsp;</td>
					<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Submit</button></td>
				</tr>
		</table>		         	                       
		</form>
		</div>	';	
		
	}
					
	
	
	
	echo $tengah;
