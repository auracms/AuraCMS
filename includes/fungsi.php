<?php

if (!defined('FUNCTION')) {
	Header("Location: index.php");
    exit;
}
function kotakjudul($title, $content) {
	global  $themes;
    $thefile = addslashes(file_get_contents("themes/".themes."/boxmenu.html"));

    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    echo $r_file;
}


if (!isset ($_GET['lang'])) $_GET['lang'] = NULL;
if (isset ($_GET['lang'])){
	$_SESSION['bahasa'] = $_GET['lang'];  
	$ref = $_SERVER['HTTP_REFERER'];	
	Header("Location: $ref");
}

if(isset ($_SESSION['bahasa']) && $_SESSION['bahasa'] == 'en'){
	define('_EN','_en');
	$currentlang = 'en';	
}else{
	define('_EN','');
	$currentlang = 'id';
}

function kotakspesial($title, $content) {
    global  $themes;    
    $thefile = addslashes(file_get_contents("themes/".themes."/boxmenuspesial.html"));
    $thefile = "\$r_file=\"".$thefile."\";";
    eval($thefile);
    echo $r_file;
}

function modul($posisi){
    global $db,$STYLE_INCLUDE,$script_INCLUDE;
    		$total = 0;
    		$numb = 0;
    	if (isset($_GET['mod'])) {
	    	$pilih = mysql_real_escape_string(strip_tags($_GET['mod']));
	    	$numb = $db->sql_numrows($db->sql_query("SELECT `id` FROM `mod_actions` WHERE `modul` = '$pilih'"));
	    	$modulku = $db->sql_query("SELECT * FROM `mod_actions` LEFT JOIN `mod_modul` ON (`mod_modul`.`id` = `mod_actions`.`modul_id`) WHERE `mod_actions`.`modul` = '$pilih' AND `mod_actions`.`position` = '$posisi' ORDER BY `mod_actions`.`order`");
	    	//print_r($modulku);
	    	$total = $db->sql_numrows($modulku);
	    	while($viewmoduls = $db->sql_fetchrow($modulku)) {
		    	
		    		 if (file_exists($viewmoduls['content']) && $viewmoduls['type'] == 'module'){
                    	include $viewmoduls['content'];
                    	if ($viewmoduls['spesial'] == 'yes'){
	                	 	kotakspesial($viewmoduls['modul'], @$out,'yes');
		                   	$out = '';
						}else{
							kotakjudul($viewmoduls['modul'], @$out,'');
							$out = '';
						}
            		}
            		if ($viewmoduls['type'] == 'block') {
						if ($viewmoduls['spesial'] == 'yes'){
	            			kotakspesial($viewmoduls['modul'], $viewmoduls['content'],'yes');
						}else{
							kotakjudul($viewmoduls['modul'], $viewmoduls['content'],'');
						}
            		}
	    	}
	    
    	}
	
    	if ($total == 0 && $numb == 0) {
    	$modulku = $db->sql_query( "SELECT * FROM `mod_modul` WHERE `published`= '1' AND `position`= '$posisi' ORDER BY `ordering`" );
    	
                while ($viewmodul = $db->sql_fetchrow($modulku)) {
	                if (file_exists($viewmodul['content']) && $viewmodul['type'] == 'module'){
                    	include $viewmodul['content'];
						if ($viewmodul['spesial'] == 'yes'){
						    kotakspesial($viewmodul['modul'], @$out,'');
							$out = '';
						}else{
							kotakjudul($viewmodul['modul'], @$out,'yes');
							$out = '';
						}
            		}
            		if ($viewmodul['type'] == 'block') {
						if ($viewmodul['spesial'] == 'yes'){
							kotakspesial($viewmodul['modul'], $viewmodul['content'],'yes');
						}else{
							kotakjudul($viewmodul['modul'], $viewmodul['content'],'');
						}
            		}               	                    
                }
          
            }  
               
}

function strip_ext($name){
	$ext = strrchr($name, '.');
	if($ext !== false) {
		$name = substr($name, 0, -strlen($ext));
	}
	return $name;
}

// HTML and Word filter
function text_filter($message, $type="") {
    if (intval($type) == 2) {
        $message = htmlspecialchars(trim($message), ENT_QUOTES);
    } else {
        $message = strip_tags(urldecode($message));
        $message = htmlspecialchars(trim($message), ENT_QUOTES);
    }   
    return $message;
}

