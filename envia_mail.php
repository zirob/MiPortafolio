<?php

include('PHPMailer/class.phpmailer.php');
include('PHPMailer/class.smtp.php');
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true; 
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; 
$mail->Username = "portafolioramirezbrs@gmail.com"; 
$mail->Password = "cipedi5glu";


$mail->From = "portafolioramirezbrs@gmail.com"; 
$mail->FromName = "Portafolio Boris"; 
$mail->Subject = "Enviado desde Portafolio"; 
$mail->AltBody = "Este es un mensaje de prueba."; 
$mail->MsgHTML("<b>Este es un mensaje de prueba</b>."); 
// $mail->AddAttachment("files/files.zip"; 
// $mail->AddAttachment("files/img03.jpg"; 
$mail->AddAddress("ramirezbrs@gmail.com", "Boris Ramirez"); 
$mail->IsHTML(true); 
$mail->SMTPDebug = 2;

if(!$mail->Send()) { 
	echo "Error: " . $mail->ErrorInfo; 
} else { 
	echo "Mensaje enviado correctamente"; 
}
?>