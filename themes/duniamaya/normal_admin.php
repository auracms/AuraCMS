<?php

	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;

	$tengah  = '';
	//$tengah .= '<div id="dragbox">';
	$tengah .= '
	<div class="box" style="min-height:260px;">';
	$s = mysql_query ("SELECT * FROM `mod_admin` ORDER BY `ordering` ASC");
	$i =  0;
	while($data = mysql_fetch_array($s)){
		$warna = empty ($warna) ? 'background-color:#f4f4f8;' : '';
		if (($i % 6) == 0){
			$margin 	= 'margin:0 3px 3px 0';
		}else{
			$margin = 'margin:0 3px 3px 0';
		}
	
		$menu 	= $data['mod'] == 1 ? 'admin.php?mod='.$data['url'] : $data['url'];
		if ($data['url']== '?action=logout') {
			$images = 'images/logout.png';
		}else{
			$images	= $data['mod'] == 1 ? 'mod/'.$data['url'].'/images/admin_'.$data['url'].'.png' : 'images/'.basename($data['url'],'.php').'.png';
		}

		$tengah .= '<div class="border rb rt" style="text-align:center;vertical-align: middle;width:97px;'.$margin.';min-height:50px;float:left;'.$warna.'"><a href="'.$menu.'" title="'.$data['menu'].'"><img src="'.$images.'" alt="'.$data['menu'].'" border="0" /></a><br /><a href="'.$menu.'" title="'.$data['menu'].'">'.$data['menu'].'</a></div>';
		$i++;
	}
	$tengah .= '
	</div><div style="clear:both;"></div>';
	$tengah .= '
	<div class="sorts">
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Content</a></li>
				<li><a href="#tabs-2">Pages</a></li>
				<li><a href="#tabs-3">Video</a></li>
			</ul>
			<div id="tabs-1">';
			
			$no = 1;
				
			$b 		= $db->sql_query("SELECT * FROM `mod_content` WHERE `type`='news' ORDER BY `date` DESC LIMIT 0,15");
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
			</div>
			</div>
			<div id="tabs-2">';
			$no = 1;
				
			$a 		= $db->sql_query("SELECT * FROM `mod_content` WHERE `type`='pages' ORDER BY `date` DESC LIMIT 0,15");
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
				while($data = $db->sql_fetchrow($a)){
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
			</div>
			</div>
			<div id="tabs-3">';
			$no = 1;
				
			$c 		= $db->sql_query("SELECT * FROM `mod_video` ORDER BY `date` DESC LIMIT 0,5");
			$ref 	= urlencode($_SERVER['REQUEST_URI']);
			$tengah .= '
			<div class="border rb">
			<table class="list">
				<thead>
					<tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Video Title</td>
			            <td style="text-align: center;width:80px;">Thumbnail</td>
			            <td style="text-align: center;width:80px;">Action</td>
					</tr>
				</thead>
			    <tbody>';
				while($data = $db->sql_fetchrow($c)){
					$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$data['title'].'</td>
			            <td style="text-align: center;"><img src="http://i2.ytimg.com/vi/'.$data['code'].'/default.jpg" border="0" alt=""></td>
			            <td style="text-align: center;"><a class="edit" href="admin.php?mod=video&amp;action=edit&amp;id='.$data['id'].'" title="Edit">Edit</a> <a class="delete" href="admin.php?mod=video&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" title="Delete">Delete</a></td>
					</tr>';
					$no++;					
				}
				$tengah .= '
				</tbody>
			</table>
			</div>			
			</div>
		</div>
	</div>';
	
	
	
	
	//$tengah .= '</div>';
	
	
	
	echo $tengah;