// Mail check
function checkemail($email) {
    global $error;
    $email = strtolower($email);
    if ((!$email) || ($email=="") || (!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/", $email))) $error .= "<center>Error, E-Mail address invalid!<br />Please use the standard format (<b>admin@domain.com</b>)</center>";
    if ((strlen($email) >= 4) && (substr($email, 0, 4) == "www.")) $error .= "<center>Error, E-Mail address invalid!<br />Please remove the beginning (<b>www.</b>)</center>";
    if (strrpos($email, " ") > 0) $error .= "<center>Error, E-Mail address invalid!<br />Please do not use spaces.</center>";
    return $error;
}

// Mail send
function mail_send($email, $smail, $subject, $message, $id="", $pr="") {
    $email = text_filter($email);
    $smail = text_filter($smail);
    $subject = text_filter($subject);
    $id = intval($id);
    $pr = (!$pr) ? "3" : "".intval($pr)."";
    $message = (!$id) ? "".$message."" : "".$message."<br /><br />IP: ".getenv("REMOTE_ADDR")."<br />User agent: ".getenv("HTTP_USER_AGENT")."";
    $mheader = "MIME-Version: 1.0\n"
    ."Content-Type: text/html; charset=utf-8\n"
    ."Reply-To: \"$smail\" <$smail>\n"
    ."From: \"$smail\" <$smail>\n"
    ."Return-Path: <$smail>\n"
    ."X-Priority: $pr\n"
    ."X-Mailer: AuraCMS v2.0 Mailer\n";
    @mail($email, $subject, $message, $mheader);
}

function kirimemail ($to, $from, $sender, $subject, $msg) {
    global $db;
	
	$message = '<p><a href="' . get_setting('url') . '" target="_blank"><img src="' . get_setting('url') . '/images/logo.png" alt="' . get_setting('title') . '" border="0"></a></p>';
	$message .= '<font style="font-family:Verdana;font-size:11px"><p>' . $msg . '</p><p></p>';
	
	$mail = new PHPMailer ();
    $mail->Subject 	= stripslashes ($subject);
    $mail->CharSet 	= 'UTF-8';
    if (get_setting('mailtype') == 'mail'){
		$mail->Mailer = 'mail';
    }else{
		if (get_setting('mailtype') == 'smtp'){
			$mail->IsSMTP ();
			$mail->Host = get_setting('smtphost');
			$mail->Port = get_setting('smtpport');
			$mail->SMTPSecure = get_setting('smtpssl');
			$mail->SMTPAuth = true;			
			$mail->Username = get_setting('smtpusername');
			$mail->Password = get_setting('smtppassword');
		}
    }

    $mail->msgHTML($message);
    $mail->AltBody = $message; 
	$mail->From = $from;
	$mail->FromName = $sender;
    $mail->AddAddress ($to);
	$mail->addReplyTo($from, $sender);
    $mail->Send ();
    $mail->ClearAddresses ();
}


class paging {
    function paging ($limit) {
      $this->rowperpage = $limit;
      $this->pageperstg = 5;
    }
    function getPaging($jumlah, $pg, $stg) {
        if (empty($_GET['pg']) and !isset ($_GET['pg'])) {
			$pg = 1;
		}
		if (empty($_GET['stg']) and !isset ($_GET['stg'])) {
			$stg = 1;
		} 
    $qs = '';
    $arr = explode("&",$_SERVER["QUERY_STRING"]);
    if (is_array($arr)) {
        for ($i=0;$i<count($arr);$i++) {
			if (!is_int(strpos($arr[$i],"pg=")) && !is_int(strpos($arr[$i],"stg=")) && !is_int(strpos($arr[$i],"offset="))&& trim($arr[$i]) != "") {
				$qs .= $arr[$i]."&amp;";
			}
        }
    }
      if ($this->rowperpage<$jumlah) {
        $allpage = ceil($jumlah/$this->rowperpage);
        $allstg  = ceil($allpage/$this->pageperstg);
        $minpage = (($stg-1)*$this->pageperstg)+1;
        $maxpage = $stg*$this->pageperstg;
        if ($maxpage>$allpage) $maxpage = $allpage;
        if ($allpage>1) {
             if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
          $rtn  = '<div class="paging">';
          if ($stg>1) $rtn .= '<a class="nextstage" href="'.$_SERVER['PHP_SELF'].'?'.$qs.'pg='.($minpage-1).'&amp;stg='.($stg-1). '&amp;offset='. $newoffset .'">&laquo;&laquo;&laquo;</a>';
          if ($pg>1) {
            if ($pg==$minpage) {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn .= '<a class="nextpage" href="'.$_SERVER['PHP_SELF'].'?'.$qs.'pg='.($pg-1).'&amp;stg='.($stg-1). '&amp;offset='.$newoffset.'">&laquo; Previous</a>';
            } else {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn .= '<a class="nextpage" href="'.$_SERVER['PHP_SELF'].'?'.$qs.'pg='.($pg-1).'&amp;stg='.$stg.'&amp;offset='.$newoffset.'">&laquo; Previous</a>';
            }
          }
          for ($i=$minpage;$i<=$maxpage;$i++) {

            if ($i==$pg) {
              $rtn .= '<span>'.$i.'</span>';
            } else {
                if  ($i==1) {
                 $newoffset = 0;
              }else {
                  $newoffset = ($i-1)*$this->rowperpage;
              }
              $rtn .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$qs.'pg='.$i.'&amp;stg='.$stg.'&amp;offset='.$newoffset.'" title="Page '.$i.'">'.$i.'</a>';
            }
          }
          if ($pg<=$maxpage) {
            if ($pg==$maxpage && $stg<$allstg) {
              $rtn .= '<a class="nextpage" href="'.$_SERVER["PHP_SELF"].'?'.$qs.'pg='.($pg+1).'&amp;stg='.($stg+1).'&amp;offset='.(($pg)*$this->rowperpage).'">Next &raquo;</a>';
            } elseif ($pg<$maxpage) {
              $rtn .= '<a class="nextpage" href="'.$_SERVER["PHP_SELF"].'?'.$qs.'pg='.($pg+1).'&amp;stg='.$stg.'&amp;offset=' .(($pg)*$this->rowperpage). '">Next &raquo;</a>';
            }
          }
          if ($stg<$allstg) {
              $rtn .= '<a class="nextstage" href="'.$_SERVER['PHP_SELF'].'?'.$qs.'pg='.($maxpage+1).'&amp;stg='.($stg+1).'&amp;offset='.(($maxpage)*$this->rowperpage).'"> &raquo;&raquo;&raquo;</a>';
              }
          //$rtn = substr($rtn,0,strlen($rtn)-3);
          $rtn .= '</div>';
          return $rtn;
        }
      }
    }
    
    
     function getPagingajax($jumlah, $pg, $stg) {
        if (!isset ($pg,$stg)){
              $pg = 1;
              $stg = 1;
          }
          $qs = '';
      $arr = explode("&",$_SERVER["QUERY_STRING"]);
      if (is_array($arr)) {
        for ($i=0;$i<count($arr);$i++) {
          if (!is_int(strpos($arr[$i],"pg=")) && !is_int(strpos($arr[$i],"stg=")) && !is_int(strpos($arr[$i],"offset=")) && !is_int(strpos($arr[$i],"math.rand=")) && trim($arr[$i]) != "") {
              $qs .= $arr[$i]."&";
          }
        }
      }
      if ($this->rowperpage<$jumlah) {
        $allpage = ceil($jumlah/$this->rowperpage);
        $allstg  = ceil($allpage/$this->pageperstg);
        $minpage = (($stg-1)*$this->pageperstg)+1;
        $maxpage = $stg*$this->pageperstg;
        if ($maxpage>$allpage) $maxpage = $allpage;
        if ($allpage>1) {
             if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
          $rtn  = array ();
          
          if ($stg>1) {
	          $rtn[] = array('link'=>"".$qs."pg=".($minpage-1)."&stg=".($stg-1). "&offset=". $newoffset,'title'=>'&laquo;&laquo;&laquo;');
      			}
          if ($pg>1) {
            if ($pg==$minpage) {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn[] = array ('link'=>"".$qs."pg=".($pg-1)."&stg=".($stg-1). "&offset=".$newoffset,'title'=>'&laquo; Previous');
            } else {
                if (($pg-1) == 1){
                    $newoffset = 0;

                } else {
                   $newoffset = (($pg-2)*$this->rowperpage);
                }
              $rtn[] = array('link'=>"".$qs."pg=".($pg-1)."&stg=$stg&offset=".$newoffset,'title'=>'&laquo; Previous');
            }
          }
          for ($i=$minpage;$i<=$maxpage;$i++) {

            if ($i==$pg) {
              $rtn[] = array('link'=>'','title'=>'<b>'.$i.'</b>');
            } else {
                if  ($i==1) {
                 $newoffset = 0;
              }else {
                  $newoffset = ($i-1)*$this->rowperpage;
              }
              $rtn[] = array('link'=>"".$qs."pg=$i&stg=$stg&offset=$newoffset",'title'=>$i);
            }
          }
          if ($pg<=$maxpage) {
            if ($pg==$maxpage && $stg<$allstg) {
              $rtn[] = array('link'=>"".$qs."pg=".($pg+1)."&stg=".($stg+1)."&offset=".(($pg)*$this->rowperpage),'title'=>'Next &raquo;');
            } elseif ($pg<$maxpage) {
              $rtn[] = array('link'=>"".$qs."pg=".($pg+1)."&stg=$stg&offset=" .(($pg)*$this->rowperpage),'title'=>'Next &raquo;');
            }
          }
          if ($stg<$allstg) {
              $rtn[] = array('link'=>"".$qs."pg=".($maxpage+1)."&stg=".($stg+1)."&offset=".(($maxpage)*$this->rowperpage),'title'=>'&raquo;&raquo;&raquo;');
              }
         // $rtn = substr($rtn,0,strlen($rtn)-3);
         
          return $rtn;
        }
      }
    }
    
    
    
  }

class paging_s { 
 
    function paging_s ($limit, $aksi='', $query='', $pageperstg=5) { 
	      $this->rowperpage = $limit; 
	      $this->pageperstg = $pageperstg; 
	      $this->sendiri 	= $aksi;
	      $this->query 		= $query;
	}    
    
    function getPaging($jumlah, $pg, $stg) { 
		
		if (empty($_GET['pg']) and !isset ($_GET['pg'])) {
			$pg = 1;
		}
		if (empty($_GET['stg']) and !isset ($_GET['stg'])) {
			$stg = 1;
		} 		
  
     
      if ($this->rowperpage<$jumlah) { 
        $allpage = ceil($jumlah/$this->rowperpage); 
        $allstg  = ceil($allpage/$this->pageperstg); 
        $minpage = (($stg-1)*$this->pageperstg)+1; 
        $maxpage = $stg*$this->pageperstg;
        if ($maxpage>$allpage) $maxpage = $allpage; 
        if ($allpage>1) {
	         if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            } 
          $rtn  = '<div class="paging" style="text-align:center;">'; 
          if ($stg>1) $rtn .= '<a class="nextstage" href="'.$this->sendiri.'-'.($minpage-1).'-'.($stg-1). '-'. $newoffset .$this->query.'">&laquo;&laquo;&laquo;</a>'; 
          if ($pg>1) { 
            if ($pg==$minpage) {
	            if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            }
              $rtn .= '<a class="nextpage" href="'.$this->sendiri.'-'.($pg-1).'-'.($stg-1). '-'.$newoffset.$this->query.'">&laquo; Previous</a>'; 
            } else { 
	            if (($pg-1) == 1){
		            $newoffset = 0;
		            
	            } else {
		           $newoffset = (($pg-2)*$this->rowperpage);
	            }
              $rtn .= '<a class="nextpage" href="'.$this->sendiri.'-'.($pg-1).'-'.$stg.'-'.$newoffset.$this->query.'">&laquo; Previous</a>'; 
            } 
          } 
          for ($i=$minpage;$i<=$maxpage;$i++) {
	          
            if ($i==$pg) { 
              $rtn .= '<span>'.$i.'</span>'; 
            } else { 
	            if  ($i==1) {
		         $newoffset = 0;   
	          }else {
		          $newoffset = ($i-1)*$this->rowperpage;
	          }
              $rtn .= '<a href="'.$this->sendiri.'-'.$i.'-'.$stg.'-'.$newoffset.$this->query.'" title="Page '.$i.'">'.$i.'</a>'; 
            } 
          } 
          if ($pg<=$maxpage) { 
            if ($pg==$maxpage && $stg<$allstg) { 
              $rtn .= ' <a class="nextpage" href="'.$this->sendiri.'-'.($pg+1).'-'.($stg+1).'-'.(($pg)*$this->rowperpage).$this->query.'">Next &raquo;</a>'; 
            } elseif ($pg<$maxpage) { 
              $rtn .= ' <a class="nextpage" href="'.$this->sendiri.'-'.($pg+1).'-'.$stg.'-' .(($pg)*$this->rowperpage). $this->query.'">Next &raquo;</a>'; 
            } 
          } 
          if ($stg<$allstg) {
	          $rtn .= '<a class="nextstage" href="'.$this->sendiri.'-'.($maxpage+1).'-'.($stg+1).'-'.(($maxpage)*$this->rowperpage).$this->query.'"> &raquo;&raquo;&raquo;</a>';
      		} 
          //$rtn = substr($rtn,0,strlen($rtn)-3); 
          $rtn .= '</div>'; 
          return $rtn; 
        } 
      } 
    } 
}

