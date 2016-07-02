<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Reporte de Servicios Otorgados</title>
<link rel="stylesheet" type="text/css" href="../../../css/01/class_gen.css"/>
</head>

<body>

<?php

/*
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('default_socket_timeout', 6000);
*/

$data = $_POST['data'];

if (!isset($data)){
	header('Location: http://difcentro.tecnointel.mx/');
}
parse_str($data);


switch (intval($tiporeporte)) {
	case 0:
		$tr = "_fmt_1.xlsx";
		break;
	case 1:
		$tr = "_fmt_2.xlsx";
		break;
	case 2:
		$tr = "_fmt_3.xlsx";
		break;
	case 3:
		$tr = "_fmt_4.xlsx";
		break;
	case 4:
		$tr = "_fmt_5.xlsx";
		break;
}

set_include_path(get_include_path() . PATH_SEPARATOR . '../PHPExcel/Classes/');
set_time_limit(600);
require_once("../PHPExcel/Classes/PHPExcel.php");
require_once("../PHPExcel/Classes/PHPExcel/Reader/Excel2007.php");
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
$objPHPExcel = $objReader->load("templates/".$tr); 
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->setIncludeCharts(TRUE);

$oS = $objPHPExcel->getActiveSheet();

require('../oCentura.php');
$f = oCentura::getInstance();

$rs = $f->getQuerys(2022,$data);

$oS->setCellValue("D3", $fi);
$oS->setCellValue("F3", $ff);

switch (intval($tiporeporte)) {
	case 0:
		$k=6;
		break;
	case 1:
		$k=7;
		$oS->setCellValue("D4", $cComunidad);
		break;
	case 2:
		$k=8;
		$oS->setCellValue("D4", $cComunidad);
		$oS->setCellValue("D5", $cBloque);
		break;
	case 3:
		$k=8;
		$oS->setCellValue("D4", $cComunidad);
		$oS->setCellValue("D5", $cServicio);
		break;
	case 4:
		$k=7;
		$oS->setCellValue("D4", $cComunidad);
		break;
}
$total=0;
foreach ($rs as $i => $value) {

		$oS->setCellValue("A".$k, $rs[$i]->idbeneficiootorgado);
		$oS->setCellValue("B".$k, $rs[$i]->beneficiario);
		$oS->setCellValue("C".$k, $rs[$i]->localidad);
		$oS->setCellValue("D".$k, $rs[$i]->telefono);
		$oS->setCellValue("E".$k, $rs[$i]->correo_electronico);
		$oS->setCellValue("F".$k, $rs[$i]->csexo);
		$oS->setCellValue("G".$k, $rs[$i]->beneficio);
		$oS->setCellValue("H".$k, $rs[$i]->subcategoria);
		$oS->setCellValue("I".$k, $rs[$i]->cantidad);
		$oS->setCellValue("J".$k, $rs[$i]->fecha2);
		$total += intval($rs[$i]->cantidad);
		if ( ($i+1) < count($rs) ){
			$oS->insertNewRowBefore($k+1, 1);
		}	
		++$k;

}
$oS->setCellValue("I".$k, $total);

$fileout= "salidas/reporte-por-fecha.xlsx";
$objWriter->save($fileout);

echo "Archivo generado con &eacute;xito, para abrir haga click <a href='http://difcentro.tecnointel.mx/php/01/docs/".$fileout."'>aqu&iacute;</a>"  

?>


</body>
</html>


