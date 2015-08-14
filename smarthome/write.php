<?php
require_once("globals.php");
require_once('function.php');
require_once('../PHPMailer/class.phpmailer.php');
//without a bit we can do nothing
$bit = NULL;
if(isset($_POST["bit"]))$bit = filter_var($_POST["bit"], FILTER_SANITIZE_STRING);
if(isset($_GET["bit"]))$bit = filter_var($_GET["bit"], FILTER_SANITIZE_STRING);	

if(isset($_POST["key"]))$key = filter_var($_POST["key"], FILTER_SANITIZE_STRING);
if(isset($_GET["key"]))$key = filter_var($_GET["key"], FILTER_SANITIZE_STRING);	

/*
if(!EncodeKey($key)){
$SubjectActivity = "AKTIVITA Authorizace";
  smtpmailer($GLOBALS["email"], $GLOBALS["from"], $GLOBALS["from_name"], $SubjectActivity,"Unauthorized access key:$key");
  $retval['state'][] = array("bit" => $bit, "val" => $val, "dir" => $dir, "result" => 0);
  echo json_encode($retval);
  exit(); 
}
*/


$allowedbits = array(10,11,17, 18, 21, 22, 24, 25);

if($bit!=NULL){
  //only some bits are allowed.
	//basically you can't access GPIOs you haven't exported before but
	//why should you provoke errors (when accessing non-existant files)?
	if(!in_array($bit, $allowedbits)) {
    exit();
	}
	//set direction only when the setdir-parameter is used
	if(isset($_POST["setdir"])) {
		$dir = strtolower(substr($_POST["setdir"], 0, 1));
		if($dir == "i") {
			$dir = "in";
		} elseif($dir == "o") {
			$dir = "out";
		} else {
			$dir = null;
		}
		
		if($dir !== null) {
			echo "echo \"$dir\" > /sys/class/gpio/gpio$bit/direction";
			echo shell_exec("echo \"$dir\" > /sys/class/gpio/gpio$bit/direction");
		}
	}
	
	//set value only when the setval-parameter is used
    $val = NULL;
    if(isset($_POST["setval"]))$val = filter_var($_POST["setval"], FILTER_SANITIZE_STRING);
    if(isset($_GET["setval"]))$val = filter_var($_GET["setval"], FILTER_SANITIZE_STRING);
    if($val !=NULL)shell_exec("echo \"$val\" > /sys/class/gpio/gpio$bit/value");
    $retval['state'][] = array("bit" => $bit, "val" => $val, "dir" => $dir, "result" =>"1");
    switch($bit){
      case 17 :
         $SubjectActivity = "AKTIVITA VRATA GARÁŽ STAV:$val";
         $BodyActivity = "Byla zaznamenána aktivita u garážových vrat! Stav:$val";
      break;
      case 18 :
         $SubjectActivity = "AKTIVITA VRATA VJEZD STAV:$val";
         $BodyActivity = "Byla zaznamenána aktivita u vjezdových vrat! Stav:$val";
      break;
      case 21 :
         $SubjectActivity = "AKTIVITA  SVĚTLO VENKU STAV:$val";
         $BodyActivity = "Byla zaznamenána aktivita světlo venku! Stav:$val";
      break;
      case 22 :
         $SubjectActivity = "AKTIVITA  STAV:$val";
         $BodyActivity = "Byla zaznamenána aktivita ! Stav:$val";
      break;
    }
    
    //$retval['state'][] = array("bit" => $bit, "val" => $val, "dir" => $dir, "result" => 1);
    //smtpmailer($GLOBALS["email"], $GLOBALS["from"], $GLOBALS["from_name"], $SubjectActivity,$BodyActivity."\n<pre>".var_dump($retval)."</pre>");
    echo json_encode($retval);
}
