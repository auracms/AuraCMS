<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	ob_start();
	
	global $db,$url,$kecamatan_id,$all_visitors;
	include 'mod/statistik/counter.php';
	
	function validip($ip) {
		if (!empty($ip) && $ip == long2ip(ip2long($ip))) {
			$reserved_ips = array(
				array('0.0.0.0', '0.255.255.255'),
				array('10.0.0.0', '10.255.255.255'),
				array('100.64.0.0', '100.127.255.255'),
				array('127.0.0.0', '127.255.255.255'),
				array('169.254.0.0', '169.254.255.255'),
				array('172.16.0.0', '172.31.255.255'),
				array('192.0.2.0', '192.0.2.255'),
				array('192.88.99.0', '192.88.99.255'),
				array('192.168.0.0', '192.168.255.255'),
				array('198.18.0.0', '198.19.255.255'),
				array('198.51.100.0', '198.51.100.255'),
				array('203.0.113.0', '203.0.113.255'),
				array('255.255.255.0', '255.255.255.255')
			);
			foreach ($reserved_ips as $r) {
				$min = ip2long($r[0]);
				$max = ip2long($r[1]);
				if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
			}
			return true;
		}else{
			return false;
		}
	}
		

	class usersOnline {

		var $timeout = 600;
		var $count = 0;
		var $error;
		var $i = 0;
		
		function usersOnline () {
			$this->timestamp = time();
			$this->ip = $this->ipCheck();
			$this->new_user();
			$this->delete_user();
			$this->count_users();
		}
		
	
		function ipCheck() {
			$ip = '';
			if (isset($_SERVER)) {
				if (!empty($_SERVER['HTTP_CLIENT_IP']) && validip($_SERVER['HTTP_CLIENT_IP'])) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				}else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && validip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}else{
					$ip = $_SERVER['REMOTE_ADDR'];
				}
			}else{
				if (getenv('HTTP_CLIENT_IP') && validip(getenv('HTTP_CLIENT_IP'))) {
					$ip = getenv('HTTP_CLIENT_IP');
				}else if (getenv('HTTP_X_FORWARDED_FOR') && validip(getenv('HTTP_X_FORWARDED_FOR'))) {
					$ip = getenv('HTTP_X_FORWARDED_FOR');
				}else{
					$ip = getenv('REMOTE_ADDR');
				}
			}
			return $ip;
		}
		
		function new_user() {
			global $db;
			$insert = $db->sql_query("INSERT INTO `mod_useronline` (`timestamp`, `ip`) VALUES ('$this->timestamp', '$this->ip')");
			if (!$insert) {
				$this->error[$this->i] = "Unable to record new visitor\r\n";			
				$this->i ++;
			}
		}
		
		function delete_user() {
			global $db;
			$delete = $db->sql_query("DELETE FROM `mod_useronline` WHERE `timestamp` < ($this->timestamp - $this->timeout)");
			if (!$delete) {
				$this->error[$this->i] = "Unable to delete visitors";
				$this->i ++;
			}
		}
		
		function count_users() {
			global $db;
			if (count($this->error) == 0) {
				$count = $db->sql_numrows ( $db->sql_query("SELECT DISTINCT `ip` FROM `mod_useronline`"));
				return $count;
			}
		}

	}


	// Read our Parameters
	$today			=	'Today';
	$yesterday		=	'Yesterday';
	$x_month		=	'This month';
	$x_week			=	'This week';
	$all			=	'All days';
	
	$locktime		=	15;
	$initialvalue	=	1;
	$records		=	1;
	
	$s_today		=	1;
	$s_yesterday	=	1;
	$s_all			=	1;
	$s_week			=	1;
	$s_month		=	1;
	
	$s_digit		=	1;
	$disp_type		= 	'Mechanical';
	
	$widthtable		=	'100';
	$pretext		= 	'';
	$posttext		= 	'';
	
	// From minutes to seconds
	$locktime		=	$locktime * 60;
	

	// Now we are checking if the ip was logged in the database. Depending of the value in minutes in the locktime variable.
	$day			 =	date('d');
	$month			 =	date('n');
	$year			 =	date('Y');
	$daystart		 =	mktime(0,0,0,$month,$day,$year);
	$monthstart		 =  mktime(0,0,0,$month,1,$year);
	
	// weekstart
	$weekday		 =	date('w');
	$weekday--;
	if ($weekday < 0)	$weekday = 7;
	$weekday		 =	$weekday * 24*60*60;
	$weekstart		 =	$daystart - $weekday;

	$yesterdaystart	 =	$daystart - (24*60*60);
	$now			 =	time();
	$ip				 =	$_SERVER['REMOTE_ADDR'];
	

	$r	= mysql_query("SELECT MAX( id ) AS total FROM `mod_visitcounter`");
	list($total) = mysql_fetch_array($r);

	if ($total !== NULL) {
		$all_visitors += $total;
	} else {
		$all_visitors = $initialvalue;
	}
	
	
	// Delete old records
	$temp = $all_visitors-$records;
	
	if ($temp>0){
		//$query		 =  mysql_query ("DELETE FROM `mod_visitcounter` WHERE `id`<'$temp'");
	}
	
	$item	=	mysql_fetch_assoc(mysql_query ("SELECT COUNT(*) AS `total` FROM `mod_visitcounter` WHERE `ip`='$ip' AND (tm+'$locktime')>'$now'"));
	$items	=	$item['total'];
	
	if (empty($items))
	{
		mysql_query ("INSERT INTO `mod_visitcounter` (`id`, `tm`, `ip`) VALUES ('', '$now', '$ip')");
	}
	
	$n				 = 	$all_visitors;
	$div = 100000;
	while ($n > $div) {
		$div *= 10;
	}

	$query1			 =	mysql_fetch_assoc(mysql_query ("SELECT COUNT(*) AS `total_today` FROM `mod_visitcounter` WHERE `tm`>'$daystart'"));
	$today_visitors	 =	$query1['total_today'];
	
	$query2			 	 =	mysql_fetch_assoc(mysql_query ("SELECT COUNT(*) AS `total_yesterday` FROM `mod_visitcounter` WHERE `tm`>'$yesterdaystart' AND `tm`<'$daystart'"));
	$yesterday_visitors	 =	$query2['total_yesterday'];
		
	$query3			 =	mysql_fetch_assoc(mysql_query ("SELECT COUNT(*) AS `total_week` FROM `mod_visitcounter` WHERE `tm`>='$weekstart'"));
	$week_visitors	 =	$query3['total_week'];

	$query4			 =	mysql_fetch_assoc(mysql_query ("SELECT COUNT(*) AS `total_month` FROM `mod_visitcounter` WHERE `tm`>='$monthstart'"));
	$month_visitors	 =	$query4['total_month'];
	
	echo '<div>';
	
	
	
		
	echo '<div><table cellpadding="0" cellspacing="0" style="text-align: center; width: 100%;"><tbody align="center">';
	// Show today, yestoday, week, month, all statistic
	//echo 	spaceer("vtoday.gif", 'Visitors', $theCount);
	
	$visitors_online = new usersOnline();

	if (count($visitors_online->error) == 0) {
	
		if ($visitors_online->count_users() == 1) {
			//echo "There is " . $visitors_online->count_users() . " visitor online";
			echo spaceer("usersonline.gif", 'Visitor Online', $visitors_online->count_users());
		}
		else {
			//echo "There are " . $visitors_online->count_users() . " visitors online";
			echo spaceer("usersonline.gif", 'Visitors Online', $visitors_online->count_users());
		}
	}
	else {
		echo "<b>Users online class errors:</b><br /><ul>\r\n";
		for ($i = 0; $i < count($visitors_online->error); $i ++ ) {
			echo "<li>" . $visitors_online->error[$i] . "</li>\r\n";
		}
		echo "</ul>\r\n";
	
	}
	
	
	echo 	spaceer("vtoday.gif", 'Hits', $hits);
	if($s_today)		echo 	spaceer("vtoday.gif", $today, $today_visitors);
	if($s_yesterday)	echo 	spaceer("vyesterday.gif", $yesterday, $yesterday_visitors);
	if($s_week)			echo 	spaceer("vweek.gif", $x_week, $week_visitors);
	if($s_month)		echo 	spaceer("vmonth.gif", $x_month, $month_visitors);
	if($s_all)			echo 	spaceer("vall.gif", $all, $all_visitors);
	
	echo '</tbody></table></div>';
	echo '</div>';
	

	function spaceer($a1,$a2,$a3)
	{
		$ret = '<tr style="text-align:left;"><td><img src="mod/statistik/images/'.$a1.'" alt="mod_mod_visitcounter"/></td>';
		$ret .= '<td>'.$a2.'</td>';
		$ret .= '<td style="text-align:right;">'.$a3.'</td></tr>';
		return $ret;
	}

$out = ob_get_contents();
ob_end_clean();

?>