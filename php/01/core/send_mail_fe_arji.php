<?php

// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";

// Cabeceras adicionales
$cabeceras .= 'To: '.utf8_decode($tutor).' <$emailto>'. "\r\n";
$cabeceras .= utf8_decode('From: Colegio Arjí, A.C. <caja@arji.edu.mx>') . "\r\n";
$cabeceras .= 'Cc: manager@logydes.com.mx' . "\r\n";
$titulo = utf8_decode("Colegio Arjí, Factura Electrónica");

$body = "<html>
  <head>

    <meta http-equiv='content-type' content='text/html; charset=utf-8'>
    <title>Colegio Arjí, Factura Electrónica</title>
  </head>
  <body style='font-size: 10pt; font-family: Verdana,Geneva,sans-serif'
    bgcolor='#FFFFFF' text='#000000'>
     <p><img src='' alt=''></p>
    <p>Estimados $tutor, le enviamos la siguiente factura:</p>
    <p class='ecxmsonormal'>
      <ul style='list-style: circle'>
        <li>
          <a href='".$dir_upload.$pdf."' target='_blank'>$pdf</a>
        </li>
        <li>
          <a href='".$dir_upload.$xml."' target='_blank'>$xml</a>
        </li>
      </ul>
</p>
    <p>&nbsp;Para abrir el archivo requiere el
      programa Acrobat Reader</p>
    <p>que puede descargar aquí:&nbsp;<a
        href='http://get.adobe.com/es/reader/' target='_blank'><strong><em>Acrobat
            Reader</em></strong></a></p>
    <p>&nbsp;Para mayor información comuníquese a los teléfonos:&nbsp;<em><strong>351-02-50</strong> ext. <strong>507</strong></em></p>
  </body>
</html>";



mail("caja@arji.edu.mx",$titulo,$body,$cabeceras);

?>

