<?php
require_once("globals.php");
require_once('function.php');
require_once('../PHPMailer/class.phpmailer.php');
	
$bits = array(10,11,17, 18, 21, 22, 24, 25);

$retval = array();
for($x = 0; $x < count($bits); $x++) {
  $bit = $bits[$x];
  $val = trim(@shell_exec("cat /sys/class/gpio/gpio".$bit."/value"));
  $dir = trim(@shell_exec("cat /sys/class/gpio/gpio".$bit."/direction"));
	
  $val = $val == "1" ? 1 : 0;
  $dir = $dir == "out" ? "o" : "i";
	
  $retval['state'][] = array("bit" => $bit, "val" => $val, "dir" => $dir);
}
echo json_encode($retval);
