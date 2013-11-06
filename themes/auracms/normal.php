<?php
	global $db;
	
	$query 	= $db->sql_query(" SELECT * FROM `mod_content` WHERE `type`='news' AND `published`='1' AND `headline`='1' ORDER BY `id` DESC LIMIT 0,5");
	$slider = '
	<div class="flexslider">
		<ul class="slides">';
		
		while($data = $db->sql_fetchrow($query)){
			$slider .= '
			<li>
				<a href=""><img src="images/normal/'.$data['image'].'" width="597" height="230" alt="'.$data['title'].'" /></a>
				<p class="flex-caption"><a href=""><h3>'.$data['title'].'</h3><p>'.limittxt($data['content'],250).'</p></a></p>
			</li>';	
		}
	$slider .= '
		</ul>
	</div>';
	
	$tengah = '';
	$qc		= $db->sql_query(" SELECT * FROM `mod_content` WHERE `type`='news' AND `published`='1' AND `headline`='0' ORDER BY `id` DESC LIMIT 0,5");
	$tengah .= '<h1 class="bgdepan">Berita Terbaru</h1><div class="box">';
	while($data = $db->sql_fetchrow($qc)){

		$gambar = ($data['image'] == '') ? '' : '<img src="images/thumb/'.$data['image'].'" width="90" height="70" border="0" alt="'.$data['title'].'" style="margin-right:5px; padding:3px; float:left; border:1px solid #dddddd;" />';

		$tengah .='
		<div class="news">
			<h4><a href="article-'.$data['seftitle'].'.html" title="'.$data['title'].'">'.$data['title'].'</a></h4>
			<div class="date">'.datetimes($data['date'],false).' - dilihat '.$data['hits'].' kali</div>
			'.$gambar.limitTXT(strip_tags($data['content']),300).'
		</div>';
		
	}
	$tengah .= '</div>';
	
echo $tengah;