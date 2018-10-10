<?php
require_once("../class/class.MySQL.php");
require_once("../globals.php");
require_once '../functions.php';
/*
if(isset($_GET["label"]))$Label = filter_var($_GET["label"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
if(isset($_GET["color"]))$Color = filter_var($_GET["color"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
if(isset($_GET["temp"]))$Temp = filter_var($_GET["temp"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
if(isset($_GET["cerp"]))$Cerp = filter_var($_GET["cerp"], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
*/
$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
$rows = GetSensorsTemp($oMySQL);
foreach ($rows as $row) {
        $bgcolor=getColor($row['act_temp'],$row['limits_pos'],$row['limits_neg']);
        echo print_temp($row,$bgcolor);
}