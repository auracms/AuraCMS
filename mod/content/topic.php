<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	ob_start();
	global $db,$url;
	
	$hasil = $db->sql_query( "SELECT * FROM `mod_topic` ORDER BY `topic` DESC");
	echo '<ul>';
	
	while ($data = $db->sql_fetchrow($hasil)) {
		echo '<li><a href="category-'.$data['seftitle'].'.html">'.$data['topic'].'</a></li>';
	}
	echo '</ul>';
	$out = ob_get_contents();
	ob_end_clean();
?>