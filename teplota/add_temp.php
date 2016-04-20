<?php
require_once("class/class.MySQL.php");
require_once('class/PHPMailerAutoload.php');
require_once("globals.php");

if(isset($_GET["id"]))$id = filter_var($_GET["id"], FILTER_SANITIZE_STRING);
if(isset($_GET["value"]))$value = filter_var($_GET["value"], FILTER_SANITIZE_STRING);
if(isset($_GET["desc"]))$desc = filter_var($_GET["desc"], FILTER_SANITIZE_STRING);


$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
$Mail = new PHPMailer();

$temp=round($value,1);
//echo "CALL insert_value('$desc','$id',$value)";
$res = $oMySQL->ExecuteSQL("CALL insert_value('$desc','$id',$value)");
if(!$res){
  return $oMySQL->lastError;
}
