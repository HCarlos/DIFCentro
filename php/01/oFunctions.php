<?php
class oFunctions {
	 
	 private static $instancia;
	 public $IdUser;
	 public $User;
	 public $Pass;
	 public $Nav;
	 public $URL;
	 public $defaultMail;
	 public $arrMes3;
	 public $dias;
	 public $fromApp;
	 private $ccountry;
	 private $pfeed;
	 
	 private function __construct(){ 
	          $this->arrMes3     = array(' ','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
			$this->aMeses      = array(' ','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre',
								  'Octubre','Noviembre','Diciembre');
			$this->dias        = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');					  
			$this->ccountry = "150920";
			$this->pfeed = "http://weather.yahooapis.com/forecastrss?u=c&w=";
	 		$this->Nav         = "Ninguno";
	 		$this->Nav_Long    = $_SERVER["HTTP_USER_AGENT"];
			$this->IdUser       = 0;
			$this->User        = "";
			$this->Pass        = "";
	 		$this->defaultMail = "gpmedicionweb@gmail.com";
	 		$this->URL         = "http://siicopre.tecnointel.mx/";
			$this->IP          = $_SERVER["REMOTE_ADDR"];
			$this->Host        = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$this->fromApp     = 0;
			
	 		//$this->URL         = "http://localhost/";
			
			if (isset($_COOKIE['__chLib32'])){
				$x = explode('.',$_COOKIE['__chLib32']);
				$this->User = $x[0];
				$this->Pass = $x[1];
				$this->IdUser = (int)$x[2];
				$this->fromApp = (int)$x[6];

			}
		

	 }
	 
	 private function time2minuts($time) {
		    $minuts = 0;
		    $atime = explode(" ", $time);
		    if (strtolower($atime[1]) == "pm") $minuts = 12*60;
		    $ttime = explode(":", $atime[0]);
	    	    if ($ttime[0] == "12") $ttime[0] = "0";
		    $minuts += (int)$ttime[0]*60 + (int)$ttime[1];
		    return $minuts;
	}


	public static function getInstance(){
				if (  !self::$instancia instanceof self){
					  self::$instancia = new self;
				}
				return self::$instancia;
	 }
	 
	 


	public function getURL(){
		return $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	 // Fecha Actual Larga
	public function getDateLong($fecha,$param=0){
		           $Res = "";
				  if ($fecha)
					{
						  $f=split("-",$fecha);
						  $nummes=(int)$f[1];
						  $numdia=(int)$f[2];
						  $numanio=(int)$f[0];
			
						  $mes1=$this->aMeses;
						  //$mes1=split("-",$mes1);
						  
						  $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
						  $x =$dias[date("w", mktime(0, 0, 0, $nummes, $numdia, $numanio))];
						  
						  $Res="$x $f[2] de $mes1[$nummes] de $f[0]";
			
				  } 		
					  
				  //$o = oBase::getInstance();
				  
				  return $Res; // $o->clearChar($Res,0);  
	
	}

	// Obtiene que Navegador utiliza el cliente
	public function getNavigator($user_agent,$type) {  
				 $navegadores = array(  
								 'RIM' => 'RIM Tablet OS',  
								 'Android' => 'Android',  
								 'iPhone' => 'iPhone',  
								 'iPad' => 'iPad',  
								 'iPod' => 'iPod',  
								 'Opera' => 'Opera',  
								 'Galeon' => 'Galeon',  
								 'Chrome' => 'Chrome',  
								 'MyIE'=>'MyIE',  
								 'Safari'=>'Safari',  
								 'Lynx' => 'Lynx',  
								 'Konqueror'=>'Konqueror',  
								 'Camino'=>'Camino',  
								 'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',  
								 'Mozilla Firefox'=> '(Firebird)|(Firefox)',  
								 'IE10' => '(MSIE 10\.[0-9]+)',  
								 'IE9' => '(MSIE 9\.[0-9]+)',  
								 'IE8' => '(MSIE 8\.[0-9]+)',  
								 'IE7' => '(MSIE 7\.[0-9]+)',  
								 'IE6' => '(MSIE 6\.[0-9]+)',  
								 'IE5' => '(MSIE 5\.[0-9]+)',  
								 'IE4' => '(MSIE 4\.[0-9]+)',  
		            );  
			
				  foreach ($navegadores as $navegador=>$pattern){  
					   if (eregi($pattern, $user_agent))  {
						   $this->Nav = $navegador; 
						   return $this->Nav;
					   }
				   }  
		   
		  return "Desconocido";  
	}

	public function my_explode($delim, $str, $lim = 1)
	{
				 if ($lim > -2) return explode($delim, $str, abs($lim));
				
				 $lim = -$lim;
				 $out = explode($delim, $str);
				 if ($lim >= count($out)) return $out;
				
				 $out = array_chunk($out, count($out) - $lim + 1);
				
				 return array_merge(array(implode($delim, $out[0])), $out[1]);
	}
     
	// Devuelve la fecha de publicación de una Nota
	public function getBFecha($fecha="0000-00-00",$hora="00:00:00",$tipo=0){
		  $hoy = date("Y-m-d",time());
		  $f = explode("-",$hoy); 
		  $aFecha = explode("-",$fecha);  // (int)$f1;
		  $aHora = explode(":",$hora);  // (int)$f1;
		  $Origen = "";
		  $ano  = date('Y');
		  $anoO = $aFecha[0];
		  switch ($tipo){
			     case 0:
					  $nDia = (int)$f[2] - (int)$aFecha[2];
					  if ($nDia==0 && $ano == $anoO){
						 $Origen = $aHora[0]!="00"?"Hoy a las ".$aHora[0]." : ".$aHora[1]." hrs":"Hoy";
					  }else if ($nDia==1 && $ano == $anoO) {
							  $Origen = $aHora[0]!="00"?"Ayer a las ".$aHora[0]." : ".$aHora[1]." hrs":"Ayer";
						   }else{
							   $aMeses = $this->aMeses;
							   $Origen = $aFecha[2]." de ".$aMeses[(int)$aFecha[1]]." de ".$aFecha[0];
							   $Origen .= $aHora[0]!="00"?" :: ".$aHora[0].":".$aHora[1]." hrs":"";
					  }
					  break;
			     case 1:
					  $nDia = (int)$f[2] - (int)$aFecha[2];
					  if ($nDia==0 && $ano == $anoO){
						 $Origen = $aHora[0]." : ".$aHora[1];
					  }else if ($nDia==1 && $ano == $anoO) {
							  $Origen = "Ayer ".$aHora[0]." : ".$aHora[1];
						   }else{
							   $aFec = $this->arrMes3;
							   $Origen = strtoupper($aFec[(int)$aFecha[1]])." ".$aFecha[2].", ".$aHora[0].":".$aHora[1];
					  }
					  break;
			     case 2:
					  $nDia = (int)$f[2] - (int)$aFecha[2];
					  if ($nDia==0 && $ano == $anoO){
						 $Origen = $aHora[0]!="00"?$aHora[0]." : ".$aHora[1]." hrs":"Hoy";
					  }else if ($nDia==1 && $ano == $anoO) {
							  $Origen = $aHora[0]!="00"?"Ayer a las ".$aHora[0]." : ".$aHora[1]." hrs":"Ayer";
						   }else{
							   $aFec = $this->arrMes3;
							   $Origen = strtoupper($aFec[(int)$aFecha[1]])." ".$aFecha[2].", ".$aFecha[0]." | ".$aHora[0].":".$aHora[1];
					  }
					  break;
			     case 3:
					  $nDia = (int)$f[2] - (int)$aFecha[2];
					  if ($nDia==0 && $ano == $anoO){
						 $Origen = "Hoy";
					  }else if ($nDia==1 && $ano == $anoO) {
							  $Origen = "Ayer";
						   }else{
							   $aFec = $this->arrMes3;
							   $ano = $ano!=$aFecha[0]?", ".$aFecha[0]:"";
							   $Origen = strtoupper($aFec[(int)$aFecha[1]])." ".$aFecha[2].$ano;
					  }
					  break;
			     case 4:
					 $Origen = $aHora[0]." : ".$aHora[1];
					 break;
				case 5:
					  $aMeses = $this->aMeses;
					  $Origen = $this->dias[date('w')]." ".$aFecha[2]." de ".$aMeses[(int)$aFecha[1]]." de ".$aFecha[0];
					  break;	 
			     case 6:
					  $nDia = (int)$f[2] - (int)$aFecha[2];
					  if ($nDia==0 && $ano == $anoO){
						 $Origen = $aHora[0]!="00"?"Hoy ".$aHora[0]." : ".$aHora[1]." hrs":"Hoy";
					  }else if ($nDia==1 && $ano == $anoO) {
							  $Origen = $aHora[0]!="00"?"Ayer ".$aHora[0]." : ".$aHora[1]." hrs":"Ayer";
						   }else{
							   $aMeses = $this->aMeses;
							   //$Origen = $aFecha[2]." de ".$aMeses[(int)$aFecha[1]]." de ".$aFecha[0];
							   //$Origen .= $aHora[0]!="00"?" :: ".$aHora[0].":".$aHora[1]." hrs":"";
							   $Origen = $aFecha[2].".".$aMeses[(int)$aFecha[1]].".".$aFecha[0];
					  }
					  break;
		  }
		  return $Origen;
	}

     // Devuelve la fecha en formato 26.Nov.2010
	public function getWith3LetterMonth($fecha){
		    $aFecha = $this->arrMes3;
		    $fec = explode("/",$fecha);
		    return $fec[0].".".$aFecha[(int)$fec[1]].".".$fec[2];
	}

     // Devuelve la fecha en formato 26.Nov.2010
	public function getWith3LetterMonthH($fecha){
		    $aFecha = $this->arrMes3;
		    $fec = explode("-",$fecha);
		    return $fec[2].".".$aFecha[(int)$fec[1]].".".$fec[0];
	}

     // Devuelve la fecha en formato 26.Nov.2010
	public function getWith3LetterMonthD($fecha){
		    $aFecha = $this->arrMes3;
		    $fec = explode("-",$fecha);
		    return $fec[2]."-".$aFecha[(int)$fec[1]]."-".$fec[0];
	}

	// Ayuda a obtener el URL de Conection
	public function curl_get_result($url) {
		  $ch = curl_init();
		  $timeout = 5;
		  curl_setopt($ch,CURLOPT_URL,$url);
		  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		  $data = curl_exec($ch);
		  curl_close($ch);
		  return $data;
	} 
     
	public function replace_accents($str) {
		  $search = array("&Aacute;",
					   "&Eacute;",
					   "&Iacute;",
					   "&Oacute;",
					   "&Uacute;",
					   "&aacute;",
					   "&eacute;",
					   "&iacute;",
					   "&oacute;",
					   "&uacute;",
					   "&Ntilde;",
					   "&ntilde;",
					   "&quot;",
					   "Â",
					   "&apos;",
					   "&Uuml;",
					   "&uuml;",
					   "&lt;",
					   "&gt;",
					   "&quot;",
					   "Â",
					   "&apos;",
					   " ",
					   ":",
					   ";",
					   ",",
					   ".",
					   "%",
					   "&iquest",						 
					   "?",
					   "&amp;",
					   '"',
					   'br-/-',
					   "&iexcl;",
					   "-a-",
					   "-le-",
					   "-el-",
					   "-al-",
					   "-la-",
					   "-las-",
					   "-Las-",
					   "-los-",
					   "-ella-",
					   "-son-",
					   "-ellos-",
					   "-del-",
					   "-para-",
					   "-que-",
					   "-con-",
					   "-sin-",
					   "-su-",
					   "-de-",
					   "-en-",
					   "-es-",
					   "-no-",
					   "-si-",
					   "-asi-",
					   "-y-",
					   "-un-",
					   "-se-",
					   "-por-",
					   "A-",
					   "Se-",
					   "Los-",
					   "La-",
					   "El-"
					  );
		 $replace = array("A",
					   "E",
					   "I",
					   "O",
					   "U",
					   "a",
					   "e",
					   "i",
					   "o",
					   "u",
					   "N",
					   "n",
					   '',
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "-",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "'",
					   "",
					   "",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "-",
					   "",
					   "",
					   "",
					   "",
					   ""
	  );
	  
	  //$str =  strtolower($str);
	  $str = htmlentities($str, ENT_COMPAT, "UTF-8");
	  $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/','$1',$str);
	  for($i=0;$i<3;++$i){$str = str_replace($search,$replace,$str);}
	  
	  return strtolower($str);
	}
	
     // Devuelve los archivos enlazados de PHP
	public function getFilePHP($param=0){
		  $myURL = $this->URL=="http://localhost/"?"http://localhost/":"http://www.diariopresente.com.mx/";
		  $file = "";
		  switch ($param){
			    case 0:
					$file = $myURL."php/02/getData.php";
					break;
			    case 1:
					//$file = "php/02/resources/captcha.php?url=".$this->getFilePHP(2)."&rnd=".rand();
					$file = $myURL."php/02/resources/captcha.php?url=".$this->getFilePHP(2);
					break;
			    case 2:
					$file = $myURL."images/02/web/bgcaptcha.gif";
					break;
			    case 3:
					$file = $myURL."php/feed.php";
					break;
		  }
		  return $file;
	}

	public function getDataURLNota($idnota=0,$titulo="",$seccion=""){
		  $x = str_replace("&lt;br /&gt;","",$titulo);
		  $x = str_replace("&lt;strong&gt;","",$x);
		  $x = str_replace("&lt;/strong&gt;","",$x);
		  $titulo = $x;
	       $retorna="";
		  $tit_hidde =  $this->replace_accents($titulo);
		  $seccion_u =  $this->replace_accents($seccion);
		  
		  //$retorna = "http://www.diariopresente.com.mx/n".$idnota."-".$tit_hidde."/";
		  //$retorna = "http://www.diariopresente.com.mx/n".$idnota.".html";
		  //$retorna = "http://www.diariopresente.com.mx/i/".$seccion_u."/".$idnota."/".$tit_hidde."/";
		  $retorna = "http://www.diariopresente.com.mx/section/".$seccion_u."/".$idnota."/".$tit_hidde."/";
	       return $retorna;
		 
	}

	public function getDataURLOther($Q,$path="",$id=0,$title="",$tipo=0,$cad=""){
	       $retorna="";
		  $path =  $this->replace_accents($path);
			$x = str_replace("&lt;br /&gt;","",$title);
			$x = str_replace("&lt;strong&gt;","",$x);
			$x = str_replace("&lt;/strong&gt;","",$x);
		  $title =  $this->replace_accents($x);
		  //$retorna = "http://www.diariopresente.com.mx/section/".$seccion_u."/".$idnota."/".$tit_hidde."/";
		  switch($tipo){
			    case 0:
		               // Para las Galerías 
		  			//$retorna = $Q->URL."f/".$id.$cad."/".$title."/";
		  			$retorna = $Q->URL.$path."/".$id.$cad."/".$title."/";
					break;
			    case 1:
		               // Para las Secciones 
		  			$retorna = $Q->URL."seccion/".$title."/";
					break;
			    case 2:
		               // Para los Videos 
		  			$retorna = $Q->URL.$path."/".$id."/".$title."/";
		  			//$retorna = $Q->URL.$path."/".$id.$cad."/".$title."/";
					break;
			    case 3:
		               // Para las columnas 
					$cad = $this->replace_accents($cad);
		  			$retorna = $Q->URL.$path."/".$id."/".$cad."/".$title."/";
					break;
			    case 4:
		               // Para las columna especifica 
					$cad = $this->replace_accents($cad).".".$id.".".$title;
		  			$retorna = $Q->URL.$path."/".$cad."/";
					break;
			    case 5:
		               // Para las Secciones 
		  			$retorna = $Q->URL.$title."/";
					break;
		  }
	       return $retorna;
		 
	}

	public function getDataURLNota03($idnota=0,$titulo="",$seccion=""){
		  $x = str_replace("&lt;br /&gt;","",$titulo);
		  $x = str_replace("&lt;strong&gt;","",$x);
		  $x = str_replace("&lt;/strong&gt;","",$x);
		  $titulo = $x;
	       $retorna="";
		  $tit_hidde =  $this->replace_accents($titulo);
		  $seccion_u =  $this->replace_accents($seccion);
		  
		  //$retorna = "http://www.diariopresente.com.mx/n".$idnota."-".$tit_hidde."/";
		  //$retorna = "http://www.diariopresente.com.mx/n".$idnota.".html";
		  $retorna = "http://www.diariopresente.com.mx/section/".$seccion_u."/".$idnota."/".$tit_hidde."/";
		  //$retorna = "http://www.diariopresente.com.mx/nota/".$seccion_u."/".$idnota."/".$tit_hidde."/";
	       return $retorna;
		 
	}
	
 
	 public function AddDec($val,$nVal,$ini,$fin,$tipo){
		   switch($tipo){
			     case 0:
					 $val = ($val-$nVal) < $ini? $fin : $val - $nVal;
					 break;
			     case 1:
					 $val = ($val+$nVal) > $fin? $ini : $val + $nVal;
					 break;
		   }
		   return $val;
	 }
	 

	public function getFechaHeme($dia=0,$mes=0,$ano=0){
    		  $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
		  
		  $Mes = "";
		  $Mesc = "";
		  $cDia = str_pad($dia, 2, "0", STR_PAD_LEFT);
		  $cMes = str_pad($mes+1, 2, "0", STR_PAD_LEFT);
		  $cAno = "20".str_pad(($ano-2000), 2, "0", STR_PAD_LEFT);
		  $ccAno = str_pad(($ano-2000), 2, "0", STR_PAD_LEFT);
		  
		  
            return $dia."_".($mes-1)."_".$ano."_".date("w", mktime(0, 0, 0, $mes, $dia, $ano));
		  
	}
	
	// Evualua una hora en un intervalo de tiempo
	public function evalTimeIncRangeTimes($ho1="00:00",$ho2="00:00",$ho3="00:00"){
		  $h1 = strtotime($ho1);
		  $h2 = strtotime($ho2);
		  $h3 = strtotime($ho3);
		  $bRet=false;
		  if ( $h3 >= $h1 && $h3 <= $h2) {
		      $bRet = true;
		  }  		
		  return $bRet;
	}

	public function redimencionar($file) {
	// Función que REEMPLAZA una imágen JPEG por otra con diferenetes dimenciones...
	// Se da por echo la imágen existe y es una imágen JPEG (no se hacen validaciones)
	
	    $imagen = ImageCreateFromJPEG($file);
		   unlink($file); // BORRAMOS el archivo original
	    $width  = imagesx($imagen);
	    $height = imagesy($imagen);
	    
	    $nueva_anchura  = 200; // Define aquí el ancho requerdo
	    $nueva_altura = ($nueva_anchura * $height) / $width ;// Para un alto proporcinal (RECOMENDADO) ó ingresa directamente el alto requerido.
	    
		   if (function_exists("imagecreatetruecolor")) {
			 $calidad = ImageCreateTrueColor($nueva_anchura, $nueva_altura);
		   } else $calidad = ImageCreate($nueva_anchura, $nueva_altura);
	
	    ImageCopyResized($calidad, $imagen, 0, 0, 0, 0, $nueva_anchura, $nueva_altura, $width, $height);
	    ImageJPEG($calidad, $file, 100);
	    imagedestroy($imagen);
	    return true;
	// Forma de uso:
	// redimencionar(/ruta/archivo.jpg) 
	}

	public function IsIE(){
		  $arIE = array("IE6","IE7","IE8","IE9");
		  $Nav = $this->getNavigator($_SERVER['HTTP_USER_AGENT'],0);
		  $bReturn = false;
		  if (in_array($Nav,$arIE)){
			  $bReturn = true;
		  }
		  return $bReturn;
	}
	
	public function IsIE678(){
		  $arIE = array("IE6","IE7","IE8");
		  $Nav = $this->getNavigator($_SERVER['HTTP_USER_AGENT'],0);
		  $bReturn = false;
		  if (in_array($Nav,$arIE)){
			  $bReturn = true;
		  }
		  return $bReturn;
	}

	public function IsIE9(){
		  $arIE = array("IE9");
		  $Nav = $this->getNavigator($_SERVER['HTTP_USER_AGENT'],0);
		  $bReturn = false;
		  if (in_array($Nav,$arIE)){
			  $bReturn = true;
		  }
		  return $bReturn;
	}
	
	public function CreateImageJPGFromFile($pId=0,$pForderImages="images/",$pImage="",$pForderFly="fly/",$pSufijo="_mod_",$width2=125,$height2=100){
		  $forderFly="fly/";
		  $ret=false;
	
	       $imgSlider = explode(".",$pImage);
	       $nombreImgSlider = $imgSlider[0];

		  $source0=$pForderImages.$pImage;
		  $size = GetImageSize($source0);
		  $width1  = $size[0];
		  $height1 = $size[1];
		  if ($pId>0)
	       	$source1= $pForderFly.$nombreImgSlider.$pSufijo.$pId.".jpg";
		  else	
	       	$source1= $pForderFly.$nombreImgSlider.$pSufijo.".jpg";
		  $source = $this->URL.$source1;
		  if (!file_exists($source1)){
			$gds = imagecreatefromjpeg($source0);
			$gdd = imagecreatetruecolor($width2, $height2);
			$dest = $source1; 
			imagecopyresampled($gdd, $gds, 0, 0, 0, 0, $width2, $height2, $width1, $height1); 
			imagejpeg($gdd, $dest);
			imagedestroy($gds);
			imagedestroy($gdd);
			$ret=true;
		  }
		  return $ret;
	}
	

	public function IsiPhone(){
		  $ar = array("iPhone","iPad","iPod");
		  $Nav = $this->getNavigator($_SERVER['HTTP_USER_AGENT'],0);
		  $bReturn = false;
		  if (in_array($Nav,$ar)){
			  $bReturn = true;
		  }
		  return $bReturn;
	}


	public function IsAndroid(){
		  $ar = array("Android");
		  $Nav = $this->getNavigator($_SERVER['HTTP_USER_AGENT'],0);
		  $bReturn = false;
		  if (in_array($Nav,$ar)){
			  $bReturn = true;
		  }
		  return $bReturn;
	}

	public function IsMobile(){
		  $ar = array("iPhone","iPad","iPod","Android","Symbian","RIM");
		  $Nav = $this->getNavigator($_SERVER['HTTP_USER_AGENT'],0);
		  $bReturn = false;
		  if (in_array($Nav,$ar)){
			  $bReturn = true;
		  }
		  return $bReturn;
	}

	public function ArrayComboToString($arr=array()){
		   $arr2 = array();
		   foreach($arr as $i=>$value){
			   $arr2[] = $arr[$i]->label;
		   }
		   return implode(',',$arr2);
	}

	public function time_stamp($session_time){
	    $time_difference = time() - strtotime($session_time) ;
	    $seconds = $time_difference ;
	    $minutes = round($time_difference / 60 );
	    $hours = round($time_difference / 3600 );
	    $days = round($time_difference / 86400 );
	    $weeks = round($time_difference / 604800 );
	    $months = round($time_difference / 2419200 );
	    $years = round($time_difference / 29030400 );
	    $lRet = "En estos momentos";
	    if($seconds <= 0){
	        $lRet = "En estos momentos";
	    }else if($seconds > 1 && $seconds <= 59){
	       	$lRet = "Hace $seconds segundos";
	    }else if($minutes <=60){
	                if($minutes==1){
	                    $lRet =  "Hace un minuto";
	                }else{
	                    $lRet = "Hace $minutes minutos";
	                }
	            }else if($hours <=24){
	                if($hours==1){
	                    $lRet = "Hace una hora";
	                }else{
	                    $lRet = "Hace $hours horas";
	                }

	    }else if($days <= 7){
	            if($days==1){
	                $lRet = "Hace un día";
	            }else{
	                $lRet = "Hace $days días";
	            }

	    }else if($weeks <= 4){
	            if($weeks==1){
	                $lRet = "Hace una semana";
	            }else{
	                $lRet = "Hace $weeks semanas";
	            }

	     }else if($months <=12){
	            if($months==1){
	                $lRet = "Hace un més";
	            }else{
	                $lRet = "Hace $months meses";
	            }

	     }else{
	        if($years==1){
	            $lRet = "Hace un año";
	        }else{
	            $lRet = "Hace $years años";
	        }

	    }

	    return $lRet;

	}

	public function base64_to_jpeg( $inputfile, $outputfile ) {
	  /* read data (binary) */
	  $ifp = fopen( $inputfile, "rb" );
	  $imageData = fread( $ifp, filesize( $inputfile ) );
	  fclose( $ifp );
	  /* encode & write data (binary) */
	  $ifp = fopen( $outputfile, "wb" );
	  fwrite( $ifp, base64_decode( $imageData ) );
	  fclose( $ifp );
	  /* return output filename */
	  return( $outputfile );
	}

	function FormatCal($cal=0,$con=0,$ina=0,$pivot=0, $idmatclas=1){
		$calx = "";
		$conx = "";
		$inax = "";
		if ( $idmatclas == 1 || $idmatclas == 3 ){

			if ($cal>0){
				//$calx = round(floatval($cal)/10,0);
				$calx = $cal;
			}
			switch (intval($calx)) {
				case 10:
					$conx = "DIEZ";
					break;
				case 9:
					$conx = "NUEVE";
					break;
				case 8:
					$conx = "OCHO";
					break;
				case 7:
					$conx = "SIETE";
					break;
				case 6:
					$conx = "SEIS";
					break;
				case 5:
					$conx = "CINCO";
					break;
				case 4:
					$conx = "CINCO"; // "CUATRO";
					$calx = 5;
					break;
				case 3:
					$conx = "CINCO"; // "TRES";
					$calx = 5;
					break;
				case 2:
					$conx = "CINCO"; // "DOS";
					$calx = 5;
					break;
				case 1:
					$conx = "CINCO"; // "UNO";
					$calx = 5;
					break;
				case 0:
					$conx = "CINCO"; // "CERO";
					$calx = 5;
					break;
				default:
					$conx = $calx; //"ACREDITADA";
					$calx = 5;
					break;

			}
			// $calx = $cal;
			if ($ina>0){
				$inax = intval($ina);
			}
			if ( $pivot == 0 ){
				return str_pad($calx, 3, " ", STR_PAD_LEFT).'  ( '.$conx.' )';
			}else{
				return number_format($cal,1);
			}

		}else{

			if ( floatval($cal) > 0){
				return str_pad("", 3, " ", STR_PAD_LEFT).'   '."ACREDITADA";
			}

		}


	}
	
	
	
}

?>