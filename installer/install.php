<?php

	
	include 'includes/template.php';
	ob_start();
	
	function dumping() {
		global $db;
				
		$file = 'installer/auracms.sql';
		
		if (!file_exists($file)) { 
			exit('Could not load sql file: ' . $file); 
		}
		
		$lines = file($file);
		
		if ($lines) {
			$sql = '';

			foreach($lines as $line) {
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$sql .= $line;
  
					if (preg_match('/;\s*$/', $line)) {				
						$db->sql_query($sql);	
						$sql = '';
					}
				}
			}
		}		
	}
	
	function url(){
		$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '/index.php';	
		if( $url = explode('/index.php', $url) ) 
			$url = $url[0];
			
		return $url;
	}
	
	if(isset($_POST['step_1']) AND empty($_SESSION['mysql_host'])){
		$mysql_host 	= $_POST['mysql_host'];
		$mysql_user		= $_POST['mysql_user'];
		$mysql_password	= $_POST['mysql_password'];
		$mysql_database	= $_POST['mysql_database'];
		
		$connect = @mysql_connect($mysql_host,$mysql_user,$mysql_password);
		if(!$connect){
			echo '<div class="errorfly go-front" id="status">Could not connect db: ' . mysql_error() .' !</div>';
		}else{		
		
			mysql_query("DROP DATABASE $mysql_database", $connect);
			mysql_query("CREATE DATABASE IF NOT EXISTS $mysql_database", $connect);
			$database = mysql_select_db( $mysql_database,$connect );
			if( !$database ){
				echo '<div class="errorfly go-front" id="status">Could not connect db: ' . mysql_error() .' !</div>';
			}else{
			
				$file_name = "_connection.php";
				if(!file_exists('includes/'.$file_name)){
					$file = "installer/_connection.php";
					@copy($file,'includes/_connection.php');
				}
				$fo = @fopen('includes/'.$file_name,"w+");
				$s 	= fgets($fo,6);
				$text = ("<?php
/*
 * @version		3.4.0
 * @package		AuraCMS
 * @copyright	Copyright (C) 2013 AuraCMS.
 * @license		GNU/GPL, see LICENSE.txt
 * @description 	containing database information
 *
 * October 22, 2013 , 10:40:23 AM  
 * Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 
	
	/*  Pengaturan Error Warning
		0 = false
		E_ALL = true
	*/
	error_reporting(0);
	
	define('FUNCTION', true);

    \$mysql_database	= '$mysql_database';
    \$mysql_host		= '$mysql_host';
    \$mysql_user		= '$mysql_user';
    \$mysql_password	= '$mysql_password';
	
	 
	
	if( ! ini_get('date.timezone') )
	{
	   date_default_timezone_set(\"Asia/Jakarta\");
	}

?>");
		
				rewind($fo);
				fwrite($fo,$text);
				$connect = fclose($fo);
			
				$_SESSION['mysql_host']		= $mysql_host;
				$_SESSION['mysql_database']	= $mysql_database;
				$_SESSION['mysql_user']		= $mysql_user;
				$_SESSION['mysql_password']	= $mysql_password;
			}
		}
	}



	if(isset($_POST['step_2'])){ 
		
		$username 	= $_POST['username'];
		$email		= $_POST['email'];
		$url		= $_POST['url'];
		$title		= $_POST['title'];
		$password	= $_POST['password'];
		
		if(!empty($title) or !empty($username) or !empty($email) or !empty($password) or !empty($url)){		
			if(preg_match('/^.+@.+\\..+$/',$email)){
				require('includes/_connection.php');
				require('includes/mysql.php');
				dumping();			
				$pass	= md5($username.$password);
				$insert = $db->sql_query("INSERT INTO `mod_user` (`username`,`password`,`email`,`name`,`active`,`level`,`timelogin`) VALUE ('$username','$pass','$email','Administrator','1','administrator','3600')"); 
				if($insert){
					$_SESSION['username'] = '';
					$_SESSION['success']  = '1';
					$db->sql_query("UPDATE `mod_setting` SET `title`='$title',`url`='$url' WHERE `id`='1'"); 
				
				}else{
					echo '<div class="errorfly go-front" id="status">'.mysql_error().' !</div>';
				}				
			}else{
				echo '<div class="errorfly go-front" id="status">Email or User are invalid !</div>';
			}
		}else{
			echo '<div class="errorfly go-front" id="status">Please fill the fields correctly !</div>';
		}
	}

	if(isset($_POST['admin'])){ 		
		session_destroy();
		rename("includes/_connection.php","includes/connection.php");	
		header("location:login.php");
	}else if(isset($_POST['home'])){ 	
		session_destroy();
		rename("includes/_connection.php","includes/connection.php");
		header("location:index.html");
	}
	function InsUrl() {
		$InsUrl = str_replace('index.php','',$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]);
		if(_FINDEX_=='BACK') {
			$jurl = substr_count($InsUrl,"/")-1;
			$ex = explode("/",$InsUrl);
			$no = 1 ;
			$InsUrl = '';
			foreach($ex as $b) {$InsUrl .= "$b/";  if($no==$jurl) break; $no++;}	
		}
		else {
			$InsUrl= $InsUrl;
		}
		return "http://$InsUrl";
	}
	

	if(empty($_SESSION['mysql_host']) AND empty($_SESSION['success'])) {
		if($_SERVER['SERVER_ADDR'] == '127.0.0.1' or $_SERVER['SERVER_ADDR'] == '::1' ){				
			echo '<div class="tip"><b>Tips</b> : Saat ini Anda sedang menggunakan server lokal.<p>Anda tidak perlu membuat database terlebih dahulu</p><p>Database terbentuk secara otomatis apabila nama database tersedia.</p></div>';
		}else{
			echo '<div class="tip"><b>Tips</b> : Saat ini Anda sedang menggunakan server hosting.<p>Anda harus membuat database beserta username dan password melalui CPanel.</p><p>Setelah itu masukan pada kolom yang harus diisi.</p></div>';
		}
        
		$mysql_host		= !isset($mysql_host) ? 'localhost' : $mysql_host;
		$mysql_password	= !isset($mysql_password) ? '' : $mysql_password;
		$mysql_user		= !isset($mysql_user) ? '' : $mysql_user;
		$mysql_database	= !isset($mysql_database) ? '' : $mysql_database;
		echo '
		<form id="formElem" method="post" action="">
			<fieldset class="step">
				<legend>Database Configuration</legend>
				<p><label>MySQL Host Name *</label><input autocomplete="off" name="mysql_host" type="text" value="'.$mysql_host.'"  placeholder="Host Name"></p>
                <p><label>MySQL Username *</label><input autocomplete="off" value="'.$mysql_user.'" name="mysql_user" type="text"></p>
                <p><label>MySQL Password *</label><input autocomplete="off" value="'.$mysql_password.'" name="mysql_password" type="password" ></p>
				<p><label>MySQL Database *</label><input autocomplete="off" value="'.$mysql_database.'" name="mysql_database" type="text"></p>
				<P class="submit"><button id="registerButton" type="submit" name="step_1">Next</button></p>
			</fieldset>
		</form>';  
	}
				
	if(!empty($_SESSION['mysql_host'])  AND !empty($_SESSION['mysql_user']) AND empty($_SESSION['success'])) {
		$title		= !isset($title) ? '' : $title;		
		$username	= !isset($username) ? '' : $username;
		$password	= !isset($password) ? '' : $password;
		$email		= !isset($email) ? '' : $email;
        echo '
		<form id="formElem" method="post" action="">
			<fieldset class="step">
				<legend>Website Configuration</legend>
				<p><label>Site Name *</label><input name="title" type="text" value="'.$title.'"></p>
				<p><label>Site Url *</label><input name="url" type="text" value="'.$url.'"></p>
                <p><label>User Name *</label><input autocomplete="off" value="'.$username.'" name="username" type="text" ></p>
                <p><label>Password *</label><input  autocomplete="off" value="'.$password.'" name="password" type="password"></p>
                <p><label>Email *</label><input value="'.$email.'" name="email" type="text"></p>
                <P class="submit"><button id="registerButton" type="submit" name="step_2">Next</button></p>
			</fieldset>
		</form>';  
	
	}else if(!empty($_SESSION['success'])) 	{
	
        echo '
		<form id="formElem" method="post" action="">
            <fieldset class="step"><legend>Install Successfuly</legend>
				<p>Selamat, AuraCMS telah sukses di instal dan telah siap digunakan :)</p>
				<P class="submit"><button id="registerButton" type="submit" name="admin" style="float:left">Admin Page</button><button id="registerButton" type="submit" name="home">Home Page</button></p>
            </fieldset>
		</form>';
	}
	
	$content = ob_get_contents();
	ob_end_clean();
	
	$define = array ('content'=> $content);		
	$tpl 	= new template ('installer/installer.html');
	
	$tpl-> define_tag($define);
	$tpl-> cetak();
	
?>