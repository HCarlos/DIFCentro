<?php

/*

error_reporting(E_ALL);
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
		//$this->Image('../../../images/web/'.$this->logoIBO,196,5,10,10);

		$this->sety(5);
		$this->setX(0);

		// /* *********************************************************
		// ** ENCABEZADO
		// ** ********************************************************* */

		$this->SetTextColor(0,0,0);
		$this->SetFont('Times','B',12);
		$this->Cell(210.5,4,utf8_decode("COLEGIO ARJÍ A.C."),'',1,'C');
		$this->setX(0);
		$this->SetFont('Arial','',10);
		$this->Cell(210.5,4,utf8_decode("AV. MÉXICO NUM. 2 COL. DEL BOSQUE, VILLAHERMOSA TABASCO, TELS 351-02-60 EXT 200"),'',1,'C');
		$this->setX(0);
		$this->Cell(210.5,4,utf8_decode("INCORPORADO A LA SEP CLAVE 27PPR0024S"),'',1,'C');
		$this->setX(0);
		$this->SetFont('Arial','B',10);
		$this->setX(0);
		$this->Cell(210.5,4,utf8_decode("BOLETA DE EVALUACIÓN PRIMARIA"),'',1,'C');

		$this->Ln(6);
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 205.5, 4, 2, '12', 'FD');
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
		$this->RoundedRect(5, $y, 205.5, 4, 2, '34', 'FD');
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

	function FormatCal($cal=0,$con=0,$ina=0){
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

		// return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).' '.str_pad($inax, 2, " ", STR_PAD_LEFT);
		return str_pad($calx, 3, " ", STR_PAD_LEFT);

	}


}

$arrAlu = explode(",",$strgrualu);

// $M->Actualiza_Promedios_Grupales_por_Materia($user,$arrAlu[0]);
// $M->Actualizar_Promedios_Grupales_Idiomas(0,0,$user,$arrAlu[0],0);
// $M->Actualizar_Promedios_Grupales_Idiomas(0,0,$user,$arrAlu[0],1);

$pdf = new PDF_Diag('P','mm','Letter');
//$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(TRUE, 0.1);
$pdf->SetLeftMargin(5);

