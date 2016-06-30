-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-03-2015 a las 13:32:31
-- Versión del servidor: 5.1.73-cll
-- Versión de PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `tecnoint_dbSIIM`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`tecnoint`@`localhost` PROCEDURE `Generar_Usuarios_from_Null_Usuario`()
    NO SQL
Begin

  DECLARE done INT DEFAULT 0;
  DECLARE pIdAlu INT;
  DECLARE pIdUser INT;
  
  DECLARE cur1 CURSOR FOR SELECT idalumno FROM cat_alumnos WHERE idusuario IS NULL;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

  OPEN cur1;

  REPEAT
    FETCH cur1 INTO pIdAlu;
    IF NOT done THEN
		SET @X = Generar_Usuario_from_Alumno(pIdAlu);
    END IF;
  UNTIL done END REPEAT;

  CLOSE cur1;
  
End$$

CREATE DEFINER=`tecnoint`@`localhost` PROCEDURE `practice`()
    NO SQL
Begin

DECLARE ID INT DEFAULT 0;
DECLARE xIdGrupo INT DEFAULT 0;
DECLARE xIdCiclo INT DEFAULT 0;
DECLARE xIdEmp INT DEFAULT 0;
DECLARE xIdUser INT DEFAULT 0;
DECLARE xFecha DATETIME;

DECLARE cur CURSOR FOR 
SELECT idgrupo, idciclo, idemp, creado_por, creado_el 
FROM nivel_grupos; 

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xIdGrupo, xIdCiclo, xIdEmp, xIdUser, xFecha;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;

		Insert Into grupo_promedios(idgrupo, idciclo, idemp, creado_por, creado_el)
		values(xIdGrupo, xIdCiclo, xIdEmp, xIdUser, xFecha);
        
END LOOP bucle;

CLOSE cur;

End$$

CREATE DEFINER=`tecnoint`@`localhost` PROCEDURE `practice_generar_usuario_a_profesor`()
    NO SQL
Begin

DECLARE ID INT DEFAULT 0;
DECLARE xIdProfesor INT DEFAULT 0;

DECLARE cur CURSOR FOR 
SELECT idprofesor 
FROM cat_profesores
Where idusuario IS NULL; 

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xIdProfesor;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;

		set @X = Generar_Usuario_from_Profesor(xIdProfesor);

END LOOP bucle;

CLOSE cur;

End$$

