<?php 
$cadena_xml='<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\r\n";
//$cadena_xml.='<cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:iedu="http://www.sat.gob.mx/iedu" version="3.2" serie="'.$serie.'" folio="'.$folio.'" fecha="'.$fecha.'" sello="'.$sello.'" formaDePago="'.$forma_pago.'" noCertificado="'.$num_certificado.'"  certificado="'.$certificado_texto.'" subTotal="'.number_format($subtotal2, 2, '.','').'" TipoCambio="1.00" Moneda="MXN" total="'.number_format($total_cadena, 2, '.','').'" tipoDeComprobante="ingreso" metodoDePago="'.$metodo_pago.'" LugarExpedicion="'.$lugar_expedicion.'" NumCtaPago="'.$referencia.'" FolioFiscalOrig="" SerieFolioFiscalOrig="" FechaFolioFiscalOrig="'.$fecha.'" MontoFolioFiscalOrig="'.number_format($total_cadena, 2, '.','').'" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3  http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/iedu http://www.sat.gob.mx/sitio_internet/cfd/iedu/iedu.xsd" xmlns:cfdi="http://www.sat.gob.mx/cfd/3">'."\r\n";
$cadena_xml.='<cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:iedu="http://www.sat.gob.mx/iedu" version="3.2" serie="'.$serie.'" folio="'.$folio.'" fecha="'.$fecha.'" sello="'.$sello.'" formaDePago="'.$forma_pago.'" noCertificado="'.$num_certificado.'"  certificado="'.$certificado_texto.'" subTotal="'.number_format($subtotal2, 2, '.','').'" TipoCambio="1.00" Moneda="MXN" total="'.number_format($total_cadena, 2, '.','').'" tipoDeComprobante="'.$tipo_cfdi.'" metodoDePago="'.$metodo_pago.'" LugarExpedicion="'.$lugar_expedicion.'" FechaFolioFiscalOrig="'.$fecha.'" MontoFolioFiscalOrig="'.number_format($total_cadena, 2, '.','').'" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3  http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/iedu http://www.sat.gob.mx/sitio_internet/cfd/iedu/iedu.xsd" xmlns:cfdi="http://www.sat.gob.mx/cfd/3">'."\r\n";
  
$cadena_xml.='<cfdi:Emisor rfc="'.$rfc_emisor.'" nombre="'.$razon_social_emisor.'">'."\r\n";
$cadena_xml.='<cfdi:DomicilioFiscal calle="'.$calle_emisor.'" noExterior="'.$num_exterior_emisor.'" noInterior="'.$num_interior_emisor.'" colonia="'.$colonia_emisor.'" municipio="'.$localidad_emisor.'" estado="'.$estado_emisor.'" pais="'.$pais_emisor.'" codigoPostal="'.$codigo_postal_emisor.'" />'."\r\n";
$cadena_xml.='<cfdi:ExpedidoEn calle="'.$calle_emisor.'" noExterior="'.$num_exterior_emisor.'" noInterior="'.$num_interior_emisor.'" colonia="'.$colonia_emisor.'" municipio="'.$localidad_emisor.'" estado="'.$estado_emisor.'" pais="'.$pais_emisor.'" codigoPostal="'.$codigo_postal_emisor.'" />'."\r\n";
$cadena_xml.='<cfdi:RegimenFiscal Regimen="'.$regimen_fiscal_emisor.'" />'."\r\n";
$cadena_xml.='</cfdi:Emisor>'."\r\n";
  
  $numInter = $num_interior!=''?'"  noInterior="'.$num_interior.'"':'';
 
  $cadena_xml.='<cfdi:Receptor rfc="'.$rfc.'" nombre="'.$razon_social.'">'."\r\n";
    $cadena_xml.='<cfdi:Domicilio calle="'.$calle.'" noExterior="'.$num_exterior.$numInter.' colonia="'.$colonia.'" municipio="'.$localidad.'" estado="'.$estado.'" pais="'.$pais.'" codigoPostal="'.$codigo_postal.'" />'."\r\n";
  $cadena_xml.='</cfdi:Receptor>'."\r\n";
  
  $cadena_xml.='<cfdi:Conceptos>'."\r\n"; 

$arConc = explode(';',$cadConc);
foreach($arConc as $i=>$value){
	$Item = explode('|',$arConc[$i]);
	if (intval($Item[0])>0) {
    $cadena_xml.='<cfdi:Concepto cantidad="'.$Item[0].'" unidad="Pza" descripcion="'.$Item[1].'" valorUnitario="'.$Item[3].'" importe="'.$Item[3].'">'."\r\n";
      $cadena_xml.='<cfdi:ComplementoConcepto>'."\r\n";
        $cadena_xml.='<iedu:instEducativas version="1.0" nombreAlumno="'.$Item[4].'" CURP="'.$Item[5].'" nivelEducativo="'.$Item[7].'" autRVOE="'.$Item[6].'" rfcPago="'.$rfc.'" />'."\r\n";
      $cadena_xml.='</cfdi:ComplementoConcepto>'."\r\n";
    $cadena_xml.='</cfdi:Concepto>'."\r\n";
	}
    
}
  $cadena_xml.='</cfdi:Conceptos>'."\r\n";
  
  $cadena_xml.='<cfdi:Impuestos totalImpuestosTrasladados="0.00" totalImpuestosRetenidos="0.00" />'."\r\n";
/*
    $cadena_xml.='<cfdi:Traslados>'."\r\n";
      $cadena_xml.='<cfdi:Traslado impuesto="IVA" tasa="16.00" importe="16.00" />'."\r\n";
    $cadena_xml.='</cfdi:Traslados>'."\r\n";
  $cadena_xml.='</cfdi:Impuestos>'."\r\n";
*/
$cadena_xml.='</cfdi:Comprobante>	'."\r\n";

/*
        <xs:simpleType name="tRFC">
                <xs:annotation>
                        <xs:documentation>Tipo definido para la expresión de RFC's de contribuyentes. Cabe hacer la mención que debido a las reglas definidas por el estándar XML en el caso de que un RFC dado incluya un caracter ampersand, dicho caracter deberá ser expresado mediante la secuencia de escape especificado como parte del estándar. En la definición del tipo se expresa una longitud mínima y máxima, sin embargo la longitud puede ser redefinida como una extensión según se determina el uso particular</xs:documentation>
                </xs:annotation>
                <xs:restriction base="xs:string">
                        <xs:minLength value="12"/>
                        <xs:maxLength value="13"/>
                        <xs:whiteSpace value="collapse"/>
                        <xs:pattern value="[A-Z,Ñ,&amp;]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?"/>
                </xs:restriction>
        </xs:simpleType>
        <xs:simpleType name="tCURP">
                <xs:annotation>
                        <xs:documentation>Tipo definido para la expresión de una CURP. </xs:documentation>
                </xs:annotation>
                <xs:restriction base="xs:string">
                        <xs:whiteSpace value="collapse"/>
                        <xs:length value="18"/>
                        <xs:pattern value="[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]"/>
                </xs:restriction>
        </xs:simpleType>
*/

?>
