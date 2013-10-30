<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}

	//================= No need to change anything after here  ========================

	$IPnum 			= "0.0.0.0"; //Set as a String
	$userStatus 	= 0;
	$maxadmindata 	= !isset($maxadmindata) ? 5 : $maxadmindata;
	// Get the current IP number ------------------------------
	$IPnum 			= getenv("REMOTE_ADDR");


	$hasil = $db->sql_query( "SELECT * FROM `mod_usercounter` WHERE `id`='1'" );
	$total = $db->sql_numrows($hasil);
	if ($total <= 0){
		$upDate = $db->sql_query ( "INSERT INTO `mod_usercounter` (`id`,`ip`,`counter`,`hits`) VALUES ('1','$IPnum','1','1')" );	
		$hasil 	= $db->sql_query( "SELECT * FROM `mod_usercounter` WHERE `id`='1'" );
	}
	while ($data = $db->sql_fetchrow($hasil)) {
		$IPdata		= $data['ip'];
		$theCount	= $data['counter'];
		$hits		= $data['hits'];
	}


	$IParray = explode("-",$IPdata); //Make array of IPs

	for($ipCount=0;$ipCount<count($IParray);$ipCount++){

		if($IParray[$ipCount]==$IPnum){
			$userStatus = 1;
		}                               

	}

	// OK it's a new visitor
	// Store the IP number in case they ever come back.
	// The counter, give it one.

	$IPdata	= '';

	if($userStatus == 0){
		$IPdata="$IPnum-";
		for ($i=0; $i<$maxadmindata; $i++):
			$IPdata .= "$IParray[$i]-";		
		endfor;

		$theCount++;
		$db->sql_query( "UPDATE `mod_usercounter` SET `ip`='$IPdata',`counter`='$theCount' WHERE `id`='1'");

	}

	$hits++;
	$db->sql_query( "UPDATE `mod_usercounter` SET `hits`='$hits' WHERE `id`='1'" );

?>