--
-- Funciones
--
CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualizar_Pagos_Metodo_A`(`pIdFamilia` INT(10), `pParam` INT(10)) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin
DECLARE ID INT Default 0;
DECLARE xIdEdoCta INT Default 0;
DECLARE xStatus_Movto INT Default 0;
DECLARE xIs_Vencimiento INT Default 0;
DECLARE xVencimiento Date;
DECLARE xDqFpV INT Default 0;

DECLARE cur CURSOR FOR 
SELECT idedocta, status_movto, is_vencimiento, vencimiento, dias_que_faltan_para_vencer
FROM _viEdosCta
where  idfamilia = pIdFamilia and status_movto = 0 and is_vencimiento = 1 and dias_que_faltan_para_vencer > 10;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xIdEdoCta, xStatus_Movto, xIs_Vencimiento, xVencimiento, xDqFpV ;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;
        
        update estados_de_cuenta 
        	set porcdescto = 5
        	Where idedocta = xIdEdoCta;

END LOOP bucle;

	if pParam = 1 then
		INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
			VALUES(-1,NOW(),'Event','UpdatePagos',pIdFamilia,'Update Pagos'); 
	end if;

CLOSE cur;

return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualizar_Promedios_Grupales`(`pIdGrupo` INT(10), `pIdCiclo` INT(10), `pIdUser` INT(10), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8, `pIdGruAlu` INT(10)) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin
	select idgrupo, idciclo, idemp into @pIdGrupo, @pIdCiclo, @pIdEmp From _viBoletas where idgrualu = pIdGruAlu limit 1;

	Select avg(cal0), avg(con0), sum(ina0) into @cal0, @con0, @ina0 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal0 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal0 = ROUND(@cal0), con0 = @con0, ina0 = @ina0, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(cal1), avg(con1), sum(ina1) into @cal1, @con1, @ina1 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal1 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal1 = ROUND(@cal1), con1 = @con1, ina1 = @ina1, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(cal2), avg(con2), sum(ina2) into @cal2, @con2, @ina2 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal2 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal2 = ROUND(@cal2), con2 = @con2, ina2 = @ina2, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(cal3), avg(con3), sum(ina3) into @cal3, @con3, @ina3 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal3 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal3 = ROUND(@cal3), con3 = @con3, ina3 = @ina3, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(cal4), avg(con4), sum(ina4) into @cal4, @con4, @ina4 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal4 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal4 = ROUND(@cal4), con4 = @con4, ina4 = @ina4, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(cal5), avg(con5), sum(ina5) into @cal5, @con5, @ina5 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal5 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal5 = ROUND(@cal5), con5 = @con5, ina5 = @ina5, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(cal6), avg(con6), sum(ina6) into @cal6, @con6, @ina6 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal6 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal6 = ROUND(@cal6), con6 = @con6, ina6 = @ina6, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(cal7), avg(con7), sum(ina7) into @cal7, @con7, @ina7 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and cal7 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set cal7 = ROUND(@cal7), con7 = @con7, ina7 = @ina7, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(promcal), avg(promcon), sum(sumina) into @promcal, @promcon, @sumina From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and promcal > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set promcal = ROUND(@promcal), promcon = @promcon, sumina = @sumina, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	Select avg(promcalgpo), avg(promcongpo), sum(suminagpo) into @promcalgpo, @promcongpo, @suminagpo From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp and padre <= 0 and promcalgpo > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios set promcalgpo = ROUND(@promcalgpo), promcongpo = @promcongpo, suminagpo = @suminagpo, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idemp = @pIdEmp;

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Update','Grupo_Promedios',@pIdGrupo,'Update Promedio Grupos'); 

	return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualizar_Promedios_Grupales_Idiomas`(`pIdGrupo` INT(10), `pIdGruAlu` INT(10), `pIdioma` INT(10), `pIdCiclo` INT(10), `pIdUser` INT(10), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin

	select idgrupo, idciclo, idemp into @pIdGrupo, @pIdCiclo, @pIdEmp From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma limit 1;

	Select idgrupo, idciclo, idioma, idemp into @pIdG, @pIdC, @pIdi, @pIdEmp From grupo_promedios_idiomas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;
	if ( (@pIdG IS NULL) or (@pIdi IS NULL) ) then
		Insert Into grupo_promedios_idiomas(idgrupo,idciclo, idioma, idemp)values(@pIdGrupo,@pIdCiclo,pIdioma,@pIdEmp);
	end if; 

	Select avg(cal0), avg(con0), sum(ina0) into @cal0, @con0, @ina0 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal0 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal0 = ROUND(@cal0), con0 = @con0, ina0 = @ina0, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(cal1), avg(con1), sum(ina1) into @cal1, @con1, @ina1 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal1 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal1 = ROUND(@cal1), con1 = @con1, ina1 = @ina1, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(cal2), avg(con2), sum(ina2) into @cal2, @con2, @ina2 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal2 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal2 = ROUND(@cal2), con2 = @con2, ina2 = @ina2, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(cal3), avg(con3), sum(ina3) into @cal3, @con3, @ina3 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal3 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal3 = ROUND(@cal3), con3 = @con3, ina3 = @ina3, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(cal4), avg(con4), sum(ina4) into @cal4, @con4, @ina4 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal4 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal4 = ROUND(@cal4), con4 = @con4, ina4 = @ina4, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(cal5), avg(con5), sum(ina5) into @cal5, @con5, @ina5 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal5 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal5 = ROUND(@cal5), con5 = @con5, ina5 = @ina5, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(cal6), avg(con6), sum(ina6) into @cal6, @con6, @ina6 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal6 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal6 = ROUND(@cal6), con6 = @con6, ina6 = @ina6, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(cal7), avg(con7), sum(ina7) into @cal7, @con7, @ina7 From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and cal7 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_promedios_idiomas set cal7 = ROUND(@cal7), con7 = @con7, ina7 = @ina7, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(promcal), avg(promcon), sum(sumina) into @promcal, @promcon, @sumina From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and promcal > 0 and idmatclas in (1,2,3,4,5);
    
	update grupo_promedios_idiomas set promcal = ROUND(@promcal), promcon = @promcon, sumina = @sumina, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	Select avg(promcalgpo), avg(promcongpo), sum(suminagpo) into @promcalgpo, @promcongpo, @suminagpo From _viBoletas where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp and padre <= 0 and promcalgpo > 0 and idmatclas in (1,2,3,4,5);
    
	update grupo_promedios_idiomas set promcalgpo = ROUND(@promcalgpo), promcongpo = @promcongpo, suminagpo = @suminagpo, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrupo = @pIdGrupo and idciclo = @pIdCiclo and idioma = pIdioma and idemp = @pIdEmp;

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Update','grupo_promedios_idiomas',@pIdGrupo,'Update Promedio Grupos por Idioma'); 

	Return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualizar_Promedios_Padres`(`pIdPadre` INT(10), `pIdGruAlu` INT(10), `pIdUser` INT(10), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(10) CHARSET utf8
    NO SQL
Begin

	Select avg(cal0), avg(con0), sum(ina0) into @cal0, @con0, @ina0 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal0 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal0 = ROUND(@cal0), con0 = @con0, ina0 = @ina0, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(cal1), avg(con1), sum(ina1) into @cal1, @con1, @ina1 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal1 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal1 = ROUND(@cal1), con1 = @con1, ina1 = @ina1, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(cal2), avg(con2), sum(ina2) into @cal2, @con2, @ina2 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal2 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal2 = ROUND(@cal2), con2 = @con2, ina2 = @ina2, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(cal3), avg(con3), sum(ina3) into @cal3, @con3, @ina3 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal3 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal3 = ROUND(@cal3), con3 = @con3, ina3 = @ina3, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(cal4), avg(con4), sum(ina4) into @cal4, @con4, @ina4 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal4 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal4 = ROUND(@cal4), con4 = @con4, ina4 = @ina4, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(cal5), avg(con5), sum(ina5) into @cal5, @con5, @ina5 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal5 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal5 = ROUND(@cal5), con5 = @con5, ina5 = @ina5, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(cal6), avg(con6), sum(ina6) into @cal6, @con6, @ina6 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal6 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal6 = ROUND(@cal6), con6 = @con6, ina6 = @ina6, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(cal7), avg(con7), sum(ina7) into @cal7, @con7, @ina7 From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and cal7 > 0 and idmatclas in (1,2,3,4,5);
	update boletas set cal7 = ROUND(@cal7), con7 = @con7, ina7 = @ina7, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(promcal), avg(promcon), sum(sumina) into @promcal, @promcon, @sumina From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and promcal > 0 and idmatclas in (1,2,3,4,5);
	update boletas set promcal = ROUND(@promcal), promcon = @promcon, sumina = @sumina, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	Select avg(promcalgpo), avg(promcongpo), sum(suminagpo) into @promcalgpo, @promcongpo, @suminagpo From _viBoletas where padre = pIdPadre and idgrualu = pIdGruAlu and promcalgpo > 0 and idmatclas in (1,2,3,4,5);
	update boletas set promcalgpo = ROUND(@promcalgpo), promcongpo = @promcongpo, suminagpo = @suminagpo, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrumat = pIdPadre and idgrualu = pIdGruAlu;

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Update','Boletas',pIdPadre,'IdBoleta-PROMEDIO-Padre'); 

	Return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualiza_Grupo_Alumno_Promedio`(`pIdGruAlu` INT(10), `pIdUser` INT(10), `pIdEmp` INT(10), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(10) CHARSET utf8
    NO SQL
Begin
	
	-- Actualiza_Grupo_Alumno_Promedio

	Select idgrualu into @cidg From grupo_alumno_promedio where idgrualu = pIdGruAlu;
	if (@cidg IS NULL) then
		Insert Into grupo_alumno_promedio(idgrualu)values(pIdGruAlu);
	end if; 

	Select avg(cal0), avg(con0), sum(ina0) into @cal0, @con0, @ina0 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal0 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal0 = ROUND(@cal0), con0 = @con0, ina0 = @ina0, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(cal1), avg(con1), sum(ina1) into @cal1, @con1, @ina1 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal1 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal1 = ROUND(@cal1), con1 = @con1, ina1 = @ina1, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(cal2), avg(con2), sum(ina2) into @cal2, @con2, @ina2 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal2 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal2 = ROUND(@cal2), con2 = @con2, ina2 = @ina2, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(cal3), avg(con3), sum(ina3) into @cal3, @con3, @ina3 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal3 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal3 = ROUND(@cal3), con3 = @con3, ina3 = @ina3, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(cal4), avg(con4), sum(ina4) into @cal4, @con4, @ina4 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal4 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal4 = ROUND(@cal4), con4 = @con4, ina4 = @ina4, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(cal5), avg(con5), sum(ina5) into @cal5, @con5, @ina5 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal5 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal5 = ROUND(@cal5), con5 = @con5, ina5 = @ina5, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(cal6), avg(con6), sum(ina6) into @cal6, @con6, @ina6 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal6 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal6 = ROUND(@cal6), con6 = @con6, ina6 = @ina6, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(cal7), avg(con7), sum(ina7) into @cal7, @con7, @ina7 From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and cal7 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set cal7 = ROUND(@cal7), con7 = @con7, ina7 = @ina7, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(promcal), avg(promcon), sum(sumina) into @promcal, @promcon, @sumina From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and promcal > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set promcal = ROUND(@promcal), promcon = @promcon, sumina = @sumina, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	Select avg(promcalgpo), avg(promcongpo), sum(suminagpo) into @promcalgpo, @promcongpo, @suminagpo From _viBoletas where idgrualu = pIdGruAlu and padre <= 0 and promcalgpo > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio set promcalgpo = ROUND(@promcalgpo), promcongpo = @promcongpo, suminagpo = @suminagpo, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	-- UPDATE 21-01-2015 22:46
	Select avg(promcalof), avg(promcon), sum(sumina) into @promcalof, @promconof, @suminaof From _viBoletas where idgrualu = pIdGruAlu and isoficial = 1 and promcal > 0 and idmatclas in (1,3);
	
    update grupo_alumno_promedio set promcalof = ROUND(@promcalof,1), promconof = @promconof, suminaof = @suminaof, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu;

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Update','grupo_alumno_promedio',@pIdGrupo,'Update Promedio Alumno'); 

	Return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualiza_Grupo_Alumno_Promedio_Idioma`(`pIdGruAlu` INT(10), `pIdioma` INT(10), `pIdUser` INT(10), `pIdEmp` INT(10), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(50) CHARSET utf8
    NO SQL
Begin

	Select idgrualu into @cidg From grupo_alumno_promedio_idioma where idgrualu = pIdGruAlu and idioma = pIdioma;
	if (@cidg IS NULL) then
		Insert Into grupo_alumno_promedio_idioma(idgrualu,idioma)values(pIdGruAlu,pIdioma);
	end if; 

	Select avg(cal0), avg(con0), sum(ina0) into @cal0, @con0, @ina0 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal0 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal0 = ROUND(@cal0), con0 = @con0, ina0 = @ina0, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(cal1), avg(con1), sum(ina1) into @cal1, @con1, @ina1 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal1 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal1 = ROUND(@cal1), con1 = @con1, ina1 = @ina1, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(cal2), avg(con2), sum(ina2) into @cal2, @con2, @ina2 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal2 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal2 = ROUND(@cal2), con2 = @con2, ina2 = @ina2, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(cal3), avg(con3), sum(ina3) into @cal3, @con3, @ina3 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal3 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal3 = ROUND(@cal3), con3 = @con3, ina3 = @ina3, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(cal4), avg(con4), sum(ina4) into @cal4, @con4, @ina4 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal4 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal4 = ROUND(@cal4), con4 = @con4, ina4 = @ina4, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(cal5), avg(con5), sum(ina5) into @cal5, @con5, @ina5 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal5 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal5 = ROUND(@cal5), con5 = @con5, ina5 = @ina5, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(cal6), avg(con6), sum(ina6) into @cal6, @con6, @ina6 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal6 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal6 = ROUND(@cal6), con6 = @con6, ina6 = @ina6, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(cal7), avg(con7), sum(ina7) into @cal7, @con7, @ina7 From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and cal7 > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set cal7 = ROUND(@cal7), con7 = @con7, ina7 = @ina7, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(promcal), avg(promcon), sum(sumina) into @promcal, @promcon, @sumina From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and promcal > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set promcal = ROUND(@promcal), promcon = @promcon, sumina = @sumina, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	Select avg(promcalgpo), avg(promcongpo), sum(suminagpo) into @promcalgpo, @promcongpo, @suminagpo From _viBoletas where idgrualu = pIdGruAlu and idioma = pIdioma and padre <= 0 and promcalgpo > 0 and idmatclas in (1,2,3,4,5);
	update grupo_alumno_promedio_idioma set promcalgpo = ROUND(@promcalgpo), promcongpo = @promcongpo, suminagpo = @suminagpo, ip = pIP, host = pHost, modi_por = pIdUser, modi_el = NOW() where idgrualu = pIdGruAlu and idioma = pIdioma;

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Update','grupo_alumno_promedio_idioma',pIdGruAlu,'Update Promedio Alumno por Dioma'); 

	Return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualiza_Promedios_Boletas_por_Grupo`(`pIdGrupo` INT(10), `pIdEmp` INT(10), `pIdCiclo` INT(10)) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin

-- Actualiza_Promedios_Boletas_por_Grupo

DECLARE xIdBoleta INT DEFAULT 0; 
DECLARE xIdGruMat INT DEFAULT 0; 
DECLARE xIdGruAlu INT DEFAULT 0; 
DECLARE xnivel INT DEFAULT 0;

DECLARE xcal0 DECIMAL(10,2) DEFAULT 0;
DECLARE xcal1 DECIMAL(10,2) DEFAULT 0;
DECLARE xcal2 DECIMAL(10,2) DEFAULT 0;
DECLARE xcal3 DECIMAL(10,2) DEFAULT 0;
DECLARE xcal4 DECIMAL(10,2) DEFAULT 0;
DECLARE xcal5 DECIMAL(10,2) DEFAULT 0;
DECLARE xcal6 DECIMAL(10,2) DEFAULT 0;
DECLARE xcal7 DECIMAL(10,2) DEFAULT 0;

DECLARE xcon0 DECIMAL(10,2) DEFAULT 0;
DECLARE xcon1 DECIMAL(10,2) DEFAULT 0;
DECLARE xcon2 DECIMAL(10,2) DEFAULT 0;
DECLARE xcon3 DECIMAL(10,2) DEFAULT 0;
DECLARE xcon4 DECIMAL(10,2) DEFAULT 0;
DECLARE xcon5 DECIMAL(10,2) DEFAULT 0;
DECLARE xcon6 DECIMAL(10,2) DEFAULT 0;
DECLARE xcon7 DECIMAL(10,2) DEFAULT 0;

DECLARE xina0 INT DEFAULT 0;
DECLARE xina1 INT DEFAULT 0;
DECLARE xina2 INT DEFAULT 0;
DECLARE xina3 INT DEFAULT 0;
DECLARE xina4 INT DEFAULT 0;
DECLARE xina5 INT DEFAULT 0;
DECLARE xina6 INT DEFAULT 0;
DECLARE xina7 INT DEFAULT 0;

DECLARE xidmatclas INT DEFAULT 0;

DECLARE ID INT DEFAULT 0;

DECLARE cur CURSOR FOR 
SELECT idboleta, idgrumat, idgrualu, clave_nivel, cal0,con0,ina0, cal1,con1,ina1, cal2,con2,ina2, cal3,con3,ina3, cal4,con4,ina4, cal5,con5,ina5, cal6,con6,ina6, cal7,con7,ina7,idmatclas
FROM _viBoletas
Where idemp = pIdEmp and idciclo = pIdCiclo and idgrupo = pIdGrupo ; 

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xIdBoleta, xIdGruMat, xIdGruAlu, xnivel, xcal0,xcon0,xina0,xcal1,xcon1,xina1, xcal2,xcon2,xina2, xcal3,xcon3,xina3, xcal4,xcon4,xina4, xcal5,xcon5,xina5, xcal6,xcon6,xina6, xcal7,xcon7,xina7,xidmatclas;
		
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;

	set @d = 0;

	if ((xnivel IS NULL) or (xnivel <= 0)) then 
		set @d = 0;
		set xnivel = 0;
	end if; 
	
	if (xnivel = 1 or xnivel = 5) then		
		if xcal0 > 0 then set @d = @d + 1; end if;
		if xcal1 > 0 then set @d = @d + 1; end if;
		if xcal2 > 0 then set @d = @d + 1; end if;
    end if; 

	if (xnivel = 2 or xnivel = 3 or xnivel = 4) then		
		if xcal0 > 0 then set @d = @d + 1; end if;
		if xcal1 > 0 then set @d = @d + 1; end if;
		if xcal2 > 0 then set @d = @d + 1; end if;
		if xcal3 > 0 then set @d = @d + 1; end if;
		if xcal4 > 0 then set @d = @d + 1; end if;
		if xcal5 > 0 then set @d = @d + 1; end if;
		if xcal6 > 0 then set @d = @d + 1; end if;
		if xcal7 > 0 then set @d = @d + 1; end if;
	end if;

	set @promcal = 0;
	set @promcon = 0;
	set @sumina = 0;
	set @promcalof = 0;
	set @promconof = 0;
	set @suminaof = 0;

	set @sumacal = xcal0 + xcal1 + xcal2 + xcal3 + 
		 			xcal4 + xcal5 + xcal6 + xcal7;

	set @sumacon = xcon0 + xcon1 + xcon2 + xcon3 + 
					xcon4 + xcon5 + xcon6 + xcon7;

	set @sumina = xina0 + xina1 + xina2 + xina3 + 
					xina4 + xina5 + xina6 + xina7;

	if ((NOT @sumacal IS NULL) and (@d > 0)) then 
		if (xidmatclas = 7) then
			set @promcal = @sumacal;
			set @promcon = @sumacon;
		else
			set @promcal = @sumacal / @d;
			set @promcon = @sumacon / @d;
		end if;
	end if; 
    
    IF(not @sumina IS NULL) THEN
		set @promina = @sumina;
    end if;

-- OFICIALES

	if (xnivel = 2 or xnivel = 3 or xnivel = 4) then

		-- Inasistencias	
        
		if (xidmatclas = 7) then 
			set @bim0 = xcal0 + xcal1;
			set @bim1 = xcal2;
			set @bim2 = xcal3 + xcal4;
			set @bim3 = xcal5;
			set @bim4 = xcal6 + xcal7;

		else

			set @bim0 = ((xcal0 + xcal1)/2);
			set @bim1 = xcal2;
			set @bim2 = ((xcal3 + xcal4)/2);
			set @bim3 = xcal5;
			set @bim4 = ((xcal6 + xcal7)/2);

			set @bim0 = ROUND( (@bim0/10),1 );
			set @bim1 = ROUND( (@bim1/10),1 );
			set @bim2 = ROUND( (@bim2/10),1 );
			set @bim3 = ROUND( (@bim3/10),1 );
			set @bim4 = ROUND( (@bim4/10),1 );

		end if;

		set @sumacalof = @bim0  + @bim1 + @bim2 + @bim3 + @bim4;

		-- Inasistencias
        
		if (xidmatclas = 7) then  

			set @sumaconof = xcon0 + xcon1 + xcon2 + xcon3 + xcon4 + xcon5 + xcon6 + xcon7;

			set @suminaof = xina0 + xina1 + xina2 + xina3 + xina4 + xina5 + xina6 + xina7;

			set @promcalof = @sumacalof;
			set @promconof = @sumaconof;


		else
			set @sumaconof = ((xcon0 + xcon1)/2) + xcon2 + ((xcon3 + 
							xcon4)/2) + xcon5 + ((xcon6 + xcon7)/2);

			set @suminaof = xina0 + xina1 + xina2 + xina3 + 
							xina4 + xina5 + xina6 + xina7;

			set @promcalof = ROUND( ( @sumacalof / 5 ), 1 );

			set @promconof = @sumaconof / 5;

		end if;
	    
	    IF(not @suminaof IS NULL) THEN
			set @prominaof = @suminaof;
	    end if;
        

	     if (@promcal < 60) then
	    	set @promcalof = 5;
	     end if;

    end if; 

	if (xnivel = 5) then	

		set @bim0 = ROUND( (xcal6/10),0 );
		set @bim1 = ROUND( (xcal3/10),0 );
	    
	    if (xcal6 < 60) then
			set @bim0 = 5;
	    end if;
	    
	    if (xcal3 < 60) then
			set @bim1 = 5;
	    end if;

		set @promcalof = ROUND( ( (@bim0  + @bim1) / 2 ),0 );

	     if (xcal7 < 60) then
	    	set @promcalof = 5;
	     end if;

    end if; 


    update boletas 
    	set promcal = ROUND(@promcal),
    		promcon = @promcon,
    		sumina = @sumina,
    		bim0 = @bim0,
    		bim1 = @bim1,
    		bim2 = @bim2,
    		bim3 = @bim3,
    		bim4 = @bim4,
    		promcalof = @promcalof,
    		promconof = @promconof,
    		suminaof = @suminaof
    	where idboleta	= xIdBoleta;

END LOOP bucle;

CLOSE cur;


return "OK";
    
End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Actualiza_Promedios_Grupales_por_Materia`(`pIdGruAlu` INT(10)) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin

DECLARE ID INT DEFAULT 0;
DECLARE xIdGruMat INT DEFAULT 0;

DECLARE cur CURSOR FOR 
SELECT idgrumat 
FROM boletas
Where idgrualu = pIdGruAlu; 

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xIdGruMat;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;
/*
		Select idmatclas, sum(cal0), sum(cal1), sum(cal2), sum(cal3), sum(cal4), sum(cal5), sum(cal6), sum(cal7) 
        INTO @xidmatclas, @xcal0, @xcal1, @xcal2, @xcal3, @xcal4, @xcal5, @xcal6, @xcal7 
		From _viBoletas 
		Where idgrumat = xIdGruMat and idmatclas = 7
		group by idmatclas;
		
		if ( (NOT @xidmatclas IS NULL) and @xidmatclas = 7) then
			
			set @xsumina =  @xcal0 + @xcal1 + @xcal2 + @xcal3 + @xcal4 + @xcal5 + @xcal6 + @xcal7;
		
			update boletas 
				set promcal = @xsumina,
					sumina = @xsumina
				Where idgrumat = xIdGruMat;	

				Select sum(promcal), sum(sumina) 
		        INTO @prome, @suma
				From boletas 
				Where idgrumat = xIdGruMat;

				update boletas 
					set promcalgpo = @prome,
						suminagpo  = @suma
					Where idgrumat = xIdGruMat;	

		else
*/
			-- Mod: 21-01-2015 17:14
			Select avg(promcal), avg(promcon), sum(sumina)
	        INTO @prome, @cond, @suma
			From boletas 
			Where idgrumat = xIdGruMat;

			update boletas 
				set promcalgpo = ROUND(@prome),
					promcongpo = @cond,
					suminagpo  = @suma
				Where idgrumat = xIdGruMat;	

			
--		end if;	


END LOOP bucle;

CLOSE cur;

return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Clonar_Config_Eval_Anterior_Gru_Mat_Prof`(`pIdGruMat` INT(10), `pNumEval` INT(5), `pIdUser` INT(10), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS int(10)
    NO SQL
Begin

DECLARE xNumEval INT DEFAULT 0;
DECLARE xDescripcion VARCHAR(100) Default '';
Declare xPorc DECIMAL(10,2) Default 0;
DECLARE ID INT DEFAULT 0;

DECLARE cur CURSOR FOR 
SELECT descripcion, porcentaje 
FROM grupo_materia_config 
Where idgrumat = pIdGruMat and num_eval = pNumEval - 1
Order by idgrumatcon asc; 

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xDescripcion, xPorc;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;

		Insert Into grupo_materia_config(idgrumat, descripcion, porcentaje, num_eval, idemp, ip, host, creado_por, creado_el)values( pIdGruMat, xDescripcion, xPorc, pNumEval, pIdEmp, pIP, pHost, pIdUser, NOW() );
        
END LOOP bucle;

CLOSE cur;

INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
VALUES(pIdUser,NOW(),'Insert','Grupo_Materia_Config',pIdGruMat,'clone-GRUMATCON'); 

Return pNumEval;

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Copiar_Alumnos_de_Grupo_a_Grupo`(`pGrupoOrigen` INT(10), `pGrupoDestino` INT(10), `pIdCiclo` INT(10), `pIdUser` INT(10), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin

DECLARE ID INT DEFAULT 0;
DECLARE xIdAlumno INT DEFAULT 0;
DECLARE xNum_Lista INT DEFAULT 0;

DECLARE cur CURSOR FOR 
SELECT idalumno, num_lista 
FROM grupo_alumnos where idgrupo = pGrupoOrigen; 

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xIdAlumno, xNum_Lista;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;

		Insert Into grupo_alumnos(idciclo, idgrupo, idalumno, num_lista, idemp, ip, host, creado_por, creado_el)
		values(pIdCiclo, pGrupoDestino, xIdAlumno, xNum_Lista, pIdEmp, pIP, pHost, pIdUser, NOW() );
        
END LOOP bucle;

CLOSE cur;

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Insert','Move_Alumnos',pIdGruAlu,'IdGruAlu'); 


	return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Generar_Estado_de_Cuenta_por_Alumno`(`pClave_Nivel` INT(10), `pBeca_SEP` DECIMAL(10,2), `pBeca_Arji` DECIMAL(10,2), `pBeca_SP` DECIMAL(10,2), `pBeca_Bach` DECIMAL(10,2), `pIdCiclo` INT(10), `pIdFamilia` INT(10), `pIdAlu` INT(10), `pIdEmp` INT(5), `pIdUser` INT(10), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin
DECLARE xIdPago INT Default 0;
DECLARE xImporte Decimal Default 0.00;
DECLARE xIs_Pagos_Diversos INT Default 0;

DECLARE ID INT Default 0;
DECLARE Cur CURSOR FOR 
SELECT idpago 
FROM _viPagos	
Where clave_nivel = pClave_Nivel and aplica_a = 0; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;
OPEN Cur;
	bucle: LOOP

		FETCH Cur INTO xIdPago;
			IF (ID = 1) THEN
				LEAVE bucle;
			END IF;

           set @Y = Generar_Estado_de_Cuenta_por_Concepto(xIdPago,pIdCiclo,pIdFamilia,pIdAlu,pClave_Nivel,pBeca_SEP,pBeca_Arji,pBeca_SP,pBeca_Bach,pIdUser,pIdEmp,pIP,pHost,0,0,0);
            

	END LOOP bucle;

CLOSE Cur;

return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Generar_Estado_de_Cuenta_por_Concepto`(`pIdPago` INT(10), `pIdCiclo` INT(10), `pIdFamilia` INT(10), `pIdAlumno` INT(10), `pClave_Nivel` INT(10), `pBeca_SEP` DECIMAL(10,4), `pBeca_Arji` DECIMAL(10,4), `pBeca_SP` DECIMAL(10,4), `pBeca_Bach` DECIMAL(10,4), `pIdUser` INT(10), `pIdEmp` INT(10), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8, `pNumPagos` INT(3) ZEROFILL, `pDescto` DECIMAL(7,4) ZEROFILL, `pRecargo` DECIMAL(7,4) ZEROFILL) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin
DECLARE cur_year INT;
DECLARE cur_month INT;
DECLARE temp_date VARCHAR(10);
DECLARE start_date DATE;
DECLARE end_date DATE;

DECLARE xImporte Decimal Default 0.00;
DECLARE xIs_Pagos_Diversos INT Default 0;
DECLARE xIs_Descto_Beca INT Default 0;

DECLARE ID INT Default 0;
DECLARE Cur CURSOR FOR 
SELECT importe, is_pagos_diversos, is_descto_beca 
FROM _viPagos   
Where idpago = pIdPago
limit 1; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;
OPEN Cur;
    bucle: LOOP

        FETCH Cur INTO xImporte, xIs_Pagos_Diversos, xIs_Descto_Beca;
            IF (ID = 1) THEN
                LEAVE bucle;
            END IF;
            
            if pNumPagos = 0 then 
                set @numPagos = 10;
            else
                if pNumPagos = 1 then 
                    set @numPagos = 1;
                else
                    if pNumPagos = 10 then 
                        set @numPagos = 10;
                    else
                        set @numPagos = 10;
                    end if;
                end if;
            end if;
            
            if xIs_Pagos_Diversos = 0 then
                -- set pNumPagos = 1;
                set @numPagos = 1;
            end if;
            
            if pNumPagos = 12 then 
                set @subtotal = xImporte/12;
            else
                set @subtotal = xImporte/@numPagos;
            end if;

            SET @x = 1;

            SET cur_month = (SELECT MONTH(CURRENT_DATE()));
            SET cur_year = (SELECT YEAR(CURRENT_DATE()));

            if (cur_month >= 1 and cur_month <= 8) then
                SET cur_year  =  cur_year - 1;
            end if; 

            SET temp_date = (SELECT CONCAT(cur_year,'-08-10') );
            SET start_date = (SELECT CAST(temp_date AS DATE) );

            WHILE @x  <= @numPagos DO
                
                if (xIs_Descto_Beca = 1) then 
                    set @porcDescto = pBeca_SEP + pBeca_Arji + pBeca_SP + pBeca_Bach + pDescto;
                else
                    set @porcDescto = pDescto;
                end if;

                set @Descto = @subtotal * (@porcDescto / 100);
                set @Importe = @subtotal - @Descto;

                set @porcRecargo = pRecargo;
                set @Recargo = @subtotal * (@porcRecargo / 100);
                set @Total = @Importe + @Recargo;
                set @subtotal2 = @subtotal;

                if pNumPagos = 12 then
                    if (@x = 3 or @x = 7) then
                        set @subtotal2 = @subtotal * 2;
                        set @Descto = @Descto * 2;
                        set @Importe = @Importe * 2;
                        set @Recargo = @Recargo * 2;
                        set @Total = @Total * 2;                    
                    end if; 
                end if;

                SET start_date = (SELECT DATE_ADD(start_date, INTERVAL 1 MONTH));

                Insert Into estados_de_cuenta(idciclo, clave_nivel, idfamilia, idalumno, idpago, num_pago, subtotal, porcdescto,descto, importe, porcrecargo, recargo, total, is_vencimiento, vencimiento, idemp, ip, host, creado_por, creado_el)
                Values(pIdCiclo, pClave_Nivel, pIdFamilia, pIdAlumno, pIdPago, @x, @subtotal2, pDescto, @Descto, @Importe, pRecargo, @Recargo, @Total, 1,start_date, pIdEmp, pIP, pHost, pIdUser, NOW() );

                SET  @x = @x + 1;

            END WHILE;

    END LOOP bucle;

CLOSE Cur;

return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Generar_Estado_de_Cuenta_por_Familia`(`pIdFamilia` INT(10), `pIdCiclo` INT(10), `pIdEmp` INT(5), `pIdUser` INT(10), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin
DECLARE xClave_Nivel Decimal(10,2) Default 0;
DECLARE xBeca_SEP Decimal(10,2) Default 0;
DECLARE xBeca_Arji Decimal(10,2) Default 0;
DECLARE xBeca_SP Decimal(10,2) Default 0;
DECLARE xBeca_Bach Decimal(10,2) Default 0;
DECLARE xIdAlu INT Default 0;
DECLARE ID INT Default 0;

DECLARE Cur CURSOR FOR 
SELECT clave_nivel,beca_sep,beca_arji,beca_sp,beca_bach,idalumno
FROM _viGrupo_Alumnos	
Where idciclo = pIdCiclo and idfamilia = pIdFamilia; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;
OPEN Cur;
	bucle: LOOP

		FETCH Cur INTO xClave_Nivel,xBeca_SEP,xBeca_Arji,xBeca_SP,xBeca_Bach,xIdAlu;
			IF (ID = 1) THEN
				LEAVE bucle;
			END IF;

			set @X = Generar_Estado_de_Cuenta_por_Alumno(xClave_Nivel,xBeca_SEP,xBeca_Arji,xBeca_SP,xBeca_Bach,pIdCiclo,pIdFamilia,xIdAlu,pIdEmp,pIdUser,pIP,pHost);
	
	END LOOP bucle;

CLOSE Cur;

return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Generar_Numero_de_Lista_Por_Grupo`(`xIdGrupo` INT(10), `pParam0` INT(2), `pIdUser` INT(5), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS int(10)
    NO SQL
Begin

DECLARE xNumLista INT DEFAULT 0;
DECLARE xIdGruAlu INT DEFAULT 0;
DECLARE xNL INT DEFAULT 0;
DECLARE ID INT DEFAULT 0;


DECLARE cur CURSOR FOR 
SELECT idgrualu, num_lista 
FROM _viGrupo_Alumnos 
Where idgrupo = xIdGrupo 
Order by alumno asc;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xIdGruAlu, xNumLista;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;

		If pParam0 = 0 Then

			if (xNL = 0) then 

				SELECT max(num_lista) into @maximo 
				FROM _viGrupo_Alumnos 
				Where idgrupo = xIdGrupo
				group by xIdGrupo; 
				set xNL = @maximo; 		

			end if;
			
			set xNL = xNL + 1; 		
			
			update grupo_alumnos 
			set num_lista = xNL,
				ip = pIP,
				host = pHost,
				modi_por = pIdUser,
				modi_el = NOW()
			where idgrualu = xIdGruAlu and 
				num_lista <= 0;      

		End If;
        
END LOOP bucle;

CLOSE cur;

INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
VALUES(pIdUser,NOW(),'Update','Grupo_Alumnos',xIdGrupo,'num_lista'); 

Return xNL;

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Generar_Usuario_from_Alumno`(`pIdAlu` INT(10)) RETURNS int(10)
    NO SQL
Begin

set @idusuario = 0;

	SELECT ap_paterno, ap_materno, nombre, email, idemp, creado_por INTO @app,@apm,@nombre,@email,@idemp,@creado_por FROM cat_alumnos where idalumno = pIdAlu LIMIT 1;

		set @valDat1 = (Select concat('alu',LPAD(pIdAlu,4,'0')) );

		Insert into usuarios(username,password,apellidos,nombres,correoelectronico,idusernivelacceso,idemp,creado_por,creado_el,status_usuario)
    Values(@valDat1,md5(@valDat1),concat(@app,' ',@apm),@nombre,@email,6,@idemp,@creado_por,NOW(),1);
    
    	set @idusuario = (Select LAST_INSERT_ID() );
    
		Update cat_alumnos set idusuario = @idusuario where idalumno = pIdAlu;


return @idusuario;

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Generar_Usuario_from_Persona`(`pIdPer` INT(10)) RETURNS int(10)
    NO SQL
Begin

	set @idusuario = 0;

	SELECT ap_paterno, ap_materno, nombre, email1, idemp, creado_por 
    INTO @app,@apm,@nombre,@email1,@idemp,@creado_por 
    FROM cat_personas 
    where idpersona = pIdPer;

		set @valDat1 = (Select concat('per',LPAD(pIdPer,4,'0')) );

		Insert into usuarios(username,password,apellidos,nombres,correoelectronico,idusernivelacceso,idemp,creado_por,creado_el,status_usuario)
    Values(@valDat1,md5(@valDat1),concat(@app,' ',@apm),@nombre,@email1,10,@idemp,@creado_por,NOW(),1);
    
    	set @idusuario = (Select LAST_INSERT_ID() );
    
		Update cat_personas set idusuario = @idusuario where idpersona = pIdPer;


return @idusuario;

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Generar_Usuario_from_Profesor`(`pIdProfesor` INT(10)) RETURNS int(10)
    NO SQL
Begin

set @idusuario = 0;

	SELECT ap_paterno, ap_materno, nombre, email, idemp, creado_por INTO @app,@apm,@nombre,@email,@idemp,@creado_por FROM cat_profesores where idprofesor = pIdProfesor LIMIT 1;

		set @valDat1 = (Select concat('pro',LPAD(pIdProfesor,4,'0')) );

		Insert into usuarios(username,password,apellidos,nombres,correoelectronico,idusernivelacceso,idemp,creado_por,creado_el,status_usuario)
    Values(@valDat1,md5(@valDat1),concat(@app,' ',@apm),@nombre,@email,6,@idemp,@creado_por,NOW(),1);
    
    	set @idusuario = (Select LAST_INSERT_ID() );
    
		Update cat_profesores set idusuario = @idusuario where idprofesor = pIdProfesor;


return @idusuario;

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Inserta_Bol_Partes_alumnos_agregados_a_lo_ultimo`(`pIdGruMat` INT(10), `pIdGruAlu` INT(10), `pIdBoleta` INT(10), `pIdBolComodin` INT(10), `pIdUser` INT) RETURNS int(10)
    NO SQL
Begin
	DECLARE ID INT DEFAULT 0;
	DECLARE xIdGruMatCon INT DEFAULT 0;
	DECLARE xIdEmp INT DEFAULT 0;

	DECLARE cur CURSOR FOR 
	SELECT idgrumatcon, idemp 
	FROM boleta_partes WHERE idboleta = pIdBolComodin; 

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

	OPEN cur;

	bucle: LOOP

		FETCH cur INTO xIdGruMatCon, xIdEmp;
			IF (ID = 1) THEN
				LEAVE bucle;
			END IF;

			Insert Into boleta_partes(idgrumatcon, idboleta, idemp, creado_por, creado_el)
			values(xIdGruMatCon, pIdBoleta, xIdEmp, pIdUser, NOW());
	        
	END LOOP bucle;

	CLOSE cur;		

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Insert','Boleta_Partes',pIdBoleta,'IdBoleta'); 


	return 0;

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Llamar_Actualizar_Promedios_Padres_from_IdGruAlu`(`pIdGruAlu` INT(10), `pIdUser` INT(10), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100) CHARSET utf8) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin

DECLARE ID INT DEFAULT 0;
DECLARE xPadre INT DEFAULT 0;

DECLARE cur CURSOR FOR 
SELECT distinct padre
FROM 
_viBoletas 
WHERE idgrualu = pIdGruAlu AND padre > 0;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET ID = 1;

OPEN cur;

bucle: LOOP

	FETCH cur INTO xPadre;
		IF (ID = 1) THEN
			LEAVE bucle;
		END IF;

    	Set @X = Actualizar_Promedios_Padres(xPadre,pIdGruAlu,pIdUser,pIdEmp,pIP,pHost);


END LOOP bucle;

CLOSE cur;

return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Llamar_Actualiza_Promedios_Boletas_por_Grupo`(`pIdGruAlu` INT(10)) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin 

Select idgrupo, idciclo, idemp into @idgrupo, @idciclo, @idemp 
from _viBoletas where idgrualu = pIdGruAlu limit 1;

set @x = Actualiza_Promedios_Boletas_por_Grupo(@idgrupo, @idemp, @idciclo);

return @x;

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Mover_Alumno_de_Grupo_a_Grupo`(`pIdGruAlu` INT(10), `pGrupoOrigen` INT(10), `pGrupoDestino` INT(10), `pIdUser` INT(10), `pIdEmp` INT(5), `pIP` VARCHAR(50) CHARSET utf8, `pHost` VARCHAR(100)) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin
	DECLARE ID INT DEFAULT 0;
	DECLARE xIdGruMat INT DEFAULT 0;

	DECLARE cur CURSOR FOR 
	SELECT distinct (idgrumat) FROM _viBoletas WHERE idgrupo = pGrupoDestino;

	DELETE FROM boletas WHERE idgrualu = pIdGruAlu;
	UPDATE grupo_alumnos SET idgrupo = pGrupoDestino WHERE idgrualu = pIdGruAlu AND idgrupo = pGrupoOrigen; 
	
	OPEN cur;

	bucle: LOOP

		FETCH cur INTO xIdGruMat;
			IF (ID = 1) THEN
				LEAVE bucle;
			END IF;

			Insert Into boletas(idgrumat,idgrualu,idemp,ip,host,creado_por,creado_el)
			value(xIdGruMat,pIdGruAlu,pIdEmp,pIP,pHost,pIdUser,NOW() );

	END LOOP bucle;

	CLOSE cur;		

	INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
	VALUES(pIdUser,NOW(),'Insert','Move_Alumnos',pIdGruAlu,'IdGruAlu'); 


	return "OK";

End$$

CREATE DEFINER=`tecnoint`@`localhost` FUNCTION `Quitar_Concepto_de_Pago_de_Alumno`(`pIdFamilia` INT(10), `pIdAlumno` INT(10), `pIdEdoCta` INT(10), `pIdPago` INT(10), `pIdUser` INT(10)) RETURNS varchar(100) CHARSET utf8
    NO SQL
Begin

set @X = (Select status_movto from estados_de_cuenta where idfamilia = pIdFamilia and idalumno = pIdAlumno and idpago = pIdPago and status_movto = 1 limit 1);

if ( @X IS NULL ) then 

	-- Delete from `SI se puede elimianer este Concepto.` where x = -1;

	Delete from estados_de_cuenta 
	where idfamilia = pIdFamilia and 
			idalumno = pIdAlumno and 
			idpago = pIdPago and 
			status_movto = 0;

else

	Delete from `NO se puede elimianer este Concepto.` where x = -1;

end if;

	return "OK";

End$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_clases`
--

CREATE TABLE IF NOT EXISTS `cat_clases` (
  `idclase` int(10) NOT NULL AUTO_INCREMENT,
  `idoficina` int(10) NOT NULL DEFAULT '0',
  `clase` varchar(100) NOT NULL,
  `status_clase` int(2) NOT NULL DEFAULT '1',
  `idemp` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idclase`),
  KEY `empsts0` (`idemp`,`status_clase`),
  KEY `ofnampsts` (`idoficina`,`idemp`,`status_clase`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Clases' AUTO_INCREMENT=61 ;

--
-- Volcado de datos para la tabla `cat_clases`
--

INSERT INTO `cat_clases` (`idclase`, `idoficina`, `clase`, `status_clase`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, 23, 'CERTIFICACIÓN DE DOCUMENTOS Y CONSTANCIAS DE NO ADEUDO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(2, 23, 'ANUENCIA MUNICIPAL (APERTURA Y REVALIDACIÓN DE CARNICERIAS URBANAS Y RURALES)', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(3, 23, 'OTORGAMIENTO ANUENCIA MUNICIPAL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(4, 23, 'INSPECCIÓN DE PROTECCIÓN CIVIL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(5, 1, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(6, 2, 'JUECES CALIFICADORES ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(7, 2, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(8, 3, 'SERVICIO DE RECOLECCIÓN DE BASURA PESOS EN TONELADAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(9, 3, 'SERVICIO DE RECOLECCIÓN DE BASURA EN LOTES BALDÍOS PESOS EN TONELADAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(10, 3, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(11, 4, 'PERMISOS DE DEMOLICIÓN POR METRO CUADRADO ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(12, 4, 'DE TERRENOS A PERPETUIDAD EN LOS CEMENTERIOS POR CADA LOTE DE DOS METROS DE LONGITUD POR UNO DE ANCH', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(13, 4, 'DE TERRENOS A PERPETUIDAD EN LOS CEMENTERIOS POR CADA LOTE DE DOS METROS DE LONGITUD POR UNO DE ANCH', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(14, 4, 'POR CESIÓN DE DERECHOS DE PROPIEDAD Y BOVEDAS ENTRE PARTICULARES', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(15, 4, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(16, 4, 'CONSTRUCCIÓN, REMODELACIÓN DE BOVEDAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(17, 4, 'GUARDARESTOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(18, 4, 'INHUMACIÓN, EXHUMACIÓN, RE INHUMACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(19, 4, 'SERVICIOS DIVERSOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(20, 4, 'OTORGAMIENTO DE CONSESIÓN DE LOCAL EN MDO. MUNICIPAL M2', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(21, 4, 'AUTORIZACIÓN TRASPASOS DE LOCALES ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(22, 4, 'RECUPERACIÓN DE LOS LOCALES Y/O ESPACIOS DE MDO.', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(23, 4, 'ÁREAS ADYACENTES A LOS MERCADOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(41, 13, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(24, 5, 'INSPECCIÓN DE PROTECCIÓN CIVIL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(25, 5, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(26, 5, 'EXPEDICIÓN DE CONSTANCIA DE AFECTACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(27, 5, 'INSPECCIÓN DE DICTÁMENES DE RIESGO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(28, 6, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(29, 6, '(PERMISOS DIVERSOS)', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(30, 6, 'INGRESOS DIVERSOS DE FISCALIZACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(31, 7, 'DERECHO DE OCUPACIÓN DE VÍA PÚBLICA AMBULANTE', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(32, 7, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(33, 8, 'ANUENCIAS FISCALIZACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(34, 8, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(35, 9, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(36, 10, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(37, 11, 'ESPECTACULOS PUBLICOS DIVERSOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(38, 12, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(39, 12, 'RECTIFICACIÓN DE MEDIDAS Y COLINDANCIAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(40, 12, 'POR TRAMITES EFECTUADOS VÍA MODEM', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(42, 14, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(43, 15, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(44, 16, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(45, 17, 'PERMISO DE NO INCONVENIENCIA DE CONTAMINACIÓN  AUDITIVA, FIJA O MÓVIL (PERIFONEO)', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(46, 17, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(47, 18, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(48, 19, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(49, 20, 'RECUPERACIÓN DIF CUOTAS CENDI', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(50, 20, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(51, 21, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(52, 22, 'PARA CONSTRUCCIONES DE LOZA DE CONCRETO Y PISO DE MOSAICO O MÁRMOL POR METRO CUADRADO ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(53, 22, 'PARA OTRAS CONSTRUCCIONES POR METRO CUADRADO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(54, 22, 'CONSTRUCCIONES BARDAS, RELLENOS Y/O EXCAVACIONES ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(55, 22, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(56, 22, '1. ARRIMO DE CAÑOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(57, 22, 'ALINEAMIENTO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(58, 22, 'RECTIFICACIÓN DE MEDIDAS A SOLICITUD DEL INTERESADO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(59, 22, 'APROBACIÓN DE PLANOS DE CONSTRUCCIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(60, 20, '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1, '2015-03-23 16:55:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_dependencias`
--

CREATE TABLE IF NOT EXISTS `cat_dependencias` (
  `iddependencia` int(10) NOT NULL AUTO_INCREMENT,
  `dependencia` varchar(100) NOT NULL,
  `status_dependencia` int(2) NOT NULL DEFAULT '1',
  `idemp` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(10) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`iddependencia`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Dependencias' AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `cat_dependencias`
--

INSERT INTO `cat_dependencias` (`iddependencia`, `dependencia`, `status_dependencia`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, 'SECRETARÍA DEL H. AYUNTAMIENTO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(2, 'DIRECCIÓN DE ASUNTOS JURÍDICOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(3, 'COORDINACIÓN GENERAL DE SERVICIOS MUNICIPALES', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(4, 'COORDINACIÓN DE PROTECCIÓN CIVIL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(5, 'COORDINACIÓN DE FISCALIZACIÓN Y NORMATIVIDAD', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(6, 'CONTRALORÍA MUNICIPAL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(7, 'COORDINACIÓN DE SALUD ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(8, 'DIRECCIÓN DE FINANZAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(9, 'SINDICATURA', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(10, 'DIRECCCIÓN DE ADMINISTRACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(11, 'DIRECCIÓN DE DESARROLLO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(12, 'DIRECCIÓN DE EDUCACIÓN, CULTURA Y RECREACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(13, 'DIRECCIÓN DE PROTECCIÓN AMBIENTAL Y DESARROLLO SUSTENTABLE', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(14, 'DIRECCIÓN DE FOMENTO ECONÓMICO Y TURISMO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(15, 'INSTITUTO DE PLANEACIÓN Y DESARROLLO URBANO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(16, 'DIRECCIÓN DEL DIF', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(17, 'COORDINACIÓN GENERAL DEL SAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(18, 'DIRECCIÓN DE OBRAS PÚBLICAS', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1, '2015-03-23 15:36:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_ingresos`
--

CREATE TABLE IF NOT EXISTS `cat_ingresos` (
  `idingreso` int(10) NOT NULL AUTO_INCREMENT,
  `idsubclase` int(10) NOT NULL DEFAULT '0',
  `tipo` int(2) NOT NULL DEFAULT '0',
  `porcentaje_ingreso` decimal(5,2) NOT NULL DEFAULT '0.00',
  `dsm_min` decimal(5,2) NOT NULL DEFAULT '0.00',
  `dsm_max` decimal(5,2) NOT NULL DEFAULT '0.00',
  `costo_min` decimal(12,2) NOT NULL DEFAULT '0.00',
  `costo_max` decimal(12,2) NOT NULL DEFAULT '0.00',
  `clave` varchar(20) NOT NULL,
  `ingreso` text NOT NULL,
  `ano` int(4) NOT NULL DEFAULT '0',
  `status_ingreso` int(2) NOT NULL DEFAULT '1',
  `idemp` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idingreso`),
  KEY `empsts` (`idemp`,`status_ingreso`),
  KEY `tipo` (`tipo`),
  KEY `subclassempsts` (`idsubclase`,`idemp`,`status_ingreso`),
  KEY `tipoempsts` (`tipo`,`idemp`,`status_ingreso`),
  KEY `stuctipoempsts` (`idsubclase`,`tipo`,`idemp`,`status_ingreso`),
  KEY `idsubclase` (`idsubclase`),
  FULLTEXT KEY `inreso_text` (`ingreso`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Ingresos' AUTO_INCREMENT=382 ;

--
-- Volcado de datos para la tabla `cat_ingresos`
--

INSERT INTO `cat_ingresos` (`idingreso`, `idsubclase`, `tipo`, `porcentaje_ingreso`, `dsm_min`, `dsm_max`, `costo_min`, `costo_max`, `clave`, `ingreso`, `ano`, `status_ingreso`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, 75, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4306-010000', 'BÚSQUEDA DE CUALQUIER DOCUMENTO EN LOS ARCHIVOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(2, 75, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '4306-020001', 'CERTIFICACIÓN DE REG. DE FIERROS Y SEÑALES PARA MARCAR GANADO Y MADERERO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(3, 1, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4306-030011', 'A. HASTA 10 HOJAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(4, 1, 4, '0.00', '0.10', '0.10', '6.65', '6.65', '4306-030011', 'B. POR HOJA SUBSECUENTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(5, 1, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '4306-030011', 'CERTIFICACIÓN DE DOCUMENTOS Y CONSTANCIA DE NO ADEUDO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(6, 2, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4409-010000', 'ANUENCIA MUNICIPAL (APERTURA Y REVALIDACIÓN DE CARNICERIAS URBANAS Y RURALES)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(7, 2, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4409-010000', '(RUSTICO)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(8, 2, 4, '0.00', '15.00', '15.00', '996.75', '996.75', '4409-010000', '(URBANO)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(9, 3, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4409-020000', 'ANUENCIA RUSTICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(10, 3, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4409-020000', 'ANUENCIA URBANA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(11, 4, 4, '0.00', '9.00', '9.00', '598.05', '598.05', '4416-040000', 'A. EVENTUAL / POR EVENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(12, 5, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-040000', '1. HASTA 20 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(13, 5, 4, '0.00', '7.00', '7.00', '465.15', '465.15', '4416-040000', '2. 21 A 40 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(14, 5, 4, '0.00', '9.00', '9.00', '598.05', '598.05', '4416-040000', '3. 41 A 60 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(15, 5, 4, '0.00', '11.00', '11.00', '730.95', '730.95', '4416-040000', '4. 61 A 80 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(16, 5, 4, '0.00', '13.00', '13.00', '863.85', '863.85', '4416-040000', '5. 81 Y MAS METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(17, 4, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4416-040000', 'EXPEDICIÓN DE CONSTANCIA DE PROTECCIÓN CIVIL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(18, 4, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4416-040000', 'REEXPEDICIÓN DE CONSTANCIA DE PROTECCIÓN CIVIL POR MODIFICACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(19, 75, 4, '0.00', '1.00', '1.00', '66.45', '66.45', '4416-100000', 'REVALIDACIÓN DE FIERRO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(20, 75, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4305-010000', 'EXPEDICIÓN DE TÍTULOS DE TERRENOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(21, 75, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4303-030000', 'POR REPOSICIÓN DE TÍTULOS DE PROPIEDAD', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(22, 75, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010008', 'MULTA SECRETARÍA MUNICIPAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(23, 75, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4417-010000', 'INGRESOS DIVERSOS SECRETARÍA DEL H. AYUNTAMIENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(24, 75, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4417-010001', 'CARTA DE RADICACIÓN (EXTRANJEROS)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(25, 75, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4417-010002', 'CONSTANCIA DE ORIGEN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(26, 75, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '4417-010003', 'CERTIFICACIÓN DE PUNTOS DE ACUERDOS A PARTICULARES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(27, 75, 4, '0.00', '200.00', '200.00', '999.99', '999.99', '4417-010004', 'EVENTOS DEPORTIVOS CON CIERRE DE VIALIDADES PARA EVENTOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(28, 75, 4, '0.00', '200.00', '200.00', '999.99', '999.99', '4417-010005', 'OCUPACIÓN DE ÁREAS PÚBLICAS PARA EVENTOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(29, 75, 4, '0.00', '6.00', '6.00', '398.70', '398.70', '4417-010006', 'CONSTANCIA PARA NOTORIO ARRAIGO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(30, 6, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4306-010000', 'BÚSQUEDA DE CUALQUIER DOCUMENTO EN LOS ARCHIVOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(31, 6, 4, '0.00', '1.00', '1.00', '66.45', '66.45', '4306-020003', 'POR CERTIFICACIÓN DE ACTA DE NACIMIENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(32, 6, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4306-020004', 'CERTIFICACIÓN DE DEFUNCIÓN, SUPERVIVENCIA, MATRIMONIO, CONSTANCIA DE ACTOS POSITIVOS O NEGATIVOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(33, 6, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-020005', 'CERTIFICACIÓN DE ACTA DE DIVORCIO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(34, 6, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4306-030001', 'ASENTAMIENTO O EXPOSICIÓN, RECONOCIMIENTO, DESIGNACIÓN Y SUPERVIVENCIA CELEBRADO A DOMICILIO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(35, 6, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-030002', 'POR CADA ACTO CELEBRADO EN LAS OFICINAS DEL REGISTRO CIVIL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(36, 6, 4, '0.00', '30.00', '30.00', '999.99', '999.99', '4306-030004', 'POR CELEBRACIÓN DE MATRIMONIO EFECTUADO A DOMICILIO EN HORAS HÁBILES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(37, 6, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4306-030005', 'POR CELEBRACIÓN DE MATRIMONIO EFECTUADOS A DOMICILIO EN HORAS EN HORAS EXTRAORDINARIAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(38, 6, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4306-030006', 'POR CELEBRACIÓN DE MATRIMONIO EFECTUADO EN EL REGISTRO CIVIL EN HORAS HÁBILES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(39, 6, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4306-030007', 'POR CELEBRACIÓN DE MATRIMONIO EFECTUADO EN EL REGISTRO CIVIL EN HORAS EXTRAORDINARIAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(40, 6, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4306-030008', 'POR ACTO DE DIVORCIO ADMINISTRATIVO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(41, 6, 4, '0.00', '60.00', '60.00', '999.99', '999.99', '4306-030009', 'DISOLUCIÓN DE SOCIEDAD CONYUGAL ACEPTANDO EL RÉGIMEN DE SEPARACIÓN DE BIENES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(42, 6, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-030010', 'REGISTRO EXTEMPORÁNEO DE LAS PERSONAS CONFORME AL CÓDIGO CIVIL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(43, 6, 4, '0.00', '0.40', '0.40', '26.58', '26.58', '4306-030012', 'KILÓMETROS DE DISTANCIA POR ACTOS DE REGISTRO CIVIL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(44, 6, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-030013', 'ACTO DE ADOPCIÓN EN EL REGISTRO CIVIL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(45, 6, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '4306-030010', 'MULTA EXTEMPORANEA REGISTRO CIVIL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(46, 7, 5, '0.00', '10.00', '30.00', '664.50', '999.99', '6104-010001', 'PROVOQUEN ALGÚN DELITO Y PERTURBE EL ORDEN PÚBLICO LA HIGIENE Y LA SALUD PÚBLICA. (ART. 214, 215 Y 244)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(47, 7, 5, '0.00', '10.00', '30.00', '664.50', '999.99', '6104-010001', 'REALIZAR MANIFESTACIONES PÚBLICAS VIOLENTAS ( ARTS. 214, 215, 216 Y 217 )', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(48, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'I) EXHIBIRSE DE MANERA INDECENTE O INDECOROSA EN LA VÍA PÚBLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(49, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'II) ORINAR O DEFECAR EN LA VÍA PÚBLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(50, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'III) SOSTENER RELACIONES SEXUALES O ACTOS DE EXHIBICIONISMOS OBSCENOS EN LA VÍA O LUGARES PÚBLICOS, INTERIOR DE VEHÍCULOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(51, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'V) MOLESTAR A LOS TRANSEÚNTES O VECINOS CON PALABRAS O SILBIDOS OBSCENOS, INVITACIONES O CUALQUIER EXPRESIÓN QUE DENOTE FALTA DE RESPETO. DIRIGIRSE A UNA PERSONA CON FRASES O ADEMANES GROSEROS QUE AFECTEN SU DIGNIDAD O PUDOR, AMAGARLA, ASEDIARLA O IMPEDIR SU LIBERTAD DE ACCIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(52, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'VI) PROFERIR PALABRAS OBSCENAS EN LUGARES PÚBLICOS, SILBIDOS O TOQUES DE CLAXON', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(53, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'VII) HACER BROMAS INDECOROSAS O MORTIFICANTES O MOLESTAR A LAS PERSONAS ATRAVES DE TELÉFONOS, TIMBRES, INTERFÓN O CUALQUIER MEDIO DE COMUNICACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(54, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'VIII) INVITAR AL PÚBLICO AL COMERCIO CARNAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(55, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'IX) FALTAR EN LUGARES PÚBLICOS AL RESPETO Y CONSIDERACIÓN QUE SE DEBE A LOS ADULTOS MAYORES, MUJERES, NIÑOS Y DESVALIDOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(56, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'X) ASUMIR O EJECUTAR EN LUGARES PÚBLICOS ACTITUDES OBSCENAS O EN CONTRA DE LAS BUENAS COSTUMBRES, ACTOS INMORALES EN EL INTERIOR DE VEHÍCULOS EN PARQUE, JARDINES, VÍAS PÚBLICAS O EN CUALQUIER OTRO LUGAR CONSIDERADO PÚBLICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(57, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'XI) INDUCIR O INCITAR A MENORES E INCAPACES A COMETER FALTAS CONTRA DE LA MORAL Y LAS BUENAS COSTUMBRES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(58, 8, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'XII) ENVIAR A MENORES DE EDAD A COMPRAR CIGARROS, BEBIDAS ALCOHÓLICAS O CUALQUIER OTRO PRODUCTO PROHIBIDO PARA ELLOS. (ARTS. 218,244)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(59, 9, 5, '0.00', '1.00', '40.00', '66.45', '999.99', '6104-010001', 'II. INGERIR BEBIDAS EMBRIAGANTES, SUSTANCIAS , ENERVANTES O PSICOTRÓPICOS, PROHIBIDOS EN LA VÍA PÚBLICA O EN EL INTERIOR DE VEHÍCULOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(60, 9, 4, '0.00', '40.00', '40.00', '999.99', '999.99', '6104-010001', 'III. ALTERAR EL ORDEN, PROFERIR INSULTOS O PROVOCAR ALTERCADOS EN REUNIONES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(61, 9, 4, '0.00', '40.00', '40.00', '999.99', '999.99', '6104-010001', 'XXIX. PERNOCTAR EN LA VÍA PÚBLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(62, 10, 5, '0.00', '5.00', '20.00', '332.25', '999.99', '6104-010001', 'ART. 222 PROSTITUIRSE EN LA VÍA PÚBLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(63, 10, 5, '0.00', '5.00', '20.00', '332.25', '999.99', '6104-010001', 'ART. 224 VAGANCIA Y MALVIVENCIA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(64, 10, 5, '0.00', '5.00', '20.00', '332.25', '999.99', '6104-010001', 'ART. 226 LA PERSONA QUE EN ESTADO DE EBRIEDAD O BAJO EL INFLUJO DE DROGAS, ESTUPEFACIENTES O ENERVANTES, SE ENCUENTRE INCONSCIENTE O TIRADO EN ALGÚN SITO PÚBLICO SERÁ LEVANTADO Y PUESTO A DISPOSICIÓN DE LA AUTORIDAD', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(65, 11, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4306-020010', 'CERTIFICACIÓN DE DOCUMENTO JURÍDICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(66, 11, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010017', 'MULTA MUNICIPAL ASUNTOS JURÍDICOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(67, 11, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '6104-010021', 'CONSTANCIAS DE DETENCIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(68, 11, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '6104-010022', 'CONVENIO VECINAL Y/O FAMILIAR', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(69, 11, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '6104-010022', 'CERTIFICACIÓN DE DOCUMENTOS HASTA 100 FOJAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(70, 11, 4, '0.00', '1.00', '1.00', '66.45', '66.45', '6104-010022', 'CERTIFICACIÓN DE DOCUMENTOS POR 20 FOJAS EXCEDENTE DE 100', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(71, 11, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '6107-010004', 'REINTEGRO POR DAÑOS CAUSADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(72, 11, 9, '0.00', '0.00', '0.00', '0.00', '999.99', '6107-010005', 'INDEMNIZACIÓN POR DAÑOS CAUSADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(73, 12, 4, '0.00', '3.70', '3.70', '245.87', '245.87', '4306-040001', '3/4 TONELADA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(74, 12, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-040001', '1 TONELADA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(75, 12, 4, '0.00', '15.00', '15.00', '996.75', '996.75', '4306-040001', '3 TONELADAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(76, 12, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-040001', 'POR CADA TONELADA ADICIONAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(77, 13, 4, '0.00', '3.70', '3.70', '245.87', '245.87', '4306-040002', '3/4 TONELADA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(78, 13, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-040002', '1 TONELADA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(79, 13, 4, '0.00', '15.00', '15.00', '996.75', '996.75', '4306-040002', '3 TONELADAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(80, 13, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-040002', 'POR CADA TONELADA ADICIONAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(81, 14, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-040004', 'DESECHOS POR RESIDUOS SÓLIDOS EN EL SITIO DE DISPOSICIÓN 1AR. FINAL, POR TONELADA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(82, 14, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010013', 'MULTAS COORDINACIÓN DE LIMPIA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(83, 15, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4301-060000', 'A) DEMOLICIÓN DE BÓVEDAS POR LA ADMINISTRACIÓN MUNICIPAL POR M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(84, 16, 5, '0.00', '10.00', '20.00', '664.50', '999.99', '4303-010000', 'A) CABECERA MUNICIPAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(85, 17, 5, '0.00', '5.00', '10.00', '332.25', '664.50', '4303-010000', 'B) EN LOS DEMÁS CEMENTERIOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(86, 18, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4303-020000', 'A) CABECERA MUNICIPAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(87, 18, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4303-020000', 'B) EN LOS DEMÁS CEMENTERIOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(88, 19, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4303-030000', 'REPOSICIÓN DE TÍTULOS DE PROPIEDAD', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(89, 19, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4416-110000', 'A) CONSTRUCCIÓN DE GAVETA POR LA ADMINISTRACIÓN MUNICIPAL, RUSTICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(90, 20, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-110000', 'B) PERMISO PARA DEMOLICIÓN O REMODELACIÓN DE BÓVEDA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(91, 20, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-110000', 'C) PERMISO PARA CONSTRUCCIÓN DE CAPILLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(92, 20, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-110000', 'D) PERMISO PARA CONSTRUCCIÓN DE GUARDARESTOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(93, 20, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-110000', 'E) PERMISO PARA CONSTRUCCIÓN DE MONUMENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(94, 20, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-110000', 'F) PERMISO FORRAR DE AZULEJO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(95, 20, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-110000', 'G) PERMISO PARA REMODELACIÓN DE CAPILLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(96, 21, 4, '0.00', '40.00', '40.00', '999.99', '999.99', '4416-120000', 'A) COMPRA DE GUARDA RESTOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(97, 19, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4305-010000', 'EXPEDICIÓN DE TÍTULOS DE TERRENOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(98, 22, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-020000', 'A) PERMISO PARA INHUMACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(99, 22, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-020000', 'B) PERMISO PARA EXHUMACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(100, 22, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-020000', 'C) PERMISO PARA RE INHUMACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(101, 23, 4, '0.00', '7.00', '7.00', '465.15', '465.15', '4416-080000', 'A) APERTURA Y CIERRE DE BÓVEDAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(102, 23, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-090000', 'B) PERMISO DE CONSTRUCCIÓN DE BÓVEDAS POR GAVETAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(103, 24, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4416-01-0000', '1. HASTA 1.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(104, 24, 4, '0.00', '75.00', '75.00', '999.99', '999.99', '4416-01-0000', '2. ENTRE 1.6 Y 3.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(105, 24, 4, '0.00', '100.00', '100.00', '999.99', '999.99', '4416-01-0000', '3. MAYOR 3.6 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(106, 25, 4, '0.00', '35.00', '35.00', '999.99', '999.99', '4416-200000', '1. HASTA 1.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(107, 25, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4416-200000', '2. ENTRE 1.6 Y 3.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(108, 25, 4, '0.00', '65.00', '65.00', '999.99', '999.99', '4416-200000', '3. MAYOR 3.6 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(109, 26, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4416-200000', '1. HASTA 1.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(110, 26, 4, '0.00', '30.00', '30.00', '999.99', '999.99', '4416-200000', '2. ENTRE 1.6 Y 3.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(111, 26, 4, '0.00', '40.00', '40.00', '999.99', '999.99', '4416-200000', '3. MAYOR 3.6 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(112, 19, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4416-010000', 'AUTORIZACIÓN DE CAMBIO DE GIRO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(113, 27, 4, '0.00', '25.00', '25.00', '999.99', '999.99', '4416-010000', '1. HASTA 1.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(114, 27, 4, '0.00', '37.00', '37.00', '999.99', '999.99', '4416-010000', '2. ENTRE 1.6 Y 3.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(115, 27, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4416-010000', '3. MAYOR A 3.6 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(116, 28, 4, '0.00', '17.00', '17.00', '999.99', '999.99', '4416-200000', '1. HASTA 1.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(117, 28, 4, '0.00', '25.00', '25.00', '999.99', '999.99', '4416-200000', '2. ENTRE 1.6 Y 3.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(118, 29, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4416-200000', '1. HASTA 1.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(119, 29, 4, '0.00', '15.00', '15.00', '996.75', '996.75', '4416-200000', '2.ENTRE 1.6 Y 3.5 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(120, 29, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4416-200000', '3. MAYOR 3.6 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(121, 19, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4416-210000', 'AUTORIZACIÓN DE REMODELACIÓN DE LOCAL EN MDO.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(122, 19, 4, '0.00', '1.00', '1.00', '66.45', '66.45', '4416-030000', 'JUEGO DE FORMAS VALORADAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(123, 19, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-260001', 'RECUPERACIÓN DE LOS LOCALES Y/O ESPACIOS DE MDO.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(124, 30, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-260001', '1 AÑO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(125, 30, 4, '0.00', '12.00', '12.00', '797.40', '797.40', '4416-260001', '2 AÑOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(126, 30, 4, '0.00', '18.00', '18.00', '999.99', '999.99', '4416-260001', '3 AÑOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(127, 30, 4, '0.00', '24.00', '24.00', '999.99', '999.99', '4416-260001', '4 AÑOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(128, 30, 4, '0.00', '30.00', '30.00', '999.99', '999.99', '4416-260001', '5 AÑOS EN ADELANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(129, 31, 4, '0.00', '6.00', '6.00', '398.70', '398.70', '4416-260001', '1 AÑO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(130, 31, 4, '0.00', '12.00', '12.00', '797.40', '797.40', '4416-260001', '2 AÑOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(131, 31, 4, '0.00', '15.00', '15.00', '996.75', '996.75', '4416-260001', '3 AÑOS EN ADELANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(132, 31, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-230001', 'OTROS INGRESOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(133, 19, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-260001', 'CUOTA RECUPERACIÓN DE MERCADO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(134, 19, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4306-030011', 'CERTIFICACIÓN LOCAL DE MERCADO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(135, 32, 4, '0.00', '9.00', '9.00', '598.05', '598.05', '4416-040000', 'A. EVENTUAL / POR EVENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(136, 33, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-040000', '1. HASTA 20 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(137, 33, 4, '0.00', '0.20', '0.20', '13.29', '13.29', '4416-040000', '2. POR CADA MT2 ADICIONAL A 20', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(138, 34, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4416-040000', 'EXPEDICIÓN DE CONSTANCIA DE PROTECCIÓN CIVIL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(139, 34, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4416-040000', 'REEXPEDICIÓN DE CONSTANCIA DE PROTECCIÓN CIVIL POR MODIFICACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(140, 35, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '4416-040000', 'A) HASTA 40 MT2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(141, 35, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-040000', 'B) DE 41 A 80 MT2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(142, 35, 4, '0.00', '7.00', '7.00', '465.15', '465.15', '4416-040000', 'C) DE 81 A MÁS MT2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(143, 36, 4, '0.00', '51.00', '51.00', '999.99', '999.99', '4418-010001', '1. DE 0 HASTA 250 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(144, 36, 4, '0.00', '70.00', '70.00', '999.99', '999.99', '4418-010002', '2. DE 251 HASTA 500 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(145, 36, 4, '0.00', '110.00', '110.00', '999.99', '999.99', '4418-010003', 'DE MÁS DE 500 METROS CUADRADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(146, 34, 4, '0.00', '41.00', '41.00', '999.99', '999.99', '4418-010004', 'EXPEDICIÓN DE DICTÁMENES DE RIESGO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(147, 37, 7, '0.00', '0.00', '0.00', '999.99', '999.99', '4416-060000', 'PERMISOS DE VENTA DE BEBIDAS ALCOHÓLICAS EN ENVASE ABIERTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(148, 37, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-070000', 'PERMISO EVENTUAL VENTAS DE BEBIDAS ALCOHÓLICAS ENVASE CERRADO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(149, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130000', 'INGRESOS DIVERSOS DE FISCALIZACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(150, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130001', 'AMPLIACIÓN DE HORARIO DE COMERCIANTES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(151, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130002', 'POR MAQUINAS DE VIDEO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(152, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130003', 'POR JUEGOS MECÁNICOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(153, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130004', 'POR ROCKOLAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(154, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130005', 'POR COLOCAR TOLDO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(155, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130006', 'POR MESAS DE BILLAR', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(156, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130007', 'POR MÚSICA VIVA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(157, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130008', 'POR ESPECTÁCULOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(158, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130009', 'POR BAILARINA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(159, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130010', 'POR CINE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(160, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130011', 'ANUENCIA FISCALIZACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(161, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130011', 'CAMBIO DE DOMICILIO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(162, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130012', 'PERMISO DE ESPECTÁCULO PÚBLICO SIN VENTA DE BEBIDAS ALCOHÓLICAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(163, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-130013', 'PERMISO DE ESPECTÁCULO PÚBLICO CON VENTA DE BEBIDAS ALCOHÓLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(164, 38, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4416-250000', 'PERMISOS POR OCUPACIÓN DE ESPACIOS DENTRO DEL PARQUE (TEMPORAL)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(165, 38, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010004', 'MULTA POR VIOLACIÓN AL BANDO DE POLICÍA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(166, 38, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010010', 'MULTA POR VIOLACIÓN A LA LEY DE ALCOHOLES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(167, 38, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010014', 'MULTA POR VIOLACIÓN AL REGLAMENTO DE ESPECTÁCULOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(168, 38, 3, '0.00', '1.00', '1.00', '150.00', '999.99', '4405-020000', 'DERECHO DE OCUPACIÓN VÍA PÚBLICA ZONA LUZ', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(169, 38, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010005', 'MULTA POR ZONA LUZ', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(170, 39, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4416-130000', 'MAQUINAS ELECTRÓNICAS (JUEGOS DE CASINOS - COSTO POR TERMINAL)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(171, 39, 4, '0.00', '25.00', '25.00', '999.99', '999.99', '4416-130000', 'MÚSICA VIVA EN RESTAURANT', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(172, 39, 4, '0.00', '35.00', '35.00', '999.99', '999.99', '4416-130000', 'MÚSICA VIVA EN RESTAURANT BAR', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(173, 39, 4, '0.00', '45.00', '45.00', '999.99', '999.99', '4416-130000', 'MÚSICA VIVA EN BAR', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(174, 39, 4, '0.00', '64.00', '64.00', '999.99', '999.99', '4416-130000', 'MÚSICA VIVA CON BAILARINAS EN BAR', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(175, 39, 4, '0.00', '39.00', '39.00', '999.99', '999.99', '4416-130000', 'BAILARINAS EN RESTAURANT (PAGANDO MÚSICA VIVA EN RESTAURANT)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(176, 39, 4, '0.00', '29.00', '29.00', '999.99', '999.99', '4416-130000', 'BAILARINAS EN RESTAURANT - BAR (PAGANDO MÚSICA VIVA EN BAR)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(177, 39, 4, '0.00', '96.00', '96.00', '999.99', '999.99', '4416-130000', 'SHOW DE LENCERIA Y TANGAS EN BAR (PAGANDO MÚSICA VIVA CON BAILARINAS EN BAR)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(178, 39, 4, '0.00', '115.00', '115.00', '999.99', '999.99', '4416-130000', 'SHOW DE LENCERIA Y TANGAS (BARES CON LICENCIA DE ESPECTÁCULOS PÚBLICOS)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(179, 39, 4, '0.00', '115.00', '115.00', '999.99', '999.99', '4416-130000', 'SHOW DE LENCERIA Y TANGAS (BARES CON LICENCIA DE ESPECTÁCULOS PÚBLICOS)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(180, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-010000', 'DERECHO DE OCUPACIÓN DE VÍA PÚBLICA AMBULANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(181, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'A) DERECHO DE OCUPACIÓN DE VÍA PÚBLICA TEMPORAL ( VENDEDOR TEMPORAL )', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(182, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'B) COLOCAR STAND EN VÍA PÚBLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(183, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'C) COLOCACIÓN DE CABALLETES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(184, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'D) COLOCACIÓN DE JUEGOS MECÁNICOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(185, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'E) COLOCACIÓN DE CARRITOS ELÉCTRICOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(186, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'F) COLOCACIÓN DE TRAMPOLINES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(187, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'G) MÓDULO DE INFORMACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(188, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'J) RENTA DE PATINES INFANTILES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(189, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'K) PERMISO POR VENTA DE ESTACIONAMIENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(190, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'L) COLOCACIÓN DE ARTÍCULOS EN ESTACIONAMIENTO (VERIFICAR QUE SEAN TEMPORAL)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(191, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'LL) PERMISO DE DEGUSTACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(192, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'M) PERMISO PROVISIONAL INTERIOR DE ALTABRISA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(193, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'N) COLOCACIÓN DE JUEGOS DE PLASTILINA Y PINTURA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(194, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'Ñ) PAGO DE SERVICIO DE PELUQUERÍA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(195, 40, 6, '0.00', '0.00', '0.00', '150.00', '999.99', '4405-030000', 'O) EXHIBICIÓN DE PLÁSTICO EN VÍA PÚBLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(196, 40, 6, '0.00', '0.00', '0.00', '0.00', '999.99', '4405-030000', 'P) COLOCACIÓN DE TRAMPOLÍN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(197, 40, 6, '0.00', '0.00', '0.00', '0.00', '999.99', '4405-030000', 'R) COLOCACIÓN DE PATINAJE EN LINEA Y CARRITOS INFLABLES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(198, 41, 6, '0.00', '0.00', '0.00', '0.00', '999.99', '4405-040000', 'POR MESAS Y SILLAS EN LA VÍA PÚBLICA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(199, 42, 4, '0.00', '25.00', '25.00', '999.99', '999.99', '4416-130011', 'A) MICRO 0-5 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(200, 42, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4416-130011', 'B) PEQUEÑA 6-20 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(201, 42, 4, '0.00', '100.00', '100.00', '999.99', '999.99', '4416-130011', 'C) MEDIANA 21-100 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(202, 42, 4, '0.00', '200.00', '200.00', '999.99', '999.99', '4416-130011', 'D) GRANDE 101 EMPLEADOS EN ADELANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(203, 42, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-130011', '', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(204, 43, 4, '0.00', '25.00', '25.00', '999.99', '999.99', '4416-130011', 'A) MICRO 0-20 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(205, 43, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4416-130011', 'B) PEQUEÑA 21-50 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(206, 43, 4, '0.00', '100.00', '100.00', '999.99', '999.99', '4416-130011', 'C) MEDIANA 51-100 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(207, 43, 4, '0.00', '200.00', '200.00', '999.99', '999.99', '4416-130011', 'D) GRANDE 101 EMPLEADOS EN ADELANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(208, 43, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-130011', '', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(209, 44, 4, '0.00', '32.00', '32.00', '999.99', '999.99', '4416-130011', 'A) MICRO 0-30 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(210, 44, 4, '0.00', '67.00', '67.00', '999.99', '999.99', '4416-130011', 'B) PEQUEÑA 31-100 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(211, 44, 4, '0.00', '114.00', '114.00', '999.99', '999.99', '4416-130011', 'C) MEDIANA 101-500 EMPLEADOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(212, 44, 4, '0.00', '489.00', '489.00', '999.99', '999.99', '4416-130011', 'D) GRANDE 501 EMPLEADOS EN ADELANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(213, 45, 4, '0.00', '30.00', '30.00', '999.99', '999.99', '4416-130011', 'ISLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(214, 45, 4, '0.00', '60.00', '60.00', '999.99', '999.99', '4416-130011', 'TIENDA DEPARTAMENTAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(215, 45, 4, '0.00', '120.00', '120.00', '999.99', '999.99', '4416-130011', 'SUB-SUB ANCLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(216, 45, 4, '0.00', '240.00', '240.00', '999.99', '999.99', '4416-130011', 'SUB ANCLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(217, 45, 4, '0.00', '480.00', '480.00', '999.99', '999.99', '4416-130011', 'ANCLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(218, 47, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-130000', 'ESPECTÁCULOS PÚBLICOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(219, 46, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4416-130000', 'BAILES POPULARES EN RANCHERÍA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(220, 46, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4416-130000', 'BAILES POPULARES EN VILAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(221, 46, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4416-130000', 'OBRA DE TEATRO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(222, 46, 4, '0.00', '40.00', '40.00', '999.99', '999.99', '4416-130000', 'MASIVO MEDIANO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(223, 46, 4, '0.00', '60.00', '60.00', '999.99', '999.99', '4416-130000', 'MASIVO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(224, 48, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4306-030011', 'CONSTANCIA DE NO INHABILITACIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(225, 48, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4416-190000', 'CÉDULA DE PADRÓN DE CONTRATISTA CONTRALORÍA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(226, 48, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6104-010016', 'MULTA MUNICIPAL CONTRALORÍA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(227, 49, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4414-010000', 'VISITA MÉDICA POR CONTROL VENÉREO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(228, 49, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010006 ', 'MULTAS DE LA COORDINACIÓN DE SALUD', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(229, 50, 2, '0.05', '0.00', '0.00', '0.00', '0.00', '1101-020000', 'ESPECTACULOS PÚBLICOS DE CIRCO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(230, 50, 2, '0.05', '0.00', '0.00', '0.00', '0.00', '1101-030000', 'ESPECTACULOS PÚBLICOS DE CINE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(231, 50, 2, '0.05', '0.00', '0.00', '0.00', '0.00', '1101-040000', 'ESPECTACULOS PÚBLICOS DEPORTIVOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(232, 50, 2, '0.05', '0.00', '0.00', '0.00', '0.00', '1101-050000', 'ESPECTACULOS PÚBLICOS TAURINO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(233, 50, 2, '0.05', '0.00', '0.00', '0.00', '0.00', '1101-060000', 'ESPECTACULOS PÚBLICOS CULTURALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(234, 50, 2, '0.05', '0.00', '0.00', '0.00', '0.00', '1101-070000', 'ESPECTACULOS PÚBLICOS CONCIERTOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(235, 50, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '1201-010000', 'IMPUESTO PREDIAL URBANO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(236, 50, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '1201-020000', 'IMPUESTO PREDIAL RÚSTICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(237, 50, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4306-020006', 'POR CERTIFICACIÓN DE TIPO DE PREDIO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(238, 50, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4306-030011', 'CERTIFICACIÓN DE DOCTO. Y CONSTANCIAS DE NO ADEUDO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(239, 51, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4401-010000', '1) MURO O MARQUESINA PINTADO O ADOSADO (MT2 X DÍA)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(240, 51, 4, '0.00', '0.01', '0.01', '0.53', '0.53', '4401-010000', 'A) LUMINOSO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(241, 51, 4, '0.00', '0.01', '0.01', '0.53', '0.53', '4401-010000', 'B) ELÉCTRICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(242, 51, 4, '0.00', '0.01', '0.01', '0.66', '0.66', '4401-010000', 'C) NEÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(243, 51, 4, '0.00', '0.01', '0.01', '0.66', '0.66', '4401-010000', 'D) FLUORESCENTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(244, 51, 4, '0.00', '0.03', '0.03', '1.99', '1.99', '4401-010000', 'E) ELECTRÓNICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(245, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4401-020000', 'AUTORIZACIÓN DE USO DE SUELO, ANUNCIOS, CARTELES Y PUBLICIDAD EN CASETA TELEFÓNICAS)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(246, 50, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4306-010000', 'BÚSQUEDA DE CUALQUIER DOCUMENTO EN LOS ARCHIVOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(247, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-150000', 'PASOS PLUVIALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(248, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-160000', 'SANITARIOS PÚBLICOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(249, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '5101-010000', 'ARRENDAMIENTO LOCAL DEL CENMA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(250, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6101-010003', 'REINTEGRO DE SUELDOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(251, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6101-010004', 'REINTEGRO POR DAÑOS AL ERARIO PÚBLICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(252, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6107-010001', 'INDEMNIZACIÓN POR CHEQUES DEVUELTOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(253, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '1701-010000', 'RECARGO DE PREDIAL URBANO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(254, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '1701-020000', 'RECARGO DE PREDIAL RÚSTICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(255, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '1703-010000', 'ACTUALIZACIÓN PREDIAL URBANO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(256, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '1703-020000', 'ACTUALIZACIÓN PREDIAL RÚSTICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(257, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '1703-030000', 'ACTUALIZACIÓN DE TRASLADO DE DOMINIO URBANO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(258, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '1703-040000', 'ACTUALIZACIÓN DE TRASLADO DE DOMINIO RÚSTICO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(259, 50, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6104-010007', 'MULTA PREDIAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(260, 50, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010011', 'MULTA POR INCUMPLIMIENTO CATASTRO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(261, 52, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4413-020000', 'POR LA EXPEDICIÓN Y CERTIFICACIÓN DEL VALOR CATASTRAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(262, 52, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4413-010000', 'POR LA EXPEDICIÓN DE CADA PLANO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(263, 52, 2, '0.02', '0.00', '0.00', '0.00', '0.00', '4413-030000', 'EXP. DE AVALÚOS DE PROPIEDAD RAÍZ, SOBRE EL VALOR CATASTRAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(264, 52, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4413-100000', 'CERTIFICACIÓN DE DOCUMENTO CATASTRALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(265, 52, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4413-040000', 'RECTIFICACIÓN DE MEDIDAS Y COLINDANCIAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(266, 53, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4413-040000', '* 106 M2 HASTA 200 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(267, 53, 4, '0.00', '15.00', '15.00', '996.75', '996.75', '4413-040000', '* 201 M2 HASTA 300 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');
INSERT INTO `cat_ingresos` (`idingreso`, `idsubclase`, `tipo`, `porcentaje_ingreso`, `dsm_min`, `dsm_max`, `costo_min`, `costo_max`, `clave`, `ingreso`, `ano`, `status_ingreso`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(268, 53, 4, '0.00', '25.00', '25.00', '999.99', '999.99', '4413-040000', '* 301 M2 HASTA 600 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(269, 53, 4, '0.00', '50.00', '50.00', '999.99', '999.99', '4413-040000', '* 601 M2 EN ADELANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(270, 54, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4413-040000', '* HASTA UNA HECTÁREA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(271, 54, 4, '0.00', '40.00', '40.00', '999.99', '999.99', '4413-040000', '* DE 1-00-00 HASTA 3-00-00 HAS.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(272, 54, 4, '0.00', '60.00', '60.00', '999.99', '999.99', '4413-040000', '* DE 3-00-01 HASTA 5-00-00 HAS.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(273, 54, 4, '0.00', '80.00', '80.00', '999.99', '999.99', '4413-040000', '* DE 5-00-01 HASTA 10-00-00 HAS.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(274, 54, 4, '0.00', '100.00', '100.00', '999.99', '999.99', '4413-040000', '* DE 10-00-01 EN ADELANTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(275, 52, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4413-060000', 'COPIAS DE PLANOS CARTOGRÁFICOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(276, 52, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4413-060000', 'TAMAÑO CARTA (20X35 CMS)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(277, 52, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4413-060000', 'TAMAÑO OFICIO (20X32 CMS)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(278, 52, 4, '0.00', '8.00', '8.00', '531.60', '531.60', '4413-060000', 'TAMAÑO LAMINA (60X90 CMS)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(279, 52, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4413-110000', 'PLANOS MANZANEROS DE 90 X 200 CMS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(280, 52, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4413-120000', 'VERIFICACIÓN DE PREDIO, PLANOS, DE FRACCIONAMIENTOS URBANIZADOS, POR CADA MANZANA.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(281, 52, 4, '0.00', '0.05', '0.05', '3.32', '3.32', '4413-130000', 'VERIFICACIÓN DE LA POLIGONAL DEL TERRENO DE PREDIOS RÚSTICOS EN CASO DE FRACC. X M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(282, 52, 4, '0.00', '1.00', '1.00', '66.45', '66.45', '4413-140000', 'REGISTRO DE PLANOS Y OTORGAMIENTO DE CLAVE CATASTRAL.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(283, 55, 4, '0.00', '6.00', '6.00', '398.70', '398.70', '4413-020000', 'A) POR LA EXPEDICIÓN Y CERTIFICACIÓN DEL VALOR CATASTRAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(284, 55, 4, '0.00', '6.00', '6.00', '398.70', '398.70', '4413-020000', 'B) POR EL REGISTRO DE ESCRITURAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(285, 55, 4, '0.00', '1.00', '1.00', '66.45', '66.45', '4413-020000', 'C) CONSULTA DE INFORMACIÓN DOCUMENTAL ALMACENADA EN DISCO COMPACTO, POR DOCUMENTO.', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(286, 52, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4413-050000', 'COPIAS DE DOCUMENTO DE ARCHIVO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(287, 52, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4413-070000', 'MANIFESTACIÓN DE CONSTRUCCIONES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(288, 52, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4413-080000', 'LEGALIZACIÓN DE TÍTULOS MUNICIPALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(289, 52, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4413-090000', 'CONSTANCIA DE SUPERFICIE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(290, 56, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '5101-030000', 'RENTA DE PALAPAS, EN PARQUES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(291, 56, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '5101-040001', 'ARRENDAMIENTO DEL LOCAL PASEO DE LAS ILUSIONES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(292, 57, 7, '0.00', '0.00', '0.00', '999.99', '999.99', '5101-020000', 'RENTA DEL GRAN SALÓN VILLAHERMOSA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(293, 58, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6101-010001', 'MECANIZACIÓN AGRICOLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(294, 58, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6101-010002', 'FOMENTO PESQUERO Y ACUICOLA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(295, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4408-010000', 'CUOTA MENSUAL GYM ATENEO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(296, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4408-020000', 'CUOTA INSCRIPCIONES GYM ATENEO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(297, 59, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '4408-030001', 'CUOTA MENSUAL PARA CURSO DE SPINING Y PESAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(298, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA MENSUAL ALBERCA VILLA LAS FLORES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(299, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA INSCRIPCIÓN ALBERCA VILLA LAS FLORES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(300, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA MENSUAL RECREATIVO DE ATASTA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(301, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA INSCRIPCIÓN RECREATIVO DE ATASTA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(302, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA MENSUAL RECREATIVO INFONAVIT ATASTA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(303, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA INSCRIPCIÓN RECREATIVO INFONAVIT ATASTA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(304, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA MENSUAL COMPAÑÍA DE DANZA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(305, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA INSCRIPCIÓN COMPAÑÍA DE DANZA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(306, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA MENSUAL CENTRO CULTURAL VILLAHERMOSA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(307, 59, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4403-030001', 'CUOTA INSCRIPCIÓN CENTRO CULTURAL VILLAHERMOSA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(308, 60, 4, '0.00', '10.00', '10.00', '664.50', '664.50', '4416-170000', 'A. POR 6 MESES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(309, 60, 4, '0.00', '15.00', '15.00', '996.75', '996.75', '4416-170000', 'B. POR 1 AÑO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(310, 61, 4, '0.00', '30.00', '30.00', '999.99', '999.99', '4416-170000', 'II. PERMISO DE NO INCONVENIENCIA AMBIENTAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(311, 61, 4, '0.00', '20.00', '20.00', '999.99', '999.99', '4416-170000', 'III. INFORMÉ PREVENTIVO EN MATERIA AMBIENTAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(312, 61, 4, '0.00', '30.00', '30.00', '999.99', '999.99', '4416-170000', 'IV. MANIFESTACIÓN DE IMPACTO AMBIENTAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(313, 61, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010018', 'MULTA MUNICIPAL MEDIO AMBIENTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(314, 62, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4411-010000', 'ESPECTÁCULOS PÚBLICOS MUSEVI', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(315, 62, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4411-020000', 'ESTACIONAMIENTO MUSEVI', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(316, 62, 4, '0.00', '1.00', '1.00', '66.45', '66.45', '4416-140000', 'ANUENCIAS MUNICIPALES SARE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(317, 62, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4415-020000', 'COSTOS DE ENTRADA A LA CASA DE LA TIERRA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(318, 62, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4415-020000', 'NIÑOS Y ADULTOS MAYORES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(319, 62, 7, '0.00', '0.00', '0.00', '15.00', '15.00', '4415-020000', 'ESTUDIANTES CON CREDENCIAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(320, 62, 7, '0.00', '0.00', '0.00', '15.00', '15.00', '4415-020000', 'PÚBLICO EN GENERAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(321, 62, 7, '0.00', '0.00', '0.00', '25.00', '25.00', '4415-020000', 'PARTICIPANTES DE TALLERES EDUCATIVOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(322, 62, 7, '0.00', '0.00', '0.00', '10.00', '10.00', '5101-010000', 'ARRENDAMIENTO LOCAL CENMA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(323, 63, 4, '0.00', '30.00', '30.00', '999.99', '999.99', '4304-050001', 'FACTIBILIDAD PARA CAMBIO DE USO DE SUELO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(324, 64, 8, '0.00', '0.00', '0.00', '250.00', '999.99', '4304-050001', 'A) COLEGIATURAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(325, 65, 7, '0.00', '0.00', '0.00', '250.00', '250.00', '4403-020000', 'RECUPERACIÓN DIF INSCRIPCIONES CENDI', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(326, 65, 7, '0.00', '0.00', '0.00', '50.00', '50.00', '4403-020000', 'B) CENTRO DE CAPACITACIÓN ATASTA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(327, 65, 7, '0.00', '0.00', '0.00', '50.00', '50.00', '4403-020000', 'A) INSCRIPCIONES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(328, 65, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4403-010000', 'B) COLEGIATURAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(329, 65, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4403-010000', 'C) COLEGIATURAS CENDI', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(330, 65, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4403-010000', 'A) COOPERACIONES DIVERSAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(331, 65, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4403-030001', 'RECUPERACIÓN CUOTA INSTITUTO MUNICIPAL DEL DEPORTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(332, 65, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4406-010000', 'CUOTAS POR TALLERES IMPARTIDOS (DIF Y DECUR)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(333, 65, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4406-020001', 'INGRESOS MUNICIPALES DIF', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(334, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4416-050000', 'BASES DE LICITACIÓN PÚBLICA NACIONAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(335, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4402-150000', 'DERECHO DE INTERCONEXIÓN', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(336, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4402-290000', 'REZAGOS DE CONSUMO DE AGUA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(337, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '0005', 'OTROS INGRESOS DE SAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(338, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4501-010000', 'RECARGO SISTEMA DE AGUA Y SANEAMIENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(339, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '5103-010000', 'VENTA DE MEDIDOR CONVENCIONAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(340, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '5103-020000', 'VENTA DE MEDIDOR ESPECIAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(341, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '5103-030000', 'VENTA DE MACRO MEDIDOR', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(342, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '5103-040000', 'IMPUESTO AL VALOR AGRESADO SAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(343, 66, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '6104-010015', 'MULTA MUNICIPAL AGUA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(344, 67, 4, '0.00', '0.05', '0.05', '3.32', '3.32', '6104-010015', 'A) HABITACIONALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(345, 67, 4, '0.00', '0.10', '0.10', '6.65', '6.65', '6104-010015', 'B) NO HABITACIONALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(346, 68, 4, '0.00', '0.03', '0.03', '1.99', '1.99', '6104-010015', 'A) HABITACIONALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(347, 68, 4, '0.00', '0.05', '0.05', '3.32', '3.32', '6104-010015', 'B) NO HABITACIONALES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(348, 69, 4, '0.00', '0.10', '0.10', '6.65', '6.65', '6104-010015', 'A) METRO LINEAL BARDA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(349, 69, 4, '0.00', '0.04', '0.04', '2.66', '2.66', '6104-010015', 'B) METRO CÚBICO RELLENO O EXCAVACIONES', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(350, 69, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '4301040000', 'PERMISO PARA OCUPACIÓN VÍA PÚBLICA CON MATERIAL DE CONSTRUCCIÓN HASTA 3 DÍAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(351, 69, 4, '0.00', '0.02', '0.02', '1.33', '1.33', '4301-050000', 'PERMISO PARA OCUPACIÓN VÍA PÚBLICA CON TAPIAL Y/O OTRAS PROTECCIONES POR METRO CUADRADO POR DÍAS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(352, 69, 4, '0.00', '0.05', '0.05', '3.32', '3.32', '4301-060000', 'PERMISO PARA DEMOLICIÓN POR METRO CUADRADO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(353, 70, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4301-070000', 'EXPEDICIÓN DE CONSTANCIA TERMINACIÓN DE OBRA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(354, 70, 4, '0.00', '0.03', '0.03', '1.99', '1.99', '4302-010000', 'FRACCIONAMIENTO ÁREA TOTALMENTE VENDIBLE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(355, 70, 4, '0.00', '0.10', '0.10', '6.65', '6.65', '4302-020000', 'CONDOMINIO POR METRO CUADRADO DEL TERRENO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(356, 70, 4, '0.00', '0.10', '0.10', '6.65', '6.65', '4302-030000', 'LOTIFICACIONES POR METRO CUADRADO DEL ÁREA TOTAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(357, 70, 4, '0.00', '0.05', '0.05', '3.32', '3.32', '4302-040000', 'RELOTIFICACIONES POR METRO CUADRADO DEL ÁREA VENDIBLE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(358, 70, 4, '0.00', '0.08', '0.08', '5.32', '5.32', '4302-050000', 'DIVISIONES POR METRO CUADRADO ÁREA VENDIBLE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(359, 70, 4, '0.00', '0.08', '0.08', '5.32', '5.32', '4302-060000', 'SUBDIVISIONES POR METRO CUADRADO ÁREA VENDIBLE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(360, 70, 1, '0.00', '0.00', '0.00', '0.00', '999.99', '4304-010000', '1. ARRIMO DE CAÑOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(361, 71, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4304-010001', 'CALLE PAVIMENTADA O ASFALTADAS POR METRO LINEAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(362, 71, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4304-010002', 'CALLE SIN PAVIMENTAR O ASFALTO POR METRO LINEAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(363, 71, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4304-010003', 'TODO ENTRONQUE POR CUENTA DEL INTERESADO X METRO LINEAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(364, 70, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4304-020001', 'POR LLAVE O INSERCIÓN A LA RED DE AGUA POTABLE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(365, 70, 4, '0.00', '3.00', '3.00', '199.35', '199.35', '4304-020002', 'POR CADA REINSTALACIÓN DE SERVICIOS', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(366, 70, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4304-020003', 'TODO ENTRONQUE PAGARA POR METRO LINEAL ROTURA', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(367, 70, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4304-030001', 'POR LA AUTORIZACIÓN FACTIBILIDAD DE USO DE SUELO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(368, 70, 4, '0.00', '4.00', '4.00', '265.80', '265.80', '4304-030002', 'NUMERO OFICIAL (INCLUYENDO LA PLACA)', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(369, 72, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4304-030003', 'A) HASTA 10 MTS. LINEALES DE FRENTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(370, 72, 4, '0.00', '0.50', '0.50', '33.23', '33.23', '4304-030003', 'B) CADA METRO EXCEDENTE', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(371, 73, 4, '0.00', '5.00', '5.00', '332.25', '332.25', '4304-030004', 'A) HASTA 200 M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(372, 73, 4, '0.00', '0.02', '0.02', '1.33', '1.33', '4304-030004', 'B) CADA M2 ADICIONAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(373, 74, 4, '0.00', '0.04', '0.04', '2.66', '2.66', '4304-040000', 'A) HASTA 50 M2 POR M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(374, 74, 4, '0.00', '0.06', '0.06', '3.99', '3.99', '4304-040000', 'B) CONST. 50.01-100 M2 POR M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(375, 74, 4, '0.00', '0.08', '0.08', '5.32', '5.32', '4304-040000', 'C) CONST. 100.01-200 M2 POR M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(376, 74, 4, '0.00', '0.10', '0.10', '6.65', '6.65', '4304-040000', 'D) CONST. 200.01 M2 EN ADELANTE POR M2', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(377, 70, 4, '0.00', '2.00', '2.00', '132.90', '132.90', '4306-020002', 'CERTIFICACIÓN DE NÚMERO OFICIAL Y ALINEAMIENTO', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(378, 70, 3, '0.00', '1.00', '1.00', '350.00', '999.99', '4416-050000', 'BASES DE LICITACIÓN PÚBLICA NACIONAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(379, 70, 3, '0.00', '1.00', '1.00', '350.00', '999.99', '4416-180000', 'BASES DE LICITACIÓN PÚBLICA ESTATAL', 2015, 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(380, 75, 5, '0.00', '10.00', '100.00', '664.50', '999.99', '6104-010002', 'MULTA DE OBRA PÚBLICA', 2015, 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1, '2015-03-24 10:43:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_oficinas`
--

CREATE TABLE IF NOT EXISTS `cat_oficinas` (
  `idoficina` int(10) NOT NULL AUTO_INCREMENT,
  `iddependencia` int(10) NOT NULL DEFAULT '0',
  `oficina` varchar(100) NOT NULL,
  `status_oficina` int(2) NOT NULL DEFAULT '1',
  `idemp` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idoficina`),
  KEY `empsts` (`idemp`,`status_oficina`),
  KEY `depempsts` (`iddependencia`,`idemp`,`status_oficina`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Oficinas' AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `cat_oficinas`
--

INSERT INTO `cat_oficinas` (`idoficina`, `iddependencia`, `oficina`, `status_oficina`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, 1, 'SECRETARÍA DEL H. AYUNTAMIENTO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(2, 1, 'REGISTRO CIVIL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(3, 2, 'JUECES CALIFICADORES', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(4, 3, 'COORDINACIÓN DE LIMPIA', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(5, 3, 'COORDINACIÓN DE PANTEONES', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(6, 4, 'COORDINACIÓN DE PROTECCIÓN CIVIL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(7, 5, 'SUBCOORDINACIÓN DE ALCOHOLES', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(8, 5, 'SUBCOORDINACIÓN DE AMBULANTES', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(9, 5, 'SUBCOORDINACIÓN DE ANUENCIAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(10, 6, 'CONTRALORÍA MUNICIPAL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(11, 7, 'CONTROL VENEREO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(12, 8, 'SUBDIRECCIÓN DE INGRESOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(13, 8, 'SUBDIRECCIÓN DE CATASTRO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(14, 3, 'PARQUES, JARDINES Y MONUMENTOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(15, 10, 'DIRECCCIÓN DE ADMINISTRACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(16, 11, 'DIRECCIÓN DE DESARROLLO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(17, 12, 'INSTALACIONES DEPORTIVAS Y CULTURALES DE LA CD. DE VILLAHERMOSA, TAB.', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(18, 13, 'DIRECCIÓN DE PROTECCIÓN AMBIENTAL Y DESARROLLO SUSTENTABLE', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(19, 14, 'DIRECCIÓN DE FOMENTO ECONÓMICO Y TURISMO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(20, 15, ' INSTITUTO DE PLANEACIÓN Y DESARROLLO URBANO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(21, 16, 'DIRECCIÓN DEL DIF', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(22, 17, 'COORDINACIÓN GENERAL DEL SAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(23, 18, 'DIRECCIÓN DE OBRAS PÚBLICAS', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1, '2015-03-23 16:52:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_subclases`
--

CREATE TABLE IF NOT EXISTS `cat_subclases` (
  `idsubclase` int(10) NOT NULL AUTO_INCREMENT,
  `idclase` int(10) NOT NULL DEFAULT '0',
  `subclase` varchar(100) NOT NULL,
  `status_subclase` int(2) NOT NULL DEFAULT '1',
  `idemp` int(5) NOT NULL DEFAULT '5',
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idsubclase`),
  KEY `empstssubcls` (`idemp`,`status_subclase`),
  KEY `clasesempsts` (`idclase`,`idemp`,`status_subclase`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Subclases' AUTO_INCREMENT=76 ;

--
-- Volcado de datos para la tabla `cat_subclases`
--

INSERT INTO `cat_subclases` (`idsubclase`, `idclase`, `subclase`, `status_subclase`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, 1, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(2, 2, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(3, 3, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(4, 4, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(5, 4, 'B. LOCAL / FIJO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(6, 5, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(7, 6, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(8, 6, 'FALTAS A LA MORAL (ARTS. 218 Y 244)', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(9, 6, 'ÓRDEN PÚBLICO (ART. 219)', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(10, 6, 'EMBRIAGUEZ E INTOXICACIÓN POR PSICOTRÓPICO ESTUFEPACIENTES (ARTS. 222,224 Y 226)', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(11, 7, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(12, 8, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(13, 9, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(14, 10, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(15, 11, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(16, 12, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(17, 13, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(18, 14, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(19, 15, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(20, 16, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(21, 17, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(22, 18, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(23, 19, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(24, 20, 'A) INTERIOR DEL MERCADO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(25, 20, 'PERMISO DE OCUPACIÓN ÁREAS ADYACENTES', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(26, 20, 'C. ÁREAS CONSIDERADAS O SEÑALADAS COMO ZONA DE MDO.', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(27, 21, 'A. INTERIOR DEL MERCADO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(28, 21, 'B. ÁREAS ADYACENTES DEL EXTERIOR DEL MERCADO ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(29, 21, 'C. ÁREAS CONSIDERADAS O SEÑALADAS COMO ZONA DE MDO.', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(30, 22, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(31, 23, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(32, 24, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(33, 24, 'B. LOCAL / FIJO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(34, 25, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(35, 26, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(36, 27, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(37, 28, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(38, 29, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(39, 30, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(40, 31, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(41, 32, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(42, 33, 'SECTOR COMERCIO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(43, 33, 'SECTOR SERVICIOS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(44, 33, 'SECTOR INDUSTRIAL', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(45, 33, 'FRANQUICIAS', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(46, 34, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(47, 34, 'INGRESOS DIVERSOS DE FISCALIZACIÓN', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(48, 35, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(49, 36, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(50, 37, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(51, 37, 'AUTORIZACIONES PARA LA COLOCACIÓN DE ANUNCIOS, CARTELES Y PUBLICIDAD (PZA X DÍA)', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(52, 38, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(53, 39, 'A) PREDIO URBANO LOTE TIPO HASTA 105 M2', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(54, 39, 'B) PREDIO RUSTICO', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(55, 40, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(56, 41, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(57, 42, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(58, 43, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(59, 44, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(60, 45, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(61, 46, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(62, 47, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(63, 48, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(64, 49, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(65, 50, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(66, 51, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(67, 52, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(68, 53, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(69, 54, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(70, 55, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(71, 56, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(72, 57, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(73, 58, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(74, 59, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(75, 60, ' ', 1, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `idconfig` int(11) NOT NULL AUTO_INCREMENT,
  `llave` varchar(100) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `observaciones` varchar(150) NOT NULL,
  `movible` int(2) NOT NULL DEFAULT '0' COMMENT '0 = no, 1 = Si',
  `idemp` int(5) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idconfig`),
  UNIQUE KEY `keyempcnf` (`llave`,`idemp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Configuraciones' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`idconfig`, `llave`, `valor`, `observaciones`, `movible`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, 'iva', '0.16', 'IVA', 0, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1514, '2015-03-26 10:26:06'),
(2, 'vsm', '66.45', 'Valor Salario Mínimo', 0, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1514, '2015-03-26 10:26:55');

--
-- Disparadores `config`
--
DROP TRIGGER IF EXISTS `BEFORE_UPDATE_config`;
DELIMITER //
CREATE TRIGGER `BEFORE_UPDATE_config` BEFORE UPDATE ON `config`
 FOR EACH ROW Begin

INSERT INTO logs(iduser,date_mov,typemov,table_mov,idkey,fieldkey)
VALUES(new.modi_por,NOW(),'Update','Config',new.valor,new.llave);


End
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contribuciones`
--

CREATE TABLE IF NOT EXISTS `contribuciones` (
  `idcontribucion` int(10) NOT NULL AUTO_INCREMENT,
  `idingreso` int(10) NOT NULL,
  `cantidad` decimal(12,2) NOT NULL DEFAULT '0.00',
  `precio_unitario` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL,
  `descuento` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `contribuyente` varchar(250) NOT NULL,
  `fecha` datetime NOT NULL,
  `generado_por` int(5) NOT NULL DEFAULT '0',
  `cobrado_por` int(5) NOT NULL DEFAULT '0',
  `fecha_cobro` datetime NOT NULL,
  `observaciones` varchar(250) NOT NULL,
  `tokenpay` varchar(100) NOT NULL,
  `idtransacpay` varchar(100) NOT NULL,
  `status_contribucion` int(2) NOT NULL DEFAULT '0' COMMENT '0=Creado, 1=Pagado, 2=Cancelado',
  `idemp` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idcontribucion`),
  KEY `ingemp` (`idingreso`,`idemp`,`status_contribucion`),
  KEY `genempsts` (`generado_por`,`idemp`,`status_contribucion`),
  KEY `cobempsts` (`cobrado_por`,`idemp`,`status_contribucion`),
  KEY `tokenpay` (`tokenpay`),
  KEY `idtransacpay` (`idtransacpay`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Contribuciones' AUTO_INCREMENT=26 ;

--
-- Volcado de datos para la tabla `contribuciones`
--

INSERT INTO `contribuciones` (`idcontribucion`, `idingreso`, `cantidad`, `precio_unitario`, `subtotal`, `descuento`, `total`, `contribuyente`, `fecha`, `generado_por`, `cobrado_por`, `fecha_cobro`, `observaciones`, `tokenpay`, `idtransacpay`, `status_contribucion`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(19, 61, '1.00', '2658.00', '2658.00', '0.00', '0.00', 'mirna lopexz', '2015-03-25 12:53:03', 1512, 2, '2015-03-26 11:26:32', '', '', '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-25 12:53:03', 0, '0000-00-00 00:00:00'),
(22, 72, '1.00', '1000.00', '1000.00', '0.00', '0.00', 'Sin Sal Min solo pago dir', '2015-03-26 09:05:17', 1512, 2, '2015-03-27 16:38:45', '', '', '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-26 09:05:17', 0, '0000-00-00 00:00:00'),
(18, 54, '1.00', '1661.25', '1661.25', '0.00', '0.00', 'pancho lopez', '2015-03-25 12:35:23', 1512, 1514, '2015-03-26 11:41:00', '', '', '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-25 12:35:23', 0, '0000-00-00 00:00:00'),
(21, 62, '1.00', '1329.00', '1329.00', '0.00', '0.00', 'Con sal Min', '2015-03-26 09:04:42', 1512, 1514, '2015-03-27 15:40:48', '', '', '', 0, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-26 09:04:42', 0, '0000-00-00 00:00:00'),
(16, 58, '1.00', '1993.50', '1993.50', '0.00', '0.00', 'Jose lopez', '2015-03-25 12:32:53', 1512, 2, '2015-03-26 11:46:22', '', '', '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-25 12:32:53', 0, '0000-00-00 00:00:00'),
(17, 31, '2.00', '66.45', '132.90', '0.00', '0.00', 'Carmen Lopez', '2015-03-25 12:33:40', 1512, 1514, '2015-03-26 11:40:40', '', '', '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-25 12:33:40', 0, '0000-00-00 00:00:00'),
(13, 31, '1.00', '66.45', '66.45', '0.00', '0.00', 'Juana perez lopez', '2015-03-25 12:20:12', 1512, 2, '2015-03-26 11:22:43', '', '', '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-25 12:20:12', 0, '0000-00-00 00:00:00'),
(12, 31, '1.00', '66.45', '66.45', '0.00', '0.00', 'rubi', '2015-03-25 12:18:27', 1512, 0, '0000-00-00 00:00:00', '', '', '', 0, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-25 12:18:27', 0, '0000-00-00 00:00:00'),
(14, 31, '1.00', '66.45', '66.45', '0.00', '0.00', 'bernarda arias', '2015-03-25 12:25:25', 1512, 0, '0000-00-00 00:00:00', '', '', '', 0, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-25 12:25:25', 0, '0000-00-00 00:00:00'),
(23, 69, '1.00', '332.25', '332.25', '0.00', '0.00', 'juana lopez', '2015-03-26 15:07:27', 1512, 2, '2015-03-27 15:51:43', 'Debe entregar los requisitos que se le piden...', '', '', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-26 15:07:27', 0, '0000-00-00 00:00:00'),
(24, 31, '2.00', '66.45', '132.90', '0.00', '0.00', 'Carlos Mejía', '2015-03-27 15:48:28', 1512, 1514, '2015-03-27 15:49:01', '', '', '', 1, 1, '189.236.70.58', 'dsl-189-236-70-58-dyn.prod-infinitum.com.mx', 1512, '2015-03-27 15:48:28', 0, '0000-00-00 00:00:00'),
(25, 69, '1.00', '332.25', '332.25', '0.00', '0.00', 'Alfonso Pérez', '2015-03-28 09:31:00', 1512, 0, '2015-03-28 11:58:00', '', 'b3dba773b59eb407b3ebaa029369fc59', '999', 1, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1512, '2015-03-28 09:31:00', 0, '0000-00-00 00:00:00');

--
-- Disparadores `contribuciones`
--
DROP TRIGGER IF EXISTS `BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `BEFORE_INSERT` BEFORE INSERT ON `contribuciones`
 FOR EACH ROW Begin

set new.tokenpay = MD5( concat( new.idcontribucion,'-',new.generado_por,'-',NOW() ) );

End
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `idemp` int(10) NOT NULL AUTO_INCREMENT,
  `rs` varchar(150) NOT NULL DEFAULT '',
  `ncomer` varchar(100) NOT NULL DEFAULT '',
  `df` varchar(200) NOT NULL DEFAULT '',
  `rfc` varchar(20) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL,
  PRIMARY KEY (`idemp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Empresas' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`idemp`, `rs`, `ncomer`, `df`, `rfc`, `logo`) VALUES
(1, 'Colegio Arjí, A.C.', 'Colegio Arjí, A.C.', 'Prueba', 'Prueba', ''),
(2, 'Test Emp', 'Test Emp', 'Test Emp', 'TE', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `idlog` int(10) NOT NULL AUTO_INCREMENT,
  `iduser` int(10) NOT NULL,
  `date_mov` datetime NOT NULL,
  `typemov` varchar(20) NOT NULL,
  `table_mov` varchar(50) NOT NULL,
  `idkey` int(10) NOT NULL,
  `fieldkey` varchar(100) NOT NULL,
  PRIMARY KEY (`idlog`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Guarda el log de operaciones' AUTO_INCREMENT=1664800 ;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`idlog`, `iduser`, `date_mov`, `typemov`, `table_mov`, `idkey`, `fieldkey`) VALUES
(1664792, 0, '2015-03-24 08:40:39', 'Update', 'Config', 66, 'vsm'),
(1664793, 0, '2015-03-24 17:34:22', 'Update', 'Config', 66, 'vsm'),
(1664794, 1514, '2015-03-26 10:22:14', 'Update', 'Config', 0, 'iva'),
(1664795, 1514, '2015-03-26 10:25:11', 'Update', 'Config', 0, 'iva'),
(1664796, 1514, '2015-03-26 10:26:06', 'Update', 'Config', 0, 'iva'),
(1664797, 1514, '2015-03-26 10:26:23', 'Update', 'Config', 66, 'vsm'),
(1664798, 1514, '2015-03-26 10:26:48', 'Update', 'Config', 66, 'vsm'),
(1664799, 1514, '2015-03-26 10:26:55', 'Update', 'Config', 66, 'vsm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `iduser` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `registro` varchar(15) NOT NULL,
  `especialidad` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `domicilio` varchar(150) NOT NULL,
  `colonia` varchar(100) NOT NULL,
  `municipio` varchar(50) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `otrostel` varchar(100) NOT NULL,
  `teloficina` varchar(50) NOT NULL,
  `telpersonal` varchar(50) NOT NULL,
  `telfax` varchar(50) NOT NULL,
  `correoelectronico` varchar(100) NOT NULL,
  `idmunicipio` int(5) NOT NULL,
  `idestado` int(5) NOT NULL,
  `cedulaprofesional` varchar(20) NOT NULL,
  `registro_colegio` varchar(20) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL,
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL,
  `modi_el` datetime NOT NULL,
  `foto` varchar(100) NOT NULL,
  `p1` int(2) NOT NULL DEFAULT '0',
  `p2` int(2) NOT NULL DEFAULT '0',
  `p3` int(2) NOT NULL DEFAULT '0',
  `p4` int(2) NOT NULL DEFAULT '0',
  `p5` int(2) NOT NULL DEFAULT '0',
  `p6` int(2) NOT NULL DEFAULT '0',
  `p7` int(2) NOT NULL DEFAULT '0',
  `p8` int(2) NOT NULL DEFAULT '0',
  `p9` int(2) NOT NULL DEFAULT '0',
  `p10` int(2) NOT NULL DEFAULT '0',
  `p11` int(2) NOT NULL DEFAULT '0',
  `p12` int(2) NOT NULL DEFAULT '0',
  `p13` int(2) NOT NULL DEFAULT '0',
  `p14` int(2) NOT NULL DEFAULT '0',
  `p15` int(2) NOT NULL DEFAULT '0',
  `p16` int(2) NOT NULL DEFAULT '0',
  `p17` int(2) NOT NULL DEFAULT '0',
  `p18` int(2) NOT NULL DEFAULT '0',
  `p19` int(2) NOT NULL DEFAULT '0',
  `p20` int(2) NOT NULL DEFAULT '0',
  `p21` int(2) NOT NULL DEFAULT '0',
  `p22` int(2) NOT NULL DEFAULT '0',
  `p23` int(2) NOT NULL DEFAULT '0',
  `p24` int(2) NOT NULL DEFAULT '0',
  `p25` int(2) NOT NULL DEFAULT '0',
  `p26` int(2) NOT NULL DEFAULT '0',
  `p27` int(2) NOT NULL DEFAULT '0',
  `p28` int(2) NOT NULL DEFAULT '0',
  `p29` int(2) NOT NULL DEFAULT '0',
  `p30` int(2) NOT NULL DEFAULT '0',
  `p31` int(2) NOT NULL DEFAULT '0',
  `p32` int(2) NOT NULL DEFAULT '0',
  `p33` int(2) NOT NULL DEFAULT '0',
  `p34` int(2) NOT NULL DEFAULT '0',
  `p35` int(2) NOT NULL DEFAULT '0',
  `p36` int(2) NOT NULL DEFAULT '0',
  `p37` int(2) NOT NULL DEFAULT '0',
  `p38` int(2) NOT NULL DEFAULT '0',
  `p39` int(2) NOT NULL DEFAULT '0',
  `p40` int(2) NOT NULL DEFAULT '0',
  `p41` int(2) NOT NULL DEFAULT '0',
  `p42` int(2) NOT NULL DEFAULT '0',
  `p43` int(2) NOT NULL DEFAULT '0',
  `p44` int(2) NOT NULL DEFAULT '0',
  `p45` int(2) NOT NULL DEFAULT '0',
  `p46` int(2) NOT NULL DEFAULT '0',
  `p47` int(2) NOT NULL DEFAULT '0',
  `p48` int(2) NOT NULL DEFAULT '0',
  `p49` int(2) NOT NULL DEFAULT '0',
  `p50` int(2) NOT NULL DEFAULT '0',
  `idper` int(10) NOT NULL DEFAULT '0' COMMENT 'Id del Catalogo de Personal con quien esta relacionado',
  `idemp` int(10) NOT NULL DEFAULT '0',
  `idusernivelacceso` int(10) NOT NULL DEFAULT '1',
  `status_usuario` int(2) NOT NULL DEFAULT '0' COMMENT '0=Inactivo, 1=Activo',
  `token` varchar(60) DEFAULT NULL,
  `token_validated` int(2) NOT NULL DEFAULT '0' COMMENT '0=No, 1=Si',
  `token_source` varchar(60) NOT NULL,
  `registrosporpagina` int(3) NOT NULL DEFAULT '10',
  `param1` varchar(100) NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `up` (`username`,`password`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `useremp` (`username`,`idemp`),
  KEY `idemp` (`idemp`),
  KEY `idusernivelacceso` (`idusernivelacceso`),
  KEY `idper` (`idper`),
  KEY `status_usuario` (`status_usuario`),
  KEY `userpassstatus` (`username`,`password`,`status_usuario`),
  KEY `emostatu` (`idemp`,`status_usuario`),
  KEY `upes` (`username`,`password`,`idemp`,`status_usuario`),
  KEY `valid_token` (`token_validated`),
  KEY `token` (`token`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1515 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`iduser`, `username`, `password`, `apellidos`, `nombres`, `registro`, `especialidad`, `nombre`, `domicilio`, `colonia`, `municipio`, `estado`, `otrostel`, `teloficina`, `telpersonal`, `telfax`, `correoelectronico`, `idmunicipio`, `idestado`, `cedulaprofesional`, `registro_colegio`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`, `foto`, `p1`, `p2`, `p3`, `p4`, `p5`, `p6`, `p7`, `p8`, `p9`, `p10`, `p11`, `p12`, `p13`, `p14`, `p15`, `p16`, `p17`, `p18`, `p19`, `p20`, `p21`, `p22`, `p23`, `p24`, `p25`, `p26`, `p27`, `p28`, `p29`, `p30`, `p31`, `p32`, `p33`, `p34`, `p35`, `p36`, `p37`, `p38`, `p39`, `p40`, `p41`, `p42`, `p43`, `p44`, `p45`, `p46`, `p47`, `p48`, `p49`, `p50`, `idper`, `idemp`, `idusernivelacceso`, `status_usuario`, `token`, `token_validated`, `token_source`, `registrosporpagina`, `param1`) VALUES
(1, 'devch', '68053af2923e00204c3ca7c6a3150cf7', 'Hidalgo', 'Carlos', 'mmmn', 'nnnn', '', '', '', '', '', '', '351-02-60', 'no tengo', '', 'DevCH@arji.edu.mx', 0, 0, 'cedula', 'cvt', '189.133.133.206', 'dsl-189-133-133-206-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '1.png', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1000, 1, 'c6918f2d97c2fac678b8c682e64b1e32', 1, 'c6918f2d97c2fac678b8c682e64b1e32', 7, ''),
(2, 'prueba', '202cb962ac59075b964b07152d234b70', 'Prueba', 'Prueba', '', '', '', '', '', '', '', '', '0000', '000', '', 'prueba@hotmail.com', 0, 0, '', '', '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1513, '2015-03-25 15:58:25', '2.jpg', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2, 1, '11dcf2e49a58211e902f5bd70909c6ff', 1, '11dcf2e49a58211e902f5bd70909c6ff', 10, ''),
(1514, 'supervisor', 'cb17bd2285f26466a477579632350588', 'Supervisor', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '', '', '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1513, '2015-03-25 17:00:53', 0, '0000-00-00 00:00:00', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 3, 1, NULL, 0, 'c3f60bb7dc5a15323f6fdcd4027cb553', 10, ''),
(1513, 'admin1', '80177534a0c99a7e3645b52f2027a48b', '1', 'Admin', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '', '', '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1, '2015-03-24 17:36:47', 1513, '2015-03-25 15:58:06', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 5, 1, NULL, 0, '0a99ca4bd2c569244b10cdefcd5e7678', 10, ''),
(1512, 'peter', '202cb962ac59075b964b07152d234b70', 'peter', 'prueba', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '', '', '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1, '2015-03-24 12:02:19', 0, '0000-00-00 00:00:00', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, NULL, 0, '0e4a312194222cdf2f1251e8ae2db83e', 10, '');

--
-- Disparadores `usuarios`
--
DROP TRIGGER IF EXISTS `BEFORE_INSERTE_usuarios`;
DELIMITER //
CREATE TRIGGER `BEFORE_INSERTE_usuarios` BEFORE INSERT ON `usuarios`
 FOR EACH ROW Begin

	-- if new.token_source = "" and not IS_NULL(new.username) then
	
    	set new.token_source = md5( concat( new.username,NOW() ) );
    
    -- end if;

End
//
DELIMITER ;
DROP TRIGGER IF EXISTS `BEFORE_UPDATE_usuarios`;
DELIMITER //
CREATE TRIGGER `BEFORE_UPDATE_usuarios` BEFORE UPDATE ON `usuarios`
 FOR EACH ROW Begin

	if new.token_source = "" then
	
    	set new.token_source = md5( concat( new.username,now() ) );
    
    end if;

End
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_dep`
--

CREATE TABLE IF NOT EXISTS `usuarios_dep` (
  `iddepuser` int(10) NOT NULL AUTO_INCREMENT,
  `iddependencia` int(10) NOT NULL,
  `iduser` int(10) NOT NULL,
  `idemp` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL DEFAULT '0',
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL DEFAULT '0',
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`iddepuser`),
  UNIQUE KEY `userdep` (`iddependencia`,`iduser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Asocia Usuarios a Dependencia' AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `usuarios_dep`
--

INSERT INTO `usuarios_dep` (`iddepuser`, `iddependencia`, `iduser`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(12, 6, 1512, 1, '189.236.70.58', 'dsl-189-236-70-58-dyn.prod-infinitum.com.mx', 1514, '2015-03-27 15:43:26', 0, '0000-00-00 00:00:00'),
(10, 1, 1512, 1, '189.133.205.171', 'dsl-189-133-205-171-dyn.prod-infinitum.com.mx', 1, '2015-03-24 17:23:20', 0, '0000-00-00 00:00:00'),
(11, 6, 1514, 1, '189.236.70.58', 'dsl-189-236-70-58-dyn.prod-infinitum.com.mx', 1514, '2015-03-27 15:43:26', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_niveldeacceso`
--

CREATE TABLE IF NOT EXISTS `usuarios_niveldeacceso` (
  `idusernivelacceso` int(10) NOT NULL AUTO_INCREMENT,
  `clave` varchar(20) NOT NULL,
  `nivel_de_acceso` varchar(50) NOT NULL,
  `movible` int(2) NOT NULL DEFAULT '0' COMMENT '0=No, 1=Si',
  `idemp` int(10) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(10) NOT NULL,
  `creado_el` datetime NOT NULL,
  `modi_por` int(10) NOT NULL,
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idusernivelacceso`),
  KEY `movible` (`movible`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Nivel de Acceso de Usuarios' AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `usuarios_niveldeacceso`
--

INSERT INTO `usuarios_niveldeacceso` (`idusernivelacceso`, `clave`, `nivel_de_acceso`, `movible`, `idemp`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, '001', 'Capturista', 0, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(2, '002', 'Cajero', 0, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(3, '003', 'Coordinador', 0, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(4, '004', 'Director', 0, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(5, '005', 'Administrador', 0, 1, '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_viUsuarios`
--
CREATE TABLE IF NOT EXISTS `_viUsuarios` (
`iduser` int(10)
,`username` varchar(20)
,`password` varchar(60)
,`apellidos` varchar(100)
,`nombres` varchar(100)
,`foto` varchar(100)
,`user` varchar(20)
,`registro` varchar(15)
,`especialidad` varchar(50)
,`domicilio` varchar(150)
,`colonia` varchar(100)
,`idmunicipio` int(5)
,`idestado` int(5)
,`teloficina` varchar(50)
,`telpersonal` varchar(50)
,`telfax` varchar(50)
,`correoelectronico` varchar(100)
,`idemp` int(10)
,`empresa` varchar(150)
,`logoempresa` varchar(100)
,`idusernivelacceso` int(10)
,`nivel_de_acceso` varchar(50)
,`clave` varchar(20)
,`status_usuario` int(2)
,`token` varchar(60)
,`token_source` varchar(60)
,`token_validated` int(2)
,`registrosporpagina` int(3)
,`param1` varchar(100)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_vi_Cat_Clases`
--
CREATE TABLE IF NOT EXISTS `_vi_Cat_Clases` (
`idclase` int(10)
,`idoficina` int(10)
,`clase` varchar(100)
,`status_clase` int(2)
,`oficina` varchar(100)
,`idemp` int(5)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_vi_Cat_Ingresos`
--
CREATE TABLE IF NOT EXISTS `_vi_Cat_Ingresos` (
`idingreso` int(10)
,`idsubclase` int(10)
,`tipo` int(2)
,`porcentaje_ingreso` decimal(5,2)
,`dsm_min` decimal(5,2)
,`dsm_max` decimal(5,2)
,`costo_min` decimal(12,2)
,`costo_max` decimal(12,2)
,`clave` varchar(20)
,`ingreso` text
,`ano` int(4)
,`status_ingreso` int(2)
,`subclase` varchar(100)
,`idclase` int(10)
,`status_subclase` int(2)
,`idoficina` int(10)
,`clase` varchar(100)
,`status_clase` int(2)
,`iddependencia` int(10)
,`oficina` varchar(100)
,`status_oficina` int(2)
,`dependencia` varchar(100)
,`status_dependencia` int(2)
,`idemp` int(5)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_vi_Cat_Oficinas`
--
CREATE TABLE IF NOT EXISTS `_vi_Cat_Oficinas` (
`idoficina` int(10)
,`iddependencia` int(10)
,`oficina` varchar(100)
,`status_oficina` int(2)
,`dependencia` varchar(100)
,`idemp` int(5)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_vi_Cat_SubClases`
--
CREATE TABLE IF NOT EXISTS `_vi_Cat_SubClases` (
`idsubclase` int(10)
,`subclase` varchar(100)
,`idclase` int(10)
,`status_subclase` int(2)
,`clase` varchar(100)
,`idemp` int(5)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_vi_Contribuciones`
--
CREATE TABLE IF NOT EXISTS `_vi_Contribuciones` (
`idcontribucion` int(10)
,`idingreso` int(10)
,`cantidad` decimal(12,2)
,`precio_unitario` decimal(12,2)
,`subtotal` decimal(12,2)
,`descuento` decimal(12,2)
,`total` decimal(12,2)
,`contribuyente` varchar(250)
,`observaciones` varchar(250)
,`tokenpay` varchar(100)
,`idtransacpay` varchar(100)
,`fecha` datetime
,`cFecha` varchar(10)
,`generado_por` int(5)
,`cobrado_por` int(5)
,`fecha_cobro` datetime
,`status_contribucion` int(2)
,`ingreso` text
,`tipo` int(2)
,`idsubclase` int(10)
,`porcentaje_ingreso` decimal(5,2)
,`dsm_min` decimal(5,2)
,`dsm_max` decimal(5,2)
,`costo_min` decimal(12,2)
,`costo_max` decimal(12,2)
,`clave` varchar(20)
,`ano` int(4)
,`status_ingreso` int(2)
,`creado_por_usuario` varchar(20)
,`cobrado_por_usuario` varchar(20)
,`cobrado_por_usuario_nombre_completo` varchar(224)
,`idemp` int(5)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_vi_Dep_User`
--
CREATE TABLE IF NOT EXISTS `_vi_Dep_User` (
`iddepuser` int(10)
,`iddependencia` int(10)
,`dependencia` varchar(100)
,`iduser` int(10)
,`username` varchar(20)
,`usuario` varchar(201)
,`idingreso` int(10)
,`ingreso` text
,`tipo` int(2)
,`idsubclase` int(10)
,`porcentaje_ingreso` decimal(5,2)
,`dsm_min` decimal(5,2)
,`dsm_max` decimal(5,2)
,`costo_min` decimal(12,2)
,`costo_max` decimal(12,2)
,`clave` varchar(20)
,`ano` int(4)
,`status_ingreso` int(2)
,`idemp` int(5)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `_vi_Usuario_Dep`
--
CREATE TABLE IF NOT EXISTS `_vi_Usuario_Dep` (
`iddepuser` int(10)
,`iddependencia` int(10)
,`dependencia` varchar(100)
,`iduser` int(10)
,`username` varchar(20)
,`usuario` varchar(201)
,`idemp` int(5)
);
-- --------------------------------------------------------

--
-- Estructura para la vista `_viUsuarios`
--
DROP TABLE IF EXISTS `_viUsuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_viUsuarios` AS select `u`.`iduser` AS `iduser`,`u`.`username` AS `username`,`u`.`password` AS `password`,`u`.`apellidos` AS `apellidos`,`u`.`nombres` AS `nombres`,`u`.`foto` AS `foto`,`u`.`username` AS `user`,`u`.`registro` AS `registro`,`u`.`especialidad` AS `especialidad`,`u`.`domicilio` AS `domicilio`,`u`.`colonia` AS `colonia`,`u`.`idmunicipio` AS `idmunicipio`,`u`.`idestado` AS `idestado`,`u`.`teloficina` AS `teloficina`,`u`.`telpersonal` AS `telpersonal`,`u`.`telfax` AS `telfax`,`u`.`correoelectronico` AS `correoelectronico`,`u`.`idemp` AS `idemp`,`e`.`rs` AS `empresa`,`e`.`logo` AS `logoempresa`,`u`.`idusernivelacceso` AS `idusernivelacceso`,`un`.`nivel_de_acceso` AS `nivel_de_acceso`,(case when (`un`.`clave` is not null) then `un`.`clave` else 0 end) AS `clave`,`u`.`status_usuario` AS `status_usuario`,`u`.`token` AS `token`,`u`.`token_source` AS `token_source`,`u`.`token_validated` AS `token_validated`,`u`.`registrosporpagina` AS `registrosporpagina`,`u`.`param1` AS `param1` from ((`usuarios` `u` left join `empresa` `e` on((`u`.`idemp` = `e`.`idemp`))) left join `usuarios_niveldeacceso` `un` on((`u`.`idusernivelacceso` = `un`.`idusernivelacceso`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `_vi_Cat_Clases`
--
DROP TABLE IF EXISTS `_vi_Cat_Clases`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_vi_Cat_Clases` AS select `cls`.`idclase` AS `idclase`,`cls`.`idoficina` AS `idoficina`,`cls`.`clase` AS `clase`,`cls`.`status_clase` AS `status_clase`,`o`.`oficina` AS `oficina`,`cls`.`idemp` AS `idemp` from (`cat_clases` `cls` left join `cat_oficinas` `o` on((`cls`.`idoficina` = `o`.`idoficina`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `_vi_Cat_Ingresos`
--
DROP TABLE IF EXISTS `_vi_Cat_Ingresos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_vi_Cat_Ingresos` AS select `ing`.`idingreso` AS `idingreso`,`ing`.`idsubclase` AS `idsubclase`,`ing`.`tipo` AS `tipo`,`ing`.`porcentaje_ingreso` AS `porcentaje_ingreso`,`ing`.`dsm_min` AS `dsm_min`,`ing`.`dsm_max` AS `dsm_max`,`ing`.`costo_min` AS `costo_min`,`ing`.`costo_max` AS `costo_max`,`ing`.`clave` AS `clave`,`ing`.`ingreso` AS `ingreso`,`ing`.`ano` AS `ano`,`ing`.`status_ingreso` AS `status_ingreso`,`sc`.`subclase` AS `subclase`,`sc`.`idclase` AS `idclase`,`sc`.`status_subclase` AS `status_subclase`,`cls`.`idoficina` AS `idoficina`,`cls`.`clase` AS `clase`,`cls`.`status_clase` AS `status_clase`,`o`.`iddependencia` AS `iddependencia`,`o`.`oficina` AS `oficina`,`o`.`status_oficina` AS `status_oficina`,`dep`.`dependencia` AS `dependencia`,`dep`.`status_dependencia` AS `status_dependencia`,`ing`.`idemp` AS `idemp` from ((((`cat_subclases` `sc` left join `cat_ingresos` `ing` on((`ing`.`idsubclase` = `sc`.`idsubclase`))) left join `cat_clases` `cls` on((`sc`.`idclase` = `cls`.`idclase`))) left join `cat_oficinas` `o` on((`cls`.`idoficina` = `o`.`idoficina`))) left join `cat_dependencias` `dep` on((`o`.`iddependencia` = `dep`.`iddependencia`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `_vi_Cat_Oficinas`
--
DROP TABLE IF EXISTS `_vi_Cat_Oficinas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_vi_Cat_Oficinas` AS select `o`.`idoficina` AS `idoficina`,`o`.`iddependencia` AS `iddependencia`,`o`.`oficina` AS `oficina`,`o`.`status_oficina` AS `status_oficina`,`dep`.`dependencia` AS `dependencia`,`o`.`idemp` AS `idemp` from (`cat_oficinas` `o` left join `cat_dependencias` `dep` on((`o`.`iddependencia` = `dep`.`iddependencia`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `_vi_Cat_SubClases`
--
DROP TABLE IF EXISTS `_vi_Cat_SubClases`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_vi_Cat_SubClases` AS select `sc`.`idsubclase` AS `idsubclase`,`sc`.`subclase` AS `subclase`,`sc`.`idclase` AS `idclase`,`sc`.`status_subclase` AS `status_subclase`,`cls`.`clase` AS `clase`,`sc`.`idemp` AS `idemp` from (`cat_subclases` `sc` left join `cat_clases` `cls` on((`sc`.`idclase` = `cls`.`idclase`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `_vi_Contribuciones`
--
DROP TABLE IF EXISTS `_vi_Contribuciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_vi_Contribuciones` AS select `contrib`.`idcontribucion` AS `idcontribucion`,`contrib`.`idingreso` AS `idingreso`,`contrib`.`cantidad` AS `cantidad`,`contrib`.`precio_unitario` AS `precio_unitario`,`contrib`.`subtotal` AS `subtotal`,`contrib`.`descuento` AS `descuento`,`contrib`.`total` AS `total`,`contrib`.`contribuyente` AS `contribuyente`,`contrib`.`observaciones` AS `observaciones`,`contrib`.`tokenpay` AS `tokenpay`,`contrib`.`idtransacpay` AS `idtransacpay`,`contrib`.`fecha` AS `fecha`,date_format(`contrib`.`fecha`,'%d-%m-%Y') AS `cFecha`,`contrib`.`generado_por` AS `generado_por`,`contrib`.`cobrado_por` AS `cobrado_por`,`contrib`.`fecha_cobro` AS `fecha_cobro`,`contrib`.`status_contribucion` AS `status_contribucion`,`ing`.`ingreso` AS `ingreso`,`ing`.`tipo` AS `tipo`,`ing`.`idsubclase` AS `idsubclase`,`ing`.`porcentaje_ingreso` AS `porcentaje_ingreso`,`ing`.`dsm_min` AS `dsm_min`,`ing`.`dsm_max` AS `dsm_max`,`ing`.`costo_min` AS `costo_min`,`ing`.`costo_max` AS `costo_max`,`ing`.`clave` AS `clave`,`ing`.`ano` AS `ano`,`ing`.`status_ingreso` AS `status_ingreso`,`genpor`.`username` AS `creado_por_usuario`,`cobpor`.`username` AS `cobrado_por_usuario`,concat(`cobpor`.`apellidos`,' ',`cobpor`.`nombres`,' (',`cobpor`.`username`,')') AS `cobrado_por_usuario_nombre_completo`,`contrib`.`idemp` AS `idemp` from (((`contribuciones` `contrib` left join `_vi_Cat_Ingresos` `ing` on((`contrib`.`idingreso` = `ing`.`idingreso`))) left join `_viUsuarios` `genpor` on((`contrib`.`generado_por` = `genpor`.`iduser`))) left join `_viUsuarios` `cobpor` on((`contrib`.`cobrado_por` = `cobpor`.`iduser`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `_vi_Dep_User`
--
DROP TABLE IF EXISTS `_vi_Dep_User`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_vi_Dep_User` AS select `du`.`iddepuser` AS `iddepuser`,`du`.`iddependencia` AS `iddependencia`,`ing`.`dependencia` AS `dependencia`,`du`.`iduser` AS `iduser`,`u`.`username` AS `username`,concat(`u`.`apellidos`,' ',`u`.`nombres`) AS `usuario`,`ing`.`idingreso` AS `idingreso`,`ing`.`ingreso` AS `ingreso`,`ing`.`tipo` AS `tipo`,`ing`.`idsubclase` AS `idsubclase`,`ing`.`porcentaje_ingreso` AS `porcentaje_ingreso`,`ing`.`dsm_min` AS `dsm_min`,`ing`.`dsm_max` AS `dsm_max`,`ing`.`costo_min` AS `costo_min`,`ing`.`costo_max` AS `costo_max`,`ing`.`clave` AS `clave`,`ing`.`ano` AS `ano`,`ing`.`status_ingreso` AS `status_ingreso`,`du`.`idemp` AS `idemp` from ((`usuarios_dep` `du` left join `usuarios` `u` on((`du`.`iduser` = `u`.`iduser`))) left join `_vi_Cat_Ingresos` `ing` on((`du`.`iddependencia` = `ing`.`iddependencia`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `_vi_Usuario_Dep`
--
DROP TABLE IF EXISTS `_vi_Usuario_Dep`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecnoint`@`localhost` SQL SECURITY DEFINER VIEW `_vi_Usuario_Dep` AS select `du`.`iddepuser` AS `iddepuser`,`du`.`iddependencia` AS `iddependencia`,`d`.`dependencia` AS `dependencia`,`du`.`iduser` AS `iduser`,`u`.`username` AS `username`,concat(`u`.`apellidos`,' ',`u`.`nombres`) AS `usuario`,`du`.`idemp` AS `idemp` from ((`usuarios_dep` `du` left join `usuarios` `u` on((`du`.`iduser` = `u`.`iduser`))) left join `cat_dependencias` `d` on((`du`.`iddependencia` = `d`.`iddependencia`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
