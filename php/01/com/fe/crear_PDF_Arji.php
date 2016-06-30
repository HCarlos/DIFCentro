<?php

require('../../diag/fpdf.php'); //para crear el pdf
require('numero_a_letra.php'); // funcion para convertir el total a letra

class PDF extends FPDF
{

    public $gif;
    public $serie;
    public $folio;
    public $imgs;
    public $logo;
    public $back;
    public $rs;
    public $rfc;
    public $tipo_docto;

    //Encabezado de página
    function Header()
    {   
		$this->SetFillColor(52,2,19);
         $this->Image($this->imgs.'logo_arji.gif',6,8,56);
		$this->Image($this->imgs.'back.jpg',57,85,100);
		//$this->Image('imgs/back2.jpg',57,86,150);
		//$this->Image('imgs/cedula_arji.jpg',10,192,39);
		$this->Image($this->gif,10,172,39);
		$this->SetFont('Arial','B',12);
		$this->SetTextColor(0,0,0);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode($this->rs),0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(255,255,255);
		$this->Cell(30,4,utf8_decode($this->tipo_docto),1,1,'C',true);
		$this->SetFont('Arial','B',9);
		$this->SetTextColor(0,0,0);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("R.F.C. ".$this->rs),0,0,'C');
		$this->SetFont('Arial','',8);
		$this->SetTextColor(0,0,0);
		/*$this->Cell(25,4,"Serie: ".$this->serie,1,0,'C')*/; 
		$this->Cell(30,4,$this->serie."-".$this->folio,1,1,'C');
		$this->SetFont('Arial','B',8);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("Av. México Núm. 2, Col. Del Bosque"),0,1,'C');
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode('Villahermosa, Tabasco, CP. 86160'),0,1,'C');
		$this->Ln(4);
    } 
}
			        
	$pdf=new PDF('P','mm','Letter');
	$pdf->folio 	 = $folio;
	$pdf->serie 	 = $serie;
	$pdf->gif   	 = $gif;
	$pdf->imgs  	 = $dir_imgs; 
	$pdf->logo  	 = $file_logo; 
	$pdf->back  	 = $file_back; 
	$pdf->rs    	 = $razon_social_emisor;
	$pdf->rfc   	 = $rfc_emisor;
	$pdf->tipo_docto = $tipo_docto;

	$pdf->AliasNbPages();
	$pdf->AddPage();    
	$pdf->Ln(7);

	$pdf->SetFillColor(52,2,19);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(135,4,"CLIENTE",1,0,'C',true);
	$pdf->Cell(1,4,"",0,0,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(0,0,0);
	
	//datos del cliente y datos del CFD
	$pdf->Cell(59,4,utf8_decode("Lugar de Expedición: Villahermosa, Tabasco"),"LRT",1,'L');
	$pdf->Cell(135,4,utf8_decode($razon_social),"LR",0,'L');
	$pdf->Cell(1,4,"",0,0,'C');
	$pdf->Cell(59,4,utf8_decode("Fecha y Hora: $fecha"),"LR",1,'L');
	$pdf->Cell(135,4,"RFC: ".$rfc,"LR",0,'L');
	$pdf->Cell(1,4,"",0,0,'C');
	$pdf->Cell(59,4,utf8_decode("Certificado: $num_certificado"),"LR",1,'L');
	$pdf->Cell(135,4,utf8_decode("Calle $calle $num_exterior $num_interior, Col. $colonia"),"LR",0,'L');
	$pdf->Cell(1,4,"",0,0,'C');
	$pdf->Cell(59,4,utf8_decode("Aprobación: $aprobacion   Año: $year_aprobacion"),"LR",1,'L');
	$pdf->Cell(135,4,utf8_decode("$localidad, $estado, $pais CP.$codigo_postal"),"LBR",0,'L');
	$pdf->Cell(1,4,"",0,0,'C');
	$pdf->Cell(59,4,"","LRB",1,'L');
    
	$pdf->Ln(5);
	$pdf->Cell(195,4,utf8_decode('Régimen Fiscal: '.$regimen_fiscal_emisor),'TLR',1,'L',false);
	$pdf->Cell(195,4,utf8_decode('Forma de  pago: '.$forma_pago),'LR',1,'L',false);
	$pdf->Cell(195,4,utf8_decode('Método de pago: '.trim($metodo_pago).' '.trim($referencia)),'RLB',1,'L',false);
			
	
	//detalle de conceptos
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(255,255,255);
     $pdf->Cell(75,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',true);
     $pdf->Cell(30,5,'UNIDAD',1,0,'C',true);
     $pdf->Cell(30,5,'PRECIO UNITARIO',1,0,'C',true);
     $pdf->Cell(30,5,"CANTIDAD",1,0,'C',true);
     $pdf->Cell(30,5,"IMPORTE",1,1,'C',true);
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(0,0,0);

	$descuento=0;
	$subtotal = $tot;
	$subtotal2 = $tot;
	$iva = 0;
	$iva*$subtotal2;
	$total = $tot;
	$subtotal2 + $iva;
 
	$arConc = explode(';',$cadConc);
	foreach($arConc as $i=>$value){
		$Item = explode('|',$arConc[$i]);
    		
		$posy1=$pdf->GetY();//posición antes de escribir concepto
		$pdf->SetFont('Arial','',8);
		$pdf->SetTextColor(0,0,0);
    		$pdf->MultiCell(105,4,"\n".utf8_decode($Item[1]),'L','L');
    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto
    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
		$pdf->SetY($posy1);
    		$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(30,$dif_y,"$".number_format($Item[2], 2, '.', ','),'L',0,'C');
    		$pdf->Cell(30,$dif_y,$Item[0],'L',0,'C');
    		$pdf->Cell(30,$dif_y,"$".number_format($Item[3], 2, '.', ','),'LR',1,'C');

    		$posy1=$pdf->GetY();//posición antes de escribir concepto
		$pdf->SetFont('Arial','',6);
		$pdf->SetTextColor(0,0,0);
    		$pdf->MultiCell(105,1,"\n".utf8_decode($Item[4]).' '.utf8_decode($Item[5]).' '.utf8_decode($Item[6]).' '.utf8_decode($Item[7]),'L','L');
    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto
    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    		$pdf->SetY($posy1);
    		$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(30,$dif_y,' ','L',0,'C');
    		$pdf->Cell(30,$dif_y,' ','L',0,'C');
    		$pdf->Cell(30,$dif_y,' ','LR',1,'C');
		
		//$subtotal  += number_format($Item[3]);
		//$subtotal2 += number_format($Item[3]);
		//$total     += number_format($Item[3]);

	}
	
	//cerrar tabla de conceptos
    	$h = 170-($pdf->GetY());
    	$pdf->Cell(105,$h," ",'LB',0,'C');
    	$pdf->Cell(30,$h," ",'LB',0,'C');
    	$pdf->Cell(30,$h," ",'LB',0,'C');
    	$pdf->Cell(30,$h," ",'LRB',1,'C');
            
    //subtotal y pagarè
	$pdf->SetFont('Arial','',6);
    	$pdf->Cell(42,4," ",0,0,'L');
    	$pdf->Cell(93,4,utf8_decode("Debo y pagaré a la orden de $razon_social_emisor "),0,0,'L');
	$pdf->SetFont('Arial','',8); $pdf->Cell(30,5,"Subtotal: ",0,0,'R');
    	$pdf->Cell(30,4,"$".number_format($subtotal, 2, '.', ','),0,1,'C');

    //descuento 
    	$pdf->SetFont('Arial','',6);
	$pdf->Cell(42,4," ",0,0,'L');
    	$pdf->Cell(93,4,utf8_decode("en cualquier plaza donde se requiera el pago de la cantidad consignada"),0,0,'L');
    	$pdf->SetFont('Arial','',8); $pdf->Cell(30,4,"Descuento: ",0,0,'R');
    	$pdf->Cell(30,4,"$".number_format($descuento, 2, '.', ','),0,1,'C');

    //subtotal 2
    	$pdf->SetFont('Arial','',6);
	$pdf->Cell(42,4," ",0,0,'L');
    	$pdf->Cell(93,4,utf8_decode("en éste título de credito, en un plazo no mayor a $dias_credito dias a partir del $fecha"),0,0,'L');
    	$pdf->SetFont('Arial','',8); $pdf->Cell(30,4,"Subtotal: ",0,0,'R');
    	$pdf->Cell(30,4,"$".number_format($subtotal2, 2, '.', ','),0,1,'C');

    //IVA y ejecutivo
    	$pdf->Cell(165,4,"IVA: ",0,0,'R');
    	$pdf->Cell(30,4,"$".number_format($iva, 2, '.', ','),0,1,'C');

    //cantidad con letra y total
    	$letras=utf8_decode(num2letras($total,0,0)." pesos  ");
	$total_cadena=$total;
	$total = "$".number_format($total, 2, '.', ',');
	$ultimo = substr (strrchr ($total, "."), 1 ); //recupero lo que este despues del decimal
	$letras = $letras." ".$ultimo."/100 M. N.";
			
	$pdf->SetFont('Arial','',6);
    	$pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(100,4,"______________________________________",0,0,'C');
			
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(30,4,"Total: ",0,0,'R');
    	$pdf->Cell(30,4,$total,0,1,'C');
			
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(100,4,"Firma",0,1,'C');
			
	//$pdf->Ln(3);$pdf->SetFont('Arial','B',8);
	//$pdf->Cell(35,4,"",0,0,'R');
	//$pdf->Cell(160,4,"Importe en letra: ".$letras,0,1,'C');
	$pdf->Ln(2);$pdf->SetFont('Arial','B',8);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->Cell(0,3,"Importe en letra: ".$letras,0,'L');
	$pdf->Ln(3);	

	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode("Cadena Original"),0,'L');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode($cadena),0,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode("Sello Digital"),0,'L');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode($sello),0,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode("Folio Fiscal"),0,'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode($folfis),0,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode('Este documento es una representación impresa de un CFDi'),0,'L');

	$pdf->Output($pdf_file,"F");  //guardo en disco
	$pdf->Output();//muestro el pdf



?>
