<?php
include_once('../PHPMailer/PHPMailerAutoload.php');
include_once('../class/class.HTTPAnswer.php');
include_once('../class/class.GPIO.php');
require("globals.php");
require("function.php");
$bit = NULL;
if(isset($_POST["act"]))$act = filter_var($_POST["act"], FILTER_SANITIZE_STRING);
if(isset($_GET["act"]))$act = filter_var($_GET["act"], FILTER_SANITIZE_STRING);
if(isset($_POST["bit"]))$bit = filter_var($_POST["bit"], FILTER_SANITIZE_STRING);
if(isset($_GET["bit"]))$bit = filter_var($_GET["bit"], FILTER_SANITIZE_STRING);
if(isset($_POST["value"]))$value = filter_var($_POST["value"], FILTER_SANITIZE_STRING);
if(isset($_GET["value"]))$value = filter_var($_GET["value"], FILTER_SANITIZE_STRING);
$Gpio = new GPIO();

switch ($act) {
default:
	
	break;

  case 'readall':
    $Gpio->getAllBitsOnJson();
  break;
  
  case 'readby':
    $Gpio->getBitByOnJson($bit);  
  break;
  
  case 'writevalue':
    $Gpio->writeValueByBit($bit,$value);  
  break;
  
  case 'opengate':
    $Gpio->readBitBy($bit);
    echo $Gpio->value;
    if($Gpio->value == 0)
    {
      $Gpio->writeValueByBit($bit,1);
    }
        
    usleep(1000000);
    
    $Gpio->readBitBy($bit);
    echo $Gpio->value;
    
    if($Gpio->value == 1)
    {
      $Gpio->writeValueByBit($bit,0);
    }
    $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
    smtpmailer($GLOBALS["email"], $GLOBALS["from"], $GLOBALS["from_name"],"Aktivita RASPI RELE BIT ".$bit."-".date("d.m.Y H:i:s"), "Aktivita na RASPI RELE BIT".$bit."<br>\n".$ip); 
  break;
}
?>