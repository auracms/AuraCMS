<?php

/**
 *	
 * 	March 02, 2012  , 09:32:55 PM   
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */
 
	if(!defined('INDEX')) exit;
	
	$script_include[] = <<<js
	<!-- TinyMCE -->
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/contact.js"></script>
	<!-- /TinyMCE -->
js;
	
	$tengah  ='
	<h2>Contact Us</h2>
	<div class="border" style="text-align:center;"><img src="mod/contact/images/contact-us.png" alt="Contact US" /></div>';
	$tengah .='<div class="left_message">Anda bisa menghubungi kami melalui formulir yang disediakan di bawah ini.Semua pesan yang Anda tulis disini dikirim ke email kami.<br />Terimakasih.</div>';
	
	if (isset($_POST['submit'])) {
	
	    $nama 		= text_filter($_POST['nama']);
	    $email 		= text_filter($_POST['email']);
	    $pesan 		= text_filter($_POST['pesan']);
		$gfx_check	= text_filter($_POST['gfx_check']);
	    $error = '';
	    if (!is_valid_email($email)) $error .= 'Error: E-Mail address invalid!<br />';
	    
	    if (!$nama)  $error .= 'Error: Please enter your name!<br />';
	    if (!$pesan) $error .= 'Error: Please enter a message!<br />';	
		if ($gfx_check != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) {$error .= 'Security Code Invalid <br />';}
		if (cek_posted('contact')){
			//$error .= 'Anda Telah Memposting, Tunggu beberapa Saat';
		}
		
		if ($error){
			$tengah.='<div class="error">'.$error.'</div>';
			unset($gfx_check );
		} else {
			$subject = title;
			$msg 	 = $pesan;

			kirimemail(email, $email, $nama,$subject, $msg);
			Posted('contact');			
			$tengah.='<div class="sukses">Thank you, mail has been sent!</div>';
			
			unset($nama);
			unset($email);
			unset($pesan);
			unset($gfx_check );
	      }	
	}
	
	$nama 		= !isset($nama) ? '' : $nama;
	$email 		= !isset($email) ? '' : $email;
	$pesan 		= !isset($pesan) ? '' : $pesan;
	$gfx_check 	= !isset($gfx_check) ? '' : $gfx_check;
	
	$tengah .= '
	<div class="border rb">
	<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" id="table1">
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Your Name</td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="nama" size="40" value="'.$nama.'" /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Your E-Mail</td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="email" size="40" value="'.$email.'" /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 5px;vertical-align:top;">Message</td>
				<td style="padding-top: 5px;vertical-align:top;">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><textarea rows="8" name="pesan" cols="50">'.$pesan.'</textarea></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 5px;">Security Code</td>
				<td style="padding-top: 5px;">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><img src="includes/code_image.php" border="0" alt="Security Code"></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 5px;">Type Code</td>
				<td style="padding-top: 5px;">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" class="required" name="gfx_check" size="6" value="'.$gfx_check.'" /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
				<td style="padding-top: 15px">&nbsp;</td>
				<td style="padding-left: 5px; padding-top: 15px"><input type="submit" value="Submit" name="submit"></td>
			</tr>  
		</table>  
	</form>
    </div>';
	
	
	echo $tengah;

?>
