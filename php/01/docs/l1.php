<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SIBM | Centro 2013 - 2015</title>
<link rel="stylesheet" type="text/css" href="../../../css/01/class_gen.css"/>
</head>

<body>

<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');
set_include_path(get_include_path() . PATH_SEPARATOR . '../PHPExcel/Classes/');
set_time_limit(600);

require_once("../PHPExcel/Classes/PHPExcel.php");
require_once("../PHPExcel/Classes/PHPExcel/Reader/Excel2007.php");
require_once('../vo/voConn.php');
require_once("../oFunctions.php");

$arg = $_POST["arg"];

$F = oFunctions::getInstance();
$Conn = voConn::getInstance();
$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
mysql_select_db($Conn->db);
mysql_query("SET NAMES UTF8");

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);



$objPHPExcel = $objReader->load("templates/_fmt_rep_prepa_arji_1.xlsx"); //cargamos el archivo excel (extensión *.xlsx)
$fileout= "--rep-1.xlsx";
//$query = "Select count(idprodgpo) as sumid, idprodgpo,abrdep as grupo, grupo as dependencia from _viDemanda where (fecha between '$fi' and '$ff')  group by idprodgpo";
$query = "SELECT * FROM _viUsuariosBecas $cad ";

$result = mysql_query($query);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); //objeto de PHPExcel, para escribir en el excel
$objWriter->setIncludeCharts(TRUE);


	$i=9;$k=0;	
	while ($fila = mysql_fetch_object($result)) {


		$objPHPExcel->getActiveSheet()->setCellValue("A".$i, $fila->idusuario);
		$objPHPExcel->getActiveSheet()->setCellValue("B".$i, $fila->curp);
		$objPHPExcel->getActiveSheet()->setCellValue("C".$i, $fila->apellido_paterno);

		$objPHPExcel->getActiveSheet()->setCellValue("D".$i, $fila->apellido_materno);
		$objPHPExcel->getActiveSheet()->setCellValue("E".$i, $fila->nombre);
		$objPHPExcel->getActiveSheet()->setCellValue("F".$i, $fila->fecha_nacimiento);

		$objPHPExcel->getActiveSheet()->setCellValue("G".$i, $fila->lugar_nacimiento);
		$objPHPExcel->getActiveSheet()->setCellValue("H".$i, $fila->sexo);
		$objPHPExcel->getActiveSheet()->setCellValue("I".$i, $fila->edad);
		$objPHPExcel->getActiveSheet()->setCellValue("J".$i, $fila->escuela);
		$objPHPExcel->getActiveSheet()->setCellValue("K".$i, $fila->otra_escuela);

		$objPHPExcel->getActiveSheet()->setCellValue("L".$i, $fila->nivelc);
		$objPHPExcel->getActiveSheet()->setCellValue("M".$i, $fila->grado);
		$objPHPExcel->getActiveSheet()->setCellValue("N".$i, $fila->promedio);
		$objPHPExcel->getActiveSheet()->setCellValue("O".$i, $fila->calle);
		$objPHPExcel->getActiveSheet()->setCellValue("P".$i, $fila->no_ext);
		$objPHPExcel->getActiveSheet()->setCellValue("Q".$i, $fila->no_int);
		

		$objPHPExcel->getActiveSheet()->setCellValue("R".$i, $fila->colonia);
		$objPHPExcel->getActiveSheet()->setCellValue("S".$i, $fila->localidad);
		$objPHPExcel->getActiveSheet()->setCellValue("T".$i, $fila->ciudad);
		$objPHPExcel->getActiveSheet()->setCellValue("U".$i, $fila->municipio);
		$objPHPExcel->getActiveSheet()->setCellValue("V".$i, $fila->estado);
		$objPHPExcel->getActiveSheet()->setCellValue("W".$i, $fila->email);

	TUTOR	IFE	ARCHIVO IFE	DOM TUTOR	STATUS


		$objPHPExcel->getActiveSheet()->setCellValue("X".$i, $fila->celular);
		$objPHPExcel->getActiveSheet()->setCellValue("Y".$i, $fila->telefono);
		$objPHPExcel->getActiveSheet()->setCellValue("Z".$i, $fila->formato1);
		$objPHPExcel->getActiveSheet()->setCellValue("AA".$i, $fila->no_apoyo);
		$objPHPExcel->getActiveSheet()->setCellValue("AB".$i, $fila->tipo_asesoria);
		$objPHPExcel->getActiveSheet()->setCellValue("AC".$i, $fila->beneficiario);

		$objPHPExcel->getActiveSheet()->setCellValue("AD".$i, $fila->beneficio);
		$objPHPExcel->getActiveSheet()->setCellValue("AE".$i, $fila->tipo_asesoria2);
		$objPHPExcel->getActiveSheet()->setCellValue("AF".$i, $fila->beneficiario2);
		$objPHPExcel->getActiveSheet()->setCellValue("AG".$i, $fila->beneficio2);
		$objPHPExcel->getActiveSheet()->setCellValue("AH".$i, $fila->tipo_asesoria2);
		$objPHPExcel->getActiveSheet()->setCellValue("AI".$i, $fila->beneficiario3);

		$objPHPExcel->getActiveSheet()->setCellValue("AJ".$i, $fila->beneficio3);
		$objPHPExcel->getActiveSheet()->setCellValue("AK".$i, $fila->compromiso1);
		$objPHPExcel->getActiveSheet()->setCellValue("AL".$i, $fila->compromiso2);
		$objPHPExcel->getActiveSheet()->setCellValue("AM".$i, $fila->compromiso3);
		$objPHPExcel->getActiveSheet()->setCellValue("AN".$i, $fila->areayuda);
		$objPHPExcel->getActiveSheet()->setCellValue("AO".$i, $fila->areayuda2);
		$objPHPExcel->getActiveSheet()->setCellValue("AP".$i, $fila->areayuda3);

		$objPHPExcel->getActiveSheet()->setCellValue("AQ".$i, $fila->tutor);
		$objPHPExcel->getActiveSheet()->setCellValue("AR".$i, $fila->ife);
		$objPHPExcel->getActiveSheet()->setCellValue("AS".$i, $fila->ife_file);
		$objPHPExcel->getActiveSheet()->setCellValue("AT".$i, $fila->dom_tutor);
		$objPHPExcel->getActiveSheet()->setCellValue("AU".$i, $fila->status);
		$objPHPExcel->getActiveSheet()->setCellValue("AV".$i, $fila->is_beca);


		++$i;++$k;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($i, 1);
		//$title=$iddep==0?"CATALOGO DE SERVICIOS POR DEPENDENCIA ":"CATALOGO DE SERVICIOS DE '".utf8_decode($fila->grupo)."'";
	}
	$j = $i+3;
	//$objPHPExcel->getActiveSheet()->setCellValue("C3", $title);
	$objPHPExcel->getActiveSheet()->setCellValue("B5", $F->getWith3LetterMonthH(date('Y-m-d H:i:s')));
	$objPHPExcel->getActiveSheet()->setCellValue("B".$j, $k);

            
	//$objWriter->save($fileout);//guardamos el archivo excel  
	$objWriter->save($fileout);//guardamos el archivo excel  



?>

</body>
</html>


