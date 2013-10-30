<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

	$hasil 	= mysql_query("SELECT `code` FROM `mod_video` WHERE `published`='1' ORDER BY rand() DESC LIMIT 0,1");	
	$data 	= mysql_fetch_array($hasil);
	$videoblock = '<object width="300" height="250"><param name="movie" value="http://www.youtube.com/v/'.$data['code'].'?fs=1&amp;hl=en_US&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$data['code'].'?fs=1&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="250"></embed></object>';

?>
