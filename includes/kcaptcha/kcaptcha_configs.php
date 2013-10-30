<?php

	$alphabet 			= "0123456789abcdefghijklmnopqrstuvwxyz"; # do not change without changing font files!
	$allowed_symbols 	= "23456789abcdeghkmnpqsuvxyz"; #alphabet without similar symbols (o=0, 1=l, i=j, t=f)
	$fontsdir 			= 'fonts';	
	$length 			= 6;
	$width 				= 120;
	$height 			= 60;
	$fluctuation_amplitude = 5;
	$no_spaces 			= true;	
	$show_credits 		= false; # set to false to remove credits line. Credits adds 12 pixels to image height
	$credits 			= '--'; # if empty, HTTP_HOST will be shown
	$background_color 	= array(255, 255, 255);
	$foreground_color 	= array(mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
	$jpeg_quality 		= 90;
?>