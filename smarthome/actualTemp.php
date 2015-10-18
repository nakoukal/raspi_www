<?php
include_once('globals.php');
include_once('class/class.HTTPAnswer.php');
include_once('class/class.MySQL.php');
$HTTPAnswer = new HTTPAnswer();
$oMySQL = new MySQL('temperature', $dblogin, $dbpwd, $dbhost, 3306);

function getRequiredTemp($oMySQL,$sensorName){
	$Sql = "SELECT TT.Temp reqTemp FROM sensors S 
	JOIN time_temp TT ON S.id = TT.SensorID
	WHERE WEEKDAY(NOW())+1 = TT.Day 
	AND TIME(NOW()) BETWEEN TT.TimeFrom AND TT.TimeTo
	AND S.name = '$sensorName';";
	 
	$Res = $oMySQL->ExecuteSQL($Sql);
	return $Res['reqTemp'];
}

$Sql = "SELECT temp01 oby,temp02 ven,temp03 krb,temp04 pod,temp11 aku, temp08 pokt, temp09 pokn FROM temp ORDER BY timestamp DESC LIMIT 1";
$Res = $oMySQL->ExecuteSQL($Sql);
$retval['temp'][] = array('name'=>'Venek','act'=>$Res['ven'],'req'=>getRequiredTemp($oMySQL,'temp02'));
$retval['temp'][] = array('name'=>'Obyvak','act'=>$Res['oby'],'req'=>getRequiredTemp($oMySQL,'temp01'));
$retval['temp'][] = array('name'=>'Tom','act'=>$Res['pokt'],'req'=>getRequiredTemp($oMySQL,'temp08'));
$retval['temp'][] = array('name'=>'Nela','act'=>$Res['pokn'],'req'=>getRequiredTemp($oMySQL,'temp09'));
$retval['temp'][] = array('name'=>'Krb','act'=>$Res['krb'],'req'=>getRequiredTemp($oMySQL,'temp03'));
$retval['temp'][] = array('name'=>'Podlaha','act'=>$Res['pod'],'req'=>getRequiredTemp($oMySQL,'temp04'));
$retval['temp'][] = array('name'=>'Aku','act'=>$Res['aku'],'req'=>getRequiredTemp($oMySQL,'temp11'));

$HTTPAnswer->HTTPAnswer(HTTP_ANSWER_STATUS_200,json_encode($retval),true);
