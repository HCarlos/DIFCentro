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

require_once('../oCentura.php');
$f = oCentura::getInstance();

require('../diag/sector.php');

class PDF_Diag extends PDF_Sector {
	var $nFont;
    var $profesor;
    var $materia;
    var $grupo;
    var $eval;
    var $fecha;
    var $phead;
    var $lastupdate;
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

		$this->Ln(2);
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 205, 6, 2, '12', 'FD');

		$this->SetFont('Arial','B',8);
		$this->Cell(10,$this->nFont,"#",'',0,'C');
		$this->Cell(87,$this->nFont,"NOBRE DEL ALUMNO",'L',0,'L');
		$this->Cell(18,$this->nFont,"1RA",'L',0,'C');
		$this->Cell(18,$this->nFont,"2DA",'L',0,'C');
		$this->Cell(18,$this->nFont,"3RA",'L',0,'C');
		$this->Cell(18,$this->nFont,"PARCIAL",'L',0,'C');
		$this->Cell(18,$this->nFont,"EX. ORD.",'L',0,'C');
		$this->Cell(18,$this->nFont,"FINAL",'L',1,'C');

    }
	
	function Footer(){
		
	    $this->SetY(-15);
	    $this->SetFont('Arial','I',7);
	    // $this->Cell(0,10,utf8_decode('PlatSource © '.date('Y')),0,0,'L');
		$this->Cell(0,10,utf8_decode('Última Actualización:').$this->lastupdate.'      '.utf8_decode('PlatSource © '.date('Y')),0,0,'L');
	    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
		
	}

	function ImprimeFooterTemp($val=0){
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 205, 6, 2, '34', 'FD');

	}


	function FormatCal($cal=0,$con=0,$ina=0,$pivot=0){
		$calx = "";
		$conx = "";
		$inax = "";
		if ($cal>0){
			//$calx = $cal;
			$calx = round(floatval($cal),0);
		}
		switch (intval($con)) {
			case 10:
				$conx = "E";
				break;
			case 9:
				$conx = "MB";
				break;
			case 8:
				$conx = "B";
				break;
			case 7:
				$conx = "R";
				break;
			case 6:
				$conx = "D";
				break;
			case 5:
				$conx = "NA";
				break;
			default:
				$conx = "";
				break;

		}
		if ($ina>0){
			$inax = intval($ina);
		}

		// return $calx.' '.$conx.' '.$inax;
		switch($pivot){
			case 3:
				return str_pad($calx, 3, " ", STR_PAD_LEFT);	
				break;
			case 2:
				return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).'   ';	
				break;
			default:
				return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).' '.str_pad($inax, 2, " ", STR_PAD_LEFT);
				break;
		}
		
	}

    
}
$arrAlu = explode(",",$strgrualu);

$prom = $f->getQuerys(41,"idgrualu=".$arrAlu[0],0,0,0,array(),'',1);

$result = $f->getQuerys(59,"idgrupo=".$idgrupo."&idgrumat=".$idgrumat,0,0,0,array()," order by num_lista asc ",1);


if ( count($result)>0  ){

	$pdf = new PDF_Diag('P','mm','Letter');
	//$pdf->AddFont('helvetica','','helvetica.php');
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(TRUE, 0.1);
	$pdf->SetLeftMargin(5);

	$pdf->alumno = $result[0]->alumno; //.' '.$arrAlu[$i];
	$pdf->num_lista = $result[0]->num_lista;
	$pdf->grupo = $result[0]->grupo;
	$pdf->ciclo = $result[0]->ciclo;

	$pdf->nFont = 6;
	$pdf->SetFillColor(192,192,192);
	$pdf->materia = $result[0]->materia;
	$pdf->profesor = $result[0]->profesor;
	$pdf->lastupdate = $prom[0]->modi_el;

	$pdf->logoEmp = $logoEmp;
	$pdf->lastWidht=0;
	$pdf->promcal = 0;
	$pdf->promcon = 0;
	$pdf->sumina = 0;
	$pdf->numregs = 0;


	$pdf->AddPage();



	$pdf->SetFillColor(222);

	foreach ($result as $j => $value) {

		$pdf->setX(5);
		$pdf->SetFont('Arial','',7);
		if ( floatval($result[$j]->promcal) >= 90) {
				$pdf->SetFillColor(172);
				$vBool = true;
		} else if ( floatval($result[$j]->promcal) < 60) {
					$pdf->SetFillColor(232);
					$vBool = true;
				}else{
					$vBool = false;
		}	
		$pdf->Cell(10,$pdf->nFont,$result[$j]->num_lista,'L',0,'C',$vBool);
		$pdf->Cell(87,$pdf->nFont,utf8_decode($result[$j]->alumno),'L',0,'L',$vBool);
		$pdf->Cell(18,$pdf->nFont,$pdf->FormatCal(floatval($result[$j]->cal0),floatval($result[$j]->con0),floatval($result[$j]->ina0)),'L',0,'C',$vBool);
		$pdf->Cell(18,$pdf->nFont,$pdf->FormatCal(floatval($result[$j]->cal1),floatval($result[$j]->con1),floatval($result[$j]->ina1)),'L',0,'C',$vBool);
		$pdf->Cell(18,$pdf->nFont,$pdf->FormatCal(floatval($result[$j]->cal2),floatval($result[$j]->con2),floatval($result[$j]->ina2)),'L',0,'C',$vBool);
		$pdf->Cell(18,$pdf->nFont,$pdf->FormatCal(floatval($result[$j]->cal6),floatval($result[$j]->con6),floatval($result[$j]->ina6)),'L',0,'C',$vBool);
//		$pdf->Cell(18,$pdf->nFont,$pdf->FormatCal(floatval($result[$j]->promcal),floatval($result[$j]->promcon),floatval($result[$j]->sumina)),'L',0,'C',$vBool);
		$pdf->Cell(18,$pdf->nFont,$pdf->FormatCal(floatval($result[$j]->cal3),floatval($result[$j]->con3),floatval($result[$j]->ina3)),'L',0,'C',$vBool);
		$pdf->Cell(18,$pdf->nFont,$pdf->FormatCal(floatval($result[$j]->cal7),floatval($result[$j]->con7),floatval($result[$j]->ina7)),'LR',1,'C',$vBool);

	}


} // Fin de Enf IF

$pdf->ImprimeFooterTemp(0);

$pdf->Output();

?>