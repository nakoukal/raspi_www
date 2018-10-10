<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>	
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="cache-control" content="no-cache">
		<link rel="stylesheet" type="text/css"  href="css/style.css">
        <title></title>
       </head>
	<body>
<?php
require_once("class/class.MySQL.php");
require_once("globals.php");

if(isset($_GET["sensorID"]))$sensorID = filter_var($_GET["sensorID"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
if(isset($_GET["releay"]))$releay = filter_var($_GET["releay"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
if(isset($_GET["act"]))$act = filter_var($_GET["act"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
if(isset($_GET["req"]))$req = filter_var($_GET["req"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
if(isset($_GET["des"]))$des = filter_var($_GET["des"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

echo	"<h1>$des</h1>";

echo	'<input type="button" value="HOME" name="name" onclick="window.location=\'index.php\'">';

//echo	'<div class="container">';
//echo		'<div class="contentA">';			
//echo		'</div>';
echo		'<div>';
echo		'<img src="/smarthome/temp/day_'.$sensorID.'.png" width="500px" />';			
echo		'';			
echo		'</div>';

//echo	'</div>';



?>	
		<img src="/smarthome/temp/day.png" width="500"/>

	</body>
</html>
