<?php

/**
 *	
 * 	March 02, 2012  , 09:32:55 PM   
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */
	

  $sel_date = isset($_REQUEST['sel_date']) ? (int)$_REQUEST['sel_date'] : time();
  if( isset($_POST['hrs']) ){
     $t = getdate($sel_date);
     $sel_date = mktime($_POST['hrs'], $_POST['mins'], $t['seconds'], $t['mon'], $t['mday'], $t['year']);
  }
  
  if (date('Y',$sel_date) == 2038) $sel_date = time ();
  $t = getdate($sel_date);
  $start_date = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], 1, $t['year']);
  $start_date -= 86400 * date('w', $start_date);
  
  $prev_year = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], $t['mday'], $t['year'] - 1);
  $prev_month = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'] - 1, $t['mday'], $t['year']);
  $next_year = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], $t['mday'], $t['year'] + 1);
  $next_month = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'] + 1, $t['mday'], $t['year']);
  
  

  

	$cals = ''; 
	$toolstips_cal = '';
	
	$cals .= '
	<table id="calendar" cellspacing="0" cellpadding="0" summary="This month\'s calendar" align="center">
		<tr class="caption">
			<th><a onclick="requestCalendarUrl(\'sel_date='.$prev_year.'\')" style="text-decoration: none;color:#ffffff" title="Prevous Year">&laquo;</a></th>
			<th><a onclick="requestCalendarUrl(\'sel_date='.$prev_month.'\')" style="text-decoration: none;color:#ffffff;" title="Prevous Month">&#8249;</a></th>
			<th colspan="3">'.date('M Y', $sel_date).'</th>
			<th><a onclick="requestCalendarUrl(\'sel_date='.$next_month.'\')" style="text-decoration: none;color:#ffffff;" title="Next Month">&#8250;</a></th>
			<th><a onclick="requestCalendarUrl(\'sel_date='.$next_year.'\')" style="text-decoration: none;color:#ffffff;" title="Next Year">&raquo;</a></th>
	</tr>';   
  
  
	$cals .= ' 
		<tr>
		    <th title="Minggu" scope="col" abbr="Sunday">M</th>
		    <th title="Senin" scope="col" abbr="Monday">S</th>
		    <th title="Selasa" scope="col" abbr="Tuesday">S</th>
		    <th title="Rabu" scope="col" abbr="Wednesday">R</th>
		    <th title="Kamis" scope="col" abbr="Thursday">K</th>
		    <th title="Jum`at" scope="col" abbr="Friday">J</th>
		    <th title="Sabtu" scope="col" abbr="Saturday">S</th>
	    </tr> ';   
  

    $day = 1;
    $JUDULCAL = array ();
	$TMPpesan = array() ;
	$TGLIN_ARRAY = array ();
	$TMPpesan__ = array ();
	$TMPtgl_akhir = array ();
	$TGLbackground = array ();
	$TGLcolor = array ();
	$awalbulandengannol = $t['mon'] >= 10 ? $t['mon'] : '0'.$t['mon'];
	$varwaktucalender = $t['year'] . '-' . $awalbulandengannol;
		    
	$cekdate = mysql_query ("SELECT * FROM `tbl_kalender` WHERE left( `waktu_mulai` , 7 ) = '$varwaktucalender'");
    while ($getdate = mysql_fetch_assoc($cekdate)){
	    
	    $warna 		= empty ($warna) ? '#F0F6F9': '';
	    $WKTMULAI 	= $getdate['waktu_mulai'];
	    $WKTAKHIR 	= $getdate['waktu_akhir'];
	    $GTTGL 		= (int)substr($WKTMULAI, -2, 2);
	    $TGLMULAI[$GTTGL] = $GTTGL; // 
	    $TGLbackground[$GTTGL] = $getdate['background'];
	    $TGLcolor[$GTTGL] = $getdate['color'];
	    list ($Tahun2,$Bulan2,$Tanggal2) = explode ('-',$getdate['waktu_akhir']);
	   
	    if ($GTTGL <= $Tanggal2){
		    for ($i=$GTTGL;$i<=(int)$Tanggal2;$i++){
			    $valitemselectedday = date('j', $i);
			  	$TGLIN_ARRAY[] = $i;
			   	$TMPtgl_akhir[$i] = strtotime($WKTAKHIR);
	           	if (!array_key_exists($i, $TMPpesan__)) {
		    		$TMPpesan__[$i] = '<div class="border" style="background: '.$warna.';"><b>'.$getdate['judul'._EN].'</b><br />Tanggal : <span style="color:orange;">'.datetimes ($WKTMULAI,false).'</span><br />Waktu&nbsp;&nbsp;&nbsp;: '.$getdate['waktu'].'<br />Tempat&nbsp;&nbsp;: '.$getdate['lokasi'].'<br /><div style="text-align:right;"><a title="" href="agenda-'. $getdate['seftitle'] .'.html" style="text-decoration:none;'.$warna.'">Detail</a></div></div>';
	    		}else {
		    		$TMPpesan__[$i]= $TMPpesan__[$i] . '<div class="border" style="background: '.$warna.';"><b>'.$getdate['judul'._EN].'</b><br />Tanggal : <span style="color:orange;">'.datetimes ($WKTMULAI,false).'</span><br />Waktu&nbsp;&nbsp;&nbsp;: '.$getdate['waktu'].'<br />Tempat&nbsp;&nbsp;: '.$getdate['lokasi'].'<br /><div style="text-align:right;"><a title="" href="agenda-'. $getdate['seftitle'] .'.html" style="text-decoration:none;'.$warna.'">Detail</a></div></div>';
	    		}
		    }
    	}
	  
	    if (!array_key_exists($GTTGL, $JUDULCAL)) {
		    $JUDULCAL[$GTTGL] = $getdate['judul'._EN];
		    $TMPpesan[$GTTGL] = '<b>'.$getdate['judul'._EN].'</b><br /><span style="color:orange;size:8px;">'.datetimes ($WKTMULAI).' s.d '.datetimes ($WKTAKHIR).'</span><br />'.limitTXT($getdate['isi'._EN],150).'';
    	}else {
	    	$JUDULCAL[$GTTGL] = $JUDULCAL[$GTTGL] . ', '.$getdate['judul'._EN];	
	    	$TMPpesan[$GTTGL] = $TMPpesan[$GTTGL] . '<br /><b>'.$getdate['judul'._EN].'</b><br /><span style="color:orange;">'.datetimes ($WKTMULAI).' s.d '.datetimes ($WKTAKHIR).'</span>
                        <br />'.limitTXT($getdate['isi'._EN],150).'';
    	}	
    }
    
	for($i = $start_date; $day <= 42; $i+=86400, $day++){
		$valitemselectedday = date('j', $i);
		$TGLMULAI[$valitemselectedday] = isset ($TGLMULAI[$valitemselectedday]) ? $TGLMULAI[$valitemselectedday] : NULL;	 
     	if( $day % 7 == 1 ) {
	        $cals .= "<tr>\n"; 
	        $red[0] = '<span style="color:red">';  
	        $red[1] = '</span>';  
		}else {
	        $red[0] = '';
	        $red[1] = '';
        }
        
        if( $t['mon'] == date('n', $i ) ) {

			if( $i == $sel_date ) {
            	$cals .= ' <td class="today">'. date('j', $i) ."</td>\n";           
              
              
				if (in_array((int)date('j', $i),$TGLIN_ARRAY)) {
					$toolstips_cal_EVENT = $TMPpesan__[$valitemselectedday];
				}
              
			}else {
				$dd = date('Y', $i).'-'.date('m', $next_year).'-'.date('d', $i);         
           		$nums = 0; 
           		if ($TGLMULAI[$valitemselectedday] == date('j', $i)) {
	          		$color = empty($TGLcolor[$valitemselectedday]) ? '' : 'color:'.$TGLcolor[$valitemselectedday].';';
	          		if (!empty($color)){
		          		$red[0] = '';
		          		$red[1] = ''; 
	          		}
		         	$stylebold = ' style="background:'.$TGLbackground[$valitemselectedday].';" onmouseover="kukiover(\'calendar_'.$valitemselectedday.'\');" onmouseout="kukiout(\'calendar_'.$valitemselectedday.'\');"';  
		         	$links[0] = '<a title="'.$JUDULCAL[date('j', $i)].'" href="agenda-date-'. $i .'.html" style="text-decoration:none;'.$color.'">';
		         	$links[1] = '</a>';
	         
	         		$toolstips_cal .= '
                    <div id="calendar_'.$valitemselectedday.'" style="display:none;position: absolute; padding-right: 10px; padding-left: 10px; padding-bottom: 10px; width: 258px; padding-top: 10px; background-color: #efefef;border:1px solid #b1b1b1;font-family:verdana,tahoma,arial;font-size:9px;line-height:13px">'.$TMPpesan[$valitemselectedday].'</div>';
           		}else {
					$stylebold = '';  
		         	$links[0] = '';
		         	$links[1] = '';  	
           		}           
              $cals .= ' <td'.$stylebold.'>'.$links[0].$red[0]. date('j', $i) .$red[1].$links[1]."</td>\n";
			}
              
    	} else {
			$cals .= ' <td ><span style="color:silver">'. date('j', $i) ."</span></td>\n";     
		}
    		
		if( $day % 7 == 0 )  {
			$cals .= "</tr>\n";
		}	
    		
	}
     
	unset($i);


	$cals .= '</table>';
	$cals .= $toolstips_cal;