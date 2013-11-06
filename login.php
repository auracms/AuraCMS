<?php

/**
*	AuraCMS v.3.0
* 	Oktober 1, 2013 05:12:10 AM 
*	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
*/
 
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}

	if(DS == '/'){
		define('WEBROOT_DIR',dirname(__FILE__).'/');
	}else{
		define('WEBROOT_DIR',str_replace('\\', '/', dirname(__FILE__)).'/');
	}
	if (!defined('INC')) {
		define('INC', WEBROOT_DIR . 'includes/');
	}
	if (!defined('JS')) {
		define('JS', WEBROOT_DIR . 'js/');
	}
	if (!defined('CSS')) {
		define('CSS', WEBROOT_DIR . 'css/');
	}
	if (!defined('MOD')) {
		define('MOD', WEBROOT_DIR . 'mod/');
	}
	
	include INC . 'session.php';
	@header("Content-type: text/html; charset=utf-8;");
	ob_start("ob_gzhandler");
	
	

	define('INDEX', true);
	include INC . 'connection.php';
	include INC . 'mysql.php';
	include INC . 'global.php';
	include INC . 'fungsi.php';
	include INC . 'template.php';
	include INC . 'admin.lib.php';
	include INC . 'json.php';
	
	
	if (!defined('THEMES')) {
		define('THEMES', WEBROOT_DIR . 'themes/'.get_setting('admin_themes').'/');
	}
	
	
	$tengah  = '';
	if(!isset($_GET['action'])){
		if (cek_login () && isset($_SESSION['level']) &&  $_SESSION['level']== 'administrator'){
			header("Location: admin.php");	
		}else{
		
			if (isset ($_POST['submit']) && @$_POST['loguser'] == 1){
				aura_login ();
			}	
			
			$menuatas = '<li><a class="current" href="login.html">Login</a></li>';
			$tengah .= '
			        
	        <div class="border rb">
				<form method="post" action="" enctype ="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="0" id="table1">
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Username</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="text" id="username" name="username" value=""/></td>
					</tr>	
					<tr>
						<td style="padding-right: 10px; padding-top: 5px">Password</td>
						<td style="padding-top: 5px">:</td>
						<td style="padding-left: 5px; padding-top: 5px"><input type="password" id="password" name="password" value=""/></td>
					</tr>
					<tr>
						<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
						<td style="padding-top: 15px">&nbsp;</td>
						<td style="padding-left: 5px; padding-top: 15px"><input type="hidden" value="1" name="loguser" /><button name="submit" class="primary"><span class="icon plus"></span>Login</button></td>
					</tr>
				</table>	                   
		        </form>
		        </div>
			';
			
		}
	}
	
	$define = array ('content'  => $tengah,
					 'meta_title' 		=> 'Login System',
					 'meta_description' => get_setting('description'),
					 'meta_keywords' 	=> get_setting('keyword'),
					 'menuatas'  => $menuatas);
					 
	$tpl = new template (THEMES.'login.html');
	$tpl-> define_tag($define);
	$tpl-> cetak();

?>