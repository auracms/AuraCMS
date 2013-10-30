<?php

	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}

	class T10Thumbnail{
		var $maxWidth = 500; // default lebar maksimal adalah 500px
		
		function setMaxWidth($maxWidth){ // method untuk mengubah nilai lebar maksimal
			$this->maxWidth = $maxWidth;
		}
		function setMaxHeight($maxHeight){ // method untuk mengubah nilai lebar maksimal
			$this->maxHeight = $maxHeight;
		}
		function getThumbnail($path){			
			$maxhHeight = $this->maxHeight; // tinggi maksimal disamakan saja dengan lebar maksimal
			
			if(file_exists($path)){ // cek apakah file yang akan di resize ada
				list($width,$height)=getimagesize($path); // untuk mendapatkan lebar dan tinggi gambar
				// jika lebar image lebih kecil dari ukuran thumbnail yang ditentukan maka tidak perlu di hitung lagi ratio ukurannya
				if($width < $this->maxWidth){ 
					$new_width  = $width;
					$new_height = $height;
				}else{					
			
					$new_width  = ($this->maxWidth/$width)*$width;
					$new_height = ($this->maxHeight/$height)*$height;			
				}
				
			  $path_parts = pathinfo($path);
			  switch($path_parts['extension']){ // ambil extension file untuk mengecek tipe image
			   case "jpeg":
			   case "jpg": 
			      $header = "jpeg";
				  $func1="imagecreatefromjpeg"; // fungsi untuk generate image jpg
				  $func2="imagejpeg"; // fungsi untuk generate image jpg
				break;					
			   case "gif": 
			      $header = "gif";
				  $func1="imagecreatefromgif"; // fungsi untuk generate image gif
				  $func2="imagegif"; // fungsi untuk generate image gif
				break;
			   case "png": 
				  $header = "png";
				  $func1="imagecreatefrompng"; // fungsi untuk generate image png
				  $func2="imagepng"; // fungsi untuk generate image png
				break;
			  }
			  // membuat file php ini ketika dipanggil di browser adalah berupa image
			  header("Content-type: image/$header"); 
				
			  $thumb  = imagecreatetruecolor($new_width,$new_height); // buat resource image dalam ukuran thumbnail
			  $source = $func1($path); // generate image
			  imagecopyresized($thumb,$source,0,0,0,0,$new_width,$new_height,$width,$height); // thumbnail ukuran image
			  $func2($thumb); // generate image
			}
		}		
	}
?>
