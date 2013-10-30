<?php

	
	
	include '../../includes/session.php';
	include '../../includes/conection.php';
	include '../../includes/mysql.php';
	include '../../includes/global.php';
	include '../../includes/fungsi.php';
	
	if (!cek_login ()) exit;

	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;

	echo <<<js
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>List of Pages</title>
	<style>
		body {
		margin: 0;
		padding: 0;
		font: 12px  verdana,arial, sans-serif;
		color: #222222; 
	}
	.border {
		background-color: #fff;
		border: 1px solid #D9D9D9;
		padding: 5px;
		margin: 5px -2px 5px 0;

	}
	td{
		font: 12px  verdana,arial, sans-serif;
	}
	
	h2 {
		background-color: #F6F6F6;
		border: 1px solid #D9D9D9;
		padding: 6px 5px 6px 5px;
		margin: 5px 10px 5px 10px;
		-moz-border-radius-topright: 5px;
		-moz-border-radius-topleft: 5px;
		-webkit-border-top-right-radius: 5px;
		-webkit-border-top-left-radius: 5px;
		font-size:16px;
		color:#003a88;
	}

	
	/* tabel */
	.list {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;
	}
	.list td {
		border-right: 1px solid #DDDDDD;
		border-bottom: 1px solid #DDDDDD;
	}
	.list thead td {
		background-color: #E7EFEF;
		padding: 10px 5px;
	}
	.list thead td a, .list thead td {
		text-decoration: none;
		color: #222222;
		font-weight: bold;
	}
	.list tbody a {
		text-decoration: underline;
	}
	.list tbody td {
		vertical-align: middle;
		padding: 0px 5px;
	}
	.list tbody tr:odd {
		background: #FFFFFF;
	}
	.list tbody tr:even {
		background: #E4EEF7;
	}
	.list tbody tr:hover > td {
		background-color: #FBF3EB !important;
	}
	.list .left {
		text-align: left;
		padding: 7px;
	}
	.list .right {
		text-align: right;
		padding: 7px;
	}
	.list .center {
		text-align: center;
		padding: 7px;
	}
	.list .asc {
		padding-right: 15px;
		background: url('../image/asc.png') right center no-repeat;
	}
	.list .desc {
		padding-right: 15px;
		background: url('../image/desc.png') right center no-repeat;
	}
	.list .filter td {
		padding: 5px;
		background: #E7EFEF;
	}

	/* Paging */
	.paging {
		margin-top: 10px;
		display: inline-block;
		width: 100%;
		text-align:center;
	}

	.paging a {
		border: 1px solid #CCCCCC;
		padding: 4px 7px;
		text-decoration: none;
		background: #F8F8F8;
		margin : 0 1px;
	}

	.paging span {
		border: 1px solid #CCCCCC;
		padding: 4px 7px;
		text-decoration: none;
		background: #fffff;
		margin : 0 1px;
		font-weight:bold;
	}

	.paging a:hover {
		background: #E7EFEF;
	}
	</style>
	<script type="text/javascript">
	function _CloseOnEsc() {
	  if (event.keyCode == 27) { window.close(); return; }
	}
	function Init() {
		document.body.onkeypress = _CloseOnEsc;
	}
	</script>

	</head>
	<body onload="Init()">
js;


	if (isset($_GET['search'])){
		$search = cleantext($_GET['search']);
		$QUERY =  "WHERE `title` LIKE '%$search%' AND `type`='pages'";
	}else{
		$QUERY = "WHERE `type`='pages'";
	}

	$sql = $db->sql_query("SELECT 8 FROM `mod_content` $QUERY");

	$jumlah = $db->sql_numrows($sql);
	$limit 	= 15;

	$a 		= new paging ($limit);

	if(isset($offset)){
		$no = $offset + 1;
	}else{
		$no = 1;
	}


	$query = $db->sql_query("SELECT * FROM `mod_content` $QUERY ORDER BY `title` ASC LIMIT $offset,$limit");

	echo '
	<h2>Content Manager</h2>
	<div class="border" style="margin:0 10px 5px 10px;">
		<form method="get" action="" enctype ="multipart/form-data"><table style="font-family:Verdana;font-size:12px;"><tr><td style="padding-right:10px;width:70px;">Pencarian</td><td> :</td><td style="padding-left:5px;padding-top:1px;"><input type="text" name="search" size="38" /></td><td style="width:50px;"><input type="submit" name="submit" value="Search" /></td></tr></table></form>
	</div>
	<div class="border rb" style="margin:0 10px 5px 10px;">
	<form name="frm" method="post">
		<table class="list">
		<thead>
			<tr class="head">
				<td style="text-align: center;width:30px;">No</td>
				<td>Judul Halaman</td>
				<td class="center">Action</td>
			</tr>
		</thead>
		<tbody>';

		while($data = $db->sql_fetchrow($query)){
			$warna = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
			echo '
			<tr'.$warna.'>
				<td class="center">'.$no.'</td>
				<td>'.$data['title'].'</td>
				<td class="center"><a class="enable" href="#" onClick="window.opener.document.frm.url.value=\'pages-'.$data['seftitle'].'.html\';window.close();"><img src="../../images/tick.gif" alt="" border="0" /></a></td>
			</tr>';
			$no++;
		}
echo '
		</tbody>
		</table>
	</form>
	</div>'.$a-> getPaging($jumlah, $pg, $stg).'
</body>
</html>';