function cleanText ($text,$html=true) {
        $text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
        $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
        $text = preg_replace( '/<!--.+?-->/', '', $text );
        $text = preg_replace( '/{.+?}/', '', $text );
        $text = preg_replace( '/&nbsp;/', ' ', $text );
        $text = preg_replace( '/&amp;/', ' ', $text );
        $text = preg_replace( '/&quot;/', ' ', $text );
        $text = strip_tags( $text );
        $text = preg_replace("/\r\n\r\n\r\n+/", " ", $text);
        $text = $html ? htmlspecialchars( $text ) : $text;
        return $text;
}

function validate_url($url) {
   return preg_match("/(((ht|f)tps*:\/\/)*)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((\/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$/", $url);
}

function int_filter ($nama){
//memfilter karakter alpa menjadi kosong
	if (is_numeric ($nama)){
		return (int)preg_replace ( '/\D/i', '', $nama);
	}else {
	    $nama = ltrim($nama, ';');
	    $nama = explode (';', $nama);
	    return (int)preg_replace ( '/\D/i', '', $nama[0]);
	}
}

function aura_login (){
	global $db;

	$username      = text_filter($_POST['username']);
	$password      = md5($username.$_POST['password']);
	$query         = $db->sql_query ("SELECT * FROM `mod_user` WHERE `username`='$username' AND `password`='$password' AND `active`='1'");
	$total         = $db->sql_numrows($query);
	$data          = $db->sql_fetchrow ($query);	
	
	$db->sql_freeresult ($query);
	if ($total > 0 && $username == $data['username'] && $password == $data['password']){
		$loginter 		= $_SERVER['REMOTE_ADDR'] .'|'. (time ()+ $GLOBALS['timeplus']);
		$query			= mysql_query ("UPDATE `mod_user` SET `lastlogin`='$loginter' WHERE `username`='$username'");
		$times_login 	= $data['timelogin'];
		$_SESSION['username']	= $data['username'];
		$_SESSION['name']		= $data['name'];
		$_SESSION['level']		= $data['level'];
		$_SESSION['email']		= $data['email'];
		$_SESSION['lastlogin']	= $data['lastlogin'];
		$_SESSION['timelimit'] 	= time () + $GLOBALS['timeplus'] + $times_login;
		if($_SESSION['level'] == 'administrator'){
			header ("location:admin.php");
			exit;
		}elseif($_SESSION['level'] == 'publisher'){
			header ("location:publisher.php");
			exit;
		}else{
			header ("location:index.php");
			exit;
		}
		
	}else {
		return '<div class="error">Wrong Username or Password</div>';
	}

}


