<?php 


	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}
	
	class template
	{
	    var $TAGS = array();
	    var $THEME;
	    var $CONTENT;
	    
	    
	     function template($themename)
	    {
	        $this->THEME = $themename;
	        $this->tagMulai ();
	        $this->tagAkhir ();
	    }
	    
	    function tagMulai ($tagA = '{'){
		    $this->tagAwal = $tagA;
	    }
	    
	    function tagAkhir ($tagE = '}') {
		    $this->tagAkhir = $tagE;  
	    }
	    
	    
	    
	    function define_tag($tagname, $varname=false)
	    {
		    if (is_array ($tagname)){
			    foreach ($tagname as $key => $value) 
	            { 
	            $this->TAGS[$key] = $value; 
	            } 
		    }else {
	        $this->TAGS[$tagname] = $varname;
	    		}
	    }
	    
	   
	    
	    function parse()
	    {
	        $this->CONTENT = @file($this->THEME) OR DIE ('Tidak dapat meload template');
	        
	        $this->CONTENT = implode("", $this->CONTENT);
	       
	        foreach ($this->TAGS as $kunci=>$nilai){
		        $start = $this->tagAwal . $kunci . $this->tagAkhir;
		        $this->CONTENT = str_replace($start, $nilai, $this->CONTENT);
		        
	        }
	        
	       return $this->CONTENT;
	    }
		
		function cetak()
		{
			echo $this->parse();
		}
	}
?>