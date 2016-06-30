<?php
function sendMail($email){

// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";

// Cabeceras adicionales
$cabeceras .= 'To: '.utf8_decode('Registry SiacGob').' <'.$email.'>'. "\r\n";
$cabeceras .= utf8_decode('From: SIAC Gob <dc@tabascoweb.com>') . "\r\n";
$cabeceras .= 'Cc: manager@logydes.com.mx' . "\r\n";
$titulo = utf8_decode("Bienvenido a SiacGob");
mail("dc@tabascoweb.com",$titulo,"Bienvenido, gracias por registrarte en nuestro sistema. \n\n Para validar tu cuenta haz click <a href='http://dc.tabascoweb.com/validEmail/".$email."'>aqui</a>",$cabeceras);

//mail("dc@tabascoweb.com",$titulo,"Carlos, se agregó la solicitud:".$cfolio." al sistema, realizado el día ".$fecha,$cabeceras);

}
?>

