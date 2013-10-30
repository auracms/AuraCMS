<?php

	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;
	
	if (isset ($_GET['pg'])) 		$pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) 		$stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) 	$offset = int_filter ($_GET['offset']); else $offset = 0;
	
	$script_include[] = <<<js
	<script src="js/tools.js"></script>
    <script language="javascript">
		function findpages() {
        	var addr = "mod/menu/pages.php";
        	newWindow(addr, 'CetakDetail','600','400','resizable=1,scrollbars=yes,status=0,toolbar=0,top=30px');
        }
    </script>
js;
	
	$tengah  = '
	<div class="box">
	<h2 class="widget-title">Menu <span class="styled1">Manager</span></h2>
	<div class="breadcrumb"><a href="admin.php?mod=menu" id="home">Home</a>   &nbsp;&raquo;&nbsp;   <a href="">Menu Manager</a></div>
	</div>';
	
	
	if($_GET['action'] == ''){
		
	
		$tengah .= '
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Home</a></li>
					<li><a href="#tabs-2">Add Menu</a></li>
				</ul>
				<div id="tabs-1">
				<div class="border rb">
				<form method="post" action="" id="namaform">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Menu Title</td>
			            <td style="text-align: center;width:80px;">Position</td>
			            <td style="text-align: center;width:80px;">Published</td>
			            <td style="text-align: center;width:80px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
			        $query 	= $db->sql_query("SELECT `id` FROM `mod_menu` WHERE `parentid`='0' ORDER BY `id` DESC");
					$jumlah = $db->sql_numrows ($query);

					$limit 	= 5;
					$a 		= new paging ($limit);
					$ref 	= urlencode($_SERVER['REQUEST_URI']);
					$q	 	= $db->sql_query ("SELECT * FROM `mod_menu` WHERE `parentid`='0' ORDER BY `id` ASC LIMIT $offset, $limit");
					
					if(isset($offset)){
						$no = $offset + 1;
					}else{
						$no = 1;
					}
					
					while ($data = $db->sql_fetchrow($q)){
						$warna 		= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
						$published 	= ($data['published'] == 1) ? '<a class="enable" href="?mod=menu&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=menu&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';
						$id			= $data['id'];
						$tengah .= '
						<tr'.$warna.'>
				            <td style="text-align: center;">'.$no.'</td>
				            <td class="left">'.$data['title'].'</td>
				             <td style="text-align: center;">'.$data['position'].'</td>
				            <td style="text-align: center;">'.$published.'</td>
				            <td style="text-align: center;"><a class="edit" href="admin.php?mod=menu&amp;action=edit&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=menu&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
						</tr>';
						
						$qs = $db->sql_query ("SELECT * FROM `mod_menu` WHERE `parentid`='$id' ORDER BY `id` ASC");
						while ($datas = $db->sql_fetchrow($qs)){
                            $idsub  = $datas['id'];
							$colour = empty ($colour) ? ' style="background-color:#f9f9f9;"' : '';
							$publisheds = ($datas['published'] == 1) ? '<a class="enable" href="?mod=menu&amp;action=pub&amp;pub=no&amp;id='.$datas['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=menu&amp;action=pub&amp;pub=yes&amp;id='.$datas['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

							$tengah .= '
							<tr'.$colour.'>
					            <td style="text-align: center;">&nbsp;</td>
					            <td class="left">&nbsp;&raquo;&nbsp; '.$datas['title'].'</td>
					            <td style="text-align: center;">&nbsp;</td>
					            <td style="text-align: center;">'.$publisheds.'</td>
					            <td style="text-align: center;"><a class="edit" href="admin.php?mod=menu&amp;action=edit&amp;id='.$datas['id'].'&amp;referer='.$ref.'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=menu&amp;action=delete&amp;id='.$datas['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
							</tr>';

                            $qsub = $db->sql_query ("SELECT * FROM `mod_menu` WHERE `parentid`='$idsub' ORDER BY `id` ASC");
    						while ($datasub = $db->sql_fetchrow($qsub)){

    							$colour = empty ($colour) ? ' style="background-color:#f9f9f9;"' : '';
    							$publishedsub = ($datasub['published'] == 1) ? '<a class="enable" href="?mod=menu&amp;action=pub&amp;pub=no&amp;id='.$datasub['id'].'&amp;referer='.$ref.'" title="Enable">Enable</a>' : '<a class="disable" href="?mod=menu&amp;action=pub&amp;pub=yes&amp;id='.$datasub['id'].'&amp;referer='.$ref.'" title="Disable">Disable</a>';

    							$tengah .= '
    							<tr'.$colour.'>
    					            <td style="text-align: center;">&nbsp;</td>
    					            <td class="left">&nbsp;&raquo;&nbsp; &raquo;&nbsp; '.$datasub['title'].'</td>
    					            <td style="text-align: center;">&nbsp;</td>
    					            <td style="text-align: center;">'.$publishedsub.'</td>
    					            <td style="text-align: center;"><a class="edit" href="admin.php?mod=menu&amp;action=edit&amp;id='.$datasub['id'].'&amp;referer='.$ref.'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=menu&amp;action=delete&amp;id='.$datasub['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
    							</tr>';
    						}
						}
						$no++;
					}
					
				$tengah .= '	
			        </tbody>
			    </table>
			    </form>
			    </div>';
			    $tengah .= $a-> getPaging($jumlah, $pg, $stg);	
			    $tengah .= '			
				</div>
				<div id="tabs-2">';
				if(isset($_POST['submit'])){
					$title		= cleantext($_POST['title']);
					$urls		= cleantext($_POST['url']);
					$position	= cleantext($_POST['position']);
					$parentid	= $_POST['parentid'];
					$error		= '';
					
					if (!$title)	$error .= "Error: Silahkan Isi Nama Menunya!<br />";
					if (!$urls)    	$error .= "Error: Silahkan Isi Url menunya!<br />";
					
					if($error){
						$tengah .= '<div class="error">'.$error.'</div>';
					}else{
						$cekmax 	= $db->sql_query ("SELECT (MAX(`ordering`)+1) FROM `mod_menu`");
						$getcekmax 	= $db->sql_fetchrow($cekmax);
						$ordering	= $getcekmax[0];
						$insert	 	= $db->sql_query( "INSERT INTO `mod_menu` (`title`,`url`,`published`,`parentid`,`position`,`ordering`) VALUES ('$title','$urls','1','$parentid','$position','$ordering')" );
						if($insert){
							$tengah .= '<div class="success">Menu berhasil di buat</div>';
							unset($title);
							unset($urls);
							unset($parentid);
							unset($position);
						}else{
							$tengah .= '<div class="success">Menu Gagal di buat</div>';
						}			
					}
				}
				$title 		= !isset($title) ? '' : $title;
				$urls 		= !isset($urls) ? '' : $urls;
				$parentid	= !isset($parentid) ? '' : $parentid;
				$position	= !isset($position) ? '' : $position;
				$tengah .= '
				<div class="border rb">
				<form method="post" action="" id="frm" name="frm">
				<table border="0">
					<tr>
						<td style="padding-bottom: 5px">Title</td>
						<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">:</td>
						<td style="padding-bottom: 5px"><input type="text" name="title" size="30" value="'.$title.'"></td>
					</tr>
					<tr>
						<td style="padding-bottom: 5px">Url</td>
						<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">:</td>
						<td style="padding-bottom: 5px"><input type="text" name="url" size="40" value="'.$urls.'"> <a href="#" onclick="findpages()"> <img src="images/search.png" alt=""></a></td>
					</tr>
					<tr>
						<td style="padding-bottom: 5px">Parent</td>
						<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">:</td>
						<td style="padding-bottom: 5px"><select name="parentid"><option value="0">-- No Parent --</option>';            
						$query	= $db->sql_query( "SELECT `id`,`title` FROM `mod_menu` WHERE `parentid`='0' ORDER BY `title` ASC" );
						while ($data  = $db->sql_fetchrow($query)) {
						    $id       = $data['id'];
							$selected = ($data['id']== $parentid)? 'selected':'';
							$tengah .='<option value="'.$data['id'].'" '.$selected.'>'.$data['title'].'</option>';

                            $qsub	= $db->sql_query( "SELECT `id`,`title` FROM `mod_menu` WHERE `parentid`='$id' ORDER BY `title` ASC" );
    						while ($datasub  = $db->sql_fetchrow($qsub)) {
    							$selected = ($datasub['id']== $parentid)? 'selected':'';
    							$tengah .='<option value="'.$datasub['id'].'" '.$selected.'>'.$data['title'].' &raquo; '.$datasub['title'].'</option>';
    						}
						}
					$tengah .='
						</select>
						</td>
					</tr>
					<tr>
						<td style="padding-bottom: 15px">Position</td>
						<td style="padding-bottom: 15px; padding-left:10px; padding-right:10px">:</td>
						<td style="padding-bottom: 15px"><select name="position"><option value="top">Top</option><option value="block">Block</option></select></td>
					</tr>
					<tr>
						<td style="padding-bottom: 5px">&nbsp;</td>
						<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">&nbsp;</td>
						<td style="padding-bottom: 5px"><button name="submit" class="primary"><span class="icon plus"></span>Submit</button></td>
					</tr>
				</table>
				</form>
				</div>
				</div>
			</div>
		</div>';
	}
	
	
	if($_GET['action'] == 'edit'){
		$id     = int_filter($_GET['id']);
		$referer = $_GET['referer'];
		if(isset($_POST['submit'])){
			$title		= cleantext($_POST['title']);
			$url		= cleantext($_POST['url']);
			$position	= cleantext($_POST['position']);
			$parentid	= $_POST['parentid'];
			$error		= '';
			
			if (!$title)	$error .= "Error: Silahkan Isi Nama Menunya!<br />";
			if (!$url)    	$error .= "Error: Silahkan Isi Url menunya!<br />";
			
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{
				$insert	 	= $db->sql_query( "UPDATE `mod_menu` SET `title`='$title',`url`='$url',`parentid`='$parentid',`position`='$position' WHERE `id`='$id'" );
				if($insert){
					$tengah .= '<div class="success">Menu berhasil di Edit</div>';					
					$style_include[] ='<meta http-equiv="refresh" content="0; url='.$referer.'" />';
				}else{
					$tengah .= '<div class="success">'.mysql_error().'</div>';
				}			
			}
		}
		$query 	= $db->sql_query ("SELECT * FROM `mod_menu` WHERE `id`='$id'");
		$data 	= $db->sql_fetchrow($query);
		$title 		= $data['title'];
		$url 		= $data['url'];
		$parentid	= $data['parentid'];
		$position	= $data['position'];
		$tengah .= '
		<div class="border rb">
		<form method="post" action="" id="frm" name="frm">
		<table border="0">
			<tr>
				<td style="padding-bottom: 5px">Title</td>
				<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">:</td>
				<td style="padding-bottom: 5px"><input type="text" name="title" size="30" value="'.$title.'"></td>
			</tr>
			<tr>
				<td style="padding-bottom: 5px">Url</td>
				<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">:</td>
				<td style="padding-bottom: 5px"><input type="text" name="url" size="40" value="'.$url.'">  <a href="#" onclick="findpages()"> <img src="images/search.png" alt=""></a></td>
			</tr>
			<tr>
				<td style="padding-bottom: 5px">Parent</td>
				<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">:</td>
				<td style="padding-bottom: 5px"><select name="parentid"><option value="0">-- No Parent --</option>';            
				$query	= $db->sql_query( "SELECT `id`,`title` FROM `mod_menu` WHERE `parentid`='0' ORDER BY `title` ASC" );
				while ($data  = $db->sql_fetchrow($query)) {
				    $id       = $data['id'];
					$selected = ($data['id']== $parentid)? 'selected':'';
					$tengah .='<option value="'.$data['id'].'" '.$selected.'>'.$data['title'].'</option>';

                          $qsub	= $db->sql_query( "SELECT `id`,`title` FROM `mod_menu` WHERE `parentid`='$id' ORDER BY `title` ASC" );
  						while ($datasub  = $db->sql_fetchrow($qsub)) {
  							$selected = ($datasub['id']== $parentid)? 'selected':'';
  							$tengah .='<option value="'.$datasub['id'].'" '.$selected.'>'.$data['title'].' &nbsp;&raquo;&nbsp; '.$datasub['title'].'</option>';
  						}
				}
			$tengah .='
				</select>
				</td>
			</tr>
			<tr>
				<td style="padding-bottom: 15px">Position</td>
				<td style="padding-bottom: 15px; padding-left:10px; padding-right:10px">:</td>
				<td style="padding-bottom: 15px"><select name="position">';
				if($position == 'top'){
					$tengah .= '<option value="top" selected>Top</option><option value="block">Block</option>';
				}else{
					$tengah .= '<option value="top">Top</option><option value="block" selected>Block</option>';
				}
			$tengah .= '	
				</select></td>
			</tr>
			<tr>
				<td style="padding-bottom: 5px">&nbsp;</td>
				<td style="padding-bottom: 5px; padding-left:10px; padding-right:10px">&nbsp;</td>
				<td style="padding-bottom: 5px"><button name="submit" class="primary"><span class="icon plus"></span>Submit</button></td>
			</tr>
		</table>
		</form>
		</div>';
		
	}
	
	if($_GET['action'] == 'delete'){
		$id 		= int_filter($_GET['id']);
		$delete = mysql_query("DELETE FROM `mod_menu` WHERE `id` = '$id'");
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
			$db->sql_query ("UPDATE `mod_menu` SET `published`='0' WHERE `id`='$id'");		
		}	
		
		if ($_GET['pub'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_menu` SET `published`='1' WHERE `id`='$id'");		
		}	
		$referer = $_GET['referer'];
		header("location: $referer");
		exit;
	}
	
	
	echo $tengah;