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
    var $matricula;
    var $headTitle;
    var $profesor;


    var $calumno;
    var $cmateria;
    var $cgrupo;
    var $cfecha;
    var $cperiodo;
    var $cnum_lista;
    var $cmatricula;

    var $aspectos;
    var $evaluacion;
    var $camposformativos;
    var $inicial;
    var $media;

    var $leg0;
    var $leg1;
    var $leg2;
    var $leg3;
    var $leg4;

	var $profile;
	var $teacher;    


    function Header(){   

		$this->Image('../../../images/web/'.$this->logoEmp,6.44444,5,20,20);
		//$this->Image('../../../images/web/'.$this->logoIBO,196,6.44444,10,10);

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
		$this->Cell(210.5,4,utf8_decode("AV. MÉXICO NUM. 2 COL. DEL BOSQUE, VILLAHERMOSA TABASCO, TELS 351-02-60 EXT 100"),'',1,'C');
		$this->setX(0);
		$this->Cell(210.5,4,utf8_decode("INCORPORADO A LA SEP CLAVE 27PJN0007L"),'',1,'C');
		$this->setX(0);
		$this->SetFont('Arial','B',10);
		$this->setX(0);
		$this->Cell(210.5,4,utf8_decode("BOLETA DE EVALUACIÓN PREESCOLAR - ".$this->headTitle),'',1,'C');

		$this->Ln(6);
		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(192);
		$this->RoundedRect(5, $y, 205.5, 4, 2, '12', 'FD');
		$this->SetFont('Arial','B',8);
		$this->Cell(77,4,utf8_decode($this->calumno),'',0,'C');
		$this->Cell(28,4,utf8_decode($this->cmatricula),'L',0,'C');
		$this->Cell(28,4,utf8_decode($this->cperiodo),'L',0,'C');
		//$this->Cell(20,6,utf8_decode("GRADO",'L',0,'C');
		$this->Cell(28,4,utf8_decode($this->cfecha),'L',0,'C');
		$this->Cell(28,4,utf8_decode($this->cgrupo),'L',0,'C');
		$this->Cell(16,4,utf8_decode($this->cnum_lista),'L',1,'C');

		$this->setX(5);
		$y = $this->GetY();
		$this->SetFillColor(255);
		$this->RoundedRect(5, $y, 205.5, 4, 2, '34', 'FD');
		$this->SetFont('courier','',8);
		$this->Cell(77,4,utf8_decode($this->alumno),'',0,'C');
		$this->Cell(28,4,utf8_decode($this->matricula),'L',0,'C');
		$this->Cell(28,4,utf8_decode($this->ciclo),'L',0,'C');
		//$this->Cell(20,6,utf8_decode("5",'L',0,'C');
		$this->Cell(28,4,date('d-M-Y'),'L',0,'C');
		$this->Cell(28,4,utf8_decode($this->grupo),'L',0,'C');
		$this->Cell(16,4,utf8_decode($this->num_lista),'L',1,'C');

		$this->Ln(2);

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

	function FormatCal($cal=0,$d=0,$ina=false){
		$calx = "";
		$vc = intval($cal);

		if ($vc == 100){
			$calx = $d==0?"D":"O";
		}else if ($vc >= 90 && $vc <= 99){
			$calx = $d==0?"L":"P";
		}else if ($vc >= 80 && $vc <= 89){
			$calx = $d==0?"EP":"IP";
		}else if ($vc >= 70 && $vc <= 79){
			$calx = $d==0?"I":"BP";
		}else if ($vc >= 60 && $vc <= 69){
			$calx = "NR";
		}else {
			$calx = "";
		}

		// $calx = $calx .' '.$vc;

		// return str_pad($calx, 3, " ", STR_PAD_LEFT).' '.str_pad($conx, 2, " ", STR_PAD_LEFT).' '.str_pad($inax, 2, " ", STR_PAD_LEFT);
		// return str_pad($calx, 3, " ", STR_PAD_LEFT);
		if ($ina){
			$calx = intval($cal);
			if ($calx<=0){
				$calx = '';
			}
		}
		return $calx;


	}


}

$arrAlu = explode(",",$strgrualu);

$prom_x = $f->getQuerys(41,"idgrualu=".$arrAlu[0],0,0,0,array(),'',1);

