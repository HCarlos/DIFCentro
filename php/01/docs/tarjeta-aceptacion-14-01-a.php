<?php


$curp = $_POST['data'];
if (!isset($curp)){
	header('Location: http://apoyos2014.tabascoweb.com/');
}

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require('../diag/sector.php');
require_once('../vo/voConn.php');

$Conn = voConn::getInstance();
$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
mysql_select_db($Conn->db);
mysql_query("SET NAMES UTF8");

$query = "Select * from usuarios where curp = '$curp' ";

$result = mysql_query($query);
$res = mysql_fetch_object($result);

class PDF_Diag extends PDF_Sector {
    var $cand;
    var $folio;
}

$pdf = new PDF_Diag('P','mm',array(204,122));
$pdf->SetAutoPageBreak(false, 1.00);
$pdf->cand = $res->apellido_paterno.' '.$res->apellido_materno.' '.$res->nombre;

/*
$pdf->fecha = "26 DE OCTUIBRE DE 2014.";// strtoupper(utf8_decode($F->getBFecha(date("Y-m-d"),"00:00:00",7)));
$pdf->folio = str_pad($res->idusuario, 6,"0",STR_PAD_LEFT);//."-".substr(date('Y'),2,2);
$pdf->flag = 0;
$pdf->curp = $res->curp;
*/
$pdf->AddPage();
$pdf->SetFont('Arial','',10);


$nFont = 6;
$valX=20;
// /* *********************************************************
// ** CUERPO DEL TEXTO
// ** ********************************************************* */

//$pdf->SetFillColor(250);

$pdf->SetFillColor(64,64,64);
$pdf->Image('../../../img/apoyoso-2014.gif',55,5,90);
$pdf->Image('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.urlencode("http://apoyos2014.tabascoweb.com/fri/".$res->curp).'&choe=UTF-8',173,5,22,22,'PNG');

$pdf->Ln(17);

$pdf->SetTextColor(64,64,64);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(183, 10, utf8_decode("COMPROBANTE DE ACEPTACIÓN"), '',1, 'C',false);

$pdf->Ln(5);
 		
// LINEA 1
$pdf->setX(10);
$pdf->setY(45);
$pdf->RoundedRect(30, 45, 163, 9, 2, '1234', '');

$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(64,64,64);
$pdf->Cell(20, 10, "Nombre: ", '',0, '0',false);
$pdf->SetFont('Arial','',12);
$pdf->Cell(163, 10, "  ".utf8_decode($pdf->cand), '',1, 'L',false);

// LINEA 2
$pdf->setX(10);
$pdf->setY(65);



if (intval($res->idusuario)<=5019){
	// $pdf->idreg = "F-".str_pad($res->idusuario, 4, "0", STR_PAD_LEFT)."-14";
	$pdf->folio = "F-".str_pad($res->idusuario, 4, "0", STR_PAD_LEFT)."-14";
}else{
	$pdf->folio = "F-0000-14";
}

$pdf->RoundedRect(40, 65, 73, 9, 2, '1234', '');
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(64,64,64);
$pdf->Cell(30, 10, "No. de Folio: ", '',0, 'L',false);
$pdf->SetFont('Arial','',12);
$pdf->Cell(73, 10, "  ".$pdf->folio, '',0, '',false);
$pdf->RoundedRect(153, 65, 40, 9, 2, '1234', '');
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(64,64,64);
$pdf->Cell(40, 10, "Fecha: ", '',0, 'R',false);
$pdf->SetFont('Arial','',12);
$pdf->Cell(40, 10, "  ".date("d-m-Y",strtotime($res->creado_el)), '',1, '',false);


// LINEA 2
$pdf->setX(10);
$pdf->setY(85);
$pdf->SetFont('Arial','B',12);
$pdf->RoundedRect(10, 85, 183, 12, 2, '1234', '');
$pdf->Cell(183, 6, utf8_decode("Acudir a la Dirección de Finanzas del Ayuntamiento de Centro, a recibir tu Tarjeta"), '',1, 'C',false);
$pdf->Cell(183, 6, "Bancaria y firmar tu Recibo. Presenta este comprobante y tu Credencial de Elector.", '',1, 'C',false);
$pdf->Ln(8);
$pdf->SetFont('Arial','',10); 
$intva = intval($res->idusuario); 
if ($intva >= 1 && $intva <= 1000) {
	$vF = "24 de OCTUBRE de 2014";
} else if ($intva >= 1001 && $intva <= 2000) {	
	$vF = "27 de OCTUBRE de 2014";
} else if ($intva >= 2001 && $intva <= 3000) {	
	$vF = "28 de OCTUBRE de 2014";
} else if ($intva >= 3001 && $intva <= 4000) {	
	$vF = "29 de OCTUBRE de 2014";
} else {
	$vF = "30 de OCTUBRE de 2014";
}

$pdf->Cell(183, 4, utf8_decode("Fecha para recoger tu Tarjeta: ".$vF), '',1, 'C',false);
$pdf->Cell(183, 4, utf8_decode("Cito en: Paseo Tabasco 1401, Tabasco 2000"), '',1, 'C',false);
$pdf->Cell(183, 4, "C.P. 86035, Villahermosa, Tabasco.", '',1, 'C',false);
		
$pdf->Output();

?>
