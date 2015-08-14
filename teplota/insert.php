<?php
require_once("class/class.MySQL.php");
require_once('class/class.phpmailer.php');
//require_once('class/class.TempControl.php');
require_once("globals.php");

$tempJson = 'NULL';

if(isset($_GET["temp"]))$tempJson = $_GET["temp"];
if(isset($_POST["temp"]))$tempJson = $_POST["temp"];
//if(isset($_POST["temp"]))$Temp = filter_var($_POST["temp"], FILTER_SANITIZE_STRING);
//echo $Temp = '{"a":1,"b":2,"c":3,"d":4,"e":5}';


$tempObj = json_decode($tempJson);
$tempArr = get_object_vars($tempObj);
$columns = "";
$values = "";

/**
 * Insert into temp table
 */
$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
$first = true;
foreach ($tempArr as $key => $temp) {
	$name=getSensorID($key,$oMySQL);
	$temp=round($temp,1);
	if($first){
		$columns.=$name;
		$values.=$temp;
	}else{
		$columns .= ",".$name;
		$values.= ",".$temp;
	}
	$first = false;
	
	/*Insert into new table teplota*/
	$res = $oMySQL->ExecuteSQL("CALL insert_value('$key',$temp)");
	if(!$res){
		echo $oMySQL->lastError;
		continue;
	}
}
/* Insert into table temp */
$avgAKU = round(($tempArr['2E40B4010000']+$tempArr['D94FB4010000']+$tempArr['FF6AB4010000'])/3,1);//average of aku
$sql="INSERT INTO temp ($columns,temp11,timestamp,day) VALUES ($values,$avgAKU,NOW(),DATE_FORMAT(NOW(), '%Y-%m-%d'));";
$oMySQL->ExecuteSQL($sql);



/*Function to get all sensors ids*/
function getSensorID($sensorID,$oMySQL){
	$sql="SELECT name FROM sensors WHERE sensorID='$sensorID'";
	$result=$oMySQL->ExecuteSQL($sql);
	if($oMySQL->records < 1){
		$sql="INSERT INTO sensors (sensorID) VALUES ('$sensorID');";
		$oMySQL->ExecuteSQL($sql);
		
	}
	return $result['name'];
}