function cek_login (){
    global $timeplus;

    if (!isset ($_SESSION['timelimit'])) $SES_LIMIT = null; else $SES_LIMIT = $_SESSION['timelimit'];
    if (isset ($_SESSION['username']) && !empty ($_SESSION['username']) && ( (time () + $timeplus) < $SES_LIMIT)){
    	return true;
    }else {
        return false;
    }
}

function logout (){
    unset ($_SESSION['username']);
    unset ($_SESSION['level']);
    unset ($_SESSION['email']);
    unset ($_SESSION['name']);
    unset ($_SESSION['lastlogin']);
    unset ($_SESSION['timelimit']);
	header ("location:index.php");
    exit;
}

function limittxt ($nama, $limit){
    if (strlen ($nama) > $limit) {
		$nama = substr($nama, 0, $limit) .'...';
    }else {
        $nama = $nama;
    }
	return $nama;
}

function datetimes($tgl,$Jam=true){
	$tanggal 	= strtotime($tgl);
	$bln_array 	= array (
					'01'=>'Januari',
					'02'=>'Februari',
					'03'=>'Maret',
					'04'=>'April',
					'05'=>'Mei',
					'06'=>'Juni',
					'07'=>'Juli',
					'08'=>'Agustus',
					'09'=>'September',
					'10'=>'Oktober',
					'11'=>'November',
					'12'=>'Desember'
					);
	$hari_arr 	= Array (	'0'=>'Minggu',
						   	'1'=>'Senin',
						   	'2'=>'Selasa',
							'3'=>'Rabu',
							'4'=>'Kamis',
							'5'=>'Jum`at',
							'6'=>'Sabtu'
						   );
		$hari 	= @$hari_arr[date('w',$tanggal)];
		$tggl 	= date('j',$tanggal);
		$bln 	= @$bln_array[date('m',$tanggal)];
		$thn 	= date('Y',$tanggal);
		$jam 	= $Jam ? date ('H:i:s',$tanggal) : '';
		return "$hari, $tggl $bln $thn $jam";	

}

