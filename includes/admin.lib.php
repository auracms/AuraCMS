<?php

if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
    header("HTTP/1.1 404 Not Found");
    exit;
}


function Uptime ($waktu, $mundur=false) {
$Waktu_sekarang = $waktu;
$times = <<<EEE
<script language="javascript">
var i_upSeconds=$Waktu_sekarang;
function doUptime() {
  
  var i_secs = parseInt(i_upSeconds % 60);
  var i_mins = parseInt(i_upSeconds / 60 % 60);
  var i_hours = parseInt(i_upSeconds / 3600 % 24);
  var i_days = parseInt(i_upSeconds / 86400);
  
EEE;

if ($mundur){ 

$times .= <<<EEE
var uptimeString = "";
  if (i_days > 0) {
    uptimeString += i_days;
    uptimeString += ((i_days == 1) ? " day" : " days") + ", ";
  }
  if (i_hours >= 0) {
	  if (i_hours < 10){
	  var leads = '0';
	  uptimeString += leads;
  }
    uptimeString += i_hours;
    uptimeString += ((i_hours == 1) ? " " : "") + " : ";
  }
  if (i_mins >= 0) {
	  if (i_mins < 10){
	  var leads = '0';
	  uptimeString += leads;
  }
    uptimeString += i_mins;
    uptimeString += ((i_mins == 1) ? " " : "") + " : ";
  }
  if (i_secs < 10){
	  var leads = '0';
	  uptimeString += leads;
  }
  uptimeString +=  i_secs;
  uptimeString += ((i_secs == 1) ? " " : " ");
  document.getElementById("uptime").innerHTML = uptimeString;  i_upSeconds--;
  if (i_mins > 0 | i_secs > 0 | i_hours > 0){
    setTimeout("doUptime()",1000);
		}
  
EEE;
}else {
$times .= <<<EEE
var uptimeString = "&raquo; Uptime: ";
  if (i_days > 0) {
    uptimeString += i_days;
    uptimeString += ((i_days == 1) ? " day" : " days") + ", ";
  }
  if (i_hours >= 0) {
	  if (i_hours < 10){
	  var leads = '0';
	  uptimeString += leads;
  }
    uptimeString += i_hours;
    uptimeString += ((i_hours == 1) ? " " : "") + " : ";
  }
  if (i_mins >= 0) {
	  if (i_mins < 10){
	  var leads = '0';
	  uptimeString += leads;
  }
    uptimeString += i_mins;
    uptimeString += ((i_mins == 1) ? " " : "") + " : ";
  }
  if (i_secs < 10){
	  var leads = '0';
	  uptimeString += leads;
  }
  uptimeString +=  i_secs;
  uptimeString += ((i_secs == 1) ? " " : " ");
  document.getElementById("uptime").innerHTML = uptimeString;  i_upSeconds++;  setTimeout("doUptime()",1000);	
EEE;

} 
  
  
$times .= <<<EEE
}

</script>
<body onload="doUptime();">

EEE;
$times .='<div id="uptime">&nbsp;</div>';

return $times;
}


?>