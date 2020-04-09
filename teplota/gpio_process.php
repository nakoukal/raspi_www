<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("class/class.MySQL.php");
require_once('class/PHPMailerAutoload.php');
require_once("globals.php");
require_once("functions.php");

if(isset($_GET["action"]))$action = $_GET["action"];
if(isset($_POST["action"]))$action = $_POST["action"];
if(isset($_GET["sensorid"]))$sensorID = $_GET["sensorid"];
if(isset($_POST["sensorid"]))$sensorID = $_POST["sensorid"];
if(isset($_GET["state"]))$state = $_GET["state"];
if(isset($_POST["state"]))$state = $_POST["state"];
if(isset($_GET["releay"]))$releay = $_GET["releay"];
if(isset($_POST["releay"]))$releay = $_POST["releay"];

$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
$Mail = new PHPMailer();
$jsonOut = array();
switch ($action) {
	case "get":
		$query='SELECT sensorID,releay_number,releay_timeout,state_needed,state_actual FROM v_rel_remote;';

		$jsonOut["releay"] = $oMySQL->ExecuteSQL($query);
		echo json_encode($jsonOut);
		break;

	case "set":
		$query="update rel_remote set state_actual=$state where sensorID='$sensorID' and releay_number=$releay;";
		$oMySQL->ExecuteSQL($query);

		if($state==1){
			$query="insert into sensor_events (sensorID,timefrom) values ('$sensorID',NOW());";
		}
		else{
			$query="
			UPDATE sensor_events SET timeto=now()
			WHERE sensorID='$sensorID'
			and timefrom =(SELECT timefrom FROM (SELECT MAX(timefrom ) timefrom FROM sensor_events where sensorID='$sensorID') AS timefrom);
			";
		}
		$oMySQL->ExecuteSQL($query);
		break;
	case "sun":
		echo json_encode(GetSun());
		break;
	case "get_sensors":
		$query="select sensorID,pozice,description,limits_pos,limits_neg,active,acu_temp_lim from sensors;";

		$jsonOut["sensors"] = $oMySQL->ExecuteSQL($query);
		echo json_encode($jsonOut);
		break;
	case "get_rel_remote":
		$query="select * from v_rel_remote;";

		$jsonOut["rel_remotes"] = $oMySQL->ExecuteSQL($query);
		echo json_encode($jsonOut);
		break;
	default:
		break;
}
