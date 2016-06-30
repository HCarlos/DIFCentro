<?php
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('default_socket_timeout', 6000);
*/
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
		//$this->Image('../../../images/web/'.$this->logoIBO,196,5,15,15);

		$this->sety(5);
		$this->setX(0);

		// /* *********************************************************
		// ** ENCABEZADO
		// ** ********************************************************* */

		$this->SetTextColor(0,0,0);
		$this->SetFont('Times','B',12);
		$this->Cell(297,4,utf8_decode("COLEGIO ARJÍ A.C."),'',1,'C');
		$this->setX(0);
		$this->SetFont('Arial','',10);
		$this->Cell(297,4,utf8_decode("AV. MÉXICO NUM. 2 COL. DEL BOSQUE, VILLAHERMOSA TABASCO, TELS 351-02-60 EXT 400"),'',1,'C');
		$this->setX(0);
		$this->Cell(297,4,utf8_decode("INCORPORADO A LA SEP CLAVE 27PES0077Q"),'',1,'C');
		$this->setX(0);
		$this->SetFont('Arial','B',10);
		$this->setX(0);
		$this->Cell(297,4,utf8_decode("BOLETA DE EVALUACIÓN SECUNDARIA"),'',1,'C');

		$this->Ln(6);
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 287, 4, 2, '12', 'FD');
		$this->SetFont('Arial','B',8);
		$this->Cell(90,4,utf8_decode("NOMBRE DEL ALUMNO"),'',0,'C');
		$this->Cell(30,4,utf8_decode("PERIODO"),'L',0,'C');
		//$this->Cell(20,6,utf8_decode("GRADO",'L',0,'C');
		$this->Cell(44,4,utf8_decode("FECHA"),'L',0,'C');
		$this->Cell(26,4,utf8_decode("GRUPO"),'L',0,'C');
		$this->Cell(16,4,utf8_decode("N° LISTA"),'L',1,'C');

		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(255);
		$this->RoundedRect(5, $y, 287, 4, 2, '34', 'FD');
		$this->SetFont('courier','',8);
		$this->Cell(90,4,utf8_decode($this->alumno),'',0,'C');
		$this->Cell(30,4,utf8_decode($this->ciclo),'L',0,'C');
		//$this->Cell(20,6,utf8_decode("5",'L',0,'C');
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
		//$this->Cell(0,10,utf8_decode('PlatSource © '.date('Y'),0,0,'L');
		//$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
		$this->Cell(0,10,utf8_decode('PlatSource.mx © ').date('Y'),0,0,'R');

	}

	function FormatCal($cal=0,$con=0,$ina=0,$pivot=0){
		$calx = "";
		$conx = "";
		$inax = "";
		if ($cal>0){
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
		$nret = "";
		switch($pivot){
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

// $M->Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user,$arrAlu[0]);
// $M->Actualiza_Promedios_Grupales_por_Materia($user,$arrAlu[0]);
// $M->Actualizar_Promedios_Grupales(0,0,$user,$arrAlu[0]);

$pdf = new PDF_Diag('L','mm');
//$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(TRUE, 0.1);
$pdf->SetLeftMargin(5);

$pdf->nFont = 6;
$pdf->SetFillColor(192,192,192);
$pdf->logoEmp = $logoEmp;
$pdf->logoIBO = $logoIbo;


foreach ($arrAlu as $i => $value) {
	$result = $f->getQuerys(43,"idgrualu=".$arrAlu[$i],0,0,0,array()," order by orden_impresion asc ",1);
	
	if ( count($result)>0  ){

		$pdf->alumno = $result[0]->alumno; //." - ".$arrAlu[$i];
		$pdf->num_lista = $result[0]->num_lista;
		$pdf->grupo = $result[0]->grupo;
		$pdf->ciclo = $result[0]->ciclo;

		$pdf->AddPage();

		$pdf->SetFont('Arial','B',8);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 225, 4, 2, '1', 'FD');
		$pdf->Cell(70,4,'A S I G N A T U R A','',0,'C');
		$pdf->Cell(154,4,'E  V  A  L  U  A  C  I  O  N  E  S','',1,'C');
		$pdf->setX(5);
		$y = $pdf->GetY();
		$yy = $y;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 225, 92, 2, '', 'FD');


		$pdf->SetFont('Arial','',7);
		$pdf->setX(5);
		$pdf->Cell(70,4,'','',0,'L');
		$pdf->SetFillColor(222);
		$pdf->Cell(15,4,"1RA",'T',0,'C',false);
		$pdf->Cell(15,4,"2DA",'',0,'C',true);
		$pdf->Cell(15,4,"3RA",'',0,'C',false);
		$pdf->Cell(15,4,"4TA",'',0,'C',true);
		$pdf->Cell(15,4,"5TA",'',0,'C',false);
		$pdf->Cell(15,4,"6TA",'',0,'C',true);
		$pdf->Cell(15,4,"7MA",'',0,'C',false);
		$pdf->Cell(15,4,"8VA",'',0,'C',true);
		$pdf->Cell(15,4,"FINAL",'',0,'C',false);
		$pdf->Cell(15,4,"GPO",'',1,'C',true);

		$pdf->setX(5);
		$pdf->SetFont('Courier','',6);
		$pdf->Cell(70,4,'','',0,'L');
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"",'B',0,'C',false);
		$pdf->Cell(15,4,"",'B',1,'C',true);

		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(70,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal0,$result[$j]->con0,$result[$j]->ina0),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal1,$result[$j]->con1,$result[$j]->ina1),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal2,$result[$j]->con2,$result[$j]->ina2),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal3,$result[$j]->con3,$result[$j]->ina3),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal4,$result[$j]->con4,$result[$j]->ina4),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal5,$result[$j]->con5,$result[$j]->ina5),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal6,$result[$j]->con6,$result[$j]->ina6),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal7,$result[$j]->con7,$result[$j]->ina7),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->promcal,$result[$j]->promcon,$result[$j]->sumina),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->promcalgpo,$result[$j]->promcongpo,$result[$j]->suminagpo,2),'',1,'C',true);

		}

		// Calculamos el promedio del muchacho

		// $M->Actualiza_Grupo_Alumno_Promedio($user,$result[$j]->idgrualu);

		$prom = $f->getQuerys(41,"idgrualu=".$result[$j]->idgrualu,0,0,0,array(),'',1);

		//$pdf->setX(5);
		$pdf->setY(128);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(70,4,'PROMEDIO','',0,'L');

		$pdf->SetFont('Courier','',7);
		foreach ($prom as $j => $value) {
			$pdf->lastupdate = $prom[$j]->modi_el;

			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal0,$prom[$j]->con0,$prom[$j]->ina0),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal1,$prom[$j]->con1,$prom[$j]->ina1),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal2,$prom[$j]->con2,$prom[$j]->ina2),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal3,$prom[$j]->con3,$prom[$j]->ina3),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal4,$prom[$j]->con4,$prom[$j]->ina4),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal5,$prom[$j]->con5,$prom[$j]->ina5),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal6,$prom[$j]->con6,$prom[$j]->ina6),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->cal7,$prom[$j]->con7,$prom[$j]->ina7),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->promcal,$prom[$j]->promcon,$prom[$j]->sumina),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($prom[$j]->promcalgpo,$prom[$j]->promcongpo,$prom[$j]->suminagpo,2),'',1,'C',true);

		}

		// Calculamos el promedio del GRUPO

		$idgrupo = $result[$j]->idgrupo;
		$idciclo = $result[$j]->idciclo;
		$gpo = $f->getQuerys(42,"idgrupo=".$idgrupo."&idciclo=".$idciclo,0,0,0,array(),'',1);

		$pdf->setX(5);
		$y = 132;
		$pdf->RoundedRect(5, $y, 225, 4, 2, '4', 'FD');

		$pdf->setX(5);
		$pdf->setY($y);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(70,4,'PROMEDIO DEL GRUPO','',0,'L');

		$pdf->SetFont('Courier','',7);
		foreach ($gpo as $j => $value) {

			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal0,$gpo[$j]->con0,$gpo[$j]->ina0,2),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal1,$gpo[$j]->con1,$gpo[$j]->ina1,2),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal2,$gpo[$j]->con2,$gpo[$j]->ina2,2),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal3,$gpo[$j]->con3,$gpo[$j]->ina3,2),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal4,$gpo[$j]->con4,$gpo[$j]->ina4,2),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal5,$gpo[$j]->con5,$gpo[$j]->ina5,2),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal6,$gpo[$j]->con6,$gpo[$j]->ina6,2),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->cal7,$gpo[$j]->con7,$gpo[$j]->ina7,2),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->promcal,$gpo[$j]->promcon,$gpo[$j]->sumina,2),'',0,'C',false);
			// $pdf->Cell(15,4,$pdf->FormatCal($gpo[$j]->promcalgpo,$gpo[$j]->promcongpo,$gpo[$j]->suminagpo),'',1,'C',true);
			$pdf->Cell(15,4,'','',1,'C',true);

		}

	// =================================================================================
	// INICIA PARTE DE MATERIAS HIJAS
	// =================================================================================

		$result = $f->getQuerys(44,"idgrualu=".$arrAlu[$i],0,0,0,array()," order by orden_impresion asc ",1);

		$pdf->Ln(2);

		$pdf->SetFont('Arial','B',8);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 225, 4, 2, '1', 'FD');
		$pdf->Cell(80,4,'A S I G N A T U R A','',0,'C');
		$pdf->Cell(154,4,'E  V  A  L  U  A  C  I  O  N  E  S','',1,'C');
		$pdf->setX(5);
		$y = $pdf->GetY();
		$yy = $y;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 225, 40, 2, '4', 'FD');


		$pdf->SetFillColor(222);
		$pdf->SetFont('Arial','',7);
		$pdf->setX(5);
		$pdf->Cell(70,4,'','',0,'L');
		$pdf->Cell(15,4,"1RA",'',0,'C',false);
		$pdf->Cell(15,4,"2DA",'',0,'C',true);
		$pdf->Cell(15,4,"3RA",'',0,'C',false);
		$pdf->Cell(15,4,"4TA",'',0,'C',true);
		$pdf->Cell(15,4,"5TA",'',0,'C',false);
		$pdf->Cell(15,4,"6TA",'',0,'C',true);
		$pdf->Cell(15,4,"7MA",'',0,'C',false);
		$pdf->Cell(15,4,"8VA",'',0,'C',true);
		$pdf->Cell(15,4,"FINAL",'',0,'C',false);
		$pdf->Cell(15,4,"GPO",'',1,'C',true);

		$pdf->setX(5);
		$pdf->SetFont('Courier','',6);
		$pdf->Cell(70,4,'','B',0,'L');
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',false);
		$pdf->Cell(15,4,"A   C   I",'B',0,'C',true);
		$pdf->Cell(15,4,"",'B',0,'C',false);
		$pdf->Cell(15,4,"",'B',1,'C',true);


		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(70,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal0,$result[$j]->con0,$result[$j]->ina0),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal1,$result[$j]->con1,$result[$j]->ina1),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal2,$result[$j]->con2,$result[$j]->ina2),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal3,$result[$j]->con3,$result[$j]->ina3),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal4,$result[$j]->con4,$result[$j]->ina4),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal5,$result[$j]->con5,$result[$j]->ina5),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal6,$result[$j]->con6,$result[$j]->ina6),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->cal7,$result[$j]->con7,$result[$j]->ina7),'',0,'C',true);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->promcal,$result[$j]->promcon,$result[$j]->sumina),'',0,'C',false);
			$pdf->Cell(15,4,$pdf->FormatCal($result[$j]->promcalgpo,$result[$j]->promcongpo,$result[$j]->suminagpo,2),'',1,'C',true);


		}
	 

	// =================================================================================
	// FINALIZA PARTE DE MATERIAS HIJAS
	// =================================================================================



	// =================================================================================	
		// UBICAMOS EL ENCABEZADO DE LAS OBSERVACIONES

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$pdf->RoundedRect(230, 36, 62, 4, 2, '2', 'FD');

		$pdf->setX(230);
		$pdf->setY(36);
		$pdf->Cell(224,4,'','',0,'L');
		$pdf->Cell(62,4,'OBSERVACIONES','',1,'C');

		// UBICAMOS EL CUERPO DE LAS OBSERVACIONES

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(230, 40, 62, 48, 2, '', 'FD');

		$pdf->setX(230);
		$pdf->setY(42);
		$pdf->Cell(226,4,'','',0,'L');
		$pdf->SetFont('Courier','',7);
		// $pdf->MultiCell(60, 4, utf8_decode('AQUI PONDREMOS NUESTRAS OBSERVACIONES QUE HAYAMOS COLOCADO EN LA PRIMERA MATERIA DE ESTA BOLETA DE CALIFICACIONES...'), 0, 'J');
		$pdf->MultiCell(60, 4, utf8_decode(''), 0, 'J');


		// UBICAMOS EL ENCABEZADO DE FIRMA DEL PADRE O TUTOR

		$pdf->SetFillColor(192);
		$pdf->RoundedRect(230, 88, 62, 4, 2, '', 'FD');

		$pdf->setX(230);
		$pdf->setY(88);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(224,4,'','',0,'L');
		$pdf->Cell(62,4,'OBSERVACIONES DEL PADRE O TUTOR','',1,'C');

		// UBICAMOS EL CUERPO DE FIRMA DEL PADRE O TUTOR

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(230, 92, 62, 44, 2, '3', 'FD');

		// UBICAMOS EL ENCABEZADO DE LAS OBSERVACIONES DEL MAESTRO

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$pdf->RoundedRect(230, 138, 62, 4, 2, '2', 'FD');

		$pdf->setX(230);
		$pdf->setY(138);
		$pdf->Cell(224,4,'','',0,'L');
		$pdf->Cell(62,4,'OBSERVACIONES DEL MAESTRO','',1,'C');

		// UBICAMOS EL CUERPO DE LAS OBSERVACIONES DEL MAESTRO

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(230, 142, 62, 23, 2, '', 'FD');

		// UBICAMOS EL ENCABEZADO DE LAS OBSERVACIONES DEL PADRE

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$pdf->RoundedRect(230, 159, 62, 4, 2, '', 'FD');

		$pdf->setX(230);
		$pdf->setY(159);
		$pdf->Cell(224,4,'','',0,'L');
		$pdf->Cell(62,4,'OBSERVACIONES DEL PADRE','',1,'C');

		// UBICAMOS EL CUERPO DE LAS OBSERVACIONES DEL PADRE

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(230, 163, 62, 19, 2, '3', 'FD');

		// **************************************************************************
		//  PANEL DE DE FIRMAS
		//  *************************************************************************
	  
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$pdf->RoundedRect(5, 183, 95, 4, 2, '1', 'FD');
		$pdf->RoundedRect(100, 183, 97, 4, 2, '', 'FD');
		$pdf->RoundedRect(197, 183, 95, 4, 2, '2', 'FD');

		$pdf->setX(5);
		$pdf->setY(183);
		$pdf->Cell(95,4,'FIRMA DEL DIRECTOR','',0,'C');
		$pdf->Cell(97,4,'FIRMA DEL PADRE O TUTOR','',0,'C');
		$pdf->Cell(95,4,'C.A.S.','',1,'C');

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, 187, 95, 16, 2, '4', 'FD');
		$pdf->RoundedRect(100, 187, 97, 16, 2, '', 'FD');
		$pdf->RoundedRect(197, 187, 95, 16, 2, '3', 'FD');		

	} // IF Compare
	
}

$pdf->Output();

?>