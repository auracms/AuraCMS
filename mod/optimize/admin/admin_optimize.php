<?php

	if(!defined('ADMIN')) exit;

	if (!cek_login ()) exit;

	global $total_gain,$histo,$cpt;

	echo '
	<div class="box">
	<h2 class="widget-title">Optimizing <span class="styled1">Database : '.$mysql_database.'</span></h2>
	</div>
	
	<table class="list">
	<thead>
		<tr class="head">
			<td style="text-align: center;">Table</td>
			<td style="text-align: center;">Size</td>
			<td style="text-align: center;">Status</td>
			<td style="text-align: center;">Space Saved</td>
		</tr>
	</thead>
	<tbody>';
	
	$db_clean 	 = $mysql_database;
	$tot_data 	 = 0;
	$tot_idx 	 = 0;
	$tot_all 	 = 0;
	$local_query = 'SHOW TABLE STATUS FROM '.$mysql_database;
	$result = $db->sql_query($local_query);
	if ($db->sql_numrows($result)) {
		while ($row = $db->sql_fetchrow($result)) {
			$warna 		 = empty ($warna) ? ' style="background-color:#f4f4f8;"' : '';
			$tot_data 	 = $row['Data_length'];
			$tot_idx  	 = $row['Index_length'];
			$total 		 = $tot_data + $tot_idx;
			$total 		 = $total / 1024 ;
			$total 		 = round ($total,3);
			$gain		 = $row['Data_free'];
			$gain 		 = $gain / 1024 ;
			$total_gain += $gain;
			$gain 		 = round ($gain,3);
			$local_query = 'OPTIMIZE TABLE '.$row[0];
			$resultat  	 = $db->sql_query($local_query);
			if ($gain == 0) {
				echo '
				<tr'.$warna.'>
			    	<td class="left">'.$row[0].' Kb</td>
			        <td style="text-align: center;">'.$total.'</td>
			        <td style="text-align: center;">Already optimized</td>
			        <td style="text-align: center;">0 Kb</td>
				</tr>';					
			} else {
				echo '
				<tr'.$warna.'>
			    	<td class="left">'.$row[0].' Kb</td>
			        <td style="text-align: center;">'.$total.'</td>
			        <td style="text-align: center;">Optimized!</td>
			        <td style="text-align: center;">'.$gain.' Kb</td>
				</tr>';
			}
		}
	}
	echo '</tbody>
				</table>';

	$total_gain = round ($total_gain,3);
	echo '<div class="border rb" style="text-align:center;"><strong>Optimization Results</strong><br/><br/>
	Total Space Saved : '.$total_gain.' Kb<br/>';
	$sql_query = "CREATE TABLE IF NOT EXISTS optimize_gain(gain decimal(10,3))";
	$result = $db->sql_query($sql_query);
	$sql_query = "INSERT INTO optimize_gain (gain) VALUES ('$total_gain')";
	$result = $db->sql_query($sql_query);
	$sql_query = "SELECT * FROM optimize_gain";
	$result = $db->sql_query ($sql_query);
	while ($row = $db->sql_fetchrow($result)) {
		$histo += $row[0];
		$cpt += 1;
	}
	echo 'You have run this script : '. $cpt .' times<br />'.$histo.' saved since its first execution!</div>';
?>