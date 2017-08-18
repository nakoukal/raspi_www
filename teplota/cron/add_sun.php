<?php
$base = dirname(dirname(__FILE__));
require_once("$base/globals.php");
require_once "$base/functions.php";
require_once("$base/class/class.MySQL.php");
$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
                
SaveSun($oMySQL);

echo $oMySQL->lastError;

