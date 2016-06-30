<?php

$data = $_POST['data'];

if (!isset($data)){
	header('Location: http://platsource.mx/');
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

    function Header(){ 

		$this->Image('../../../images/web/logo-arji.gif',5,5,25,25);
		$this->Ln(1);
	    $this->setY(7);
		$this->setX(40);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',10);
		$this->Cell(70,6,utf8_decode("COLEGIO ARJÍ A.C."),'',1,'L');
	    $this->setX(40);
		$this->SetFont('Arial','',12);
		$this->Cell(100,6,utf8_decode("RESUMEN DE MOVIMIENTOS POR NIVELES Y CONCEPTOS"),'',1,'L');
	    $this->setX(40);
		$this->SetFont('Arial','',8);
		$this->Cell(100,6,utf8_decode("DEL ").$this->fi.' AL '.$this->ff,'',0,'L');
		$this->SetFont('Arial','',6);
		$this->Cell(66,6,utf8_decode("FECHA DE IMPRESION: ").DATE('d-m-Y h:m:s'),'',1,'R');
		$this->Ln(10);

		$this->SetFont('Arial','B',8);
		$this->setX(5);
		$this->Cell(5,6,'#','BT',0,'C');
		$this->Cell(45,6,'CONCEPTO','BT',0,'L');
		$this->Cell(25,6,'TOTAL','BT',0,'R');
		$this->Cell(25,6,'PREESCOLAR','BT',0,'R');
		$this->Cell(25,6,'1RO INGLES' ,'BT',0,'R');
		$this->Cell(25,6,'PRIMARIA','BT',0,'R');
		$this->Cell(25,6,'SECUNDARIA' ,'BT',0,'R');
		$this->Cell(25,6,'PREPARATORIA' ,'BT',1,'R');


    }
	
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-10);
	    //Arial italic 8
	    $this->SetFont('Arial','I',7);
	    //Page number
	    $this->Cell(100,4,utf8_decode('platsource.mx ©'),0,0,'L');
	    $this->Cell(100,4,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
	}
    
}

$pdf = new PDF_Diag('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetFillColor(192,192,192);

$pdf->fi = $fi;
$pdf->ff = $ff;

$pdf->AddPage();



// /* *********************************************************
// ** ENCABEZADO
// ** ********************************************************* */

// echo $data;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',8);

$rs = $f->getQuerys(10015,$data,0,0,0,array(),"",1);

if ( count($rs) > 0 ){
	$l=0;
	$r0=$r1=$r1=$r1=$r1=0;
	foreach ($rs as $i => $value) {
		
		$r0+=$rs[$i]->cero;
		$r1+=$rs[$i]->uno;
		$r2+=$rs[$i]->dos;
		$r3+=$rs[$i]->tres;
		$r4+=$rs[$i]->cuatro;
		$total = $rs[$i]->cero+$rs[$i]->uno+$rs[$i]->dos+$rs[$i]->tres+$rs[$i]->cuatro;
		$total = $total==0?'':number_format($total,2);

		$rb = $i==(count($rs)-1)?'B':'';
		$pdf->setX(5);
		$pdf->Cell(5,6,$rs[$i]->idconcepto,$rb,0,'C');
		$pdf->Cell(45,6,utf8_decode( $rs[$i]->concepto ),$rb,0,'L');
		$pdf->Cell(25,6,$total,'',0,'R');
		$pdf->Cell(25,6,$rs[$i]->cero==0?'':number_format($rs[$i]->cero,2) ,$rb,0,'R');
		$pdf->Cell(25,6,$rs[$i]->uno==0?'':number_format($rs[$i]->uno,2) ,$rb,0,'R');
		$pdf->Cell(25,6,$rs[$i]->dos==0?'':number_format($rs[$i]->dos,2) ,$rb,0,'R');
		$pdf->Cell(25,6,$rs[$i]->tres==0?'':number_format($rs[$i]->tres,2) ,$rb,0,'R');
		$pdf->Cell(25,6,$rs[$i]->cuatro==0?'':number_format($rs[$i]->cuatro,2) ,$rb,1,'R');
		$l = $i;

	}

	// Pintamos los Totales

	$total = $r0+$r1+$r2+$r3+$r4;
	$total = $total==0?'':number_format($total,2);

	$pdf->setX(5);
	$pdf->Cell(5,6,'','',0,'C');
	$pdf->Cell(45,6,'TOTAL:','',0,'L');
	$pdf->Cell(25,6,$total,'T',0,'R');
	$pdf->Cell(25,6,$r0==0?'':number_format($r0,2) ,'',0,'R');
	$pdf->Cell(25,6,$r1==0?'':number_format($r1,2) ,'',0,'R');
	$pdf->Cell(25,6,$r2==0?'':number_format($r2,2) ,'',0,'R');
	$pdf->Cell(25,6,$r3==0?'':number_format($r3,2) ,'',0,'R');
	$pdf->Cell(25,6,$r4==0?'':number_format($r4,2) ,'',1,'R');

}else{
	$pdf->SetFont('Arial','I',10);
	$pdf->setX(5);
	$pdf->Cell(200,12,'NO SE ENCONTRARON REGISTROS','',0,'C');
}

$pdf->Output();

?>