function dateformat($str,$format = null){
	$str = strtotime($str);
	return date("Y/m/d",$str);
}

function is_valid_email($mail) {
	// checks email address for correct pattern
	// simple: 	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,6}$/i"
	$r = 0;
	if($mail) {
		$p  =	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.(";
		// TLD  (01-30-2004)
		$p .=	"com|edu|gov|int|mil|net|org|aero|biz|coop|info|museum|name|pro|asia|arpa";
		// ccTLD (01-30-2004)
		$p .=	"ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|";
		$p .=	"be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|";
		$p .=	"cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|";
		$p .=	"ec|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|ga|gd|ge|gf|gg|gh|gi|";
		$p .=	"gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|";
		$p .=	"im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|";
		$p .=	"ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|";
		$p .=	"mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|";
		$p .=	"nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|";
		$p .=	"py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|";
		$p .=	"sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|";
		$p .=	"tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|";
		$p .=	"za|zm|zw";
		$p .=	")$/i";

		$r = preg_match($p, $mail) ? 1 : 0;
	}
	return $r;
}
function cek_ip ($check) {
	$bytes = explode('.', $check);
	if (count($bytes) == 4 or count($bytes) == 6) {
		$returnValue = true;
		foreach ($bytes as $byte) {
			if (!(is_numeric($byte) && $byte >= 0 && $byte <= 255)) {
				$returnValue = false;
			}
		}
		return $returnValue;
	}
	return false;
}
function getIP(){
	$banned = array ('127.0.0.1', '192.168', '10');
	$ip_adr = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$bool 	= false;
	foreach ($banned as $key=>$val){
		if (preg_match('/'.$val.'/', $ip_adr)) {
			$bool = true;
			break;
		}
	}
	if (empty($ip_adr) or $bool or !cek_ip($ip_adr)){
		$ip_adr = @$_SERVER['REMOTE_ADDR'];	
	}
	return $ip_adr; 	
}

function posted($filename,$menit = 10){
	global $db;
	$file 	= $filename;
	$IP 	= getIP();
	$waktu 	= time() + 60 * $menit;
	$in 	= $db->sql_query ("INSERT INTO `posted_ip` (`file`,`ip`,`time`) VALUES ('$file','$IP','$waktu')");
}
function cek_posted($filename){
	global $db;
	$delete = $db->sql_query ("DELETE FROM `posted_ip` WHERE `time` < '".time()."'");
	$cek 	= $db->sql_query ("SELECT COUNT(`ip`) AS IP FROM `posted_ip` WHERE `ip` = '".getIP()."' AND `file` = '".$filename."' AND `time` > '".time()."'");
	$total 	= $db->sql_fetchrow($cek);
	if ($total['IP'] >= 1){
		return true;	
	}else {
		return false;	
	}
}

function cleartext($txt) {
	return preg_replace('/[!"\#\$%\'\(\)\?@\[\]\^`\{\}~\*\/]/', '', $txt);
}

function utf2html (&$str) {
    
    $ret = "";
    $max = strlen($str);
    $last = 0;  // keeps the index of the last regular character
    for ($i=0; $i<$max; $i++) {
        $c = $str{$i};
        $c1 = ord($c);
        if ($c1>>5 == 6) {  // 110x xxxx, 110 prefix for 2 bytes unicode
            $ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
            $c1 &= 31; // remove the 3 bit two bytes prefix
            $c2 = ord($str{++$i}); // the next byte
            $c2 &= 63;  // remove the 2 bit trailing byte prefix
            $c2 |= (($c1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
            $c1 >>= 2; // c1 shifts 2 to the right
            $ret .= "&#" . ($c1 * 0x100 + $c2) . ";"; // this is the fastest string concatenation
            $last = $i+1;       
        }
        elseif ($c1>>4 == 14) {  // 1110 xxxx, 110 prefix for 3 bytes unicode
            $ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
            $c2 = ord($str{++$i}); // the next byte
            $c3 = ord($str{++$i}); // the third byte
            $c1 &= 15; // remove the 4 bit three bytes prefix
            $c2 &= 63;  // remove the 2 bit trailing byte prefix
            $c3 &= 63;  // remove the 2 bit trailing byte prefix
            $c3 |= (($c2 & 3) << 6); // last 2 bits of c2 become first 2 of c3
            $c2 >>=2; //c2 shifts 2 to the right
            $c2 |= (($c1 & 15) << 4); // last 4 bits of c1 become first 4 of c2
            $c1 >>= 4; // c1 shifts 4 to the right
            $ret .= '&#' . (($c1 * 0x10000) + ($c2 * 0x100) + $c3) . ';'; // this is the fastest string concatenation
            $last = $i+1;       
        }
    }
    $str=$ret . substr($str, $last, $i); // append the last batch of regular characters
    return $str;
}

function decodeURIComponent($str){
//return utf2html(rawurldecode($str));
return $str;
}

function wraptext($konten,$panjang=30){
$data_konten = explode (' ',$konten);	
$TMPmsg = array ();
        for ($i=0; $i<count($data_konten); $i++){
                if (strlen($data_konten[$i]) >= $panjang) {
                    $TMPmsg[] = wordwrap($data_konten[$i], $panjang, " <br />", TRUE);
                }else {
                	$TMPmsg[] = $data_konten[$i];
            		}
        }	
return implode (" ",$TMPmsg);	
}

function stripWhitespace($str) {
	$r = preg_replace('/[\n\r\t]+/', '', $str);
	return preg_replace('/\s{2,}/', ' ', $r);
}
function stripImages($str) {
	$str = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i', '$1$3$5<br />', $str);
	$str = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $str);
	$str = preg_replace('/<img[^>]*>/i', '', $str);
	return $str;
}
function stripScripts($str) {
	return preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/i', '', $str);
}

function utf8_uri_encode( $utf8_string, $length = 0 ) {
	$unicode = '';
	$values = array();
	$num_octets = 1;
	$unicode_length = 0;

	$string_length = strlen( $utf8_string );
	for ($i = 0; $i < $string_length; $i++ ) {

		$value = ord( $utf8_string[ $i ] );

		if ( $value < 128 ) {
			if ( $length && ( $unicode_length >= $length ) )
				break;
			$unicode .= chr($value);
			$unicode_length++;
		} else {
			if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

			$values[] = $value;

			if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
				break;
			if ( count( $values ) == $num_octets ) {
				if ($num_octets == 3) {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
					$unicode_length += 9;
				} else {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
					$unicode_length += 6;
				}

				$values = array();
				$num_octets = 1;
			}
		}
	}

	return $unicode;
}

function seems_utf8($str) {
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}

function remove_accents($string) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

	if (seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		chr(195).chr(191) => 'y',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E',
		// GBP (Pound) Sign
		chr(194).chr(163) => '');

		$string = strtr($string, $chars);
	} else {
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
			.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;
}

function seo($title) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	$title = remove_accents($title);
	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title, 200);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.,+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);
	$title = str_replace(',', '-', $title);
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
}

