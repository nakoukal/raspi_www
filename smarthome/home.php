<?php
require_once("globals.php");
include_once('class/class.MySQL.php');
$oMySQL = new MySQL('temperature', $GLOBALS["dblogin"], $GLOBALS["dbpwd"], $GLOBALS["dbhost"], 3306);
$sql_temp = "
		SELECT DATE_FORMAT(timestamp, '%d.%m.%Y %H:%i') as time , 
			   temp02 as venku, 
			   temp01 as obyvak, 
			   temp04 as podlaha,
			   temp03 as krb,
			   temp11 as aku,
			   temp08 as tom,
			   temp09 as nela
		FROM temp  
		ORDER BY timestamp DESC LIMIT 1";
$oMySQL->ExecuteSQL($sql_temp);

$temp = $oMySQL->arrayedResult;

//Set the Content Type
header('Content-type: image/jpeg');

// Create Image From Existing File
$jpg_image = imagecreatefromjpeg('home.jpg');

// Allocate A Color For The Text
$black = imagecolorallocate($jpg_image, 0, 0, 0);
$white = imagecolorallocate($jpg_image, 255, 255, 255);

// Set Path to Font File
$font_path = 'font/px_sans_nouveaux.ttf';
imagettftext($jpg_image, 15, 0, 100, 15, $black, 'font/Inconsolata.otf', $temp['time']);

$text = "krb: ".$temp['krb']."°C";
imagettftext($jpg_image, 6, 0, 85, 260, $white, $font_path, $text);
  
$text = "aku: ".$temp['aku']."°C";
imagettftext($jpg_image, 6, 0, 250, 240, $black, $font_path, $text);
  
$text = "venku: ".$temp['venku']."°C";
imagettftext($jpg_image, 6, 0, 45, 45, $black, $font_path, $text);

$text = "obyvak: ".$temp['obyvak']."°C";
imagettftext($jpg_image, 6, 0, 150, 190, $black, $font_path, $text);

$text = "tom: ".$temp['tom']."°C";
imagettftext($jpg_image, 6, 0, 110, 100, $black, $font_path, $text);

$text = "nela: ".$temp['nela']."°C";
imagettftext($jpg_image, 6, 0, 200, 100, $black, $font_path, $text);
  
$text = "podlaha: ".$temp['podlaha']."°C";
imagettftext($jpg_image, 6, 0, 150, 310, $black, $font_path, $text);

// Send Image to Browser
imagejpeg($jpg_image);

// Clear Memory
imagedestroy($jpg_image);