<?php

$result = array();

$data = $_POST['data'];
parse_str($data);

$res0 = saveFileIFE($_FILES['file0'],"foto0",$result,$hideFoto0,$idavaluo);
$res1 = saveFileIFE($_FILES['file1'],"foto1",$result,$hideFoto1,$idavaluo);
$res2 = saveFileIFE($_FILES['file2'],"foto2",$result,$hideFoto2,$idavaluo);
$res3 = saveFileIFE($_FILES['file3'],"foto3",$result,$hideFoto3,$idavaluo);
$res4 = saveFileIFE($_FILES['file4'],"foto4",$result,$hideFoto4,$idavaluo);
$res5 = saveFileIFE($_FILES['file5'],"foto5",$result,$hideFoto5,$idavaluo);
$res6 = saveFileIFE($_FILES['file6'],"plano0",$result,$hidePlano0,$idavaluo);

if ($res0['status'] != "OK" ){
		unlink("../../../upload/" . $res0['image']);
		unlink("../../../upload/" . $res0['thumb']);
		echo json_encode($res0);

}else if ($res1['status'] != "OK" ){
		unlink("../../../upload/" . $res1['image']);
		unlink("../../../upload/" . $res1['thumb']);
		echo json_encode($res1);
}else if ($res2['status'] != "OK" ){
		unlink("../../../upload/" . $res2['image']);
		unlink("../../../upload/" . $res2['thumb']);
		echo json_encode($res2);
}else if ($res3['status'] != "OK" ){
		unlink("../../../upload/" . $res3['image']);
		unlink("../../../upload/" . $res3['thumb']);
		echo json_encode($res3);
}else if ($res4['status'] != "OK" ){
		unlink("../../../upload/" . $res4['image']);
		unlink("../../../upload/" . $res4['thumb']);
		echo json_encode($res4);
}else if ($res5['status'] != "OK" ){
		unlink("../../../upload/" . $res5['image']);
		unlink("../../../upload/" . $res5['thumb']);
		echo json_encode($res5);
}else if ($res6['status'] != "OK" ){
		unlink("../../../upload/" . $res6['image']);
		unlink("../../../upload/" . $res6['thumb']);
		echo json_encode($res6);
} else {

		require_once("../oCentura.php");
		$f = oCentura::getInstance();

        $data .="&foto0=".$res0['image'].
        		"&foto1=".$res1['image'].
        		"&foto2=".$res2['image'].
        		"&foto3=".$res3['image'].
        		"&foto4=".$res4['image'].
        		"&foto5=".$res5['image'].
        		"&plano0=".$res6['image'];	
		$res = $f->setSaveData(26,$data,0,0,1);

        if ($res=="OK"){
			$result['status'] = 'OK';
			$result['message'] = 'Archivos guardados con éxito';
        }else{
			$result['status'] = 'ERR';
			$result['message'] = $res;
			unlink("../../../uploads/" . $res0['image']);
			unlink("../../../uploads/" . $res1['image']);
			unlink("../../../uploads/" . $res2['image']);
			unlink("../../../uploads/" . $res3['image']);
			unlink("../../../uploads/" . $res4['image']);
			unlink("../../../uploads/" . $res5['image']);
			unlink("../../../uploads/" . $res6['image']);
			

			/*
			unlink("../../../uploads/" . $res1['image']);
			unlink("../../../uploads/" . $res1['thumb']);
			unlink("../../../uploads/" . $res2['image']);
			unlink("../../../uploads/" . $res2['thumb']);
			unlink("../../../uploads/" . $res3['image']);
			unlink("../../../uploads/" . $res3['thumb']);
			*/
        }
        
		echo json_encode($result);

}


function saveFileIFE($file,$descripcion="",$result=array(),$objeto,$idavaluo ){
	//$result = array();
	if(isset($file)){
		if(!preg_match('/\.(jpe?g|gif)$/' , $file['name'])
			
		) {
			// || getimagesize($file['tmp_name']) === FALSE
			$result['status'] = 'ERR';
			$x = end(explode(".", $file['name']));
			$result['message'] = 'Formato incorrecto de archivo: '.$x;
		} else if($file['size'] > 10240000000) {
				$result['status'] = 'ERR';
				$result['message'] = 'Por favor, agrega un archivo mas pequeño! en '.$descripcion.' desde '.$file['name'].'!';
		} else if($file['error'] != 0 || !is_uploaded_file($file['tmp_name'])) {
				$result['status'] = 'ERR';
				$result['message'] = 'Error sin especificar! en '.$descripcion.' desde '.$file['name'].'!';
		} else {
			
			$name = $file['name'];
			//$nameFile = md5($name).time();
			$nameFile = $descripcion.'-'.$idavaluo;
			$ext = end(explode(".", $name));
			$nFle   = $nameFile.".".strtolower($ext);//$file['name']."_|_".$curp."_|_";

			if ($name!=$objeto && $name!=""){
				$save_path = '../../../upload/'.$nFle;

				if ( !strpos($nameFile, "-big.") ){
					$nFleTH   = $nameFile."-big.".strtolower($ext);
				}else{
					$nFleTH   = $nameFile.$ext;
				}	
				$thumb_path = '../../../upload/'.$nFleTH;

				if( 
					! move_uploaded_file($file['tmp_name'] , $save_path)
					 
				  )
				{
					// OR
					//resize($save_path, $thumb_path, 150,150);
					$result['status'] = 'ERR';
					$result['message'] = 'No se ha subido el archivo '.$nFle.'!' ;
				}

				else {
					
					resize($save_path, $thumb_path, 640,460);
					
					//resize($save_path, $thumb_path_36, 36,36);
					//resize($save_path, $thumb_path_48, 48,48);

					unlink($save_path);

					
					$result['status'] = 'OK';
					$result['message'] = 'Archivo subido satisfactoriamente!';
					$result['url'] = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/'.$save_path;
					$result['image'] = $nFle;
					$result['thumb'] = $nThumb;
				}
			}else{
				$result['status'] = 'OK';
				$result['message'] = 'Archivo subido satisfactoriamente!';
				$result['image'] = $nFle;
				$result['thumb'] = '';
			}
		}
		
	}else{
//		$result['status'] = 'NOF';
//		$result['message'] = 'Sin archivo! '.$file['name']  ;
				$result['status'] = 'OK';
				$result['message'] = 'Archivo subido satisfactoriamente!';
				$result['image'] = $objeto;
				$result['thumb'] = '';

	}
	
	return $result;

}


function resize($in_file, $out_file, $new_width, $new_height=FALSE)
{
	$image = null;
	$extension = strtolower(preg_replace('/^.*\./', '', $in_file));
	switch($extension)
	{
		case 'jpg':
		case 'jpeg':
			$image = imagecreatefromjpeg($in_file);
			break;
		case 'png':
			$image = imagecreatefrompng($in_file);
			break;
		case 'gif':
			$image = imagecreatefromgif($in_file);
			break;
	}
	if(!$image || !is_resource($image)) return false;


	$width = imagesx($image);
	$height = imagesy($image);
	if($new_height === FALSE)
	{
		$new_height = (int)(($height * $new_width) / $width);
	}

	
	$new_image = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	$ret = imagejpeg($new_image, $out_file, 80);

	imagedestroy($new_image);
	imagedestroy($image);

	return $ret;
}



?>