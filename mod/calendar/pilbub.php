<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	ob_start();
	global $db;


    $sekarang = date('Y-m-d');
    $pilbub   = howDays($sekarang,'2013-09-09');
    echo <<<js
    <table style="width: 225px; height: 201px; background-image: url('images/pilbub.jpg');" cellspacing="0" cellpadding="0">
    	<tr>
    		<td style="height: 48px"></td>
    	</tr>
    	<tr>
    		<td style="text-align:center;font-size:80px;">$pilbub</td>
    	</tr>
    	<tr>
    		<td>&nbsp;</td>
    	</tr>
    </table>
js;

	$out = ob_get_contents();
	ob_end_clean();
?>