<?php
require_once('globals.php');
require_once('class/class.HTTPAnswer.php');
require_once('class/class.MySQL.php');
require_once('function.php');
$HTTPAnswer = new HTTPAnswer();
$oMySQL = new MySQL('temperature', $dblogin, $dbpwd, $dbhost, 3306);

$Res = $oMySQL->ExecuteSQL("CALL get_last_temp();");
$newRes = array();
foreach($Res as $row){
	$row['color'] = GetColor($row["act"],$row["limits_pos"],$row["limits_neg"]);
	$newRes[] = $row;
}
//echo "<pre>";
//print_r($newRes);
//exit();

$retval['temp'] = $newRes;

$HTTPAnswer->HTTPAnswer(HTTP_ANSWER_STATUS_200,json_encode($retval),true);
