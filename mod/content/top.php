<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	ob_start();
	global $db;
	echo '<div class="coursesearch"><div class="listmenu">
			<ul>';
			$a = $db->sql_query( "SELECT * FROM `mod_content` WHERE `type`='news' ORDER BY `id` DESC LIMIT 0,10");
			while ($data = $db->sql_fetchrow($a)) {
				echo '<li><a href="article-'.$data['seftitle'].'.html">'.$data['title'].'</a> </li>';
			}
			echo '
			</ul>
		</div></div>';

	$out = ob_get_contents();
	ob_end_clean();
?>