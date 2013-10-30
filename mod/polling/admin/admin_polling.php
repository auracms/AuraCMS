<?php
	
	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;

	$script_include[] = <<<js
	<!-- TinyMCE -->
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/init.js"></script>
	<!-- /TinyMCE -->
js;
	//include 'includes/json.php';
	$script_include[] = '<script type="text/javascript" src="mod/polling/js/jquery.numeric.js"></script>';

	$tengah  = '';

	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;
	
	function escape ($value){
		$b = mysql_real_escape_string($value);
		return $b;
	}
	
	if(!function_exists('countpolling')){
		function countpooling($array) { 		
			$counts = 0;
			foreach($array as $x) $counts++;			
			return $counts;	
		}
	}
	
	if(!function_exists('array_real_combine')){
		function array_real_combine($a, $b){
			return $a===array() && $b===array() ? array() : array_combine($a, $b);
		}
	}

	if($_GET['action'] ==''){
		
		$script_include[] = <<<jquery
		<script type="text/javascript">
			$(document).ready(function(){
				
				
			$("input.numeric").numeric();
	
	 		var num = 3;
	        $("a#tambah").click(function() {
		        num++;
		       if ($(document).find('tr#fileinputs' + (num-1)).html() == null) {
			       var row = $('tr#fileinputs').clone(true).insertAfter('tr#fileinputs');
		       }else {
		       		var row = $('tr#fileinputs').clone(true).insertAfter('tr#fileinputs' + (num-1));
	       		}
	           $(row).attr('id', 'fileinputs' + num);
	           var text = $(row).text();
	           var r = text.replace(/([a-z])([ ])(\d)/, "$1 " + num);
	           var newrow = $('tr#fileinputs' + num + " td#filenumber");
	           newrow.attr('id','filenumber' + num);
	           newrow.text(r);          
	           
	        });	
			});
		</script>
jquery;
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-question">Polling <span class="styled1">Manager</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=polling" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Polling Manager</a></div>
		</div>		
		<div class="sorts">
			<div id="tabs">
				<ul>
					<li><a href="#polling-1">Home</a></li>
					<li><a href="#polling-2">Add Polling</a></li>
				</ul>
				<div id="polling-1">';
				$a 		= $db->sql_query("SELECT * FROM `mod_polling` ORDER BY `id` DESC");
				$jumlah = $db->sql_numrows($a);
				$limit 	= 15;
				
				$a 		= new paging ($limit);

				if(isset($offset)){
					$no = $offset + 1;
				}else{
					$no = 1;
				}
				
				$b 		= $db->sql_query("SELECT * FROM `mod_polling` ORDER BY `id` DESC LIMIT $offset,$limit");
				$ref 	= urlencode($_SERVER['REQUEST_URI']);
				$tengah .= '
				<div class="border rb">
				<table class="list">
			        <thead>
			          <tr class="head">
			            <td style="text-align: center;width:30px;">No</td>
			            <td class="left">Polling</td>
			            <td style="text-align: center;width:80px;">Published</td>
			            <td style="text-align: center;width:80px;">Action</td>
			          </tr>
			        </thead>
			        <tbody>';
				while($data = $db->sql_fetchrow($b)){
					$warna 		= empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
					$content 	= jdecode($data['content']);
					$published 	= ($data['published'] == 1) ? '<a class="enable" href="?mod=polling&amp;action=pub&amp;pub=no&amp;id='.$data['id'].'&amp;referer='.$ref.'" question="Enable">Enable</a>' : '<a class="disable" href="?mod=polling&amp;action=pub&amp;pub=yes&amp;id='.$data['id'].'&amp;referer='.$ref.'" question="Disable">Disable</a>';

					$tengah .= '
					<tr'.$warna.'>
			            <td style="text-align: center;">'.$no.'</td>
			            <td class="left">'.$content->question.'</td>
			            <td style="text-align: center;">'.$published.'</td>
			            <td style="text-align: center;"><a class="edit" href="admin.php?mod=polling&amp;action=edit&amp;id='.$data['id'].'" question="Edit">Edit</a> <a class="delete" href="admin.php?mod=polling&amp;action=delete&amp;id='.$data['id'].'&amp;referer='.$ref.'" question="Delete">Delete</a></td>
					</tr>';
					$no++;					
				}
				$tengah .= '
					</tbody>
				</table>
				</div>';
				$tengah .= $a-> getPaging($jumlah, $pg, $stg);
				
				$tengah .= '
				</div>
				<div id="polling-2">';
		  
					if(isset($_POST['submit'])){
						$question 	= $_POST['question'];
						$answers 	= $_POST['answers'];
					
						$arr = array();
						foreach($answers as $val) $arr[$val]= 0;
						
						$compile 	= array('question' => $question, 'answers' => $arr);	
						$content	= jencode($compile);
		
						$error		 = '';
						
						if (!$question)  		$error .= 'Error: Please input Title.<br />';
						
						if($error){
							$tengah .= '<div class="error">'.$error.'</div>';
						}else{	
							$success  = $db->sql_query("INSERT INTO `mod_polling` (`content`,`published`) VALUES ('$content','0')");
							if($success){
								$tengah .= '<div class="success">Polling Has Been Added in Database</div>';
								unset($download);
							}else{
								$tengah .= '<div class="error">Polling Can`t Added in Database</div>';
							}
						}
						
					}
					$question 		 = !isset($question) ? '' : $question;
					$answers 		 = !isset($answers) ? '' : $answers;
					
					$tengah .= '
					<div class="border rb">
						<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
						<table border="0" cellspacing="0" cellpadding="0" id="table1">
							<tr>
								<td style="padding-right: 10px; padding-top: 5px">Question</td>
								<td style="padding-top: 5px"></td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="question" id="f1" value="'.$question.'" /></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px" valign="top">Answers 1</td>
								<td style="padding-top: 5px" valign="top"></td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="answers[]" /></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px" valign="top">Answers 2</td>
								<td style="padding-top: 5px" valign="top"></td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="answers[]" /></td>
							</tr>
							<tr id="fileinputs">
								<td id="filenumber" style="padding-right: 10px; padding-top: 5px" valign="top">Answers 3</td>
								<td style="padding-top: 5px" valign="top"></td>
								<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="answers[]" /></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 5px" valign="top"></td>
								<td style="padding-top: 5px" valign="top"></td>
								<td style="padding-left: 5px; padding-top: 5px"><a href="javascript:void(0);" id="tambah">Add Form</a></td>
							</tr>
							<tr>
								<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
								<td style="padding-top: 15px">&nbsp;</td>
								<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon pen"></span>Submit</button> <button name="reset" class="primary"><span class="icon loop"></span>Reset</button></td>
							</tr>
						</table>                         
						</form>
					</div>
				</div>
			</div>
		</div>';


	}
	
	if($_GET['action'] =='edit'){
		$id = int_filter($_GET['id']);
		
		
		
		$tengah .= '
		<div class="box">
		<h2 class="widget-question">Edit <span class="styled1">Polling</span></h2>
		<div class="breadcrumb"><a href="admin.php?mod=polling" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Edit Polling</div>
		</div>';
		if(isset($_POST['submit'])){

			$question	 = cleantext($_POST['question']);
			$answers 	 = $_POST['answers'];
			$hits 		 = $_POST['hits'];

			$error		 = '';
						
			if (!$question)  		$error .= 'Error: Please input Question.<br />';
			
			if($error){
				$tengah .= '<div class="error">'.$error.'</div>';
			}else{	

				$arr = $opt = $hit = array();
				foreach($answers as $k => $v) 
				if(!empty($v)) $opt[$k] = $v;
				
				foreach($hits as $k => $v)
				if(!empty($v)) $hit[$k] = $v;
				else $hit[$k] = 0;
		
				$arr = array_real_combine($opt, $hit);
				
				$compile 	= array('question' => $question, 'answers' => $arr);	
				$content	= jencode($compile);	

				$success = $db->sql_query("UPDATE `mod_polling` SET `content`='$content' WHERE `id`='$id'");
				if($success){
					$tengah .= '<div class="success">Polling Has Been Update</div>';
					$style_include[] = '<meta http-equiv="refresh" content="1; url=admin.php?mod=polling" />';						
				}else{
					$tengah .= '<div class="error">Polling Can`t be Update</div>';
				}
			}					
		}
			
		
		$data 	 = $db->sql_fetchrow($db->sql_query("SELECT * FROM `mod_polling` WHERE `id`='$id'"));
		$content 	= jdecode($data['content']);
		$question	= $content->question;
		$answers	= $content->answers;
		$jumlah		= countpooling($answers);
		
		$script_include[] = <<<jquery
		<script type="text/javascript">
			$(document).ready(function(){
				
				
			$("input.numeric").numeric();
	
	 		var num = $jumlah;
	        $("a#tambah").click(function() {
		        num++;
		       	if ($(document).find('tr#fileinputs' + (num-1)).html() == null) {
			       	var row = $('tr#fileinputs').clone(true).insertAfter('tr#fileinputs');
		       	}else {
		       		var row = $('tr#fileinputs').clone(true).insertAfter('tr#fileinputs' + (num-1));
				}
	           	$(row).attr('id', 'fileinputs' + num);
	           	var text = $(row).text();
	           	var r = text.replace(/([a-z])([ ])(\d)/, "$1 " + num);
	           	var newrow = $('tr#fileinputs' + num + " td#filenumber");
	           	newrow.attr('id','filenumber' + num);
	           	newrow.text(r);          
	           
	        });	
			});
		</script>
jquery;

		$tengah .= '
		<div class="border rb">
			<form name="frm" id="frm"  method="post" action="" enctype ="multipart/form-data">
			<table border="0" cellspacing="0" cellpadding="0" id="table1">
				<tr>
					<td style="padding-right: 10px; padding-top: 5px">Question</td>
					<td style="padding-top: 5px">:</td>
					<td style="padding-left: 5px; padding-top: 5px"><input type="text" name="question" value="'.$question.'" /></td>
				</tr>';
		$i = 1;
		foreach($answers as $key => $val){		
			if($i == $jumlah){
				$fileinput = ' id="fileinputs"';
				$filenumber= ' id="filenumber"';
			}else{
				$fileinput = '';
				$filenumber= '';
			}	
			$tengah .= '			
				<tr'.$fileinput.'>
					<td style="padding-right: 10px; padding-top: 5px" valign="top">Answers '.$i.'</td>
					<td style="padding-top: 5px" valign="top"></td>
					<td'.$filenumber.' style="padding-left: 5px; padding-top: 5px"><input type="text" name="answers['.$i.']" value="'.$key.'" /> <input type="text" name="hits['.$i.']" style="width:80px; margin-left:2px;" value="'.$val.'"  /></td>
				</tr>';
		$i++;
		}
			$tengah .= '				
				<tr>
					<td style="padding-right: 10px; padding-top: 15px">&nbsp;</td>
					<td style="padding-top: 15px">&nbsp;</td>
					<td style="padding-left: 5px; padding-top: 15px"><button name="submit" class="primary"><span class="icon plus"></span>Update</button></td>
				</tr>
			</table>                         
			</form>
		</div>';
	}
	
	


	if($_GET['action'] == 'delete'){
		$id 	= int_filter($_GET['id']);
		$delete = $db->sql_query("DELETE FROM `mod_polling` WHERE `id` = '$id'");
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
			$db->sql_query ("UPDATE `mod_polling` SET `published`='0' WHERE `id`='$id'");		
		}	
		
		if ($_GET['pub'] == 'yes'){	
			$id = int_filter ($_GET['id']);	
			$db->sql_query ("UPDATE `mod_polling` SET `published`='0'");
			$db->sql_query ("UPDATE `mod_polling` SET `published`='1' WHERE `id`='$id'");		
		}	
		$referer = $_GET['referer'];
		header("location: $referer");
		exit;
	}
	
	echo $tengah;