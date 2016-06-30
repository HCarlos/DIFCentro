<?php

header("application/json: charset=utf-8");

require_once("../oCentura.php");
$f = oCentura::getInstance();

$type = $_GET['token'];

if (isset($type)){

	$arr = $f->getQuerys(2015,$type);

	$m = json_encode($arr);

	echo $m;

	}
?>
