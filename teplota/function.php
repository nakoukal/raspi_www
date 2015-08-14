<?php
function sendMail($temp,$SensorDesc){
	$subject="Dosažení teploty : $temp °C na zařízení $SensorDesc";
	$body="Dosažení teploty : $temp °C na zařízení $SensorDesc čas:".date('d.m.Y H:i');
  return smtpmailer($GLOBALS["email"], $GLOBALS["from"], $GLOBALS["from_name"], $subject,$body);
}


function smtpmailer($to, $from, $from_name, $subject, $body) { 
  $mail = new PHPMailer();
 
  $mail->IsSMTP(true);
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = "tls"; 
 
  $mail->Host = "smtp.gmail.com";
  $mail->Port = 587; 
  $mail->Username = $GLOBALS["email"];
  $mail->Password = $GLOBALS["gpswd"];           
 
  $mail->From = $from;
  $mail->FromName = $from_name;

  
 
// Chceme email ve formátu HTML
  $mail->IsHTML(true);
  $mail->CharSet = "utf-8";
 

 
// Přidání adresy zákazníka
  $mail->AddAddress($to);
 
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