<?php
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class SimpleImage {
   
   var $image;
   var $image_type;
   var $error;
   var $offsetX = 0;
   var $offsetY = 0;
   var $tnWidth;
   var $tnHeight;
   var $width;
   var $height;
 
   function load($filename) {
	   if (!file_exists($filename)) {
		   $this->error = true;
		   return false;
	   }
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   
   function ratio($maxWidth,$maxHeight,$cropratio = '1:1') {
	$cropRatio		= explode(':', (string) $cropratio);
	$width = $this->getWidth();
	$height = $this->getHeight();
	if (count($cropRatio) == 2)
	{
		$ratioComputed		= $width / $height;
		$cropRatioComputed	= (float) $cropRatio[0] / (float) $cropRatio[1];
		
		if ($ratioComputed < $cropRatioComputed)
		{ // Image is too tall so we will crop the top and bottom
			$origHeight	= $height;
			$height		= $width / $cropRatioComputed;
			$offsetY	= ($origHeight - $height) / 2;
		}
		else if ($ratioComputed > $cropRatioComputed)
		{ // Image is too wide so we will crop off the left and right sides
			$origWidth	= $width;
			$width		= $height * $cropRatioComputed;
			$offsetX	= ($origWidth - $width) / 2;
		}
	}


// Setting up the ratios needed for resizing. We will compare these below to determine how to
// resize the image (based on height or based on width)
$xRatio		= $maxWidth / $width;
$yRatio		= $maxHeight / $height;

if ($xRatio * $height < $maxHeight)
{ // Resize the image based on width
	$tnHeight	= ceil($xRatio * $height);
	$tnWidth	= $maxWidth;
}
else // Resize the image based on height
{
	$tnWidth	= ceil($yRatio * $width);
 	$tnHeight	= $maxHeight;
}

	$this->tnWidth = $tnWidth;
	$this->tnHeight = $tnHeight;
	$this->offsetX = $offsetX;
	$this->offsetY = $offsetY;
	$this->width = $width;
	$this->height = $height;
   }
   
   function findSharp($orig, $final) // function from Ryan Rud (http://adryrun.com)
{
	$final	= $final * (750.0 / $orig);
	$a		= 52;
	$b		= -0.27810650887573124;
	$c		= .00047337278106508946;
	
	$result = $a + $b * $final + $c * $final * $final;
	
	return max(round($result), 0);
} // findSharp()
   
   
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=95, $permissions=null) {
	   if ($this->error) {
		   return;
	   }
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() {
	  if (!$this->error) {
     	 return imagesx($this->image);
  	}
   }
   function getHeight() {
	  if (!$this->error) {
      	return imagesy($this->image);
  	}
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
	  if (!$this->error) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
  		}  
   }     
   
   function resizeCrop($width,$height,$ratio = '2:3',$useSharp = false) {
	    if (!$this->error) {
	  $this->ratio($width,$height,$ratio);
      $new_image = imagecreatetruecolor($this->tnWidth, $this->tnHeight);
      imagecopyresampled($new_image, $this->image, 0, 0, $this->offsetX, $this->offsetY, $this->tnWidth, $this->tnHeight, $this->width, $this->height);
    if ($useSharp) { 
	    $sharpness	= $this->findSharp($this->width, $this->tnWidth);
		$sharpenMatrix	= array(
			array(-1, -2, -1),
			array(-2, $sharpness + 12, -2),
			array(-1, -2, -1)
		);
		$divisor		= $sharpness;
		$offset			= 0;
		imageconvolution($new_image, $sharpenMatrix, $divisor, $offset);
	}
      $this->image = $new_image; 
  	}  
   }
   
    
}

if (!function_exists( 'image_resize' ) ){
	function image_resize ($file,$width,$height,$save){
		global $image;
		$image = new SimpleImage();
	   	$image->load($file);
	   	$image->resize($width,$height);
	   	$image->save($save);
	}	
}

if (!function_exists( 'image_ratio' ) ){
	function image_ratio ($file,$width,$save){
		global $image;
		$image = new SimpleImage();
	   	$image->load($file);
	   	$image->resizeToWidth($width);
	   	$image->save($save);
	}	
}

if (!function_exists( 'image_scale' ) ){
	function image_scale ($file,$scale,$save){
		global $image;
		global $image;
		$image = new SimpleImage();
	   	$image->load($file);
	   	$image->scale($scale);
	   	$image->save($save);
	}	
}

if (!function_exists( 'image_fly' ) ){
	function image_fly ($file,$width){
		global $image;
	   	header('Content-Type: image/jpeg');
	   	$image = new SimpleImage();
	   	$image->load($file);
	   	$image->resizeToWidth($width);
	   	$image->output();
	}	
}

if (!function_exists( 'thumb' ) ){
	function thumb ($file,$width,$height){
		global $image;
		header('Content-Type: image/jpeg');
		$image = new SimpleImage();
	   	$image->load($file);
	   	$image->resize($width,$height);
	   	$image->output();
	}	
}


/*

You can of course do more than one thing at once. The following example will create two new images with heights of 200 pixels and 500 pixels

   include('SimpleImage.php');
   $image = new SimpleImage();
   $image->load('picture.jpg');
   $image->resizeToHeight(500);
   $image->save('picture2.jpg');
   $image->resizeToHeight(200);
   $image->save('picture3.jpg');


/*
  $image = new SimpleImage();
   $image->load('4170_1095370617770_1031727389_247976_165145_n.jpg');
   //$image->ratio(128,193,'4:6');
   $image->resizeCrop(150,190,'4:5');
  //$image->resizeCrop(128,193,'4:6');
   $image->save('gambar-'.rand(1,1000).'.jpg');
*/

