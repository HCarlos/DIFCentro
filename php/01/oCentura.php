<?php
ini_set('display_errors', '0');     # don't show any errors...
error_reporting(E_ALL | E_STRICT);  # ...but do log them

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

date_default_timezone_set('America/Mexico_City');



require_once('vo/voConn.php');
require_once('vo/voCombo.php');
require_once('vo/voUsuario.php');

require_once('vo/voEmpty.php');

require_once('vo/voAvaluo.php');
require_once('vo/voAvaluoZona.php');
require_once('vo/voAvaluoInmueble.php');
require_once('vo/voEAFCompConstruccion.php');

require_once('vo/voEstado.php');
require_once('vo/voMunicipio.php');

class oCentura {
	 
	 private static $instancia;
	 public $IdEmp;
	 public $IdUser;
	 public $User;
	 public $Pass;
	 public $Nav;
	 public $URL;
	 public $defaultMail;
	 public $CID;
	 public $Mail;
	 public $Foto;
	 public $iva;
	 
	 private function __construct(){ 
	 		$this->Nav      = "Ninguno";
			$this->IdUser    = 0;
			$this->User     = "";
			$this->Pass     = "";
			$this->iva      = 0.16;
			$this->URL      = "http://corevat.tecnointel.mx/";
	 }

	 public static function getInstance(){
				if (  !self::$instancia instanceof self){
					  self::$instancia = new self;
				}
				return self::$instancia;
	 }

	 public function getFolioTim($serie){
		    mysql_query("SET NAMES UTF8");		  
		    $result = mysql_query("select max(folio) as folio from facturas_encabezado where serie = '$serie' and isfe = 1 limit 1 ");

			if (!$result) {
    				$ret=1;
			}else{
    				$ret=intval(mysql_result($result, 0))+1;
			}

		    return $ret;
	 }

