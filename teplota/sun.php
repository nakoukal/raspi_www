<?php
require_once("functions.php");
$sun=GetSun();

$today_time = strtotime(date('H:i'));
$sunrise_time = strtotime($sun['sunrise']);
$sunset_time = strtotime($sun['sunset']);

if($today_time > $sunset_time || $today_time < $sunrise_time)
	echo 1;