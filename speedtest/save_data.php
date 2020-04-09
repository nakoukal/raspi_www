<?php
//Server ID,Sponsor,Server Name,Timestamp,Distance,Ping,Download,Upload,Share,IP Address
include_once('../class/class.MySQL.php');
require("globals.php");

if(isset($_GET["data"]))$data = $_GET["data"];

$decoded_data = base64_decode($data);

$array_data = explode("," , $decoded_data);
$assoc_data = array();

$assoc_data['ServerID'] 	= $array_data[0];
$assoc_data['Sponsor']         	= $array_data[1];
$assoc_data['Servername']       = $array_data[2];
$assoc_data['Timestamp']        = $array_data[3];
$assoc_data['Distance']         = round($array_data[4],1);
$assoc_data['Ping']         	= $array_data[5];
$assoc_data['Download']         = round($array_data[6]/1024/1024,1);
$assoc_data['Upload']         	= round($array_data[7]/1024/1024,1);
$assoc_data['Share']         	= $array_data[8];
$assoc_data['IPAddress']        = $array_data[9];

//echo "<pre>";
//var_dump($assoc_data);

$oMySQL = new MySQL('temperature', $GLOBALS["dblogin"], $GLOBALS["dbpwd"], $GLOBALS["dbhost"], 3306);


$oMySQL->Insert($assoc_data, 'speed');



