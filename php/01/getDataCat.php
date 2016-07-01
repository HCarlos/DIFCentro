<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');

header("application/json; charset=utf-8");  
header("Cache-Control: no-cache");

require_once("oCentura.php");
$f = oCentura::getInstance();

$index    = $_POST['o'];
$var2     = $_POST['t'];
$cad      = $_POST['c'];
$proc     = $_POST['p'];
$from     = $_POST['from'];
$cantidad = $_POST['cantidad'];
$otros    = $_POST['s'];

$ret = array();
switch($index){
	case -3:
		switch($proc){
			case 0:
				$ret = $f->getDepAreaFromUser($cad);
				$ret[0]->iduser =$ret[0]; 
				$ret[0]->iddep =$ret[1]; 
				break;
		}
		break;
	case -2:
		$ret = $f->getPermissions($cad);
		break;
/*
	case -1:
		switch($proc){
			case 0:
				$ret = $f->getDepFromUser($cad);
				$ret[0]->iduser =$ret[0]; 
				$ret[0]->iddep =$ret[1]; 
				break;
		}
		break;
*/		
	case -1: // Config
	case 0: // Usuarios
	case 1: // Estados
	case 2: // Muncipios
	case 3: // Cat_Niveles
	case 4: // Cat_Grupos
	case 5: // Cat_Alumnos
	case 6: // Cat_Profesores
	case 7: // Cat_Materias
	case 8: // Cat_Materias_Clasificacion
	case 9: // Cat_Parentezco
	case 10: // Cat_Personas
	case 11: // Cat_Familias
	case 12: // Familia-Personas
	case 13: // Familia-Alumnos
	case 14: // Cat_Registros_Fiscales
	case 15: // Familia-Registros-Fiscales
	case 16: // Grupo - Materias
	case 17: // Boletas
	case 18: // Grupo_Materia_Config
	case 19: // Boleta_Partes
	case 20: // Catalogo de Observaciones
	case 21: // Fijas Evaluaciones
	case 22: // Grupo - Alumnos
	case 23: // Cat_Directores
	case 24: // Profesor a Director
	case 25: // Cat_Conceptos
	case 26: // Cat_Emisores_Fiscales
	case 27: // Cat_Pagos
	case 28: // Pagos
	case 29: // Cat_Metodos_de_Pago
	case 30: // Cat_Colores
	case 31: // Cat_Productos
	case 32: // Cat_Medidas
	case 33: // Cat_Proveedores
	case 34: // Solicitud de Materiales
	case 35: // Cat_Supervisores_Caja
	case 36: // Cat_Supervisores_Sol_Mat
	case 37: // Cat_Supervisores_Entrega 
	case 38: // Solicitantes vs Autorizantes
	case 39: // Captura de Pedidos

	case 1000: // Catalogo de Dependencias
	case 1001: // Catalogo de Oficinas
	case 1002: // Catalogo de Clases
	case 1003: // Catalogo de SubClases
	case 1004: // Catalogo de Ingresos
	case 1005: // Contribuciones
	case 1006: // Subcategorias
	case 1007: // Localidades
	case 1008: // Beneficios Otorgados
	case 1009: // Catálogo de Beneficiarios

		switch($proc){
			case 0:
				$ret = $f->getCombo($index,$cad,0,0,$var2,$otros);
				break;
			case 1:
				$ret[0]->msg = $f->setAsocia($index,$cad,0,0,$var2, $otros);
				break;
			case 2:
                $res = $f->setSaveData($index,$cad,0,0,$var2);
				$ret[0]->msg = $res;
				if (trim($res)!="OK"){
					$pos = strpos($res, 'Duplicate');
					if ($pos !== false) {
	     				$ret[0]->msg = "Valor DUPLICADO";
					}else{
						//require_once('core/messages.php');
						$ret[0]->msg = str_replace("Table 'tecnoint_dbPlatSource.", "", $res);
						$ret[0]->msg = str_replace("' doesn't exist", "", $ret[0]->msg);
						
					}
				}
				break;
			case 3:
				$res = $f->genUserFromCat($cad,$index);
				if ($res == 'true'){
					$ret[0]->msg  = "OK";
				}else{
					$ret[0]->msg  = $res;
				}
				break;
			case 4:
				$ret = $f->getQuerys($var2,$cad,0,$from,$cantidad);
				if (count($ret) <= 0){
						$ret[0]->razon_social = "No se encontraron datos";
						$ret[0]->idcli  = -1;
						$ret[0]->tel1   = "";
						$ret[0]->cel1   = "";
						$ret[0]->email  = "";
				}else{
					$xx = 0;
					if (intval($var2)==22) {
						$x = $f->getQuerys($var2,$cad,0,$from,$cantidad,array(),$otros,0);
						$xx = count($x);
					}
					foreach($ret as $i=>$value){
						$ret[$i]->registros = $xx;
					}
				}
				break;
			case 10:
				$ret = $f->getQuerys($var2,$cad,0,0,0);
				break;
			case 11:
				$ret = $f->getQuerys($var2,$cad,0,0,0,array(),$otros);
				break;
			case 12:
                $res = $f->setSaveData($index,$cad,0,0,$var2,$otros);
				$ret[0]->msg = $res;
				break;
			case 13:
				$res = $f->genNumListaPorGrupo($cad);
				$ret[0]->msg  = $res;
				break;
			case 14:
				$res = $f->cloneNumEvalFromGruMatConAnterior($cad);
				$ret[0]->msg  = $res;
				break;

		}
		break;
		
}

function emoticons($msg){
	$url = "http://187.157.42.100/chating/images/emoticons/";
	$img = "<img src='http://187.157.42.100/chating/images/emoticons/sonrisa1.png' />";

	$letters = array("%3B)");
	$fruit   = array($img);

	$output = str_replace($letters, $fruit, $msg);
	
	$output = addslashes($output);

	return $output;	

}


$m = json_encode($ret);
echo $m;
?>
