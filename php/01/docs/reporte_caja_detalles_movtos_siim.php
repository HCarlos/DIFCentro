<?php

$data = $_POST['data'];

if (!isset($data)){
	header('Location: http://siim.tecnointel.mx/');
}

require('../diag/sector.php');
require_once('../oFunctions.php');
$Q = oFunctions::getInstance();
require_once('../oCentura.php');
$f = oCentura::getInstance();

parse_str($data);

class PDF_Diag extends PDF_Sector {
	var $nFont;
    var $grupo;
    var $fi;
    var $ff;
    var $user;

    function Header(){ 

		$this->Image('../../../images/web/logo-siim.gif',5,5,25,25);
		$this->Ln(1);
	    $this->setY(7);
	    // Line 1
		$this->setX(40);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',10);
		$this->Cell(70,6,utf8_decode("H. AYUNTAMIENTO CONSTITUCIONAL DE CENTRO, TABASCO."),'',1,'L');
	    $this->setX(40);
		$this->SetFont('Arial','B',12);
		$this->Cell(100,6,utf8_decode("DETALLES DE MOVIMIENTOS"),'',1,'L');

	    // Line 2
	    $this->setX(40);
		$this->SetFont('Arial','',8);
		$this->Cell(100,6,utf8_decode("DEL ").$this->fi.' AL '.$this->ff,'',0,'L');
		$this->SetFont('Arial','',6);
		$this->Cell(66,6,utf8_decode("FECHA DE IMPRESION: ").DATE('d-m-Y h:m:s'),'',1,'R');

	    // Line 3
	    $this->setX(40);
		$this->SetFont('Arial','',8);
		$this->Cell(100,6,utf8_decode("USUARIO: ").$this->user,'',0,'L');
		$this->SetFont('Arial','',6);
		$this->Cell(66,6,'','',1,'R');

		$this->Ln(7);

		$this->SetFont('Arial','B',6);
		$this->setX(5);
		$this->Cell(10,6,'#','BT',0,'R');
		$this->Cell(5,6,'','BT',0,'R');
		$this->Cell(15,6,'FECHA','BT',0,'L');
		$this->Cell(20,6,'CUENTA','BT',0,'L');
		$this->Cell(145,6,'CONCEPTO','BT',0,'L');
		$this->Cell(8,6,'IMPORTE','BT',1,'R');


    }
	
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-10);
	    //Arial italic 8
	    $this->SetFont('Arial','I',7);
	    //Page number
	    $this->Cell(100,4,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
	}
    
}

$pdf = new PDF_Diag('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetFillColor(192,192,192);

$pdf->fi = $fi;
$pdf->ff = $ff;
$pdf->user = $cusuario;

$pdf->AddPage();



// /* *********************************************************
// ** ENCABEZADO
// ** ********************************************************* */

// echo $data;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',6);

$rs = $f->getQuerys(2014,$data,0,0,0,array(),"",1);

if ( count($rs) > 0 ){
	$l=0;
	$r0=$r1=$r1=$r1=$r1=0;
	foreach ($rs as $i => $value) {
		
		$rb = $i==(count($rs)-1)?'B':'';
		/*
		$rb = $i==(count($rs)-1)?'B':'';
		$ipd = $rs[$i]->is_pagos_diversos=="1"?' - '.$rs[$i]->mes:'';

		$pdf->SetFont('Arial','',6);
		*/
	
		$pdf->setX(5);
		$pdf->Cell(10,4,$rs[$i]->idcontribucion,$rb,0,'R');
		$pdf->Cell(5,4,'',$rb,0,'R');
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(15,4,$rs[$i]->cFecha,$rb,0,'L');
		$pdf->Cell(20,4,$rs[$i]->clave,$rb,0,'L');
		$pdf->Cell(145,4,utf8_decode( substr($rs[$i]->ingreso,0,110) ).$ipd,$rb,0,'L');
		$pdf->Cell(8,4,number_format($rs[$i]->subtotal,2),$rb,1,'R');

		$r0+=$rs[$i]->subtotal;
		$l = $i;

	}


	// Pintamos los Totales
	$total = $r0+$r1+$r2+$r3+$r4;
	$pdf->setX(5);
	$pdf->Cell(10,6,'','',0,'C');
	$pdf->Cell(5,6,'','',0,'C');
	$pdf->Cell(15,6,'','',0,'L');
	$pdf->Cell(20,6,'','',0,'L');
	$pdf->Cell(145,6,'TOTAL','',0,'L');
	$pdf->Cell(8,6,$r0==0?'':number_format($r0,2) ,'',1,'R');

}else{
	$pdf->SetFont('Arial','I',10);
	$pdf->setX(5);
	$pdf->Cell(200,12,'NO SE ENCONTRARON REGISTROS','',0,'C');
}

$pdf->Output();

?>