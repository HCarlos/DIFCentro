<?php

$o = $_POST['o'];
$c = $_POST['c'];
$t = $_POST['t'];
$from = $_POST['from'];
$cantidad = $_POST['cantidad'];
$s = $_POST['s'];

if (!isset($c)){
	header('Location: http://platsource.mx/');
}

parse_str($c);

require('../diag/sector.php');

class PDF_Diag extends PDF_Sector {
	var $nFont;
    var $profesor;
    var $materia;
    var $grupo;
    var $eval;
    var $fecha;
    var $phead;
    var $lastWidht;
    var $prom;
    var $promcal;
    var $promcon;
    var $sumina;
    var $numregs;
    var $logoEmp;

    function Header(){   
		$this->Image('../../../images/web/'.$this->logoEmp,5,5,25,25);
		$this->Ln(5);

		$this->setX(30);

		// /* *********************************************************
		// ** ENCABEZADO
		// ** ********************************************************* */

		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','B',12);
		$this->Cell(110,$this->nFont,utf8_decode("COLEGIO ARJÍ A.C."),'',0,'L');
		$this->SetFont('Arial','',10);
		$this->Cell(70,$this->nFont,utf8_decode("LISTA DE CALIFICACIONES"),'LTRB',1,'C',true);

		$this->Ln(3);
		$this->setX(30);
		$this->SetFont('Arial','B',8);
		$this->Cell(22,$this->nFont,'PROFESOR:','',0,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(113,$this->nFont,$this->profesor,'',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(15,$this->nFont,utf8_decode("GRUPO:"),'',0,'');
		$this->SetFont('Arial','',8);
		$this->Cell(30,$this->nFont,$this->grupo,'',1,'L');

		$this->Ln(0);
		$this->setX(30);
		$this->SetFont('Arial','B',8);
		$this->Cell(22,$this->nFont,'MATERIA:','',0,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(113,$this->nFont,$this->materia,'',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(15,$this->nFont,utf8_decode("FECHA:"),'',0,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(30,$this->nFont,date('d-M-Y'),'',1,'L');

		$this->Ln(0);
		$this->setX(30);
		$this->SetFont('Arial','B',8);
		$this->Cell(22,$this->nFont,'','',0,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(113,$this->nFont,'','',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(15,$this->nFont,utf8_decode("EVAL:"),'',0,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(30,$this->nFont,$this->eval,'',1,'L');

		$this->Ln(2);
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 205, 6, 2, '12', 'FD');

		$rEncab = explode('|', $this->phead);
		$this->SetFont('Arial','B',8);
		$this->Cell(10,$this->nFont,$rEncab[0],'',0,'C');
		$this->Cell(70,$this->nFont,utf8_decode($rEncab[1]),'L',0,'L');
		$this->SetFont('Arial','',6);
		for ( $i=2; $i<(count($rEncab)-4); ++$i) {
			$this->Cell(15,$this->nFont,utf8_decode( $rEncab[$i] ),'L',0,'R');
			$this->lastWidht=$this->lastWidht+15;
		}
		$this->Cell(10,$this->nFont,$rEncab[count($rEncab)-4],'L',0,'C');
		$this->Cell(8,$this->nFont,$rEncab[count($rEncab)-3],'L',0,'C');
		$this->Cell(8,$this->nFont,$rEncab[count($rEncab)-2],'L',0,'C');
		$this->Cell(8,$this->nFont,$rEncab[count($rEncab)-1],'L',1,'C');





    }
	
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    //Arial italic 8
	    $this->SetFont('Arial','I',7);
	    //Page number
	    $this->Cell(0,10,utf8_decode('PlatSource © '.date('Y')),0,0,'L');
	    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
	}

	function ImprimeFooterTemp($val=0){
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 205, 6, 2, '34', 'FD');

		$rEncab = explode('|', $this->phead);
		//$this->numregs = count($rEncab);
		$this->SetFont('Arial','B',8);
		$this->Cell(10,$this->nFont,'','',0,'C');
		if ($val==0){
			$this->Cell(70,$this->nFont,'','L',0,'L');
		}else{
			$this->Cell(70,$this->nFont,'PROMEDIO','L',0,'L');
		}
		$this->SetFont('Arial','',6);
		for ( $i=2; $i<(count($rEncab)-4); ++$i) {
			$this->Cell(15,$this->nFont,'','L',0,'R');
			$lastWidht=$lastWidht+15;
		}
		if ($val==0){
			$this->Cell(10,$this->nFont,'','L',0,'R');
			$this->Cell(8,$this->nFont,'','L',0,'R');
			$this->Cell(8,$this->nFont,'','L',0,'R');
			$this->Cell(8,$this->nFont,'','L',1,'R');
		}else{
			$redondo = round(floatval($this->promcal/$this->numregs),0);
			$this->Cell(10,$this->nFont,$redondo,'L',0,'R');
			$redondo = round(floatval($this->promcon/$this->numregs),0);
			$this->Cell(8,$this->nFont,$redondo,'L',0,'R');
			$this->Cell(8,$this->nFont,$this->sumina,'L',0,'R');
			$this->Cell(8,$this->nFont,'','L',1,'R');
		}

		if ($val==1){
			$this->Ln(5);
			$this->setX(5);
			$this->SetFont('Arial','',8);
			$this->Cell(68.333333333,$this->nFont,utf8_decode('FIRMA DEL LÍDER DE ACADEMIA:'),'',0,'C');
			$this->Cell(68.333333333,$this->nFont,'FIRMA DEL PROFESOR:','',0,'C');
			$this->Cell(68.333333333,$this->nFont,'FIRMA DEL JEFE DE GRUPO:','',1,'C');

			$this->Ln(10);
			$this->setX(5);
			$this->Cell(68.333333333,$this->nFont,'_________________________________','',0,'C');
			$this->Cell(68.333333333,$this->nFont,'_________________________________','',0,'C');
			$this->Cell(68.333333333,$this->nFont,'_________________________________','',1,'C');

		}

	}
    
}

// require('../oFunctions.php');
// $F = oFunctions::getInstance();


$pdf = new PDF_Diag('P','mm','Letter');
$pdf->AliasNbPages();
//$pdf->municipio = $rs->municipio;// strtoupper(utf8_decode($F->getBFecha(date("Y-m-d"),"00:00:00",7)));
$pdf->nFont = 6;
$pdf->SetFillColor(192,192,192);
$pdf->materia = $materia;
$pdf->grupo = $grupo;
$pdf->profesor = $profesor;
$pdf->eval = $eval;
$pdf->phead = $head;
$pdf->logoEmp = $logoEmp;
$pdf->lastWidht=0;
$pdf->promcal = 0;
$pdf->promcon = 0;
$pdf->sumina = 0;
$pdf->numregs = 0;


$pdf->AddPage();



$valLast = 205-(114+$pdf->lastWidht);
$rbody = explode('°', $body);
$k = 0;
$prom = 0;
for ( $i=0; $i<count($rbody); ++$i) {
	$rs = explode('|', $rbody[$i]);
	if ($rs[1]!=''){
	//$pdf->Ln(5);
		$pdf->setX(5);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10,$pdf->nFont,$rs[0],'LB',0,'C');
		$pdf->Cell(70,$pdf->nFont,utf8_decode($rs[1]),'LB',0,'L');
		$pdf->SetFont('Arial','',8);
		for ( $j=2; $j<(count($rs)-4); ++$j) {
			$pdf->Cell(15,$pdf->nFont,$rs[$j]!=0?$rs[$j]:'','LB',0,'R');
		}
		$redondo = round(floatval($rs[count($rs)-4]),0);
//		$pdf->promcal = $pdf->promcal + intval($rs[count($rs)-4]);
//		$pdf->Cell(10,$pdf->nFont,$rs[count($rs)-4]!=0?$rs[count($rs)-4]:'','LB',0,'R');
		$pdf->promcal = $pdf->promcal + $redondo;
		$pdf->Cell(10,$pdf->nFont,$rs[count($rs)-4]!=0?$redondo:'','LB',0,'R');


		$pdf->promcon = $pdf->promcon + intval($rs[count($rs)-3]);
		$pdf->Cell(8,$pdf->nFont,$rs[count($rs)-3]!=0?$rs[count($rs)-3]:'','LB',0,'R');
		
		$pdf->sumina = $pdf->sumina + intval($rs[count($rs)-2]);
		$pdf->Cell(8,$pdf->nFont,$rs[count($rs)-2]!=0?$rs[count($rs)-2]:'','LB',0,'R');
		
		$pdf->Cell($valLast+8,$pdf->nFont,$rs[count($rs)-1]!=0?$rs[count($rs)-1]:'','LBR',1,'L');
		$pdf->numregs = $pdf->numregs + 1;
		++$k;
	}
	if ($k > 30){
		$k=0;
		$pdf->ImprimeFooterTemp(0);
		$pdf->AddPage();
	}
}

$pdf->ImprimeFooterTemp(1);




//mysql_free_result($rst);
$pdf->Output();

?>