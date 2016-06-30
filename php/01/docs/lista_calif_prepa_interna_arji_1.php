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
//getQuerys($tipo=0,$cad="",$type=0,$from=0,$cant=0,$ar=array(),$otros="",$withPag=1) {

class PDF_Diag extends PDF_Sector {
	var $nFont;
	var $logoEmp;
	var $logoIBO;
    var $alumno;
    var $materia;
    var $grupo;
    var $ciclo;
    var $num_lista;
    var $lastupdate;
    var $obsEsp;
    var $obsIng;

    function Header(){   

		$this->Image('../../../images/web/'.$this->logoEmp,5,5,20,20);
		$this->Image('../../../images/web/'.$this->logoIBO,196,5,15,15);

		$this->sety(5);
		$this->setX(0);

		// /* *********************************************************
		// ** ENCABEZADO
		// ** ********************************************************* */

		$this->SetTextColor(0,0,0);
		$this->SetFont('Times','B',12);
		$this->Cell(216,4,utf8_decode("COLEGIO ARJÍ A.C."),'',1,'C');
		$this->setX(0);
		$this->SetFont('Arial','',8);
		$this->Cell(216,4,utf8_decode("AV. MÉXICO NUM. 2 COL. DEL BOSQUE, VILLAHERMOSA TABASCO, TELS 351-02-60 EXT 400"),'',1,'C');
		$this->setX(0);
		$this->Cell(216,4,utf8_decode("INCORPORADO AL COBATAB CLAVE 27PCB00230"),'',1,'C');
		$this->setX(0);
		$this->SetFont('Arial','B',10);
		$this->setX(0);
		$this->Cell(216,4,utf8_decode("BOLETA DE EVALUACIÓN - BACHILLERATO ARJÍ"),'',1,'C');

		$this->Ln(6);
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 206, 4, 2, '12', 'FD');
		$this->SetFont('Arial','B',8);
		$this->Cell(90,4,utf8_decode("NOMBRE DEL ALUMNO"),'',0,'C');
		$this->Cell(30,4,utf8_decode("PERIODO"),'L',0,'C');
		//$this->Cell(20,6,utf8_decode("GRADO"),'L',0,'C');
		$this->Cell(44,4,utf8_decode("FECHA"),'L',0,'C');
		$this->Cell(26,4,utf8_decode("GRUPO"),'L',0,'C');
		$this->Cell(16,4,utf8_decode("N° LISTA"),'L',1,'C');

		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(255);
		$this->RoundedRect(5, $y, 206, 4, 2, '34', 'FD');
		$this->SetFont('courier','',8);
		$this->Cell(90,4,utf8_decode($this->alumno),'',0,'C');
		$this->Cell(30,4,utf8_decode($this->ciclo),'L',0,'C');
		//$this->Cell(20,6,utf8_decode("5"),'L',0,'C');
		$this->Cell(44,4,date('d-M-Y'),'L',0,'C');
		$this->Cell(26,4,utf8_decode($this->grupo),'L',0,'C');
		$this->Cell(16,4,utf8_decode($this->num_lista),'L',1,'C');

		$this->Ln(1);

    }

	function Footer(){

		$this->SetY(-7);
		//Arial italic 8
		$this->SetFont('Arial','I',4);
		$this->Cell(0,10,utf8_decode('Última Actualización:').$this->lastupdate,0,0,'L');

		//Page number
		//$this->Cell(0,10,utf8_decode('PlatSource © '.date('Y')),0,0,'L');
		//$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
		$this->Cell(0,10,utf8_decode('PlatSource.mx © '.date('Y')),0,0,'R');

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
				$conx = "I";
				break;
			case 5:
			default:
				$conx = "";
				break;

		}
		if ($ina>0){
			$inax = intval($ina);
		}

		// return $calx.' '.$conx.' '.$inax;
		switch($pivot){
			case 2:
				return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).'   ';	
				// return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.'  '.'   ';	
				break;
			default:
				return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).' '.str_pad($inax, 2, " ", STR_PAD_LEFT);
				//return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.'  '.' '.'  ';
				break;
		}
		


	}


}

$pdf = new PDF_Diag('P','mm',array(216,140));
//$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(TRUE, 0.1);
$pdf->SetLeftMargin(5);
//$pdf->municipio = $rs->municipio;// strtoupper(utf8_decode($F->getBFecha(date("Y-m-d"),"00:00:00",7)));
$pdf->nFont = 6;
$pdf->SetFillColor(192,192,192);
$pdf->logoEmp = $logoEmp;
$pdf->logoIBO = $logoIbo;


$arrAlu = explode(",",$strgrualu);

