<?php
include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/mysql.php';

$iwan	= mysql_query("SELECT `name` FROM `mod_gallery` ORDER BY rand() DESC");
$cakep 	= mysql_fetch_assoc($iwan);
echo '
<html><head><title>Preview</title></head>
<body bgcolor="#FFFFFF">
<center>
<div id="slide1dv" style="position:relative;width:300px;height:250px;overflow:hidden;padding:0px;margin:0px;border-style:solid;border-width:0px;border-color:#FFFFFF;z-index:1;FILTER: progid:DXImageTransform.Microsoft.Fade(Overlap=1.00,duration=3,enabled=false);"><img src="../../mod/gallery/storeData/normal/'.$cakep['name'].'" style="width:300px;height:250px;"></img></div><script src="slide1fade.php" type="text/javascript"></script>
</center>
</body></html>';


?>