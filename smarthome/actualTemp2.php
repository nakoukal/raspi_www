<?php
require_once('globals.php');
require_once('class/class.HTTPAnswer.php');
require_once('class/class.MySQL.php');
$HTTPAnswer = new HTTPAnswer();
$oMySQL = new MySQL('temperature', $dblogin, $dbpwd, $dbhost, 3306);

$Res = $oMySQL->ExecuteSQL("CALL get_last_temp();");

$retval['temp'] = $Res;

$HTTPAnswer->HTTPAnswer(HTTP_ANSWER_STATUS_200,json_encode($retval),true);
