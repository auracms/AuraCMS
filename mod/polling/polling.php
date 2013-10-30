<?php

/**
 *	
 * 	March 02, 2012  , 09:32:55 PM   
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */

	if (!defined('INDEX')) {
	    Header("Location: ../index.php");
	    exit;
	}
	
	if(!function_exists('percent_poll')){
		function percent_poll($hit,$jum){	
			if($jum!='') return sprintf("%01.1f",(($hit/$jum)*100));
			else return 0;
		}
	}
	
	if(!function_exists('hit_poll')){
		function hit_poll($array) {	 
			$hits = 0;
			foreach($array as $key => $val) $hits = $hits + $val;			
			return $hits; 
		}
	}

	
	
	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;

	$tengah = '
	<h2>Polling</h2>
	<div class="border" style="text-align:center;"><img src="mod/polling/images/polling.jpg" alt="Polling" /></div>';

	if(isset($_POST['submit'])){
		
		if (cek_posted('polling')){
			$tengah .= '<div class="error">Anda Telah Memvoting, Tunggu beberapa Saat lagi...!</div>';
		}else{
			$poll_id = int_filter($_POST['poll_id']);
			$answer  = int_filter($_POST['answers']);
			
			$query 	 	= $db->sql_query("SELECT * FROM `mod_polling` WHERE `id`='$poll_id' AND `published`='1' ORDER BY `id` DESC");
			$data 	 	= $db->sql_fetchrow($query);
			$content 	= jdecode($data['content']);
			
			$question	= $content->question;
			$answers 	= $content->answers;
		
			$i = 0;
			$arr = array();
			foreach($answers as $key => $val){
				if($i == $answer) $arr[$key]= $val+1;
				else $arr[$key]= $val;
			$i++;
			}
			
			$compile 	= array('question' => $question, 'answers' => $arr);	
			$content	= jencode($compile);
			
			$db->sql_query("UPDATE `mod_polling` SET `content`='$content' WHERE `id`='$poll_id' AND `published`='1'");
			posted('polling');
			
		}
	
	}
	
	$query 	 	= $db->sql_query("SELECT * FROM `mod_polling` WHERE `published`='1' ORDER BY `id` DESC");
	$data 	 	= $db->sql_fetchrow($query);
	$content 	= jdecode($data['content']);
	$q			= $content->question;
	$a			= $content->answers;
	
	$jumlah 	= hit_poll($a); 
	
	$tengah .= '
	<h2>'.$q.'</h2>
	<div class="border rb">
	<table width="100%">
	<tr>
		<th width="50%"><span class="green">Answer</span></th>
		<th><span class="green">Percentacy</span></th>
		<th align="center"><span class="green">Vote</span></th>
	</tr>';
	
	foreach($a as $key => $val){
		$tengah .= '
		<tr>
			<td>'.$key.'</td>
			<td>
				<table>
					<tr>
						<td><img src="mod/polling/images/bar.gif" width="'.percent_poll($val,$jumlah).'" height="9" />  '.percent_poll($val,$jumlah).'%</td>
					</tr>
				</table>
			</td>
			<td style="text-align:center;">'.$val.'</td>
		</tr>';
	}
	$tengah .= '
	</table>
	</div>';
	
	echo $tengah;