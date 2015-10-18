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
