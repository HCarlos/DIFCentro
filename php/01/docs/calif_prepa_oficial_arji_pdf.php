<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('default_socket_timeout', 6000);

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

define('FPDF_FONTPATH','font/');
require('../diag/sector.php');
require('../oCentura.php');
$f = oCentura::getInstance();
require('../oMetodos.php');
$M = oMetodos::getInstance();
require('../oFunctions.php');
$F = oFunctions::getInstance();

class PDF_Diag extends PDF_Sector {
	var $nFont;
	var $logoEmp;
	var $logoIBO;
    var $alumno;
    var $idgualu;
    var $matricula_oficial;
    var $materia;
    var $grupo;
    var $periodo;
    var $semestre;
    var $ciclo;
    var $num_lista;
    var $lastupdate;
    var $obsEsp;
    var $obsIng;

    function Header(){   

		// /* *********************************************************
		// ** ENCABEZADO
		// ** ********************************************************* */

    	// FOLIO
		$this->setY(0);
		// $this->Ln(0);
		$this->setX(18);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','',8);
		$this->Cell(160,4," ",'',0,'L');
		$this->Cell(10,4,$this->num_lista,'',1,'L');

    	// FECHA
		$this->setY(0);
		$this->Ln(7.5);
		$this->setX(18);
		$this->Cell(169,4," ",'',0,'L');
		$this->Cell(10,4,"16-01-2015",'',1,'R');
		$this->setX(0);

		$this->Ln(1);

    	// PLANTEL - TURNO - PERIODO - SEMESTRE - GRUPO
		$this->setY(0);
		$this->Ln(16.5);
		$this->setX(28);
		$this->Cell(40,4,"COLEGIO ARJI",'',0,'L');
		$this->Cell(43,4,"MATUTINO",'',0,'L');
		$this->Cell(43,4,$this->periodo,'',0,'L');
		$this->Cell(36,4,$this->semestre,'',0,'L');
		$this->Cell(10,4,utf8_decode($this->grupo),'',1,'L');
		$this->setX(0);

    	// CLAVE - UBICACION  
		$this->setY(0);
		$this->Ln(21);
		$this->setX(28);
		$this->Cell(78,4,"27PCB0023O",'',0,'L');
		$this->Cell(80,4,"VILLAHERMOSA, TABASCO",'',1,'L');
		$this->setX(0);

    	// ALUMNO - MATRICULA  
		$this->setY(0);
		$this->Ln(25.5);
		$this->setX(28);
		$this->Cell(140,4,utf8_decode($this->alumno),'',0,'L');
		$this->Cell(10,4,$this->matricula_oficial,'',1,'L');
		$this->setX(0);
		$this->Ln(14);

    }

	function Footer(){

		$this->setX(0);
		$this->SetY(-20);
		$this->setX(18);
		$this->SetFont('Arial','',8);
		$this->Cell(95,10,'AURA GRACIELA MERINO CASTELLANOS',0,0,'L');
		$this->Cell(10,10,'',0,0,'L');
		$this->Cell(96,10,'SILVIA DEL CARMEN DE LA ROSA ROSALES',0,1,'L');

	}



}

$pdf = new PDF_Diag('P','mm',array(196,140));
//$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(TRUE, 0.1);
$pdf->SetTopMargin(0);
$pdf->SetLeftMargin(0);
$pdf->nFont = 6;
$pdf->SetFillColor(192,192,192);


$arrAlu = explode(",",$strgrualu);

foreach ($arrAlu as $i => $value) {
	$result = $f->getQuerys(70,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);
	
	if ( count($result)>0  ){

		$pdf->alumno = $result[0]->alumno; //.' '.$arrAlu[$i];
		$pdf->matricula_oficial = $result[0]->matricula_oficial;
		$pdf->num_lista = $result[0]->num_lista;
		$pdf->grupo = $result[0]->grupo_oficial;
		$pdf->periodo = $result[0]->grupo_periodo;
		$pdf->semestre = $result[0]->grupo_periodo_ciclo;
		$pdf->ciclo = $result[0]->ciclo;

		$pdf->AddPage();

		$pdf->SetFont('Arial','',7);

		$y = $pdf->GetY();

		foreach ($result as $j => $value) {
			
			$pdf->setX(16);
			$pdf->Cell(11,4,$result[$j]->abreviatura_oficial,'',0,'L');
			$pdf->Cell(115,4,utf8_decode($result[$j]->materia_oficial),'',0,'L'); //." ".$result[$j]->idgrumat
			$pdf->Cell(18,4,str_pad($result[$j]->clave, 2, "0", STR_PAD_LEFT),'',0,'L'); //." ".$result[$j]->idgrumat
			$pdf->Cell(30,4,$F->FormatCal( floatval($result[$j]->promcalof),0,0,0, $result[$j]->idmatclas ),'',0,'L');
			$pdf->Cell(4,4,$result[$j]->creditos,'',1,'R'); //." ".$result[$j]->idgrumat

		}

		$prom = $f->getQuerys(71,"idgrualu=".$result[$j]->idgrualu,0,0,0,array(),'',1);

		$pdf->setX(0);
		$pdf->setY(107.2);
		$pdf->setX(28);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(22,4,$F->FormatCal( $prom[0]->promcalof,0,0,1,1 ),'',1,'R');

		$pdf->SetFillColor(222);

	} // Fin de Enf IF

}

$pdf->Output();

?>