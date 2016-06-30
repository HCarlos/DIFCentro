<?php


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');


header("application/json; charset=utf-8");  
header("Cache-Control: no-cache");

require_once("../oCentura.php");
$f = oCentura::getInstance();
$arg   = $_POST['data'];
$ret = array();


$ret = $f->getCombo(0,$arg,0,0,0);
if (count($ret)>0){
	$ret[0]->msg = "OK";
}else{
	$ret[0]->msg = "Username o Password incorrectos";
}
$m = json_encode($ret);
echo $m;

/*
$ret = $f->getCombo(2000,$arg,0,0,0);
if (count($ret)>0){
	$ret = $f->getCombo(2000,$arg,0,0,1);
	if (count($ret)>0){
		$ret[0]->msg = "OK";
	}else{
		$ret[0]->msg = "No ha validado su cuenta. \n\nRevise su la cuenta de correo que nos proporcionó, incluyendo su Bandeja de No Deseados";
	}	
}else{
	$ret[0]->msg = "Username o Password incorrectos.";
}
$m = json_encode($ret);
echo $m;
*/
?>
