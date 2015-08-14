<?php
require_once("class/class.MySQL.php");
require_once('class/PHPMailerAutoload.php');
require_once('class/class.TempControl.php');
require_once("globals.php");

if(isset($_GET["temp"]))$temp = $_GET["temp"];
if(isset($_POST["temp"]))$temp = $_POST["temp"];

$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
$Mail = new PHPMailer();
$TempContrl = new TempControl($temp, $oMySQL,$Mail);
$jsouOut = array();


//Insert temperature into db
$TempContrl->InsertIntoTemp();

//Control thermostat on off
$jsouOut["heating"] = $TempContrl->Thermostat();
$jsouOut["solar"] = $TempContrl->Solar();
echo json_encode($jsouOut);