function get_lang($module) {
	global $currentlang;
	if (file_exists("mod/$module/language/lang-".$currentlang.".php")) {		
		include_once("mod/$module/language/lang-".$currentlang.".php");		
	} else {
		include_once("language/lang-".$currentlang.".php");			
	}
}

//stoping xss,union and clike injection
if(!function_exists('stripos')) {
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = strpos(strtoupper($haystack), strtoupper($needle), $offset);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
} else {
	// But when this is PHP5, we use the original function
	function stripos_clone($haystack, $needle, $offset=0) {
		$return = stripos($haystack, $needle, $offset=0);
		if ($return === false) {
			return false;
		} else {
			return true;
		}
	}
} 


// Additional security (Union, CLike, XSS)
if(isset($_SERVER['QUERY_STRING']) && (!stripos_clone($_SERVER['QUERY_STRING'], "ad_click"))) {
	$queryString = $_SERVER['QUERY_STRING'];
    if (stripos_clone($queryString,'%20union%20') OR stripos_clone($queryString,'/*') OR stripos_clone($queryString,'*/union/*') OR stripos_clone($queryString,'c2nyaxb0') OR stripos_clone($queryString,'+union+') OR (stripos_clone($queryString,'cmd=') AND !stripos_clone($queryString,'&cmd')) OR (stripos_clone($queryString,'exec') AND !stripos_clone($queryString,'execu')) OR stripos_clone($queryString,'concat')) {
    	die('Illegal Operation');
    }
}
class microTimer {
    function start() {
        global $starttime;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;
    }
    function stop() {
        global $starttime;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = round (($endtime - $starttime), 5);
        return $totaltime;
    }
}