// $M->Llamar_Actualiza_Promedios_Boletas_por_Grupo($arrAlu[0]);
// $M->Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user,$arrAlu[0]);
// $M->Actualiza_Promedios_Grupales_por_Materia($user,$arrAlu[0]);

// $M->Actualizar_Promedios_Grupales(0,0,$user,$arrAlu[0]);

foreach ($arrAlu as $i => $value) {
	$result = $f->getQuerys($t,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);
	

	if ( count($result)>0  ){


		$pdf->alumno = $result[0]->alumno; //.' '.$arrAlu[$i];
		$pdf->num_lista = $result[0]->num_lista;
		$pdf->grupo = $result[0]->grupo;
		$pdf->ciclo = $result[0]->ciclo;

		$pdf->AddPage();

		$pdf->SetFont('Arial','B',6);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '1', 'FD');
		$pdf->Cell(64,4,'A S I G N A T U R A','',0,'C');
		$pdf->Cell(100,4,'E V A L U A C I O N E S','',1,'C');
		$pdf->setX(5);
		$y = $pdf->GetY();
		$yy = $y;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 164, 92, 2, '', 'FD');

		$pdf->SetFillColor(222);

		$pdf->SetFont('Arial','',6);
		$pdf->setX(5);
		$pdf->Cell(64,4,'','',0,'L');
		$pdf->Cell(12.5,4,"",'',0,'C');
		$pdf->Cell(12.5,4,"",'',0,'C');
		$pdf->Cell(12.5,4,"",'',0,'C');
		$pdf->Cell(12.5,4,"",'',0,'C');
		$pdf->Cell(12.5,4,"EXAMEN",'',0,'C');
		$pdf->Cell(12.5,4,"",'',0,'C');
		$pdf->Cell(12.5,4,"",'',0,'C');
		$pdf->Cell(12.5,4,"PROM.",'',1,'C');

		$pdf->setX(5);
		$pdf->Cell(64,4,'','B',0,'L');
		$pdf->Cell(12.5,4,"1RA",'B',0,'C');
		$pdf->Cell(12.5,4,"2DA",'B',0,'C',true);
		$pdf->Cell(12.5,4,"3RA",'B',0,'C');
		$pdf->Cell(12.5,4,"PARCIAL",'B',0,'C',true);
		$pdf->Cell(12.5,4,"FINAL",'B',0,'C');
		$pdf->Cell(12.5,4,"FINAL",'B',0,'C');
		$pdf->Cell(12.5,4,"RECUP.",'B',0,'C');
		$pdf->Cell(12.5,4,"GRUPO",'B',1,'C',true);

		$pdf->SetFillColor(222);

		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',5);
			$pdf->Cell(64,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',6);
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($result[$j]->cal0),floatval($result[$j]->con0),floatval($result[$j]->ina0)),'',0,'C', false);
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($result[$j]->cal1),floatval($result[$j]->con1),floatval($result[$j]->ina1)),'',0,'C',true);
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($result[$j]->cal2),floatval($result[$j]->con2),floatval($result[$j]->ina2)),'',0,'C',false);
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($result[$j]->promcal),floatval($result[$j]->promcon),floatval($result[$j]->sumina)),'',0,'C',true);
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($result[$j]->promcalgpo),floatval($result[$j]->promcongpo),floatval($result[$j]->suminagpo),2),'',1,'C',true);


		}

		// Calculamos el promedio del muchacho

		// $M->Actualiza_Grupo_Alumno_Promedio($user,$result[$j]->idgrualu);

		$prom = $f->getQuerys(41,"idgrualu=".$result[$j]->idgrualu,0,0,0,array(),'',1);

		//$pdf->setX(5);
		$pdf->setY(128);

		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(64,4,'PROMEDIO','',0,'L');

		$pdf->SetFillColor(222);

		$pdf->SetFont('Courier','',6);
		foreach ($prom as $j => $value) {
			$pdf->lastupdate = $prom[$j]->modi_el;

			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($prom[$j]->cal0),floatval($prom[$j]->con0),floatval($prom[$j]->ina0)),'',0,'C', false);
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($prom[$j]->cal1),floatval($prom[$j]->con1),floatval($prom[$j]->ina1)),'',0,'C',true);
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($prom[$j]->cal2),floatval($prom[$j]->con2),floatval($prom[$j]->ina2)),'',0,'C',false);
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($prom[$j]->promcal),floatval($prom[$j]->promcon),floatval($prom[$j]->sumina)),'',0,'C',true);
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($prom[$j]->promcalgpo),floatval($prom[$j]->promcongpo),floatval($prom[$j]->suminagpo),2),'',1,'C',true);

		}

		// Calculamos el promedio del GRUPO

		$idgrupo = $result[$j]->idgrupo;
		$idciclo = $result[$j]->idciclo;
		$gpo = $f->getQuerys(42,"idgrupo=".$idgrupo."&idciclo=".$idciclo,0,0,0,array(),'',1);

		$pdf->setX(5);
		$y = 132;
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '4', 'FD');

		$pdf->setX(5);
		$pdf->setY($y);

		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(64,4,'PROMEDIO DEL GRUPO','',0,'L');


		$pdf->SetFont('Courier','',6);
		foreach ($gpo as $j => $value) {

			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($gpo[$j]->cal0),floatval($gpo[$j]->con0),floatval($gpo[$j]->ina0),2),'',0,'C');
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($gpo[$j]->cal1),floatval($gpo[$j]->con1),floatval($gpo[$j]->ina1),2),'',0,'C');
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($gpo[$j]->cal2),floatval($gpo[$j]->con2),floatval($gpo[$j]->ina2),2),'',0,'C');
			$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($gpo[$j]->promcal),floatval($gpo[$j]->promcon),floatval($gpo[$j]->sumina),2),'',0,'C');
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,"",'',0,'R');
			$pdf->Cell(12.5,4,"",'',0,'R');
			//$pdf->Cell(12.5,4,$pdf->FormatCal(floatval($gpo[$j]->promcalgpo),floatval($gpo[$j]->promcongpo),floatval($gpo[$j]->suminagpo)),'',1,'R');
			$pdf->Cell(12.5,4,'','',1,'R');

		}


	// =================================================================================	
		// UBICAMOS EL ENCABEZADO DE LAS OBSERVACIONES

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',6);
		$pdf->RoundedRect(169, 36, 42, 4, 2, '2', 'FD');

		$pdf->setX(169);
		$pdf->setY(36);
		$pdf->Cell(163,4,'','',0,'L');
		$pdf->Cell(42,4,'OBSERVACIONES','',1,'C');

		// UBICAMOS EL CUERPO DE LAS OBSERVACIONES

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(169, 40, 42, 20, 2, '', 'FD');

		// UBICAMOS EL ENCABEZADO DE CAS

		$pdf->SetFillColor(192);
		$pdf->RoundedRect(169, 60, 42, 4, 2, '', 'FD');

		$pdf->setX(169);
		$pdf->setY(60);
		$pdf->Cell(163,4,'','',0,'L');
		$pdf->Cell(42,4,'C.A.S','',1,'C');

		// UBICAMOS EL CUERPO DE CAS

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(169, 64, 42, 10, 2, '', 'FD');

		// UBICAMOS EL ENCABEZADO DE FORMA DEL DIRECTOR

		$pdf->SetFillColor(192);
		$pdf->RoundedRect(169, 74, 42, 4, 2, '', 'FD');

		$pdf->setX(169);
		$pdf->setY(74);
		$pdf->Cell(163,4,'','',0,'L');
		$pdf->Cell(42,4,'FIRMA DEL DIRECTOR','',1,'C');

		// UBICAMOS EL CUERPO DE FORMA DEL DIRECTOR


		$pdf->SetFillColor(255);
		$pdf->RoundedRect(169, 78, 42, 10, 2, '', 'FD');


		// UBICAMOS EL ENCABEZADO DE FIRMA DEL PADRE O TUTOR

		$pdf->SetFillColor(192);
		$pdf->RoundedRect(169, 88, 42, 4, 2, '', 'FD');

		$pdf->setX(169);
		$pdf->setY(88);
		$pdf->SetFont('Arial','B',5);
		$pdf->Cell(163,4,'','',0,'L');
		$pdf->Cell(42,4,'OBSERVACIONES DEL PADRE O TUTOR','',1,'C');

		// UBICAMOS EL CUERPO DE FIRMA DEL PADRE O TUTOR

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(169, 92, 42, 25, 2, '', 'FD');


		// UBICAMOS EL ENCABEZADO DE FIRMA DEL PADRE O TUTOR

		$pdf->SetFillColor(192);
		$pdf->RoundedRect(169, 117, 42, 4, 2, '', 'FD');

		$pdf->setX(169);
		$pdf->setY(117);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(163,4,'','',0,'L');
		$pdf->Cell(44,4,'FIRMA DE PADRE O TUTOR','',1,'C');

		// UBICAMOS EL CUERPO DE FIRMA DEL PADRE O TUTOR

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(169, 121, 42, 15, 2, '3', 'FD');

	} // Fin de Enf IF


}

$pdf->Output();

?>