<?php

$result = array();

$data = $_POST['data'];
parse_str($data);

$res2 = saveFileIFE($_FILES['foto'],"Imagen",$result);

if ($res2['status'] != "OK"){
		unlink("../../upload/" . $res2['image']);
		unlink("../../upload/" . $res2['thumb']);
		echo json_encode($res2);
} else {

		require_once("../oCentura.php");
		$f = oCentura::getInstance();
		$msg = $mensaje." <br/> <br/> "." <img src='http://".$_SERVER["SERVER_NAME"]."/upload/".$res2['image']."' /> <br/> <br/>" ;	
    	$msg = emoticons($msg);
        $msg = addslashes($msg);
        //$data .="&msgphoto=".$msg ;	
		$res = $f->setSaveData(0,$data,0,0,109,$msg);
		$ret = explode(".",$res);
        if ($ret[0]=="OK"){
			$result['status'] = $res;
			$result['message'] = 'Archivos guardados con éxito';
        }else{
			$result['status'] = 'ERR';
			$result['message'] = $msg;
        }
        
		echo json_encode($result);
}


function saveFileIFE($file,$descripcion="",$result=array() ){
	//$result = array();
	if(isset($file)){
		if(!preg_match('/\.(jpe?g|gif)$/' , $file['name'])
			
		) {
			// || getimagesize($file['tmp_name']) === FALSE
			$result['status'] = 'ERR';
			$x = end(explode(".", $file['name']));
			$result['message'] = 'Formato incorrecto de archivo: '.$x;
		} else if($file['size'] > 1024000000) {
				$result['status'] = 'ERR';
				$result['message'] = 'Por favor, agrega un archivo mas pequeño! en '.$descripcion.' desde '.$file['name'].'!';
		} else if($file['error'] != 0 || !is_uploaded_file($file['tmp_name'])) {
				$result['status'] = 'ERR';
				$result['message'] = 'Error sin especificar! en '.$descripcion.' desde '.$file['name'].'!';
		} else {
			
			$name = $file['name'];
			$nameFile = md5($name).time();
			$ext = end(explode(".", $name));

			$nFle   = $nameFile.".".$ext;//$file['name']."_|_".$curp."_|_";
			$save_path = '../../upload/'.$nFle;

			$nFleTH   = $nameFile.".".$ext;//$file['name']."_|_".$curp."_|_";
			$thumb_path = '../../upload/'.$nFleTH;

			if( 
				! move_uploaded_file($file['tmp_name'] , $save_path)
				 
			  )
			{
				// OR
				$result['status'] = 'ERR';
				$result['message'] = 'Archivo guardado con éxito! en '.$descripcion.' de '.$nFle.'!' ;
			}

			else {

				move_uploaded_file($file['tmp_name'] , $save_path);
				
				$result['status'] = 'OK';
				$result['message'] = 'Archivo subido satisfactoriamente!';
				$result['url'] = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/'.$save_path;
				$result['image'] = $nFle;
				$result['thumb'] = $thumb_path;
			}
		}
		
	}else{
		$result['status'] = 'NOF';
		$result['message'] = 'Sin archivo! '.$file['name']  ;

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

	return true;
}


function emoticons($msg){
	$url = $_SERVER['HTTP_HOST']."/chating/images/emoticons/";
	$img = '<img src='.$url;

	$letters = array(';)');
	$fruit   = array($img."sonrisa1.png' />");

	$output  = str_replace($letters, $fruit, $msg);
	return $output;	
}




?>