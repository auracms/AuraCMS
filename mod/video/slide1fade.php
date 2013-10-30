<?

include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/mysql.php';

$content ='';
$num	= mysql_query("SELECT `id` FROM `mod_gallery` ORDER BY `id` DESC");
$jumlah = mysql_num_rows ($num);
$list 	= mysql_query ("SELECT `name` FROM `mod_gallery` ORDER BY `id` DESC");
$content .= <<<Iwan
function slide1sl(){
	fademi=1;
	imgarr=new Array();
	imgstr=new Array();
	linkstr=new Array();
	clslinkstr=new Array();
Iwan;
$i = 0;
while ($get = mysql_fetch_assoc($list)){
	$gambar = $get['name'];
	$content .= "
	imgarr[$i]=new Image();
	imgarr[$i].src='$gambar';
	imgstr[$i]='$gambar';
	linkstr[$i]='';
	clslinkstr[$i]='';
	";
	


$i++; 	
}
$content .= <<<Iwan
	vcurr=0;
	vnext=0;
	vssdiv=null;
	stepc=20*(3000/1000);
	dif=0.00;
	op=1.00;
	dif=(1.00/stepc);
	uagent = window.navigator.userAgent.toLowerCase();
	IEB=(uagent.indexOf('msie') != -1)?true:false;
	var scompat = document.compatMode;
	
	if(scompat != "BackCompat"){
	}
	
	dstr1='<div id="';
	dstr2='" style="position:absolute;text-align:'+"center"+';width:'+300+'px;height:'+250+'px;visibility:hidden;left:0px;top:0px;padding:0px;margin:0px;overflow:hidden;">';
	dstr3='<img id="slide1img';
	dstr4='" src="';
	dstr5='" style="position:relative;left:0px;top:0px;padding:0px;margin:0px;border:0px;" alt="" border="0"></img>';
	dstr6='</div>';
	this.slide1dotrans=slide1dotrans;
	this.slide1initte=slide1initte;
	this.slide1initte2=slide1initte2;
	this.slide1beftrans=slide1beftrans;
	this.slide1dotransff=slide1dotransff;
}
	
	function slide1dotrans(){	
		if(IEB==true){
			vssdiv.filters[0].apply();
		}	
		objc=document.getElementById('slide1d'+vcurr);	
		objn=document.getElementById('slide1d'+vnext);			
		objc.style.visibility="hidden";	
		objn.style.visibility="visible";	
		
		if(IEB==true){
			vssdiv.filters[0].play();
		}		
		vcurr=vnext;	
		vnext=vnext+1;	

		if(vnext>=$jumlah)	{		
			vnext=0;	
		}
	
		setTimeout('slide1dotrans()',(3000+2000));
	}
	function slide1dotransff(){	
		op=op-dif;	objc=document.getElementById('slide1d'+vcurr);	
		objn=document.getElementById('slide1d'+vnext);		
		if(op<(0.00)){
			op=0.00;
		}	
		objc.style.opacity = op;	
		objn.style.opacity = 1.00;	
		if(op>(0.00))	{		
			setTimeout('slide1dotransff()',50);	
		}else{		
			objc.style.zIndex=2;		
			objn.style.zIndex=3;		
			setTimeout('slide1beftrans()',2000);			
		}
	}
	function slide1beftrans(){	
		vcurr=vnext;	
		vnext=vnext+1;	

		if(vnext>=$jumlah){		
			vnext=0;	
		}	op=1.00;

		
		objc=document.getElementById('slide1d'+vcurr);	
		objn=document.getElementById('slide1d'+vnext);	
		objc.style.visibility="visible";	
		objn.style.visibility="visible";		
		objc.style.zIndex=3;	
		objn.style.zIndex=2;		
		objc.style.opacity = 1.00;		
		objn.style.opacity = 1.00;			
		slide1dotransff();	
	}
	function slide1initte2(){	
		vssdiv=document.getElementById("slide1dv");	
Iwan;
$content .= <<<Iwan
		if($jumlah>0){		
			objc=document.getElementById('slide1d'+0);			
			objc.style.visibility="visible";		
		}
		if($jumlah>1){		
			if((IEB==true)||(fademi==0)){			
				vcurr=0;			
				vnext=1;			
				setTimeout('slide1dotrans()',2000);		
			}else{			
				vcurr=0;			
				vnext=0;
				setTimeout('slide1beftrans()',2000);			
			}	
		}
	}
	function slide1initte(){
		i=0;	
		innertxt="";	
		for(i=0;i<$jumlah;i++){		
			innertxt=innertxt+dstr1+"slide1d"+i+dstr2+linkstr[i]+dstr3+i+dstr4+imgstr[i]+dstr5+clslinkstr[i]+dstr6;	
		}	
		spage=document.getElementById('slide1dv');	
		spage.innerHTML=""+innertxt;	
		setTimeout('slide1initte2()',200);
	}
	s=new slide1sl();
	s.slide1initte();
Iwan;


echo $content;
?>