if(!function_exists('get_parent')){
	function get_parent($id) {
		global $db;
		$id			= int_filter($id);
		$data 		= $db->sql_fetchrow($db->sql_query("SELECT `parent` FROM `mod_topic` WHERE `id`='$id'"));
		$parent_id = $data['parent'];
		if($parent_id == 0) $parent = $id; else $parent = int_filter ($data['parent']);
		return $parent;
	}
}

if(!function_exists('stats')){
	function stats(){
		global $db,$day;
		
		
		$query 	 = $db->sql_query("SELECT * FROM `stat_browse` WHERE `id`='3'");
		$data 	 = $db->sql_fetchrow($query);
		$explode = explode("#", $data["value"]);
		$count   = count($explode);
		$explode[$day]++;
		$content = '';
		for($i=0;$i<$count;$i++){
			$content .= $explode[$i] . '#';
		}
		$content = substr_replace($content, '', -1, 1);

		$db->sql_query("UPDATE `stat_browse` SET `value`='$content' WHERE `id`='3'");
	}
}

if(!function_exists('clientinfo')){
	function clientinfo(){
		global $db;
		$username = $_SESSION['username'];
		$query 	  = $db->sql_query("SELECT * FROM `mod_user` WHERE `username`='$username' AND `active`='1'");
		$data 	  = $db->sql_fetchrow($query);
		$content  = '
		<div class="box">
			<ul class="noborder">
				<li>
					<h2 class="widget-title">Client <span class="styled1">Info</span></h2>
					Welcome : <strong>'.$data['name'].'</strong><br />
					<div style="width:30px;float:left;">City</div> <div style="padding:0 5px 0 25px;float:left;">:</div> <div>'.$data['city'].'</div>
					<div style="width:30px;float:left;">Email</div> <div style="padding:0 5px 0 25px;float:left;">:</div> <div>'.$data['email'].'</div>
					<div style="width:30px;float:left;">Level</div> <div style="padding:0 5px 0 25px;float:left;">:</div> <div>'.$data['level'].'</div>
				</li>
			</ul>
		</div>';
		return $content;
	}
}

