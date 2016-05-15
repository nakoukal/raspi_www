<?php
require_once("class/class.MySQL.php");
require_once('class/PHPMailerAutoload.php');
require_once('class/class.HTTPAnswer.php');
require_once("globals.php");

if(isset($_GET["id"]))$id = filter_var($_GET["id"], FILTER_SANITIZE_STRING);
if(isset($_GET["value"]))$value = filter_var($_GET["value"], FILTER_SANITIZE_STRING);
if(isset($_GET["desc"]))$desc = filter_var($_GET["desc"], FILTER_SANITIZE_STRING);
if(isset($_POST["id"]))$id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
if(isset($_POST["value"]))$value = filter_var($_POST["value"], FILTER_SANITIZE_STRING);
if(isset($_POST["desc"]))$desc = filter_var($_POST["desc"], FILTER_SANITIZE_STRING);


$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
$Mail = new PHPMailer();
$HTTPAnswer = new HTTPAnswer();

$temp=round($value,1);
//echo "CALL insert_value('$desc','$id',$value)";
$res = $oMySQL->ExecuteSQL("CALL insert_value('$desc','$id',$value)");
if(!$res){
  echo $oMySQL->lastError;
}
else
{
   //echo "DATA_OK";
  $HTTPAnswer->HTTPAnswer(HTTP_ANSWER_STATUS_200,"1|DATA_OK",true);
}
