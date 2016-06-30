<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');


header("application/json; charset=utf-8");  
header("Cache-Control: no-cache");
$o = $_POST['o'];
$c = $_POST['c'];
$t = $_POST['t'];
$from = $_POST['from'];
$cantidad = $_POST['cantidad'];
$s = $_POST['s'];

parse_str($c);

require('../oCentura.php');
$f = oCentura::getInstance();

require('../oMetodos.php');
$M = oMetodos::getInstance();
$ret = array();

$arrAlu = explode(",",$strgrualu);

$M->Llamar_Actualiza_Promedios_Boletas_por_Grupo($arrAlu[0]);
// $M->Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user,$arrAlu[0]);
$M->Actualizar_Promedios_Grupales(0,0,$user,$arrAlu[0]);

// $M->Actualiza_Promedios_Grupales_por_Materia($user,$arrAlu[0]);

foreach ($arrAlu as $i => $value) {

	$M->Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user,$arrAlu[$i]);
	
	$M->Actualiza_Promedios_Grupales_por_Materia($user,$arrAlu[$i]);

	$result = $f->getQuerys($t,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);
	
	if ( count($result)>0  ){

		foreach ($result as $j => $value) {

		}

		$M->Actualiza_Grupo_Alumno_Promedio($user,$result[$j]->idgrualu);
	
	}

}

$ret[0]->msg = "OK";
$m = json_encode($ret);
echo $m;

?>