<?php

$result = array();

$data = $_POST['data'];
parse_str($data);

$res2 = saveFileIFE($_FILES['foto'],"Imagen",$result,$iduser);

if ($res2['status'] != "OK"){
		unlink("../../../upload/" . $res2['image']);
		unlink("../../../upload/" . $res2['thumb']);
		echo json_encode($res2);
} else {

		require_once("../oCentura.php");
		$f = oCentura::getInstance();

        $data .="&foto=".$res2['image'];	
		$res = $f->setSaveData(0,$data,0,0,204);

        if ($res=="OK"){
			$result['status'] = 'OK';
			$result['message'] = 'Archivos guardados con éxito';
        }else{
			$result['status'] = 'ERR';
			$result['message'] = $res;
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




function saveFileIFE($file,$descripcion="",$result=array(),$iduser ){
	//$result = array();
	if(isset($file)){
		if(!preg_match('/\.(jpe?g|gif|png|JPE?G|GIF|PNG)$/' , $file['name'])
			
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
			$nameFile = $iduser;//md5($name).time();
			$ext = end(explode(".", $name));
			$nFle   = $nameFile.".".$ext;//$file['name']."_|_".$curp."_|_";

			$save_path = '../../../upload/'.$nFle;

			$nFleTH   = $nameFile."-big.".$ext;//$file['name']."_|_".$curp."_|_";
			$thumb_path = '../../../upload/'.$nFleTH;

			$nFle48        = $nameFile."-48.".$ext;//$file['name']."_|_".$curp."_|_";
			$thumb_path_48 = '../../../upload/'.$nFle48;

			$nFle36        = $nameFile."-36.".$ext;//$file['name']."_|_".$curp."_|_";
			$thumb_path_36 = '../../../upload/'.$nFle36;

			if( 
				! move_uploaded_file($file['tmp_name'] , $save_path)
				 
			  )
			{
				// OR
				//resize($save_path, $thumb_path, 150,150);
				$result['status'] = 'ERR';
				$result['message'] = 'Archivo guardado con éxito! en '.$descripcion.' de '.$nFle.'!' ;
			}

			else {
				
				resize($save_path, $thumb_path, 180,200,1);
				resize($save_path, $thumb_path_36, 36,36);
				resize($save_path, $thumb_path_48, 48,48);

				unlink($save_path);

				
				$result['status'] = 'OK';
				$result['message'] = 'Archivo subido satisfactoriamente!';
				$result['url'] = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/'.$save_path;
				$result['image'] = $nFle;
				$result['thumb'] = $nThumb;
			}
		}
		
	}else{
		$result['status'] = 'NOF';
		$result['message'] = 'Sin archivo! '.$file['name']  ;

	}
	
	return $result;

}


function resize($in_file, $out_file, $new_width, $new_height=FALSE, $param=0)
{
	$image = null;
	$extension = strtolower(preg_replace('/^.*\./', '', $in_file));
	switch($extension)
	{
		case 'jpg':
		case 'jpeg':
		case 'JPG':
		case 'JPEG':
			$image = imagecreatefromjpeg($in_file);
			break;
		case 'png':
		case 'PNG':
			$image = imagecreatefrompng($in_file);
			break;
		case 'gif':
		case 'GIF':
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

	if ($param == 1) {
		if ($width>$height){
			$new_width = $width;
			$new_height = $height;
		}else if ($width<$height){
			$new_width = $width;
			$new_height = $height;
		}else{
			$new_width = $width;
			$new_height = $height;
		}
	}

	
	$new_image = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	$ret = imagejpeg($new_image, $out_file, 80);

	imagedestroy($new_image);
	imagedestroy($image);

	return $ret;
}



?>