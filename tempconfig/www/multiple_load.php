<?php
//
// +------------------------------------------------------------------------+
// | PSMA - Production Server Management Application
// +------------------------------------------------------------------------+
// | Copyright (C) 2011 Continental Automotive Systems Czech Republic s.r.o.
// | Krizka Miloslav
// +------------------------------------------------------------------------+
//

/**
  * Compress and multiple send files to browser
  *
  * @package PSMA
  * @subpackage PSMA
  *
  * @copyright Copyright (C) 2011 Continental Automotive Systems Czech Republic s.r.o.
  * @author Krizka Miloslav
  * @version v1.0
  * 
  */
	$OutputCompressing = TRUE;
	$OutputMultipleLoadCacheExpireSec = 259200;
	// Output buffering and compressing
	if ($OutputCompressing && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) 
		ob_start("ob_gzhandler"); 
	else  
		ob_start();
	
	// Content-type constants
	define('TYPE_CSS','text/css');
	define('TYPE_JAVASCRIPT','text/javascript');
	
	// Define Phases
	$Phases = array(		
		// CSS Files
   		'css_main'		=> array('ContentType'=> TYPE_CSS,
						 'Files'=> array(
							"css/screen.css",
							"js/jquery-ui/jquery-ui.min.css",
							"css/jquery.datetimepicker.css",
							"css/menu.css",
							"css/grid.css",
							"css/example.css",
							"css/lightbox.css",
						   )),
	
		// JS Files
	    'js_main'			=> 	array(	'ContentType'=> TYPE_JAVASCRIPT,
									'Files'=> array(
											"js/jquery.min.js",
											"js/jquery-ui/jquery-ui.min.js",
											"js/jquery.datetimepicker.js",
											"js/jquery-migrate-1.2.1.min.js", 
											"js/netteForms.js", 
											"js/grid.js", 
											"js/lightbox.js", 
											"js/nette.ajax.js",
											"js/main.js",)),
	);

   	// Check requested Phase
   	if (!(array_key_exists('phase',$_GET) && array_key_exists($_GET['phase'],$Phases))) {
   		echo "/* === PSMA MULTIPLE FILE LOADER === */\r\n";
   		echo "/* !!! ERROR: REQUESTED PHASE NOT FOUND !!! */\r\n";
   		die();
   	}   	
   	
   	// Send data   	
	header ("content-type: ".$Phases[$_GET['phase']]['ContentType']."; charset: UTF-8");
   	header ("cache-control: must-revalidate");
   	header ("expires: " . gmdate ("D, d M Y H:i:s", time() + $OutputMultipleLoadCacheExpireSec) . " GMT");
   	
   	echo "/* === PSMA MULTIPLE FILE LOADER === */\r\n";
   	
  	foreach($Phases[$_GET['phase']]['Files'] as $PhaseFile) {
  		if (file_exists($PhaseFile)) {
  			echo "/* FILE CONTENT $PhaseFile : */\r\n";
  			readfile($PhaseFile);			
  		} else {
  			echo "/* !!! ERROR: FILE $PhaseFile NOT FOUND !!! */\r\n";
  		}
  		echo "\r\n";
  	}   
  	
  	ob_end_flush(); flush();
?>