	 public function getFolio($serie){
		    mysql_query("SET NAMES UTF8");
		  
		    $result = mysql_query("select max(folio) as folio from facturas_encabezado where serie = '$serie' limit 1 ");

			if (!$result) {
    				$ret=1;
			}else{
    				$ret=intval(mysql_result($result, 0))+1;
			}

		    return $ret;
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
		  
		    $res = mysql_query("select idciclo from cat_ciclos where idemp = $idemp and predeterminado = 1 and status_ciclo = 1 limit 1");

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

     public function getCombo($index=0,$arg="",$pag=0,$limite=0,$tipo=0,$otros=""){

		  $Conn = voConn::getInstance();
		  $mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  mysql_select_db($Conn->db);
		  mysql_query("SET NAMES UTF8");

		  $arr = array('voCombo');
		  $indice = 0;
		  $ret = array();
		  $query="";
		 
            	switch ($index){
					case -2:
						switch($tipo){
							case 0:
								parse_str($arg);
								$pass = md5($password);
								$query = "SELECT username as label, iduser as data 
										FROM  _viUsuarios where username = '$username' and password = '$pass' and status_usuario = 1 ";
								break;		
						}
						break;
					case -1:// valida loguin de usuarios
			          	$ar = explode(".",$arg);
						$pass = md5($ar[1]);
						$query = "SELECT username as label,password as data 
								FROM usuarios 
								Where username = '$ar[0]' and password = '$pass' and status_usuario = 1";
						break;
					case 0:
						switch($tipo){
							case 0:
								parse_str($arg);
								$pass = md5($passwordL);
								$query = "SELECT username as label, concat(iduser,'|',password,'|',idemp,'|',empresa,'|',idusernivelacceso,'|',registrosporpagina,'|',clave,'|',param1) as data 
										FROM  _viUsuarios where username = '$username' and password = '$pass' and status_usuario = 1";
								break;		
							case 1:
								parse_str($arg);
								$pass = md5($passwordL);
								$query = "SELECT username as label, concat(iduser,'|',password,'|',idemp,'|',empresa,'|',idusernivelacceso,'|',registrosporpagina,'|',clave,'|',param1) as data 
										FROM  _viUsuarios where username = '$username' and password = '$pass' and status_usuario = 1";
								break;		
							case 2:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT nivel_de_acceso as label,idusernivelacceso as data 
										FROM usuarios_niveldeacceso where idemp = $idemp
										Order By label asc ";
								break;		
							case 3:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT concat(username,' - ',apellidos,' ',nombres) as label, iduser as data 
										FROM  _viUsuarios where idemp = $idemp and status_usuario = 1 and idusernivelacceso = 1";
								break;		
						}
						break;						
					case 1:
						switch($tipo){
							case 0:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT municipio as label, idmunicipio as data 
										FROM cat_municipios where idestado = $otros and status_municipio = 1
										Order By data asc ";
								break;		
							case 1:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT estado as label, idestado as data 
										FROM cat_estados where idemp = $idemp and status_estado = 1
										Order By data asc ";
								break;		
	
							case 300:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT beneficio as label, idbeneficio as data 
										FROM cat_beneficios where idemp = $idemp and status_beneficio = 1
										Order By label asc ";
								break;		
	
							case 301:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT oficina as label, idoficina as data 
										FROM cat_oficinas where idemp = $idemp and status_oficina = 1
										Order By label asc ";
								break;		

							case 302:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT clase as label, idclase as data 
										FROM cat_clases where idemp = $idemp and status_clase = 1
										Order By label asc ";
								break;		
	

							case 303:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT subclase as label, idsubclase as data 
										FROM cat_subclases where idemp = $idemp and status_subclase = 1
										Order By label asc ";
								break;	

							case 304:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$idusr = $this->getIdUserFromAlias($u);
								$query = "SELECT * 
										FROM _vi_Dep_User where idemp = $idemp and iduser = $idusr and status_ingreso = 1
										Order By ingreso asc ";
								break;		

							case 305:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$idusr = $this->getIdUserFromAlias($u);
								$query = "SELECT * 
										FROM _vi_Dep_User where idemp = $idemp and status_ingreso = 1
										Order By ingreso asc ";
								break;		
	
							case 306:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$idusr = $this->getIdUserFromAlias($u);
								$query = "SELECT distinct cobrado_por_usuario_nombre_completo as label, cobrado_por as data 
										FROM _vi_Contribuciones where idemp = $idemp and status_contribucion = 1
										Order By ingreso asc ";
								break;		
	
							case 307:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT delegacion as label, iddelegacion as data 
										FROM cat_delegaciones  
										Order By delegacion asc ";
								break;	

							case 308:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT categoria as label, idcategorialocalidad as data 
										FROM cat_categorias_localidades  
										Order By categoria asc ";
								break;	

							case 309:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT subcategoria as label, idsubcatben as data 
										FROM _vi_SubCat_Ben  
										Order By subcategoria asc ";
								break;	

							case 310:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT localidad as label, idlocalidad as data 
										FROM cat_localidades  
										Order By localidad asc ";
								break;	

							case 311:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT beneficiario as label, idbeneficiario as data 
										FROM _vi_Beneficiarios  
										Order By beneficiario asc ";
								break;	

							case 312:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT distinct beneficiario as label, idbeneficiario as data, localidad 
										FROM _vi_Beneficiarios 
										where idemp = $idemp and status_beneficiario = 1 and beneficiario != 'null'
										Order By beneficiario asc ";
								break;	



						}
						break;

					case 2:  // Asociaciones

						switch($tipo){
							case 0:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT concat(usuario,' (',username,')') as label, iddepuser as data 
										FROM _vi_Usuario_Dep where idemp = $idemp and idbeneficio = $otros 
										Order By label asc ";
								break;	

							case 1:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT ingreso as label, idingreso as data 
										FROM _vi_Cat_Ingresos where idemp = $idemp and idbeneficio = $otros 
										Order By label asc ";
								break;		
			
							case 2:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT ingreso_b as label, idingtoiding as data 
										FROM _vi_Ing_to_Ing where idemp = $idemp and idingreso_a = $otros 
										Order By label asc ";
								break;		

							case 3:
								parse_str($arg);
								$idemp = $this->getIdEmpFromAlias($u);
								$query = "SELECT subcategoria as label, idsubcatben as data 
										FROM _vi_SubCat_Ben where idemp = $idemp and idbeneficio = $otros 
										Order By subcategoria asc ";
								break;	


						}
						break;


		  	}
		  
		  	$result = mysql_query($query);
		  
		  	while ($obj = mysql_fetch_object($result, $arr[0])) {
			   	 $ret[] = $obj;
		  	}
		  
	       	mysql_close($mysql);
			
			return $ret;
			
	}

     public function setAsocia($tipo=0,$arg="",$pag=0,$limite=0,$var2=0, $otros=""){
		  $query="";

		  $ip=$_SERVER['REMOTE_ADDR']; 
		  $host=gethostbyaddr($_SERVER['REMOTE_ADDR']);//$_SERVER["REMOTE_HOST"]; 
	
		  $vRet = "Error";
		  $Conn = voConn::getInstance();
		  $mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  mysql_select_db($Conn->db);
		  mysql_query("SET NAMES 'utf8'");	
            	switch ($tipo){
					case 0:
						switch($var2){
							case 10:
								parse_str($otros);
								$iduser = $this->getIdUserFromAlias($u);
								$idemp = $this->getIdEmpFromAlias($u);
								$idciclo = $this->getCicloFromIdEmp($idemp);

			          			$ar = explode(".",$arg);
			          			$item = explode("|",$ar[0]);
								foreach($item as $i=>$valor){
									if ((int)($item[$i])>0){
										$query = "Insert Into usuarios_dep(idbeneficio,iduser,idemp,ip,host,creado_por,creado_el)value($ar[1],$item[$i],$idemp,'$ip','$host',$iduser,NOW())";
										$result = mysql_query($query);
										$vRet = $result!=1?"Error.":"OK";
									}
								}
								break;		
							case 20:

			          			$ar = explode("|",$arg);
								foreach($ar as $i=>$valor){
									if ((int)($ar[$i])>0){
										$query = "Delete from usuarios_dep where iddepuser = ".$ar[$i]." and movible = 1 ";
										$result = mysql_query($query);
										$vRet = $result!=1?"Error-- ".$ar[$i]:"OK";
									}
								}
								break;		
						}
						break;
					case 1:
						switch($var2){
							case 10:
								parse_str($otros);
								$iduser = $this->getIdUserFromAlias($u);
								$idemp = $this->getIdEmpFromAlias($u);
								$idciclo = $this->getCicloFromIdEmp($idemp);

			          			$ar = explode(".",$arg);
			          			$item = explode("|",$ar[0]);
								foreach($item as $i=>$valor){
									if ((int)($item[$i])>0){
										$query = "Insert Into asoc_ingreso_a_ingreso(idingreso_a,idingreso_b,idemp,ip,host,creado_por,creado_el)value($ar[1],$item[$i],$idemp,'$ip','$host',$iduser,NOW())";
										$result = mysql_query($query);
										$vRet = $result!=1?"Error.":"OK";
									}
								}
								break;		
							case 20:

			          			$ar = explode("|",$arg);
								foreach($ar as $i=>$valor){
									if ((int)($ar[$i])>0){
										$query = "Delete from asoc_ingreso_a_ingreso where idingtoiding = ".$ar[$i];
										$result = mysql_query($query);
										$vRet = $result!=1?"Error-- ".$ar[$i]:"OK";
									}
								}
								break;		
						}
						break;

					case 2:
						switch($var2){
							case 10:
								parse_str($otros);
								$iduser = $this->getIdUserFromAlias($u);
								$idemp = $this->getIdEmpFromAlias($u);
								$idciclo = $this->getCicloFromIdEmp($idemp);

			          			$ar = explode(".",$arg);
			          			$item = explode("|",$ar[0]);
								foreach($item as $i=>$valor){
									if ((int)($item[$i])>0){
										$query = "Insert Into asoc_subcat_beneficio(idbeneficio,idsubcategoria,idemp,ip,host,creado_por,creado_el)value($ar[1],$item[$i],$idemp,'$ip','$host',$iduser,NOW())";
										$result = mysql_query($query);
										$vRet = $result!=1?"Error.":"OK";
									}
								}
								break;		
							case 20:

			          			$ar = explode("|",$arg);
								foreach($ar as $i=>$valor){
									if ((int)($ar[$i])>0){
										$query = "Delete from asoc_subcat_beneficio where idsubcatben = ".$ar[$i];
										$result = mysql_query($query);
										$vRet = $result!=1?"Error".$ar[$i]:"OK";
									}
								}
								break;		
						}
						break;

		  		}
		  
			mysql_close($mysql);

		  return  $vRet;
	}


     public function setSaveData($index=0,$arg="",$pag=0,$limite=0,$tipo=0,$cadena2=""){
		  	$query="";

		  	$ip=$_SERVER['REMOTE_ADDR']; 
		  	$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);//$_SERVER["REMOTE_HOST"]; 
	
		  	$vRet = "Error";
		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);
		  	mysql_query("SET NAMES 'utf8'");	
		  	$ar = array();
		 
