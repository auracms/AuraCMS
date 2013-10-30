<?php


	if(!defined('INDEX')) exit;
	$index_hal = 1;
	
	
	if (isset ($_GET['pg'])) $pg = int_filter ($_GET['pg']); else $pg = 0;
	if (isset ($_GET['stg'])) $stg = int_filter ($_GET['stg']); else $stg = 0;
	if (isset ($_GET['offset'])) $offset = int_filter ($_GET['offset']); else $offset = 0;	


	
	

	if($_GET['action'] == ''){
		
		echo '<h2>Gallery Video</h2>';
		echo '<div class="border rb breadcrumb"><a href="video.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   Gallery Video</div>';
	
		$cq = mysql_query ("SELECT count(`id`) AS `total_files` FROM `mod_video` WHERE `published`='1'");
		$gd = mysql_fetch_assoc($cq);
		$jumlah = $gd['total_files'];
		
		$limit = 12;
		
		$pembagian = new paging_s ($limit,'video','.html');
		$query = mysql_query ("SELECT * FROM `mod_video` WHERE `published`='1' ORDER BY `id` DESC LIMIT $offset,$limit");
		echo '<div class="border rb rt"><table border="0" width="100%" class="photogallery" id="photo-g" cellpadding="0" cellspacing="0"><tr>';
		$a = 1;
		while ($data = mysql_fetch_array ($query)){
		echo '<td style="vertical-align:top;padding-bottom:2px;"><div class="gallery"><a href="video-'.$data['seftitle'].'.html"><img src="http://i2.ytimg.com/vi/'.$data['code'].'/default.jpg" border="0" alt=""></a></div></td>';
		if ( $a % 4 == 0 ) {
		    echo '</tr><tr>';
		}
		$a = $a + 1;
		}
		echo '</tr></table></div>';	
		

		echo  $pembagian-> getPaging($jumlah, $pg, $stg);

		

	}


	if($_GET['action'] == 'view'){
		
		$seftitle 		= text_filter(cleanText($_GET['seftitle']));
	
		$periksa 		= mysql_query ("SELECT * FROM `mod_video` WHERE `seftitle`='$seftitle' AND `published`='1'");
		$dataperiksa 	= mysql_fetch_array ($periksa);
		$id 			= $dataperiksa['id'];
		
		$GLOBAL['title'] 		= cleanText($dataperiksa['title']);
		$GLOBAL['description'] 	= limittxt(htmlentities(strip_tags($dataperiksa['description'])),200);
		$GLOBAL['keywords'] 	= empty($dataperiksa['title']) ? implode(',',explode(' ',htmlentities(strip_tags($dataperiksa['title'])))) : $dataperiksa['title'];
		
		$query 		= mysql_query ("SELECT count(`id`) AS `total_files` FROM `mod_video` WHERE `id`!='$id'");
		$getdata 	= mysql_fetch_assoc($query);
		$jumlah 	= $getdata['total_files'];
		
		$qp = mysql_query ("SELECT * FROM `mod_video` WHERE `id`!='$id' AND `id`<'$id' ORDER BY `id` DESC LIMIT 0,1");
		$qn = mysql_query ("SELECT * FROM `mod_video` WHERE `id`!='$id' AND `id`>'$id' ORDER BY `id` DESC LIMIT 0,1");
		$dp = mysql_fetch_array ($qp);
		$dn = mysql_fetch_array ($qn);
		$imgprev	= '';
		$imgnext	= '';
		$kprev		= '';
		$knext		= '';
		if(mysql_num_rows($qp)>0){
			$imgprev 	.= '<a href="video-'.$dp['seftitle'].'.html"><img src="http://i2.ytimg.com/vi/'.$dp['code'].'/default.jpg" border="0" alt=""></a>';
			$kprev		.= '&laquo; Prev';
		}
		if(mysql_num_rows($qn)>0){
			$imgnext	.= '<a href="video-'.$dn['seftitle'].'.html"><img src="http://i2.ytimg.com/vi/'.$dn['code'].'/default.jpg" border="0" alt=""></a>';
			$knext		.= 'Next &raquo;';
		}
		
		echo '<h2>'.$dataperiksa['title'].'</h2>';
		echo '<div class="border rb breadcrumb"><a href="video.html" id="home">Home</a>   &nbsp;&raquo;&nbsp;   '.$dataperiksa['title'].'</div>';
		echo '<div style="text-align:center;"><object width="514" height="360"><param name="movie" value="http://www.youtube.com/v/'.$dataperiksa['code'].'?fs=1&amp;hl=en_US&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$dataperiksa['code'].'?fs=1&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="514" height="360"></embed></object></div>';
		echo '<div class="border"><p style="text-align:left;">'.$dataperiksa['description'].'</p></div>';
		
		if($jumlah>0){
			echo '<div class="border" ><center><table style="text-align:center;"><tr><td>'.$imgprev.'</td><td>'.$imgnext.'</td></tr><tr><td align="center">'.$kprev.'</td><td align="center">'.$knext.'</td></tr></table></center></div>';
		}
	
	}

?>