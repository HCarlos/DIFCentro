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

$M->Actualiza_Promedios_Grupales_por_Materia($user,$arrAlu[0]);

// $M->Actualizar_Promedios_Grupales_Idiomas(0,0,$user,$arrAlu[0],0);
// $M->Actualizar_Promedios_Grupales_Idiomas(0,0,$user,$arrAlu[0],1);

foreach ($arrAlu as $i => $value) {
	

	$M->Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user,$arrAlu[$i]);
	$result = $f->getQuerys(45,"idgrualu=".$arrAlu[$i],0,0,0,array()," order by orden_impresion asc ",1);
	
	if ( count($result)>0  ){


		foreach ($result as $j => $value) {

		}

		$M->Actualiza_Grupo_Alumno_Promedio_Idioma($user,$result[$j]->idgrualu,0);

		$result = $f->getQuerys(46,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);

		foreach ($result as $j => $value) {

		}

		$M->Actualiza_Grupo_Alumno_Promedio_Idioma($user,$result[$j]->idgrualu,1);

	}
	
}
$M->Actualizar_Promedios_Grupales_Idiomas(0,0,$user,$arrAlu[0],0);
$M->Actualizar_Promedios_Grupales_Idiomas(0,0,$user,$arrAlu[0],1);

$ret[0]->msg = "OK";
$m = json_encode($ret);

echo $m;

?>