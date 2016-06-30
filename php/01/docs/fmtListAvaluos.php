<?php

$cad = $_POST['cad'];
if (!isset($cad)){
	header('Location: http://corevat.tecnointel.mx/');
}


// $cad = "";

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require('../diag/sector.php');
require_once('../vo/voConn.php');
require_once('../numero_a_letra.php');
require_once('../NumberToLetterConverter.php');

$Conn = voConn::getInstance();
$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
mysql_select_db($Conn->db);
mysql_query("SET NAMES UTF8");

if ( $cad == 0){
	$query = "Select * from _viAvaluos Order By idavaluo desc limit 0,20  ";
}else{
	$query = "Select * from _viAvaluos Where iduser = $cad Order By idavaluo desc limit 0,20  ";
}

$result = mysql_query($query);
$rs = mysql_fetch_object($result);

class PDF_Diag extends PDF_Sector {
	var $nFont;
    var $municipio;

    function Header(){   }
	
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    //Arial italic 8
	    $this->SetFont('Arial','I',7);
	    //Page number
	    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
	}
    
}

// require('../oFunctions.php');
// $F = oFunctions::getInstance();


$pdf = new PDF_Diag('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->municipio = $rs->municipio;// strtoupper(utf8_decode($F->getBFecha(date("Y-m-d"),"00:00:00",7)));
$pdf->nFont = 6;
$pdf->SetFillColor(64,64,64);

$pdf->AddPage();

$pdf->Image('../../../images/web/crv-01.jpg',5,5,139.57,26.20);
$pdf->Ln(25);

$pdf->setX(5);

// /* *********************************************************
// ** ENCABEZADO
// ** ********************************************************* */

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',14);
$pdf->Cell(57,$pdf->nFont,utf8_decode("H. AYUNTAMIENTO DE: "),'',0,'L');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(70,$pdf->nFont, utf8_decode(strtoupper($pdf->municipio)),'',1,'L');
$pdf->setX(5);
$pdf->SetFont('Arial','',14);
$pdf->Cell(57,$pdf->nFont,utf8_decode("DIRECCION DE FINANZAS"),'',1,'L');
$pdf->SetFont('Arial','B',14);
$pdf->Ln(2.5);
$pdf->setX(5);
$pdf->SetFillColor(164,164,164);
if ( $cad == 0){
	$pdf->Cell(269,8, utf8_decode('Listado General de Avaluos'),'TLBR',1,'C',1);
}else{
	$pdf->Cell(269,8, utf8_decode('Listado de los últimos avaluos de '.$rs->apellidos.' '.$rs->nombres),'TLBR',1,'C',1);
}
// /* *********************************************************
// /* *********************************************************

// ** IMPRIME LISTADO
// ** ********************************************************* */
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(208,208,208);
$pdf->setX(5);

// Línea 1
$pdf->setX(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$pdf->nFont,utf8_decode("FOLIO"),'LB',0,'L',1);
$pdf->Cell(20,$pdf->nFont,utf8_decode("FECHA"),'LB',0,'C',1);
$pdf->Cell(46,$pdf->nFont,utf8_decode("SOLICITANTE"),'LB',0,'L',1);
$pdf->Cell(66,$pdf->nFont,utf8_decode("UBICACIÓN"),'LB',0,'L',1);
$pdf->Cell(15,$pdf->nFont,utf8_decode("PREDIAL"),'LB',0,'L',1);
$pdf->Cell(20,$pdf->nFont,utf8_decode("TERRENO"),'LB',0,'R',1);
$pdf->Cell(20,$pdf->nFont,utf8_decode("CONSTRUC."),'LB',0,'R',1);
$pdf->Cell(22,$pdf->nFont,utf8_decode("CATASTRAL"),'LB',0,'L',1);
$pdf->Cell(25,$pdf->nFont,utf8_decode("V. CONCLUIDO"),'LBR',1,'L',1);

$rst = mysql_query($query);
$lID = 0;
$pdf->SetFont('Arial','',7);
while ($fila = mysql_fetch_object($rst)) {
	$pdf->setX(5);
	$pdf->Cell(35,$pdf->nFont,utf8_decode($fila->folio_format),'LB',0,'L',0);
	$pdf->Cell(20,$pdf->nFont,utf8_decode($fila->cfecha_avaluo),'LB',0,'C',0);
	$pdf->Cell(46,$pdf->nFont,utf8_decode($fila->nombre_solicitante),'LB',0,'L',0);
	$pdf->Cell(66,$pdf->nFont,utf8_decode(substr(trim($fila->ubicacion),0,50)),'LB',0,'L',0);
	$pdf->Cell(15,$pdf->nFont,utf8_decode($fila->cuenta_predial),'LB',0,'L',0);
	$pdf->Cell(20,$pdf->nFont,number_format($fila->superficie_terreno, 2, '.', ','),'LB',0,'R',0);
	$pdf->Cell(20,$pdf->nFont,number_format($fila->superficie_construccion, 2, '.', ','),'LB',0,'R',0);
	$pdf->Cell(22,$pdf->nFont,utf8_decode($fila->cuenta_catastral),'LB',0,'L',0);
	$pdf->Cell(25,$pdf->nFont,number_format($fila->valor_concluido, 2, '.', ','),'LBR',1,'R',0);
	++$lID;
}

$pdf->setX(5);
$pdf->Cell(244,$pdf->nFont,utf8_decode("TOTAL DE REGISTROS: "),'LB',0,'R',0);
$pdf->SetFont('Arial','B',7);
$pdf->Cell(25,$pdf->nFont,$lID,'TLBR',1,'R',1);


// /* *********************************************************
// ** CLOSE REPORT
// ** ********************************************************* */

mysql_free_result($rst);
$pdf->Output();

?>