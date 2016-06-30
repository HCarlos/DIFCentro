<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');

header("application/json; charset=utf-8");  
header("Cache-Control: no-cache");

$data =$_POST['data'];

function sendMail($data){
	parse_str($data);
// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";

// Cabeceras adicionales
$cabeceras .= 'To: '.utf8_decode('Info Logydes').' <info@logydes.com.mx>'. "\r\n";
$cabeceras .= utf8_decode('From: PlatSource <noreplay@tecnointel.mx>') . "\r\n";
//$cabeceras .= 'Cc: manager@logydes.com.mx' . "\r\n";
$titulo = utf8_decode("Token Personal");
//mail("dc@tabascoweb.com",$titulo,"Jorge, se agregó la solicitud:".$cfolio." al sistema, realizado el día ".$fecha,$cabeceras);

mail($correoelectronico,$titulo,"Estimado <b>$username</b>, este es el dato que debe copiar y pegar en el campo TOKEN de su perfil: <code>$token_source</code> \n\nEste dato es instransferible. ",$cabeceras);

}

sendMail($data);

$ret = array();
$ret[0]->msg =  "OK";
$m = json_encode($ret);
echo $m;

?>

