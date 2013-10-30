<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	ob_start();
	
	global $db,$url;

$translateKal_1 = array('01' => 'Januari',
						'02' => 'Februari',
						'03' => 'Maret',
						'04' => 'April',
						'05' => 'Mei',
						'06' => 'Juni',
						'07' => 'Juli',
						'08' => 'Agustus',
						'09' => 'September',
						'10' => 'Oktober',
						'11' => 'Nopember',
						'12' => 'Desember'
						);


	$hasil = $db->sql_query("SELECT date_format( `date` , '%Y/%m' ) AS `tanggal` FROM `mod_content` WHERE `published` = '1' AND `type`='news' GROUP BY `tanggal` DESC LIMIT 15");
	echo '<ul>';
		while ($data = $db->sql_fetchrow($hasil)) {
			list($tahun,$bulan) = explode('/',$data['tanggal']);
			$quer 	= $db->sql_query("SELECT count(`id`) AS `total` FROM `mod_content` WHERE month(`date`) = '$bulan' AND year(`date`) = '$tahun' AND `published` = '1' AND `type`='news'");
			$tot 	= $db->sql_fetchrow($quer);
			$total 	= $tot['total'];
			echo '<li><a href="arsip-'.seo($translateKal_1[$bulan].' '.$tahun).'.html">'.$translateKal_1[$bulan].' '.$tahun.' ('.$total.') </a></li>';
			
		}
	echo '</ul>';
	$out = ob_get_contents();
	ob_end_clean();
?>