            	switch ($index){
					case -1:
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into config(llave,valor,observaciones,
																	idemp,ip,host,creado_por,creado_el)
											value('$llave','$valor','$observaciones',
													$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update config set 	
															  	llave = '$llave',
															  	valor = '$valor',
															  	observaciones = '$observaciones',
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idconfig = $idconfig";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from config Where idconfig = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break;

					case 0:
						switch($tipo){
							case 0:
								parse_str($arg);
								$arr = array("alu","pro","per");
								if (!in_array(substr($username, 0,3), $arr)){
									$pass = md5($password1);
									$idusr = $this->getIdUserFromAlias($user);
									$idemp = $this->getIdEmpFromAlias($user);
									$query = "Insert Into usuarios(username,password,apellidos,nombres,
																	correoelectronico,idusernivelacceso,
																	status_usuario,
																	idemp,ip,host,creado_por,creado_el)
												value( '$username','$pass','$apellidos','$nombres',
														'$correoelectronico',$idusernivelacceso,
														$status_usuario,
													    $idemp,'$ip','$host',$idusr,NOW())";
									$result = mysql_query($query); 
									$vRet = $result!=1? mysql_error():"OK";
								}else{
									$vRet = "Error: No puede usar ese prefijo en el Nombre de Usuario";
								}
								break;		
							case 1:
								parse_str($arg);
								// if ($username !== $username2){
								// 	$arr = array("alu","pro","per","adm");
								// 	if ( (!in_array(substr($username, 0,3), $arr)) ){
										$idusr = $this->getIdUserFromAlias($user);
										$idemp = $this->getIdEmpFromAlias($user);
										if ( isset($idusernivelacceso) ){
											$idnivacc = " idusernivelacceso = $idusernivelacceso, ";	
										}else{
												$idnivacc = "";
										}
										//$query = "update usuarios set username = '$username',
										$query = "update usuarios set apellidos = '$apellidos',
																		nombres = '$nombres',
																		correoelectronico = '$correoelectronico',
																		".$idnivacc."
																		status_usuario = $status_usuario,
																		ip = '$ip', 
																		host = '$host',
																		modi_por = $idusr, 
																		modi_el = NOW()
												Where iduser = $iduser";
										$result = mysql_query($query);
										$vRet = $result!=1? mysql_error():"OK";
								// 	}else{
								// 		$vRet = "Error: No puede usar ese prefijo en el Nombre de Usuario";
								// 	}

								// }

								break;		
							case 2:
								$query = "delete from usuarios Where iduser = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 3:
								parse_str($arg);
								$pass = md5($password1);
								$query = "update usuarios set password = '$pass',
																ip = '$ip', 
																host = '$host',
																modi_por = $iduser2, 
																modi_el = NOW()
										Where iduser = $iduser";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error().$iduser:"OK";
								break;		
							case 100:
							     
								parse_str($arg);
								$tel = trim(utf8_decode($celular));
								$pass = md5($password);
								$query = "Insert Into usuarios(username,password,nombres,celular,idF,latitud,longitud,ip,host,creado_el)
													value('$username','$pass','$nombre','$tel','$idF','$latitud','$longitud','$ip','$host',NOW())";
								$result = mysql_query($query); 
								
								if ($result!=1){
									$vRet = "CMHR ".mysql_error();
								}else{
									//$cfolio = $this->getIDFromDenuncias();
									$vRet = "OK";
								}
								break;		
							case 101:
							     
								parse_str($arg);
								$query = "update usuarios set valid = 1 where username='$username'";
								$result = mysql_query($query); 
								
								if ($result!=1){
									$vRet = "CMHR ".mysql_error();
								}else{
									//$cfolio = $this->getIDFromDenuncias();
									$vRet = "OK";
								}
								break;		
							case 200:
							     
								parse_str($arg);
								$pass = md5($password);
								$query = "Insert Into usuarios(username,password,ip,host,creado_el)
													value('$username','$pass','$ip','$host',NOW())";
								$result = mysql_query($query); 
								
								if ($result!=1){
									$vRet = "CMHR ".mysql_error();
								}else{
									//$cfolio = $this->getIDFromDenuncias();
									$vRet = "OK";
								}
								break;		
							case 203:
							     
								parse_str($arg);
								//$pass = md5($password);
								/*	
								if (!isset($idusernivelacceso)){
									$idusernivelacceso = 1;
								}
								*/
								if ( isset($idusernivelacceso) ){
									$idnivacc = " idusernivelacceso = $idusernivelacceso, ";	
								}else{
									$idnivacc = "";
								}
								
								$token_validated = $token == $token_source ? 1 : 0;
								$token = intval($token_validated) == 1? $token :"";

								$query = "update usuarios set 
															 apellidos = '$apellidos', 
															 nombres = '$nombres', 
															 correoelectronico = '$correoelectronico',
															 ".$idnivacc."
															 teloficina = '$teloficina',
															 telpersonal = '$telpersonal',
															 token = '$token',
															 token_validated = $token_validated,
															 registrosporpagina = $registrosporpagina,
															 param1 = '$param1',
															 ip = '$ip',
															 host = '$host'
														where username LIKE ('$username2%')";
								$result = mysql_query($query); 
								
								if ($result!=1){
									$vRet = "CMHR ".mysql_error();
								}else{
									//$cfolio = $this->getIDFromDenuncias();
									$vRet = "OK";
								}
								break;		
							case 204:
							     
								parse_str($arg);
								$query = "update usuarios set foto = '$foto',
															 ip = '$ip',
															 host = '$host'
															 where username LIKE ('$username%')";
								$result = mysql_query($query); 
								
								if ($result!=1){
									$vRet = "CMHR ".mysql_error();
								}else{
									//$cfolio = $this->getIDFromDenuncias();
									$vRet = "OK";
								}
								break;		

							case 205:
							     
								parse_str($arg);
								$pass = md5($password);

								$query = "update usuarios set 
															 password = '$pass', 
															 ip = '$ip',
															 host = '$host'
														where username LIKE ('$username2%')";
								$result = mysql_query($query); 
								
								if ($result!=1){
									$vRet = "CMHR ".mysql_error();
								}else{
									$vRet = "OK";
								}
								break;		

						}
						break;

					case 1000: 
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_beneficios(beneficio,status_beneficio,
																	idemp,ip,host,creado_por,creado_el)
											value('$beneficio',$status_beneficio,
													$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update cat_beneficios set 	
															  	beneficio = '$beneficio',
															  	status_beneficio = $status_beneficio,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idbeneficio = $idbeneficio";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_beneficios Where idbeneficio = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break;

					case 1001: // 1001
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_oficinas(oficina, idbeneficio, status_oficina,
																	idemp,ip,host,creado_por,creado_el)
											value('$oficina', $idbeneficio, $status_oficina,
													$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update cat_oficinas set 	
															  	oficina = '$oficina',
															  	idbeneficio = $idbeneficio,
															  	status_oficina = $status_oficina,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idoficina = $idoficina";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_oficinas Where idoficina = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break;


					case 1002: // 1002
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_clases(cuenta,clase, idoficina, status_clase,
																	idemp,ip,host,creado_por,creado_el)
											value('$cuenta','$clase', $idoficina, $status_clase,
													$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update cat_clases set 	
																cuenta = '$cuenta',
															  	clase = '$clase',
															  	idoficina = $idoficina,
															  	status_clase = $status_clase,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idclase = $idclase";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_clases Where idclase = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break;


					case 1003: // 1003
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_subclases(cuenta, subclase, idclase, status_subclase,
																	idemp,ip,host,creado_por,creado_el)
											value('$cuenta','$subclase', $idclase, $status_subclase,
													$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update cat_subclases set 	
																cuenta = '$cuenta',
															  	subclase = '$subclase',
															  	idclase = $idclase,
															  	status_subclase = $status_subclase,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idsubclase = $idsubclase";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_subclases Where idsubclase = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break;


					case 1004: // 1004
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_ingresos(ingreso, 
																	idsubclase, 
																	tipo,
																	porcentaje_ingreso,
																	dsm_min,
																	dsm_max,
																	costo_min,
																	costo_max,
																	clave,
																	ano,
																	status_ingreso,
																	idemp,ip,host,creado_por,creado_el)
															value(	'$ingreso', 
																	$idsubclase,
																	$tipo,
																	$porcentaje_ingreso,
																	$dsm_min,
																	$dsm_max,
																	$costo_min,
																	$costo_max,
																	'$clave',
																	$ano, 
																	$status_ingreso,
																	$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update cat_ingresos set 	
															  	ingreso = '$ingreso',
															  	idsubclase = $idsubclase,
															  	tipo = $tipo,
															  	porcentaje_ingreso = $porcentaje_ingreso,
															  	dsm_min = $dsm_min,
															  	dsm_max = $dsm_max,
															  	costo_min = $costo_min,
															  	costo_max = $costo_max,
															  	clave = '$clave',
															  	ano = $ano,
															  	status_ingreso = $status_ingreso,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idingreso = $idingreso";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_ingresos Where idingreso = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break;


					case 1005: // 1005
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into contribuciones(
																	idingreso, 
																	cantidad,
																	precio_unitario,
																	subtotal,
																	contribuyente,
																	observaciones,
																	fecha,
																	generado_por,
																	idemp,ip,host,creado_por,creado_el)
															value(	
																	$concepto, 
																	$cantidad,
																	$precio_unitario,
																	$importe,
																	'$contribuyente',
																	'$observaciones',
																	NOW(),
																	$idusr,
																	$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update contribuciones set 	
																idingreso = $concepto, 
																cantidad = $cantidad,
																precio_unitario = $precio_unitario,
																subtotal = $importe,
																contribuyente = '$contribuyente',
																observaciones = '$observaciones',
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idcontribucion = $idcontribucion";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from contribuciones Where idcontribucion = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 3:
								parse_str($arg);
								$tp = time();
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update contribuciones 
												set status_contribucion = 1, 
													cobrado_por = $idusr, 
													fecha_cobro = NOW(),
													idtransacpay = $tp 
												Where idcontribucion = ".$idcontribucion;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 4:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update contribuciones 
												set status_contribucion = 0, 
													cobrado_por = $idusr, 
													fecha_cobro = NOW(),
													idtransacpay = '' 
												Where idcontribucion = ".$idcontribucion;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 5:
								parse_str($arg);
								$query = "update contribuciones 
												set status_contribucion = 1, 
													idtransacpay = '$idtransacpay',
													fecha_cobro = NOW() 
												Where tokenpay = '".$tokenpay."'";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;

							case 6:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($u);
								$query = "update contribuciones 
												set 
													subtotal = $importe, 
													ip = '$ip', 
													host = '$host',
													modi_por = $idusr, 
													modi_el = NOW()
												Where idcontribucion = ".$idcontribucion;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break; // 1005


					case 1006: 
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_subcategorias(subcategoria,status_subcategoria,
																	idemp,ip,host,creado_por,creado_el)
											value('$subcategoria',$status_subcategoria,
													$idemp,'$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update cat_subcategorias set 	
															  	subcategoria = '$subcategoria',
															  	status_subcategoria = $status_subcategoria,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idsubcategoria = $idsubcategoria";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_subcategorias Where idsubcategoria = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break; // 1006

					case 1007: 
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_localidades(localidad,iddelegacion,idcategorialocalidad,
																	ip,host,creado_por,creado_el)
											value('$localidad',$iddelegacion,$idcategorialocalidad,
												  '$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$query = "update cat_localidades set 	
															  	localidad = '$localidad',
															  	iddelegacion = $iddelegacion,
															  	idcategorialocalidad = $idcategorialocalidad,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idlocalidad = $idlocalidad";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_localidades Where idlocalidad = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break; // 1007

					case 1008: 
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$fec = explode("-",$fecha);
								$fecha = $fec[2].'-'.$fec[1].'-'.$fec[0];
								$query = "Insert Into beneficios_otorgados(
																	idsubcatben,
																	idbeneficiario,
																	cantidad,
																	fecha,
																	idemp,ip,host,creado_por,creado_el)
											value(
													$idsubcatben,
													$idbeneficiario,
													$cantidad,
													'$fecha',
												  '$idemp','$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$fec = explode("-",$fecha);
								$fecha = $fec[2].'-'.$fec[1].'-'.$fec[0];
								$query = "update beneficios_otorgados set 	
															  	fecha = '$fecha',
															  	idsubcatben = $idsubcatben,
															  	idbeneficiario = $idbeneficiario,
															  	cantidad = $cantidad,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idbeneficiootorgado = $idbeneficiootorgado";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from beneficios_otorgados Where idbeneficiootorgado = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break; // 1008


					case 1009: 
						switch($tipo){
							case 0:
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$idemp = $this->getIdEmpFromAlias($user);
								$query = "Insert Into cat_beneficiarios(
																	ap_paterno,
																	ap_materno,
																	nombre,
																	sexo,
																	telefono,
																	correo_electronico,
																	idlocalidad,
																	idemp,ip,host,creado_por,creado_el)
											value(
													'$ap_paterno',
													'$ap_materno',
													'$nombre',
													$sexo,
													'$telefono',
													'$correo_electronico',
													$idlocalidad,
												  '$idemp','$ip','$host',$idusr,NOW())";
								$result = mysql_query($query); 
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 1:
							     //$ar = $this->unserialice_force($arg);
								parse_str($arg);
								$idusr = $this->getIdUserFromAlias($user);
								$fec = explode("-",$fecha);
								$fecha = $fec[2].'-'.$fec[1].'-'.$fec[0];
								$query = "update cat_beneficiarios set 	
															  	ap_paterno = '$ap_paterno',
															  	ap_materno = '$ap_materno',
															  	nombre = '$nombre',
															  	sexo = $sexo,
															  	telefono = '$telefono',
															  	correo_electronico = '$correo_electronico',
															  	idlocalidad = $idlocalidad,
																ip = '$ip', 
																host = '$host',
																modi_por = $idusr, 
																modi_el = NOW()
										Where idbeneficiario = $idbeneficiario";
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
							case 2:
								$query = "delete from cat_beneficiarios Where idbeneficiario = ".$arg;
								$result = mysql_query($query);
								$vRet = $result!=1? mysql_error():"OK";
								break;		
						}
						break; // 1009

		  		}
		  
			mysql_close($mysql);

		  return  $vRet;
	}


	public function getQuerys($tipo=0, $cad="", $type=0, $from=0, $cant=0, $ar=array(), $otros="", $withPag=1 ) {
		  	
		  	$arr = array('voEmpty');
		  	$ret = array();

		  	$Conn = voConn::getInstance();
		  	$mysql = mysql_connect($Conn->server, $Conn->user, $Conn->pass);
		  	mysql_select_db($Conn->db);
			
		  	mysql_query("SET NAMES 'utf8'");	
			$index = 0;		
		  
            	switch ($tipo){
				case -6:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idconfig, llave, valor, observaciones
									FROM config
								where idemp = $idemp and idconfig = $idconfig ";
					break;
				case -5:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idconfig, llave, valor, observaciones
									FROM config
								where idemp = $idemp ";
					break;
				case -4:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT llave,valor
									FROM config
								where idemp = $idemp and llave = '$llave' ";
					break;
				case -3:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT llave,valor
									FROM config
								where idemp = $idemp";
					break;
				case -2:
					$query = "SELECT *
									FROM _viUsuarios
								where iduser = $cad  and status_usuario = 1  and idusernivelacceso <= 100";
					break;
				case -1:
					parse_str($cad);
					$iduser = $this->getIdUserFromAlias($u);
			        $idemp = $this->getIdEmpFromAlias($u);
					
					$query = "SELECT *
								FROM _viUsuarios 
								WHERE  idemp = $idemp and status_usuario = 1  and idusernivelacceso <= 100 
								Order by iduser desc";
					break;
				case 0:
						$query="SELECT *
								from _viUsuarios 
								where username LIKE ('$cad%')  and status_usuario = 1  and idusernivelacceso <= 1000 ";	
						break;	   

				case 1:
					parse_str($cad);
					$iduser = $this->getIdUserFromAlias($u);
			        $idemp = $this->getIdEmpFromAlias($u);
					
					$query = "SELECT *
								FROM cat_subcategorias 
								WHERE  idemp = $idemp and status_subcategoria = 1  
								Order by subcategoria asc";
					break;

				case 2:
					parse_str($cad);
					$iduser = $this->getIdUserFromAlias($u);
			        $idemp = $this->getIdEmpFromAlias($u);
					
					$query = "SELECT *
								FROM _vi_SubCat_Ben 
								WHERE  idemp = $idemp and status_subcat_ben = 1  
								Order by subcategoria asc";
					break;

				case 2000:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idbeneficio, beneficio, status_beneficio
									FROM cat_beneficios
								Where idemp = $idemp order by idbeneficio asc";
					break;
				case 2001:
					$query = "SELECT idbeneficio, beneficio, status_beneficio
									FROM cat_beneficios
								where idbeneficio = $cad ";
					break;
								
				case 2002:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idoficina, oficina, beneficio, status_oficina, idbeneficio
									FROM _vi_Cat_Oficinas
								Where idemp = $idemp order by idoficina asc";
					break;
				case 2003:
					$query = "SELECT idoficina, oficina, beneficio, status_oficina, idbeneficio
									FROM _vi_Cat_Oficinas
								where idoficina = $cad ";
					break;

				case 2004:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idclase, clase, cuenta, oficina, status_clase, idoficina
									FROM _vi_Cat_Clases
								Where idemp = $idemp order by idclase asc";
					break;
					
				case 2005:
					$query = "SELECT idclase, clase, cuenta, oficina, status_clase, idoficina
									FROM _vi_Cat_Clases
								where idclase = $cad ";
					break;

				case 2006:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idsubclase, subclase, cuenta, clase, status_subclase, idclase
									FROM _vi_Cat_SubClases
								Where idemp = $idemp order by idsubclase asc";
					break;
					
				case 2007:
					$query = "SELECT idsubclase, subclase, cuenta, clase, status_subclase, idclase
									FROM _vi_Cat_SubClases
								where idsubclase = $cad ";
					break;

				case 2008:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idingreso, beneficio, oficina, clase, subclase, ingreso, status_ingreso, idsubclase
									FROM _vi_Cat_Ingresos
								Where idemp = $idemp order by idingreso asc";
					break;
					
				case 2009:
					$query = "SELECT *
									FROM _vi_Cat_Ingresos
								where idingreso = $cad ";
					break;

				case 2010:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$iduser = $this->getIdUserFromAlias($u);
					$query = "SELECT *
									FROM _vi_Contribuciones
								Where generado_por = $iduser and idemp = $idemp $otros ";
					break;
					
				case 2011:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT *
									FROM _vi_Contribuciones
								Where idcontribucion = $idcontribucion and idemp = $idemp ";
					break;

				case 2012:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$iduser = $this->getIdUserFromAlias($u);
					$query = "SELECT *
									FROM _vi_Contribuciones
								Where idemp = $idemp and status_contribucion = 0 $otros ";
					break;

				case 2013:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$iduser = $this->getIdUserFromAlias($u);
					$query = "SELECT *
									FROM _vi_Contribuciones
								Where idemp = $idemp $otros ";
					break;

				case 2014:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$iduser = $this->getIdUserFromAlias($u);

					if(intval($usuario)!=0){
						$perUser = " and cobrado_por = ".$usuario;
					}else{
						$perUser = "";
					}
					$query = "SELECT *
								FROM _vi_Contribuciones
								Where idemp = $idemp  and ( DATE_FORMAT(fecha_cobro, '%d-%m-%Y') >= '$fi' and  DATE_FORMAT(fecha_cobro, '%d-%m-%Y') <= '$ff' ) ".$perUser." order by idcontribucion asc ";
					break;

				case 2015: // send Token
					$query = "SELECT tokenpay as id, cantidad, precio_unitario, subtotal, total, contribuyente, observaciones, 
										fecha, generado_por, cobrado_por, fecha_cobro, ingreso as concepto, tipo, idsubclase, 
										clave as cuenta, idtransacpay
									FROM _vi_Contribuciones
								Where tokenpay = '$cad' ";
					break;

				case 2016:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idsubcategoria, subcategoria, status_subcategoria
									FROM cat_subcategorias
								Where idemp = $idemp order by idsubcategoria asc";
					break;

				case 2017:
					$query = "SELECT idsubcategoria, subcategoria, status_subcategoria
									FROM cat_subcategorias
								where idsubcategoria = $cad ";
					break;

				case 2018:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT delegacion, idlocalidad, localidad, categoria
									FROM _vi_Localidades
								order by delegacion asc, categoria asc, localidad asc";
					break;
					
				case 2019:
					$query = "SELECT *
									FROM _vi_Localidades
								where idlocalidad = $cad ";
					break;

				case 2020:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idbeneficiootorgado, beneficiario, 
									  CONCAT(delegacion,' ',localidad) as localidad, 
									  subcategoria, cantidad, fecha
									FROM _vi_Beneficios_Otorgados
								order by beneficiario asc";
					break;
					
				case 2021:
					$query = "SELECT *
									FROM _vi_Beneficios_Otorgados
								where idbeneficiootorgado = $cad ";
					break;

				case 2022:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
			        $tr = intval($tiporeporte);
			        
			        
			        $f0 = explode("-",$fi);
			        $fi = $f0[2].'-'.$f0[1].'-'.$f0[0];

			        $f1 = explode("-",$ff);
			        $ff = $f1[2].'-'.$f1[1].'-'.$f1[0];


			        $where = "";
			        switch ($tr) {
			        	case 0:
			        		$where = " where (fecha between '".$fi."' and '".$ff."') ";
			        		break;
			        	
			        	case 1:
			        		$where = " where (idlocalidad = $idlocalidad) and (fecha between '".$fi."' and '".$ff."') ";
			        		break;

			        	case 2:
			        		$where = " where (idlocalidad = $idlocalidad) and (idbeneficio = $idbeneficio) and (fecha between '".$fi."' and '".$ff."') ";
			        		break;

			        	case 3:
			        		$where = " where (idlocalidad = $idlocalidad) and (idsubcatben = $idsubcatben) and (fecha between '".$fi."' and '".$ff."') ";
			        		break;
			        }

					$query = "SELECT *
									FROM _vi_Beneficios_Otorgados
							 ".$where;
					break;

				case 2023:
					parse_str($cad);
			        $idemp = $this->getIdEmpFromAlias($u);
					$query = "SELECT idbeneficiario, beneficiario, localidad, delegacion
									FROM _vi_Beneficiarios
								order by beneficiario asc";
					break;
					
				case 2024:
					$query = "SELECT *
									FROM _vi_Beneficiarios
								where idbeneficiario = $cad ";
					break;


				}

			$result = mysql_query($query);

			while ($obj = mysql_fetch_object($result, $arr[$index])) {
						$ret[] = $obj;
			}
			mysql_free_result($result);
		    mysql_close($mysql);
			  
			return $ret;
	
	}








 }  // OF CLASS


?>