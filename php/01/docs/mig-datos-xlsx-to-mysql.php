
	<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');
set_include_path(get_include_path() . PATH_SEPARATOR . '../PHPExcel/Classes/');
set_time_limit(600000);

require_once("../PHPExcel/Classes/PHPExcel.php");
require_once("../PHPExcel/Classes/PHPExcel/Reader/Excel2007.php");
require_once('../vo/voConn.php');
require_once("../oFunctions.php");

$F = oFunctions::getInstance();

$Conn = voConn::getInstance();
$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
mysql_select_db($Conn->db);
mysql_query("SET NAMES UTF8");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp; 
$cacheSettings = array( ' memoryCacheSize ' => '64MB'); 
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 
$objReader = PHPExcel_IOFactory::createReader('Excel2007');

$objReader->setReadDataOnly(true);

$objPHPExcel = $objReader->load("mig-0.xlsx"); //cargamos el archivo excel (extensión *.xlsx)

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); //objeto de PHPExcel, para escribir en el excel
	$objWriter->setIncludeCharts(false);
	$objWriter->setPreCalculateFormulas(false);

	$sheet = $objPHPExcel->getActiveSheet();
	
	for ($i=1;$i<2250;++$i) {
		// ->getCell('B8')->getValue();
		$id 	=  $sheet->getCell("A".$i)->getValue();// intval( $sheet->getCellByColumnAndRow(1, $i)->getValue() );
		echo $id."\n\n";
		
		// if ($id > 0){
			$matof 	= $sheet->getCell("B".$i)->getValue(); // $sheet->getCellByColumnAndRow(2, $i)->getValue(); // $sheet->getCell("B".$i);
			$fnac 	= $sheet->getCell("C".$i)->getValue();//$sheet->getCellByColumnAndRow(3, $i)->getValue();;
			mysql_query("Update cat_alumnos set  fecha_nacimiento = '".$fnac."', matricula_oficial = '".$matof."' where idalumno = ".$id);
		// }else{
		// 	exit;
		// }
		
	}
	

// mysql_free_result($result);

mysql_close($mysql);

echo "Listo => ".$i;  


?>
