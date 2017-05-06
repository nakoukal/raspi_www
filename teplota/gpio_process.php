<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("class/class.MySQL.php");
require_once('class/PHPMailerAutoload.php');
require_once("globals.php");

if(isset($_GET["action"]))$action = $_GET["action"];
if(isset($_POST["action"]))$action = $_POST["action"];
if(isset($_GET["sensorid"]))$sensorID = $_GET["sensorid"];
if(isset($_POST["sensorid"]))$sensorID = $_POST["sensorid"];
if(isset($_GET["state"]))$state = $_GET["state"];
if(isset($_POST["state"]))$state = $_POST["state"];
$jsouOut = array();
$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
$Mail = new PHPMailer();

switch ($action) {
	case "get":
		$query="select sensorID,releay_number,state_needed,state_actual from v_rel_remote;";
		$jsouOut["releay"] = $oMySQL->ExecuteSQL($query);	
		echo json_encode($jsouOut);
		break;
	case "set":
		$query="update rel_remote set state_actual=$state where sensorID='$sensorID';";
		$oMySQL->ExecuteSQL($query);
		
		if($state==1){
			$query="insert into sensor_events (sensorID,timefrom) values ('$sensorID',NOW());";
		}
		else{
			$query="
			UPDATE sensor_events
			SET timeto=now()  WHERE sensorID='$sensorID'
			and timefrom =(SELECT timefrom FROM (SELECT MAX(timefrom ) FROM sensor_events) AS timefrom) ";
		}		
		$oMySQL->ExecuteSQL($query);
		break;
	default:
		break;
}