if(!function_exists('statistik')){
	function statistik(){
		global $db,$jumlah;
		$query = $db->sql_query("SELECT * FROM `stat_browse` WHERE `id`='3'");

		$data = $db->sql_fetchrow($query);
	
		$name 	= explode("#", $data['name']);
		$value 	= explode("#", $data['value']);
		$count 	= count($name);
		$JMLVOTE = array();
		global $jumlah;
		for($i=0;$i<$count;$i++)
		{
			$jumlah += $value[$i];
		}
		if($jumlah == 0)
		{
			$jumlah = 1;
		}
		$content = '
		<div class="box">
			<ul class="noborder">
				<li>
					<h2 class="widget-title">Web <span class="styled1">Statistic</span></h2>';
					$content .= '<div class="sorts">';
					$content .= '<table  border="0">';
					for($i=0;$i<$count;$i++)
					{
						$persentase = round($value[$i] / $jumlah * 100, 2);
						$content .= '<tr>';
						$content .= '<td style="padding-right:10px;">'.$name[$i].'</td>';
						$loop = floor($persentase)* 2;
						if ($loop < 2 ){
							$loop = 1;
						}
						$td = $i % 12 ;
					    $class   = 'bar'.$td;
						$gambar  = 'images/bar/'.$class.'.gif';
						$content .= '<td><img src="'.$gambar.'" alt="" width="'.$loop.'" height="9" /></td>';
						$content .= '<td style="padding-left:10px;">'.$value[$i] . ' = ('.$persentase.'%)</td>';
						$content .= '</tr>';
						
					}
					$content .= '</table>';
					$content .= '</div>
			</ul>
		</div>';
		
		return $content;
	}
}

if(!function_exists('topmenu')){
	function topmenu(){
		global $db;


		$hasil 		= $db->sql_query( "SELECT * FROM `mod_menu` WHERE `published`='1' AND `parentid`='0' AND `position`='top' ORDER BY `ordering`" );
		$menuatas   = '<ul id="menu" class="menu">';
		while ($data = $db->sql_fetchrow($hasil)) {
		
			$parent		= $data['id'];
			$link_menu 	= $data['title'._EN];
		

			$subhasil = $db->sql_query( "SELECT * FROM `mod_menu` WHERE `published`='1' AND `parentid`='$parent' AND `position`='top' ORDER BY `ordering`" );
			$jmlsub = $db->sql_numrows( $subhasil );
			if ($jmlsub == 0) {
			    $menuatas .= '<li><a class="menulink" href="'.$data['url'].'">'.$link_menu.'</a></li>';
			}else
			if ($jmlsub>0) {
				$menuatas .= '<li><a class="menulink" href="'.$data['url'].'">'.$link_menu.'</a>';
				$menuatas .= '<ul>';
				while ($subdata = $db->sql_fetchrow($subhasil)) {
				    $parentid  = $subdata['id'];
                    $submenu   = $subdata['title'];
		        	$querysub  = $db->sql_query( "SELECT * FROM `mod_menu` WHERE `published`='1' AND `parentid`='$parentid' AND `position`='top' ORDER BY `ordering`" );
        			$jumlahsub = $db->sql_numrows( $querysub );
        			if ($jumlahsub == 0) {
        			    $menuatas .= '<li><a class="menulink" href="'.$subdata['url'].'">'.$submenu.'</a></li>';
        			}else
        			if ($jumlahsub>0) {
        				$menuatas .= '<li><a class="menulink" href="'.$subdata['url'].'">'.$submenu.'</a>';
						$menuatas .= '<ul>';
        				while ($subsub = $db->sql_fetchrow($querysub)) {
        		        	$menuatas .= '<li><a class="menulink" href="'.$subsub['url'].'" title="'.$subsub['title'].'">'.$subsub['title'].'</a></li>';
        		        }
        		        $menuatas .= '</ul></li>';
        			}
		        }
		        $menuatas .= '</ul></li>';
			}
		}
		$menuatas .= '</ul>';		
		//echo $_SERVER['REQUEST_URI'];
		return $menuatas;
	}	
}

function howDays($from, $to) {
    $first_date = strtotime($from);
    $second_date = strtotime($to);
    $offset = $second_date-$first_date;
    return floor($offset/60/60/24);
}

?>