$pdf = new PDF_Diag('P','mm','Letter');
//$pdf->AddFont('helvetica','','helvetica.php');
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(TRUE, 0.1);
$pdf->SetLeftMargin(5);

$pdf->nFont = 6;
$pdf->SetFillColor(192,192,192);
$pdf->logoEmp = $logoEmp;
$pdf->logoIBO = $logoIbo;
$pdf->lastupdate = "";//$prom_x[0]->modi_el;

$d=1;

 
foreach ($arrAlu as $i => $value) {
	// $M->Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user,$arrAlu[$i]);
	$result = $f->getQuerys(53,"idgrualu=".$arrAlu[$i]."&idioma=".$d,0,0,0,array()," order by orden_impresion asc ",1);
	if ( count($result) > 0 ){

		if ( intval($result[0]->obs7) > 0 ) {
			$idobs = $result[0]->obs7;
		}else if ( intval($result[0]->obs6) > 0 ) {
			$idobs = $result[0]->obs6;
		}else if ( intval($result[0]->obs5) > 0 ) {
			$idobs = $result[0]->obs5;
		}else if ( intval($result[0]->obs4) > 0 ) {
			$idobs = $result[0]->obs4;
		}else if ( intval($result[0]->obs3) > 0 ) {
			$idobs = $result[0]->obs3;
		}else if ( intval($result[0]->obs2) > 0 ) {
			$idobs = $result[0]->obs2;
		}else if ( intval($result[0]->obs1) > 0 ) {
			$idobs = $result[0]->obs1;
		}else{
			$idobs = $result[0]->obs0;
		}	

		$pdf->alumno = $result[0]->alumno; // .' '.$arrAlu[$i];
		$pdf->num_lista = $result[0]->num_lista;
		$pdf->grupo = $result[0]->grupo;
		$pdf->ciclo = $result[0]->ciclo;
		$pdf->matricula = $result[0]->matricula_interna;
		$pdf->profesor = $result[0]->profesor;
	
		//for ($d=0; $d<2; ++$d){ // Buble Idiomas	
		$a0 = 25;
		$a1 = 132;
		$a2 = 48;

	    $pdf->calumno = $d==0?'NOMBRE DEL ALUMNO':'STUDENT NAME';
	    $pdf->cgrupo = $d==0?'GRUPO':'GROUP';
	    $pdf->cfecha = $d==0?'FECHA':'DATE';
	    $pdf->cperiodo = $d==0?'PERÍODO':'PERIOD';
	    $pdf->cnum_lista = $d==0?'N° LISTA':'LIST';
	    $pdf->cmatricula = $d==0?'MATRÍCULA':'REGISTRATION';


		$pdf->headTitle = $d==0?'ESPAÑOL':'ENGLISH';
		$pdf->aspectos = $d==0?'A  S  P  E  C  T  O  S':'A  S  P  E  C  T  S';
	    $pdf->camposformativos = $d==0?'CAMPOS FORMATIVOS':'FORMATIVE FIELDS';
		$pdf->evaluacion = $d==0?'EVALUACIÓN':'EVALUATION';
		$pdf->inicial = $d==0?'INICIAL':'INITIAL';
		$pdf->media = $d==0?'MEDIA':'MIDDLE';

		$pdf->profile = $d==0?'PERFIL IB':'PROFILE IB';
		$pdf->teacher = $d==0?'TITUTLAR':'TEACHER';

		$pdf->leg0 = $d==0?'DESTACADO':'OUTSTANDING';
		$pdf->leg1 = $d==0?'LOGRADO':'PROFICIENT';
		$pdf->leg2 = $d==0?'EN PROCESO':'IN PROCESS';
		$pdf->leg3 = $d==0?'INICIADO':'BEGINNING PROCESS';
		$pdf->leg4 = $d==0?'NECESARIO REFORZAR':'NEEDS TO REINFORCE';

		$pdf->AddPage();

		$pdf->SetFont('Arial','B',8);


		// Encabezado Cuerpo
		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, $a0, 4, 2, '1', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 8, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 4, 2, '2', 'FD');

		$pdf->setX(5);
		$pdf->Cell($a0,4,utf8_decode($pdf->headTitle),'',0,'C');
		$pdf->Cell($a1,8,utf8_decode($pdf->aspectos),'',0,'C');
		$pdf->Cell($a2,4,utf8_decode($pdf->evaluacion),'',1,'C');

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 4, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 4, 2, '', 'FD');

		$pdf->setX(5);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell($a0,4,utf8_decode($pdf->camposformativos),'',0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell($a1,8,utf8_decode(''),'',0,'C');
		$pdf->Cell(16,4,utf8_decode($pdf->inicial),'',0,'C');
		$pdf->Cell(16,4,utf8_decode($pdf->media),'LR',0,'C');
		$pdf->Cell(16,4,utf8_decode('FINAL'),'',1,'C');


		$arrCal = $f->getQuerys(55,"idgrualu=".$result[0]->idgrualu."&idioma=".$d."&rango=37 and 39",0,0,0,array(),' order by orden_impresion asc ',1);

		//PRIMER BLOQUE

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 19.33332, 2, '4', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 19.33332, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 19.33332, 2, '3', 'FD');

		$pdf->SetFont('Arial','',7);
		
		for ($i=0; $i<3; ++$i) {

			$pdf->setX(5);
			$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');

			if (isset($arrCal[$i]->materia)){
				$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal0,$d),'',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal1,$d),'LR',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal2,$d),'',1,'C');
			}else{
				$pdf->Cell($a1,6.44444,'','',0,'L');
				$pdf->Cell(16,6.44444,'','',0,'C');
				$pdf->Cell(16,6.44444,'','LR',0,'C');
				$pdf->Cell(16,6.44444,'','',1,'C');
			}

		}


		$pdf->Ln(2);
		
		//SEGUNDO BLOQUE

		$arrCal = $f->getQuerys(55,"idgrualu=".$result[0]->idgrualu."&idioma=".$d."&rango=41 and 46",0,0,0,array(),' order by orden_impresion asc ',1);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 38.66664, 2, '14', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 38.66664, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 38.66664, 2, '23', 'FD');

		$pdf->SetFont('Arial','',7);

		for ($i=0; $i<6; ++$i) {

			$pdf->setX(5);
			$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');

			if (isset($arrCal[$i]->materia)){
				$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal0,$d),'',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal1,$d),'LR',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal2,$d),'',1,'C');
			}else{
				$pdf->Cell($a1,6.44444,'','',0,'L');
				$pdf->Cell(16,6.44444,'','',0,'C');
				$pdf->Cell(16,6.44444,'','LR',0,'C');
				$pdf->Cell(16,6.44444,'','',1,'C');
			}

		}


		$pdf->Ln(2);
		
		//TERCER BLOQUE

		$arrCal = $f->getQuerys(55,"idgrualu=".$result[0]->idgrualu."&idioma=".$d."&rango=48 and 51",0,0,0,array(),' order by orden_impresion asc ',1);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 25.77776, 2, '14', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 25.77776, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 25.77776, 2, '23', 'FD');

		$pdf->SetFont('Arial','',7);

		for ($i=0; $i<4; ++$i) {

			$pdf->setX(5);
			$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');

			if (isset($arrCal[$i]->materia)){
				$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal0,$d),'',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal1,$d),'LR',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal2,$d),'',1,'C');
			}else{
				$pdf->Cell($a1,6.44444,'','',0,'L');
				$pdf->Cell(16,6.44444,'','',0,'C');
				$pdf->Cell(16,6.44444,'','LR',0,'C');
				$pdf->Cell(16,6.44444,'','',1,'C');
			}

		}


		$pdf->Ln(2);

		// CUARTO BLOQUE

		$arrCal = $f->getQuerys(55,"idgrualu=".$result[0]->idgrualu."&idioma=".$d."&rango=53 and 55",0,0,0,array(),' order by orden_impresion asc ',1);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 19.33332, 2, '14', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 19.33332, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 19.33332, 2, '23', 'FD');

		$pdf->SetFont('Arial','',7);

		for ($i=0; $i<3; ++$i) {

			$pdf->setX(5);
			$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');

			if (isset($arrCal[$i]->materia)){
				//$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
				if ( strlen($arrCal[$i]->materia) > 65 ){
					// $nx = $pdf->GetX();
					// $ny = $pdf->GetY();

					$pdf->SetFont('Arial','',6);

					//$pdf->MultiCell($a1, 2.72, utf8_decode($arrCal[$i]->materia), 0, 'L');
					$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
					
					$pdf->SetFont('Arial','',7);

					//$pdf->setX($nx-$a1);
					//$pdf->setY($ny);
					
					//$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');
					//$pdf->Cell($a1,6.44444,'','',0,'L');

				} else {
					$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
				}

				//$pdf->Cell($a1,6.44444,'','',0,'L');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal0,$d),'',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal1,$d),'LR',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal2,$d),'',1,'C');
			}else{
				$pdf->Cell($a1,6.44444,'','',0,'L');
				$pdf->Cell(16,6.44444,'','',0,'C');
				$pdf->Cell(16,6.44444,'','LR',0,'C');
				$pdf->Cell(16,6.44444,'','',1,'C');
			}

		}


		$pdf->Ln(2);
		
		// QUINTO BLOQUE

		$arrCal = $f->getQuerys(55,"idgrualu=".$result[0]->idgrualu."&idioma=".$d."&rango=57 and 61",0,0,0,array(),' order by orden_impresion asc ',1);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 32.2222, 2, '14', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 32.2222, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 32.2222, 2, '23', 'FD');

		$pdf->SetFont('Arial','',7);

		for ($i=0; $i<5; ++$i) {

			$pdf->setX(5);
			$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');

			if (isset($arrCal[$i]->materia)){
				
				if ( strlen($arrCal[$i]->materia) > 70 ){
					//$nx = $pdf->GetX();
					//$ny = $pdf->GetY();

					$pdf->SetFont('Arial','',6);
			
					//$pdf->MultiCell($a1, 2.72, utf8_decode($arrCal[$i]->materia), 0, 'L');
					$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
					
					//$pdf->setX($nx-$a1);
					//$pdf->setY($ny);
					
					$pdf->SetFont('Arial','',7);
					//$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');
					//$pdf->Cell($a1,6.44444,'','',0,'L');

				} else {
					$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
				}
				
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal0,$d),'',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal1,$d),'LR',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal2,$d),'',1,'C');
			}else{
				$pdf->Cell($a1,6.44444,'','',0,'L');
				$pdf->Cell(16,6.44444,'','',0,'C');
				$pdf->Cell(16,6.44444,'','LR',0,'C');
				$pdf->Cell(16,6.44444,'','',1,'C');
			}

		}

		$pdf->Ln(2);

		// SEXTO BLOQUE

		$arrCal = $f->getQuerys(55,"idgrualu=".$result[0]->idgrualu."&idioma=".$d."&rango=62 and 64",0,0,0,array(),' order by orden_impresion asc ',1);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 19.33332, 2, '14', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 19.33332, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 19.33332, 2, '23', 'FD');

		$pdf->SetFont('Arial','',7);

		for ($i=0; $i<3; ++$i) {

			$pdf->setX(5);
			$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');

			if (isset($arrCal[$i]->materia)){
				$pdf->Cell($a1,6.44444,utf8_decode($arrCal[$i]->materia),'',0,'L');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal0,$d),'',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal1,$d),'LR',0,'C');
				$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[$i]->cal2,$d),'',1,'C');
			}else{
				$pdf->Cell($a1,6.44444,'','',0,'L');
				$pdf->Cell(16,6.44444,'','',0,'C');
				$pdf->Cell(16,6.44444,'','LR',0,'C');
				$pdf->Cell(16,6.44444,'','',1,'C');
			}

		}

		$pdf->Ln(2);

		// SEPTIMO BLOQUE

		$arrCal = $f->getQuerys(52,"idgrualu=".$result[0]->idgrualu."&idioma=".$d."&rango=60 and 65",0,0,0,array(),' order by orden_impresion asc ',1);

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->RoundedRect(5, $y, $a0, 6.44444, 2, '14', 'FD');
		$pdf->RoundedRect($a0+5, $y, $a1, 6.44444, 2, '', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 6.44444, 2, '23', 'FD');

		$pdf->SetFont('Arial','',8);

		$pdf->setX(5);
		$pdf->Cell($a0,6.44444,utf8_decode(''),'',0,'L');
		if (isset($arrCal[0]->materia)){
			$pdf->Cell($a1,6.44444,utf8_decode($arrCal[0]->materia),'',0,'L');
			$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[0]->cal0,$d,true),'',0,'C');
			$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[0]->cal1,$d,true),'LR',0,'C');
			$pdf->Cell(16,6.44444,$pdf->FormatCal($arrCal[0]->cal2,$d,true),'',1,'C');
		}else{
			$pdf->Cell($a1,6.44444,'','',0,'L');
			$pdf->Cell(16,6.44444,'','',0,'C');
			$pdf->Cell(16,6.44444,'','LR',0,'C');
			$pdf->Cell(16,6.44444,'','',1,'C');
		}






		// BLOQUE FINAL
		$vP = array(51,81,112,140,171,194);
		$vPArr = $f->getQuerys(54,"idgrualu=".$result[0]->idgrualu."&idioma=".$d,0,0,0,array(),'',1);
		foreach ($vPArr as $k => $value) {
			if ($k <= 5){
				$pdf->setX(5);
				$pdf->setY($vP[$k]);
				$pdf->SetFont('Courier','B',8);
				$pdf->MultiCell($a0, 4, utf8_decode($vPArr[$k]->materia), 0, 'L');
			}
		}


		$pdf->setX(5);
		$pdf->setY(220);

		// PERFIL IBO

		$obsss = $f->getQuerys(10002,$idobs,0,0,0,array()," ",1);
		if (count($obsss)>0){
			$pdf->obsEsp = $obsss[0]->observacion;
		}else{
			$pdf->obsEsp = "";
		}
		
		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(192);
		$pdf->RoundedRect(5, $y, $a0+$a1, 4, 2, '1', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 4, 2, '2', 'FD');

		$pdf->setX(5);
		$pdf->Cell($a0+$a1,4,utf8_decode($pdf->profile),'',0,'L');
		$pdf->Cell($a2,4,utf8_decode($pdf->teacher),'',1,'C');

		$pdf->setX(5);
		$y = $pdf->GetY();
		$pdf->SetFillColor(255);
		$pdf->SetFont('Arial','',8);	
		$pdf->RoundedRect(5, $y, $a0+$a1, 30, 2, '4', 'FD');
		$pdf->RoundedRect($a0+$a1+5, $y, $a2, 30, 2, '3', 'FD');

		$pdf->setX(5);
		//$pdf->Cell($a0+$a1,4,utf8_decode($pdf->obsEsp),'',0,'L');
		//$pdf->Cell($a2,4,utf8_decode($pdf->profesor),'',1,'L');

		$pdf->setX(150);
		$pdf->setY($y+1);
		$pdf->MultiCell($a0+$a1, 4, utf8_decode($pdf->obsEsp), 0, 'L');
		$pdf->setX(150);
		$pdf->setY($y+1);
		$pdf->Cell($a0+$a1,4,'','',0,'L');
		$pdf->MultiCell($a2, 4, utf8_decode($pdf->profesor), 0, 'L');

		// NOMENCLATURA

		$pdf->Ln(26);
		$pdf->setX(5);

	
		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(3,6,utf8_decode('O'),'',0,'L');
		$pdf->SetFont('Arial','',8);	
		$pdf->Cell(37,6,utf8_decode('=  ').$pdf->leg0,'',0,'L');

		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(3,6,utf8_decode('P'),'',0,'L');
		$pdf->SetFont('Arial','',8);	
		$pdf->Cell(37,6,utf8_decode('=  ').$pdf->leg1,'',0,'L');

		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(5,6,utf8_decode('IP'),'',0,'L');
		$pdf->SetFont('Arial','',8);	
		$pdf->Cell(37,6,utf8_decode('=  ').$pdf->leg2,'',0,'L');

		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(3,6,utf8_decode('BP'),'',0,'L');
		$pdf->SetFont('Arial','',8);	
		$pdf->Cell(37,6,utf8_decode('=  ').$pdf->leg3,'',0,'L');

		$pdf->SetFont('Arial','B',8);	
		$pdf->Cell(5,6,utf8_decode('NR'),'',0,'L');
		$pdf->SetFont('Arial','',8);	
		$pdf->Cell(30,6,utf8_decode('=  ').$pdf->leg4,'',1,'L');


	} // Fin Si

}



$pdf->Output();

?>