<?php
function smtpmailer($to, $from, $from_name, $subject, $body) { 
  $mail = new PHPMailer();
 
  $mail->IsSMTP(true);
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = "tls"; 
 
  $mail->Host = "smtp.gmail.com";
  $mail->Port = 587; 
  $mail->Username = $GLOBALS["from"];
  $mail->Password = $GLOBALS["gpswd"];           
 
  $mail->From = $from;
  $mail->FromName = $from_name;

  
 
// Chceme email ve formátu HTML
  $mail->IsHTML(true);
  $mail->CharSet = "utf-8";
 

 
// Přidání adresy zákazníka
  $addr = explode(';',$to);
  foreach ($addr as $ad) {
    $mail->AddAddress( trim($ad) );
  } 
  $mail->Subject = $subject;

  $mail->Body = $body;  
 
  if(!$mail->Send()) {
    echo "Chybová hláška: " . $mail->ErrorInfo;
  }
}

function EncodeKey($key){
   //dekodovani zasifrovaneho klice pro overeni autorizace
   $cas = date('YmdHi');
   $cas = strtotime($cas);
   $cas = $cas * 2 - 13;
   return ($cas == $key); 
}

function addEvent($oMySQL,$vars)
{
	$oMySQL->Insert($vars,"events");
}

function GetSun()
{
	// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://api.sunrise-sunset.org/json?lng=18.11188&lat=49.53617',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		$out = json_decode($resp);
		curl_close($curl);
		
		$mask='H:i';
	
		$tz_database = new DateTimeZone('GMT');
		$tz_user = new DateTimeZone('Europe/Prague');
 
		$date =  new DateTime($out->results->sunrise, $tz_database);
		$date->setTimezone($tz_user);
		$sunrise = $date->format($mask); 
		
		$date =  new DateTime($out->results->sunset, $tz_database);   
		$date->setTimezone($tz_user);
		$sunset = $date->format($mask); 
		
		return array('sunrise'=>$sunrise,'sunset'=>$sunset);
}
