<?php

/**
 *	
 * 	June 29, 2012 , 09:32:55 PM   
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */

	if (!defined('INDEX')) {
	    Header("Location: ../index.php");
	    exit;
	}


	ob_start();
	
	
	
	$query 	 	= $db->sql_query("SELECT * FROM `mod_polling` WHERE `published`='1' ORDER BY `id` DESC");
	$data 	 	= $db->sql_fetchrow($query);
	$content 	= jdecode($data['content']);
	$question	= $content->question;
	$answers	= $content->answers;
	
	echo '<form method="post" action="polling.html">';
	echo '<div style="font-size:17px;">'.$question.'</div>';
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="5">';
	$i = 0;
	$warna = '';
	foreach($answers as $key => $val){
		if($i == 0) $cheked = 'checked="checked"';
		else $cheked = "";
		$warna = empty ($warna) ? '' : '';
		echo '
		<tr'.$warna.'>
	    	<td width="1%" valign="middle"><input id="answers'.$i.'" name="answers" type="radio" value="'.$i.'" '.$cheked.'/></td>
	    	<td valign="middle" style="padding-left:5px;"><label for="answers'.$i.'">'.$key.'</label></td>
	  	</tr>';
		$i++; 
	}
	echo '
	<tr>
	<td style="padding-top:5px;" width="1%" valign="middle"></td>
	<td style="padding-top:5px;" valign="middle"><button name="submit" class="btn_submit"><span class="icon plus"></span>Vote</button><input type="hidden" name="poll_id" value="'.$data['id'].'" /> <button name="result" class="btn_submit"><span class="icon plus"></span>Result</button></td>
	</tr>
	</table>
	</form>';
	
	
	
	
	$out = ob_get_contents();
	ob_end_clean();