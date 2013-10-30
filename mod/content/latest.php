<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	ob_start();
	global $db,$url;
	
	$hasil = $db->sql_query( "SELECT * FROM `mod_content` WHERE `type`='news' AND `published`='1' ORDER BY `id` DESC LIMIT 0,10");
	echo '<ul>';
	
	while ($data = $db->sql_fetchrow($hasil)) {
		echo '<li><a href="article-'.$data['seftitle'].'.html">'.$data['title'].'</a></li>';
	}
	echo '</ul>';
	$out = ob_get_contents();
	ob_end_clean();
?>