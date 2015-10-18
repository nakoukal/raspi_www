<?php
include_once('../vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
include_once('class/class.HTTPAnswer.php');
include_once('class/class.GPIO.php');
include_once('class/class.MySQL.php');
require("globals.php");
require("function.php");
$bit = NULL;
$deviceName = "";
$oMySQL = "";
$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
if(isset($_POST["act"]))$act = filter_var($_POST["act"], FILTER_SANITIZE_STRING);
if(isset($_GET["act"]))$act = filter_var($_GET["act"], FILTER_SANITIZE_STRING);
if(isset($_POST["bit"]))$bit = filter_var($_POST["bit"], FILTER_SANITIZE_STRING);
if(isset($_GET["bit"]))$bit = filter_var($_GET["bit"], FILTER_SANITIZE_STRING);
if(isset($_POST["value"]))$value = filter_var($_POST["value"], FILTER_SANITIZE_STRING);
if(isset($_GET["value"]))$value = filter_var($_GET["value"], FILTER_SANITIZE_STRING);
if(isset($_POST["dev"]))$deviceName = filter_var($_POST["dev"], FILTER_SANITIZE_STRING);
if(isset($_GET["dev"]))$deviceName = filter_var($_GET["dev"], FILTER_SANITIZE_STRING);

$mailSubject="RASPI RELE BIT:$bit  $deviceName";
$mailBody="<b>Aktivita:</b> RASPI RELE BIT<BR>\n <b>Device:</b> $deviceName <BR>\n <b>Datum:</b> ".date("d.m.Y H:i:s")."<BR>\n <b>BIT :</b> $bit <BR>\n <b>IP:</b> $ip";
$oMySQL = new MySQL('temperature', $GLOBALS["dblogin"], $GLOBALS["dbpwd"], $GLOBALS["dbhost"], 3306);
$Gpio = new GPIO($oMySQL);


switch ($act) {
default:
	
	break;

  case 'readallevents':
    $Gpio->getAllEventsOnJson();
  break;
  
  case 'readall':
    $Gpio->getAllBitsOnJson();
  break;
  
  case 'readby':
    $Gpio->getBitByOnJson($bit);  
  break;
  
  case 'writevalue':
	$Gpio->readbitBy($bit);
	$value = ($Gpio->value==0)?1:0;
	$Gpio->writeValueByBit($bit,$value);
	addEvent($oMySQL,array('ip'=>$ip,'device'=>$deviceName,'bit'=>$bit,'value'=>$value));
	smtpmailer($GLOBALS["email"], $GLOBALS["from"], $GLOBALS["from_name"],$mailSubject,$mailBody);
  break;
  
  case 'opengate':
    $Gpio->readBitBy($bit);
    echo $Gpio->value;
    if($Gpio->value == 0)
    {
      $Gpio->writeValueByBit($bit,1);
	  addEvent($oMySQL,array('ip'=>$ip,'device'=>$deviceName,'bit'=>$bit,'value'=>1));
    }
        
    usleep(400000);
    
    $Gpio->readBitBy($bit);
    
    if($Gpio->value == 1)
    {
      $Gpio->writeValueByBit($bit,0);
	  addEvent($oMySQL,array('ip'=>$ip,'device'=>$deviceName,'bit'=>$bit,'value'=>0));
    }
	smtpmailer($GLOBALS["email"], $GLOBALS["from"], $GLOBALS["from_name"],$mailSubject,$mailBody);
  break;
}
?>
