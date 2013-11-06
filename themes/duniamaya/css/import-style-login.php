<?php
error_reporting(0);
header('Content-type: text/css');
ob_start("compress");
function compress($buffer) {
  /* remove comments */
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
  /* remove tabs, spaces, newlines, etc. */
  $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
  return $buffer;
}
/* Site CSS */
function inc($file){
	if(!empty($file) && file_exists($file))
	require_once($file);
}

/* Site CSS */
inc('uniform.aristo.css');
inc('login.css');
inc('css3buttons.css');
inc('inner.css');


ob_end_flush();