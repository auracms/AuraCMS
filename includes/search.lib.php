<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	
	function clean_words($mode, &$entry, &$stopword_list, &$synonym_list)
	{
		static $drop_char_match =   array('^', '$', '&', '(', ')', '<', '>', '`', '\'', '"', '|', ',', '@', '_', '?', '%', '-', '~', '+', '.', '[', ']', '{', '}', ':', '\\', '/', '=', '#', '\'', ';', '!');
		static $drop_char_replace = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', '',  '',   ' ', ' ', ' ', ' ', '',  ' ', ' ', '',  ' ',  ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' , ' ', ' ', ' ', ' ',  ' ', ' ');
	
		$entry = ' ' . strip_tags(strtolower($entry)) . ' ';
	
		if ( $mode == 'post' )
		{
			// Replace line endings by a space
			$entry = preg_replace('/[\n\r]/is', ' ', $entry); 
			// HTML entities like &nbsp;
			$entry = preg_replace('/\b&[a-z]+;\b/', ' ', $entry); 
			// Remove URL's
			$entry = preg_replace('/\b[a-z0-9]+:\/\/[a-z0-9\.\-]+(\/[a-z0-9\?\.%_\-\+=&\/]+)?/', ' ', $entry); 
			// Quickly remove BBcode.
			$entry = preg_replace('/\[img:[a-z0-9]{10,}\].*?\[\/img:[a-z0-9]{10,}\]/', ' ', $entry); 
			$entry = preg_replace('/\[\/?url(=.*?)?\]/', ' ', $entry);
			$entry = preg_replace('/\[\/?[a-z\*=\+\-]+(\:?[0-9a-z]+)?:[a-z0-9]{10,}(\:[a-z0-9]+)?=?.*?\]/', ' ', $entry);
		}
		else if ( $mode == 'search' ) 
		{
			$entry = str_replace(' +', ' and ', $entry);
			$entry = str_replace(' -', ' not ', $entry);
		}
	
		//
		// Filter out strange characters like ^, $, &, change "it's" to "its"
		//
		for($i = 0; $i < count($drop_char_match); $i++)
		{
			$entry =  str_replace($drop_char_match[$i], $drop_char_replace[$i], $entry);
		}
	
		if ( $mode == 'post' )
		{
			$entry = str_replace('*', ' ', $entry);
	
			// 'words' that consist of <3 or >20 characters are removed.
			$entry = preg_replace('/[ ]([\S]{1,2}|[\S]{21,})[ ]/',' ', $entry);
		}
	
		if ( !empty($stopword_list) )
		{
			for ($j = 0; $j < count($stopword_list); $j++)
			{
				$stopword = trim($stopword_list[$j]);
	
				if ( $mode == 'post' || ( $stopword != 'not' && $stopword != 'and' && $stopword != 'or' ) )
				{
					$entry = str_replace(' ' . trim($stopword) . ' ', ' ', $entry);
				}
			}
		}
	
		if ( !empty($synonym_list) )
		{
			for ($j = 0; $j < count($synonym_list); $j++)
			{
				list($replace_synonym, $match_synonym) = split(' ', trim(strtolower($synonym_list[$j])));
				if ( $mode == 'post' || ( $match_synonym != 'not' && $match_synonym != 'and' && $match_synonym != 'or' ) )
				{
					$entry =  str_replace(' ' . trim($match_synonym) . ' ', ' ' . trim($replace_synonym) . ' ', $entry);
				}
			}
		}
	
		return $entry;
	}
	
	function phpbb_preg_quote($str, $delimiter){
		$text = preg_quote($str);
		$text = str_replace($delimiter, '\\' . $delimiter, $text);	
		return $text;
	}
	
	function split_words(&$entry, $mode = 'post'){
		return explode(' ', trim(preg_replace('#\s+#', ' ', $entry)));
	}
?>