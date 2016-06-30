<?php

$result = array();

$data = $_POST['data'];
$xx = $data;
parse_str($data);

require_once("../oCentura.php");
$f = oCentura::getInstance();

$res = $f->setSaveData(0,$data,0,0,203);

if ($res=="OK"){
	$result['status'] = 'OK';
	$result['message'] = 'Archivos guardados con éxito.';
}else{
	$result['status'] = 'ERR';
	$result['message'] = $res;
}

echo json_encode($result);

?>