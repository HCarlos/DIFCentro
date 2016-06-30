<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');

require_once('vo/voConn.php');

class oMetodos {
	 
	 private static $instancia;
	 
	 private function __construct(){ 
	 }

	 public static function getInstance(){
				if (  !self::$instancia instanceof self){
					  self::$instancia = new self;
				}
				return self::$instancia;
	 }


	 private function getIdUserFromAlias($str){
		    mysql_query("SET NAMES UTF8");
		  
		    $result = mysql_query("select iduser from usuarios where username = '$str' and status_usuario = 1");

			if (!$result) {
    				$ret=0;
			}else{
    				$ret=intval(mysql_result($result, 0,"iduser"));
			}
		    return $ret;
	 }

	 private function getIdEmpFromAlias($str){
		    mysql_query("SET NAMES UTF8");
		  
		    $result = mysql_query("select idemp from usuarios where username = '$str' and status_usuario = 1");

			if (!$result) {
    				$ret=0;
			}else{
    				$ret=intval(mysql_result($result, 0,"idemp"));
			}
		    return $ret;
	 }

	 private function getCicloFromIdEmp($idemp=0){
		    mysql_query("SET NAMES UTF8");
		  
		    $res = mysql_query("select idciclo from cat_ciclos where idemp = $idemp and status_ciclo = 1 limit 1");

			$num_rows = mysql_num_rows($res);

			if ($num_rows <= 0) {
    			$ret=0;
			}else{
					$row = mysql_fetch_row($res);
    				$ret = intval($row[0]);
			}
		    return $ret;
	 }

	private function sayLiked($tipo,$val=""){
			switch(intval($tipo)){
					case 1:
					    return $val."%";
						break;
					case 2:
					    return "%".$val;
						break;
					default:
					    return "%".$val."%";
						break;
			}
	}	

	private function getIdProfFromAlias($str){
	    mysql_query("SET NAMES UTF8");
	  
	    $result = mysql_query("select idprofesor from _viProfesores where username = '$str' and status_usuario = 1");

		if (!$result) {
				$ret=0;
		}else{
				$ret=intval(mysql_result($result, 0,"idprofesor"));
		}
	    return $ret;
	}

	public function Actualizar_Promedios_Grupales($idgrupo=0, $idciclo=0, $user='',$idgrualu=0) {
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);

			$ip=$_SERVER['REMOTE_ADDR']; 
			$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);//$_SERVER["REMOTE_HOST"]; 
			$idemp = $this->getIdEmpFromAlias($user);
			$idusr = $this->getIdUserFromAlias($user);
			
		  	mysql_query("SET NAMES 'utf8'");
		  	//$numval = intval($numval);
			$query = "Set @Y = Actualizar_Promedios_Grupales(".$idgrupo.", ".$idciclo.", ".$idusr.", ".$idemp.", '".$ip."', '".$host."',".$idgrualu.")";

			$result = mysql_query($query);
			$ret = $result==false ? mysql_error():"OK";
		    mysql_close($mysql);
			  
			return $ret;

	}


	public function Actualiza_Grupo_Alumno_Promedio($user='',$idgrualu=0) {
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);

			$ip=$_SERVER['REMOTE_ADDR']; 
			$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);//$_SERVER["REMOTE_HOST"]; 
			$idemp = $this->getIdEmpFromAlias($user);
			$idusr = $this->getIdUserFromAlias($user);
			
		  	mysql_query("SET NAMES 'utf8'");
		  	//$numval = intval($numval);
			$query = "Set @Y = Actualiza_Grupo_Alumno_Promedio(".$idgrualu.", ".$idusr.", ".$idemp.", '".$ip."', '".$host."')";

			$result = mysql_query($query);
			$ret = $result==false ? mysql_error():"OK";
		    mysql_close($mysql);
			  
			return $ret;

	}


	public function Actualiza_Promedios_Grupales_por_Materia($user='',$idgrualu=0) {
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);

		  	mysql_query("SET NAMES 'utf8'");
			$query = "Set @Y = Actualiza_Promedios_Grupales_por_Materia(".$idgrualu.")";

			$result = mysql_query($query);
			$ret = $result==false ? mysql_error():"OK";
		    mysql_close($mysql);
			  
			return $ret;

	}


	public function  Llamar_Actualizar_Promedios_Padres_from_IdGruAlu($user='',$idgrualu=0) {
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);

			$ip=$_SERVER['REMOTE_ADDR']; 
			$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);//$_SERVER["REMOTE_HOST"]; 
			$idemp = $this->getIdEmpFromAlias($user);
			$idusr = $this->getIdUserFromAlias($user);
			
		  	mysql_query("SET NAMES 'utf8'");
			$query = "Set @Y =  Llamar_Actualizar_Promedios_Padres_from_IdGruAlu(".$idgrualu.", ".$idusr.", ".$idemp.", '".$ip."', '".$host."')";

			$result = mysql_query($query);
			$ret = $result==false ? mysql_error():"OK";
		    mysql_close($mysql);
			  
			return $ret;

	}




	public function Actualizar_Promedios_Grupales_Idiomas($idgrupo=0, $idciclo=0, $user='',$idgrualu=0,$pIdioma=0) {
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);

			$ip=$_SERVER['REMOTE_ADDR']; 
			$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);//$_SERVER["REMOTE_HOST"]; 
			$idemp = $this->getIdEmpFromAlias($user);
			$idusr = $this->getIdUserFromAlias($user);
			
		  	mysql_query("SET NAMES 'utf8'");
		  	//$numval = intval($numval);
			$query = "Set @Y = Actualizar_Promedios_Grupales_Idiomas(".$idgrupo.",".$idgrualu.",".$pIdioma.", ".$idciclo.", ".$idusr.", ".$idemp.", '".$ip."', '".$host."')";

			$result = mysql_query($query);
			$ret = $result==false ? mysql_error():"OK";
		    mysql_close($mysql);
			  
			return $ret;

	}


	public function Actualiza_Grupo_Alumno_Promedio_Idioma($user='',$idgrualu=0,$pIdioma=0) {
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);

			$ip=$_SERVER['REMOTE_ADDR']; 
			$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);//$_SERVER["REMOTE_HOST"]; 
			$idemp = $this->getIdEmpFromAlias($user);
			$idusr = $this->getIdUserFromAlias($user);
			
		  	mysql_query("SET NAMES 'utf8'");
		  	//$numval = intval($numval);
			$query = "Set @Y = Actualiza_Grupo_Alumno_Promedio_Idioma(".$idgrualu.", ".$pIdioma.", ".$idusr.", ".$idemp.", '".$ip."', '".$host."')";

			$result = mysql_query($query);
			$ret = $result==false ? mysql_error():"OK";
		    mysql_close($mysql);
			  
			return $ret;

	}


	public function Llamar_Actualiza_Promedios_Boletas_por_Grupo($idgrualu=0) {
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);

			
		  	mysql_query("SET NAMES 'utf8'");
			$query = "Set @Y = Llamar_Actualiza_Promedios_Boletas_por_Grupo(".$idgrualu.")";

			$result = mysql_query($query);
			$ret = $result==false ? mysql_error():"OK";
		    mysql_close($mysql);
			  
			return $ret;

	}








 }  // OF CLASS


?>