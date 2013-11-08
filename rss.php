<?php

/**
*	AuraCMS v.3.0
* 	Oktober 1, 2013 05:12:10 AM 
*	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
*/

	ob_start();
	header("content-type: text/xml; charset=utf-8");
	
	include 'includes/connection.php';
	include 'includes/mysql.php';
	include 'includes/global.php';
	include 'includes/fungsi.php';
	include 'includes/feedcreator.class.php'; 
	
	global $db;
	$_GET['action'] = isset($_GET['action']) ? $_GET['action'] : 'rss20';
	$rss = new UniversalFeedCreator(); 
	$rss->useCached(); 
	$rss->title 			= $GLOBAL['title']; 
	$rss->description 		= $GLOBAL['slogan']; 
	$rss->link 				= $GLOBAL['url']; 
	$rss->feedURL 			= $GLOBAL['url']."/".$_SERVER['PHP_SELF'];
	$rss->syndicationURL 	= $GLOBAL['url']; 
	$rss->cssStyleSheet 	= NULL; 
	
	$image = new FeedImage(); 
	$image->title 		= $GLOBAL['slogan']; 
	$image->url 		= $GLOBAL['url']."/images/browser-48x48.png"; 
	$image->link 		= $GLOBAL['url']; 
	$image->description = 'Feed provided by AuraCMS. Click to visit.'; 
	$rss->image 		= $image; 
	
	$hasil = $db->sql_query( "SELECT * FROM `mod_content` WHERE `published`='1' ORDER BY `id` DESC LIMIT 10" );
	
	while ($data = $db->sql_fetchrow($hasil)) {
	
		$tanggal  = $data['date'];		
		$judulnya = $data['title'];
		$isinya   = $data['content'];
		$id	  	  = $data['id'];
		$author   = $data['username'];
		$seftitle = $data['seftitle'];
	
		$item = new FeedItem(); 
		$item->title 		= $judulnya;
		$item->link 		= $GLOBAL['url']."/".$seftitle.".html";
		$item->description 	= limitTXT(strip_tags($isinya),250); 	
		$item->date   		= datetimes($tanggal); 
		$item->source 		= $GLOBAL['url'];
		$item->author 		= $author;		 
		$rss->addItem($item); 
	
	} 
	
	if($_GET['action'] =='rss091'){
		$rss->outputFeed("RSS0.91");		
	}elseif($_GET['action'] =='rss10'){
		$rss->outputFeed("RSS1.0");	
	}elseif($_GET['action'] =='rss20'){
		$rss->outputFeed("RSS2.0");;		
	}elseif($_GET['action'] =='atom03'){
		$rss->outputFeed("RSS0.3");		
	}elseif($_GET['action'] =='opml'){
		$rss->outputFeed("OPML");		
	}elseif($_GET['action'] =='pie01'){
		$rss->outputFeed("PIO0.1");		
	}elseif($_GET['action'] =='mbox'){
		$rss->outputFeed("MBOX");		
	}elseif($_GET['action'] =='html'){
		$rss->outputFeed("HTML");		
	}elseif($_GET['action'] =='js'){
		$rss->outputFeed("JS");		
	}elseif($_GET['action'] =='atom'){
		$rss->outputFeed("ATOM");		
	}elseif($_GET['action'] =='atom10'){
		$rss->outputFeed("ATOM10");		
	}		
	

?>