$pdf->nFont = 6;
$pdf->SetFillColor(192,192,192);
$pdf->logoEmp = $logoEmp;
$pdf->logoIBO = $logoIbo;



 
foreach ($arrAlu as $i => $value) {
	// $M->Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user,$arrAlu[$i]);
	$result = $f->getQuerys(45,"idgrualu=".$arrAlu[$i],0,0,0,array()," order by orden_impresion asc ",1);

	
	if ( count($result)>0  ){


		$pdf->alumno = $result[0]->alumno; // ." - ".$arrAlu[$i];
		$pdf->num_lista = $result[0]->num_lista;
		$pdf->grupo = $result[0]->grupo;
		$pdf->ciclo = $result[0]->ciclo;

		$pdf->AddPage();

		$pdf->SetFont('Arial','B',8);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '1', 'FD');
		$pdf->Cell(63.5,4,utf8_decode('ASIGNATURAS ESPAÑOL'),'',0,'C');
		$pdf->Cell(100,4,'E  V  A  L  U  A  C  I  O  N  E  S','',1,'C');
		$pdf->setX(5);
		$y = $pdf->GetY();
		$yy = $y;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 164, 60, 2, '', 'FD');


		$pdf->SetFont('Arial','',7);
		$pdf->setX(5);
		$pdf->Cell(63.5,4,'','',0,'L');
		$pdf->SetFillColor(222);
		$pdf->Cell(10,4,"1RA",'B',0,'C');
		$pdf->Cell(10,4,"2DA",'B',0,'C');
		$pdf->Cell(10,4,"3RA",'B',0,'C');
		$pdf->Cell(10,4,"4TA",'B',0,'C');
		$pdf->Cell(10,4,"5TA",'B',0,'C');
		$pdf->Cell(10,4,"6TA",'B',0,'C');
		$pdf->Cell(10,4,"7MA",'B',0,'C');
		$pdf->Cell(10,4,"8VA",'B',0,'C');
		$pdf->Cell(10,4,"FINAL",'B',0,'C');
		$pdf->Cell(10,4,"GPO",'B',1,'C');


		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(63.5,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);
			if ($j==0){
					$idobs=0; $ib0 = 0;
					if ( intval($result[$j]->obs7) > 0 ) {
						$idobs = $result[$j]->obs7;
						$ib0 = 7;
					}else if ( intval($result[$j]->obs6) > 0 ) {
						$idobs = $result[$j]->obs6;
						$ib0 = 6;
					}else if ( intval($result[$j]->obs5) > 0 ) {
						$idobs = $result[$j]->obs5;
						$ib0 = 5;
					}else if ( intval($result[$j]->obs4) > 0 ) {
						$idobs = $result[$j]->obs4;
						$ib0 = 4;
					}else if ( intval($result[$j]->obs3) > 0 ) {
						$idobs = $result[$j]->obs3;
						$ib0 = 3;
					}else if ( intval($result[$j]->obs2) > 0 ) {
						$idobs = $result[$j]->obs2;
						$ib0 = 2;
					}else if ( intval($result[$j]->obs1) > 0 ) {
						$idobs = $result[$j]->obs1;
						$ib0 = 1;
					}else{
						$idobs = $result[$j]->obs0;
						$ib0 = 0;
					}	

				$obsss = $f->getQuerys(10002,$idobs,0,0,0,array()," ",1);
				if (count($obsss)>0){
					// $pdf->obsEsp = $obsss[0]->observacion.' :: '.$idobs.' :: '.$ib0.' :: '.$result[$j]->materia;
					$pdf->obsEsp = $obsss[0]->observacion;
				}else{
					$pdf->obsEsp = "";
				}

			}
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcal),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcalgpo),'',1,'C');

		}

		// Calculamos el promedio del muchacho

		// $M->Actualiza_Grupo_Alumno_Promedio_Idioma($user,$result[$j]->idgrualu,0);

		$prom = $f->getQuerys(49,"idgrualu=".$result[$j]->idgrualu."&idioma=0",0,0,0,array(),'',1);



		//$pdf->setX(5);
		$pdf->setY(90);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(64,4,'PROMEDIO','L',0,'L');

		$pdf->SetFont('Courier','',7);
		foreach ($prom as $j => $value) {

			$pdf->lastupdate = $prom[$j]->modi_el;

			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->promcal),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->promcalgpo),'R',1,'C');

		}

		// Calculamos el promedio del GRUPO

		$idgrupo = $result[$j]->idgrupo;
		$idciclo = $result[$j]->idciclo;
		$gpo = $f->getQuerys(50,"idgrupo=".$idgrupo."&idciclo=".$idciclo."&idioma=0",0,0,0,array(),'',1);

		$pdf->setX(5);
		$y = 94;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '', 'FD');

		$pdf->setX(5);
		$pdf->setY($y);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(64,4,'PROMEDIO DEL GRUPO','',0,'L');

		$pdf->SetFont('Courier','',7);
		foreach ($gpo as $j => $value) {

			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->promcal),'',0,'C');
			//$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->promcalgpo),'',1,'C');
			$pdf->Cell(10,4,'','',1,'C');

		}


	// =================================================================================
	// INICIA PARTE DE MATERIAS HIJAS
	// =================================================================================

		$result = $f->getQuerys(46,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);

		$pdf->SetFont('Arial','B',8);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '', 'FD');
		$pdf->Cell(164,4,utf8_decode('FORMACION DE HABITOS Y CONDUCTAS ESPAÑOL'),'',1,'C');
		$pdf->setX(5);
		$y = $pdf->GetY();
		$yy = $y;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 164, 40, 2, '4', 'FD');


		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(63.5,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal0,$result[$j]->con0,$result[$j]->ina0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal1,$result[$j]->con1,$result[$j]->ina1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal2,$result[$j]->con2,$result[$j]->ina2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal3,$result[$j]->con3,$result[$j]->ina3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal4,$result[$j]->con4,$result[$j]->ina4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal5,$result[$j]->con5,$result[$j]->ina5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal6,$result[$j]->con6,$result[$j]->ina6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal7,$result[$j]->con7,$result[$j]->ina7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcal,$result[$j]->promcon,$result[$j]->sumina),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcalgpo,$result[$j]->promcongpo,$result[$j]->suminagpo),'',1,'C');


		}
		$result = $f->getQuerys(51,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);
		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(63.5,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal0,$result[$j]->con0,$result[$j]->ina0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal1,$result[$j]->con1,$result[$j]->ina1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal2,$result[$j]->con2,$result[$j]->ina2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal3,$result[$j]->con3,$result[$j]->ina3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal4,$result[$j]->con4,$result[$j]->ina4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal5,$result[$j]->con5,$result[$j]->ina5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal6,$result[$j]->con6,$result[$j]->ina6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal7,$result[$j]->con7,$result[$j]->ina7),'',0,'C');
			$os = array(1,2,3,4,5);
			if ( in_array(intval($result[$j]->idmatclas) , $os) ){
				$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcal,$result[$j]->promcon,$result[$j]->sumina),'',0,'C');
				$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcalgpo,$result[$j]->promcongpo,$result[$j]->suminagpo),'',1,'C');
			}

		}

		// **************************************************************************************************
		// 
		// MODULO EN INGLES
		// 
		// **************************************************************************************************


		$result = $f->getQuerys(47,"idgrualu=".$arrAlu[$i],0,0,0,array()," order by orden_impresion asc ",1);
		$pdf->alumno = $result[0]->alumno." - ".$arrAlu[$i];
		$pdf->num_lista = $result[0]->num_lista;
		$pdf->grupo = $result[0]->grupo;
		$pdf->ciclo = $result[0]->ciclo;

		$pdf->SetFont('Arial','B',8);

		$pdf->setX(5);
		$pdf->setY(143);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '1', 'FD');
		$pdf->Cell(63.5,4,utf8_decode('ASIGNATURAS INGLES'),'',0,'C');
		$pdf->Cell(100,4,'E  V  A  L  U  A  C  I  O  N  E  S','',1,'C');
		$pdf->setX(5);
		$y = $pdf->GetY();
		$yy = $y;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 164, 60, 2, '', 'FD');


		$pdf->SetFont('Arial','',7);
		$pdf->setX(5);
		$pdf->Cell(63.5,4,'','',0,'L');
		$pdf->SetFillColor(222);
		$pdf->Cell(10,4,"1RA",'B',0,'C');
		$pdf->Cell(10,4,"2DA",'B',0,'C');
		$pdf->Cell(10,4,"3RA",'B',0,'C');
		$pdf->Cell(10,4,"4TA",'B',0,'C');
		$pdf->Cell(10,4,"5TA",'B',0,'C');
		$pdf->Cell(10,4,"6TA",'B',0,'C');
		$pdf->Cell(10,4,"7MA",'B',0,'C');
		$pdf->Cell(10,4,"8VA",'B',0,'C');
		$pdf->Cell(10,4,"FINAL",'B',0,'C');
		$pdf->Cell(10,4,"GPO",'B',1,'C');


		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(63.5,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);

			if ($j==0){
				
					$idobs=0;
					if ( intval($result[$j]->obs7) > 0 ) {
						$idobs = $result[$j]->obs7;
					}else if ( intval($result[$j]->obs6) > 0 ) {
						$idobs = $result[$j]->obs6;
					}else if ( intval($result[$j]->obs5) > 0 ) {
						$idobs = $result[$j]->obs5;
					}else if ( intval($result[$j]->obs4) > 0 ) {
						$idobs = $result[$j]->obs4;
					}else if ( intval($result[$j]->obs3) > 0 ) {
						$idobs = $result[$j]->obs3;
					}else if ( intval($result[$j]->obs2) > 0 ) {
						$idobs = $result[$j]->obs2;
					}else if ( intval($result[$j]->obs1) > 0 ) {
						$idobs = $result[$j]->obs1;
					}else{
						$idobs = $result[$j]->obs0;
					}	

					$obsss = $f->getQuerys(10002,$idobs,0,0,0,array()," ",1);
					if (count($obsss)>0){
						// $pdf->obsIng = $obsss[0]->observacion.' :: '.$idobs.' :: '.$ib0.' :: '.$result[$j]->materia;
						$pdf->obsIng = $obsss[0]->observacion;
					}else{
						$pdf->obsIng = "";
					}

			}


			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcal),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcalgpo),'',1,'C');

		}

		// Calculamos el promedio del muchacho

		// $M->Actualiza_Grupo_Alumno_Promedio_Idioma($user,$result[$j]->idgrualu,1);

		$prom = $f->getQuerys(49,"idgrualu=".$result[$j]->idgrualu."&idioma=1",0,0,0,array(),'',1);

		//$pdf->setX(5);
		$pdf->setY(200);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(64,4,'PROMEDIO','L',0,'L');

		$pdf->SetFont('Courier','',7);
		foreach ($prom as $j => $value) {

			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->cal7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->promcal),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($prom[$j]->promcalgpo),'R',1,'C');

		}

		// Calculamos el promedio del GRUPO

		$idgrupo = $result[$j]->idgrupo;
		$idciclo = $result[$j]->idciclo;
		$gpo = $f->getQuerys(50,"idgrupo=".$idgrupo."&idciclo=".$idciclo."&idioma=1",0,0,0,array(),'',1);

		$pdf->setX(5);
		$y = 204;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '', 'FD');

		$pdf->setX(5);
		$pdf->setY($y);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(64,4,'PROMEDIO DEL GRUPO','',0,'L');

		$pdf->SetFont('Courier','',7);
		foreach ($gpo as $j => $value) {

			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->cal7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->promcal),'',0,'C');
			//$pdf->Cell(10,4,$pdf->FormatCal($gpo[$j]->promcalgpo),'',1,'C');
			$pdf->Cell(10,4,'','',1,'C');

		}


	// =================================================================================
	// INICIA PARTE DE MATERIAS HIJAS
	// =================================================================================

		$result = $f->getQuerys(48,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);

		$pdf->SetFont('Arial','B',8);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, 164, 4, 2, '', 'FD');
		$pdf->Cell(164,4,utf8_decode('FORMACION DE HÁBITOS Y CONDUCTAS INGLÉS'),'',1,'C');
		$pdf->setX(5);
		$y = $pdf->GetY();
		$yy = $y;
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, 164, 40, 2, '4', 'FD');


		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(63.5,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal0,$result[$j]->con0,$result[$j]->ina0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal1,$result[$j]->con1,$result[$j]->ina1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal2,$result[$j]->con2,$result[$j]->ina2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal3,$result[$j]->con3,$result[$j]->ina3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal4,$result[$j]->con4,$result[$j]->ina4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal5,$result[$j]->con5,$result[$j]->ina5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal6,$result[$j]->con6,$result[$j]->ina6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal7,$result[$j]->con7,$result[$j]->ina7),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcal,$result[$j]->promcon,$result[$j]->sumina),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcalgpo,$result[$j]->promcongpo,$result[$j]->suminagpo),'',1,'C');

		}
		$result = $f->getQuerys(52,"idgrualu=".$arrAlu[$i],0,0,0,array(),$s,1);
		foreach ($result as $j => $value) {

			$pdf->setX(5);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(63.5,4,utf8_decode($result[$j]->materia),'',0,'L');
			$pdf->SetFont('Courier','',7);
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal0,$result[$j]->con0,$result[$j]->ina0),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal1,$result[$j]->con1,$result[$j]->ina1),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal2,$result[$j]->con2,$result[$j]->ina2),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal3,$result[$j]->con3,$result[$j]->ina3),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal4,$result[$j]->con4,$result[$j]->ina4),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal5,$result[$j]->con5,$result[$j]->ina5),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal6,$result[$j]->con6,$result[$j]->ina6),'',0,'C');
			$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->cal7,$result[$j]->con7,$result[$j]->ina7),'',0,'C');
			$os = array(1,2,3,4,5);
			if ( in_array(intval($result[$j]->idmatclas) , $os) ){
				$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcal,$result[$j]->promcon,$result[$j]->sumina),'',0,'C');
				$pdf->Cell(10,4,$pdf->FormatCal($result[$j]->promcalgpo,$result[$j]->promcongpo,$result[$j]->suminagpo),'',1,'C');
			}

		}
	 

	// =================================================================================
	// FINALIZA PARTE DE MATERIAS HIJAS
	// =================================================================================




	// =================================================================================	

		// UBICAMOS EL PERFIL ESPEAÑOL

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$miX = 169;
		$miY = 36;
		$pdf->RoundedRect($miX, $miY, 41, 4, 2, '2', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','R',0,'L');
		$pdf->Cell(41,4,'PERFIL IB','',1,'C');

		$pdf->SetFillColor(255);
		$miX = 169;
		$miY = 40;
		$pdf->RoundedRect($miX, $miY, 41, 80, 2, '', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','',0,'L');
		$pdf->SetFont('Courier','',7);
		$pdf->MultiCell(41, 4, utf8_decode($pdf->obsEsp), 0, 'J');


		// UBICAMOS LA FIRMA DEL MAESTRO

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$miX = 169;
		$miY = 116;
		$pdf->RoundedRect($miX, $miY, 41, 4, 2, '', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','R',0,'L');
		$pdf->Cell(41,4,'FIRMA DEL MAESTRO','',1,'C');


		$pdf->SetFillColor(255);
		$miX = 169;
		$miY = 120;
		$pdf->RoundedRect($miX, $miY, 41, 22, 2, '3', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','',0,'L');
		$pdf->SetFont('Courier','',7);
		$pdf->MultiCell(41, 4, utf8_decode(''), 0, 'J');



		// UBICAMOS EL PERFIL INGLES

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$miX = 169;
		$miY = 143;
		$pdf->RoundedRect($miX, $miY, 41, 4, 2, '2', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','R',0,'L');
		$pdf->Cell(41,4,'IB PERFIL','',1,'C');

		$pdf->SetFillColor(255);
		$miX = 169;
		$miY = 147;
		$pdf->RoundedRect($miX, $miY, 41, 80, 2, '', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','',0,'L');
		$pdf->SetFont('Courier','',7);
		$pdf->MultiCell(41, 4, utf8_decode($pdf->obsIng), 0, 'J');


		// UBICAMOS LA FIRMA DEL MAESTRO

		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$miX = 169;
		$miY = 227;
		$pdf->RoundedRect($miX, $miY, 41, 4, 2, '', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','R',0,'L');
		$pdf->Cell(41,4,'FIRMA DEL MAESTRO','',1,'C');


		$pdf->SetFillColor(255);
		$miX = 169;
		$miY = 231;
		$pdf->RoundedRect($miX, $miY, 41, 21, 2, '3', 'FD');

		$pdf->setX($miX);
		$pdf->setY($miY);
		$pdf->Cell($miX-5,4,'','',0,'L');
		$pdf->SetFont('Courier','',7);
		$pdf->MultiCell(41, 4, utf8_decode(''), 0, 'J');

		// **************************************************************************
		//  PANEL DE DE FIRMAS
		//  *************************************************************************
	  
		$y = $pdf->getY()+18;
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',8);
		$pdf->RoundedRect(5, $y, 103, 4, 2, '1', 'FD');
		$pdf->RoundedRect(107, $y, 103, 4, 2, '2', 'FD');

		$pdf->setX(5);
		$pdf->setY($y);
		$pdf->Cell(103,4,'FIRMA DEL DIRECTOR','',0,'C');
		$pdf->Cell(103,4,'FIRMA DEL PADRE O TUTOR','',1,'C');

		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y+4, 103, 16, 2, '4', 'FD');
		$pdf->RoundedRect(107, $y+4, 103, 16, 2, '3', 'FD');

	
	} // End If

}



$pdf->Output();

?>