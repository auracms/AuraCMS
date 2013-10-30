<?php
    ob_start();
    global $db;

    $query  = $db->sql_query("SELECT * FROM `mod_weblinks` WHERE `published`='1' ORDER BY RAND() LIMIT 0,1");
    $banyak = $db->sql_numrows($query);
    $data   = $db->sql_fetchrow($query);



    if ($banyak > 0){
        echo '
        <div align="center"><a href="weblinks-jump-'.$data['seftitle'].'.html" target="_blank" title="'.$data['title'].'">'.$data['title'].'<br />
        <img src="http://img.bitpixels.com/getthumbnail?code=81714&url='.$data['url'].'" title="'.$data['title'].'" border="0" alt="" /></a>
        <br />View : '.$data['hits'].' x hits<br />Join : '.datetimes($data['date'],false).'<br /></div>';
    }

    $out = ob_get_contents();
    ob_end_clean();
?>