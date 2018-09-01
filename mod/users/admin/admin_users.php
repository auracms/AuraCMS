<?php

	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;

	$tengah  = '';
	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;
	
	if (!function_exists( 'get_status' ) ){
		function get_status ($id){
			global $db;
		   	if($id == '1'){
			   	$status = 'Active';
		   	}else{
			   	$status = 'Non Active';
		   	}
		   	return $status;
		}	
	}
	

	function select($gallery,$selected,$value 	= array()) {
		
	
		$a  = '<select name="'.$gallery.'" size="1">'; 
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
		<h2 class="widget-title">Users <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=users" id="home">Home</a>   &nbsp;&raquo;&nbsp;   User Manager</div>
		</div>
		<div class="border">
			<form method="post" action="" enctype ="multipart/form-data"><table style="font-family:Verdana;font-size:12px;"><tr><td style="padding-right:10px;width:70px;">Pencarian</td><td> :</td><td style="padding-left:5px;padding-top:1px;"><input type="text" name="search" size="50" placeholder="Masukkan Nama, Email atau Username" /></td><td><button type="submit" class="primary"><span class="icon plus"></span>Search</button></td></tr></table></form>
		</div>
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#users-1">Home</a></li>
					<li><a href="#users-2">Add Users</a></li>
				</ul>
				<div id="users-1">
					<div class="border rb">
					<table class="list">
					<thead>
						<tr class="head">
							<td style="text-align: center;">No.</td>
							<td style="text-align: left;">Name</td>
							<td style="text-align: center;">Email</td>
							<td style="text-align: center;">Level</td>
							<td style="text-align: center;">Status</td>
							<td style="text-align: center;">Action</td>
						</tr>
					</thead>
					<tbody>';
					
					if (isset($_POST['search'])){
						$search = cleantext($_POST['search']);
						$QUERY =  "WHERE `username` LIKE '%$search%' OR `name` LIKE '%$search%' OR `email` LIKE '%$search%'";
					}else{
						$QUERY = "";
					}
					
					$jqa 	= $db->sql_query("SELECT * FROM `mod_user` $QUERY ORDER BY `name` ASC");
					$jumlah = $db->sql_numrows($jqa);
					$limit 	= 30;							
					$a 		= new paging ($limit);

					if(isset($offset)){
						$no = $offset + 1;
					}else{
						$no = 1;
					}
				
					$ref   = urlencode($_SERVER['REQUEST_URI']);
					$query = mysql_query("SELECT * FROM `mod_user` $QUERY ORDER BY `name` ASC LIMIT $offset,$limit");
					while($data = mysql_fetch_assoc($query)) {
						$warna 	= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
						$status = ($data['active'] == 1) ? '<a class="enable" href="?mod=users&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=users&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';												
						$tengah .= '<tr'.$warna.'><td class="center">'.$no.'</td><td class="left">'.$data['name'].'</td><td style="text-align: center;">'.$data['email'].'</td><td style="text-align: center;">'.$data['level'].'</td><td style="text-align: center;">'.$status.'</td><td style="text-align:center;"><a class="edit" href="admin.php?mod=users&amp;action=edit&amp;id='.$data['id'].'&amp;referer='.$ref.'">Edit</a> <a class="delete" href="admin.php?mod=users&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" onclick="return confirm(\'Apakah anda yakin ?\')">Delete</a></td></tr>';
						$no++;
					}
					$tengah .= '
					</tbody>
					</table>
					</div>';
					$tengah .= $a-> getPaging($jumlah, $pg, $stg);
				$tengah .= '
				</div>
				<div id="users-2">';
         	
				if(isset($_POST['submit'])){
					$username 		= text_filter($_POST['username']);
					$password 		= text_filter($_POST['password']);
					$name			= text_filter($_POST['name']);
					$email			= text_filter($_POST['email']);
					$error			= '';
					
					if (!$username) 	$error .= "Error: Please input username.<br />";
					if (!$password)		$error .= "Error: Please input password.<br />";
					if (!$name)  		$error .= "Error: Please input Nama.<br />";
					if (!$email)  		$error .= "Error: Please input Email.<br />";
					
					if($error){
						$tengah .= '<div class="error">'.$error.'</div>';
					}else{	
						$password	= md5($username.$password);
						$success = $db->sql_query("INSERT INTO `mod_user` (`username`,`password`,`name`,`email`,`active`) VALUES ('$username','$password','$name',`$email`,'1')");
						if($success){
							$tengah .= '<div class="success">User Has Been Added in Database</div>';
							unset($username);
							unset($password);
							unset($email);
							unset($name);
							header("location: admin.php?mod=users");
							exit;
						}else{
							$tengah .= '<div class="error">'.mysql_error().'</div>';
						}					
					}
					
				}
				$username 	= !isset($username) ? '' : $username;
				$password 	= !isset($password) ? '' : $password;
				$name		= !isset($name) ? '' : $name;
				$email		= !isset($email) ? '' : $email;
		
				$tengah .= '
				<div class="border rb">
				<form method="post" action="" enctype ="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="0" id="table1">
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Username</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="username" size="30" value="'.$username.'" /></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Password</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input type="password" name="password" size="30" value="'.$password.'" /></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Email</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="email" size="40" value="'.$email.'" /></td>
						</tr>
						<tr>
							<td style="padding-right: 10px; padding-top: 5px">Nama</td>
							<td style="padding-top: 5px">:</td>
							<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="name" size="40" value="'.$name.'" /></td>
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
		$id 	 = int_filter($_GET['id']);
		$referer = $_GET['referer'];
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-title">Edit <span class="styled1">User</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=users#users-1" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit User</div>
		</div>
		<div class="border">
			<form method="post" action="?mod=users" enctype ="multipart/form-data">
			<table style="font-family:Verdana;font-size:12px;">
				<tr>
					<td style="padding-right:10px;width:70px;">Pencarian</td>
					<td> :</td>
					<td style="padding-left:5px;padding-top:1px;"><input type="text" name="search" size="50" placeholder="Masukkan Nama, Email atau Username" /></td>
					<td><button type="submit" class="primary"><span class="icon plus"></span>Search</button></td>
				</tr>
			</table>
			</form>
		</div>';
				
		if(isset($_POST['submit'])){
			$username 		= text_filter($_POST['username']);
			$password 		= text_filter($_POST['password']);
			$name			= text_filter($_POST['name']);
			$email			= text_filter($_POST['email']);
		
			$error			= '';
					
			if (!$username) $error .= "Error: Please input username.<br />";
			if (!$password)	$error .= "Error: Please input password.<br />";
			if (!$name)  	$error .= "Error: Please input name.<br />";
			if (!$email)  		$error .= "Error: Please input Email.<br />";
					
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	
				$password	= md5($username.$password);

				$success = $db->sql_query("UPDATE `mod_user` SET `username`='$username', `password`='$password',`name`='$name',`email`='$email' WHERE `id`='$id' AND `id`!='1'");

				if($success){
					$tengah .= '<div class="success">User Has Been Update</div>';
					header("location: $referer");
					exit;
				}else{
					$tengah .= '<div class="error">'.mysql_error().'</div>';
				}					
			}
					
		}
		$data = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_user` WHERE `id`='$id' AND `id`!='1'"));
		
		$username 	= $data['username'];
		$email 		= $data['email'];
		$name 		= $data['name'];
		$password 	= !isset($password) ? '' : $password;
		
		$tengah .= '
		<div class="border rb">
		<form method="post" action="" enctype ="multipart/form-data">
		<table border="0" cellspacing="0" cellpadding="0" id="table1">
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Username</td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="username" size="30" value="'.$username.'" readonly /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Password</td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="password" name="password" size="30" value="'.$password.'" /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Email</td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="email" size="40" value="'.$email.'" readonly /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 5px">Nama</td>
				<td style="padding-top: 5px">:</td>
				<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="name" size="40" value="'.$name.'" /></td>
			</tr>
			<tr>
				<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
				<td style="padding-top: 15px">&nbsp;</td>
				<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Submit</button></td>
			</tr>
		</table>		         	                       
		</form>
		</div>';
	}
	
	if ($_GET['action'] == 'pub'){	
		if ($_GET['pub'] == 'no'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_user` SET `active`='0' WHERE `id`='$id' AND `id`!='1'");		
		}	
		
		if ($_GET['pub'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_user` SET `active`='1' WHERE `id`='$id' AND `id`!='1'");		
		}	
		$referer = $_GET['referer'];
		header("location: $referer");
		exit;
	}
	
	if($_GET['action'] == 'delete'){
		$id 	 = int_filter($_GET['id']);
		$referer = $_GET['referer'];
		if($id == 1){
			header("location: $referer");
		}
		$delete = mysql_query("DELETE FROM `mod_user` WHERE `id` = '$id' AND `id`!='1'");
		if ($delete) {
			header("location: $referer");
			exit;	
		}else {
			$tengah .= '<div class="error">'.mysql_error().'</div>';	
		}
	}
	
echo $tengah;
?>
