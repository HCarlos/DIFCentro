-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2016 at 11:35 AM
-- Server version: 5.6.30
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tecnoint_dbSIPADPRO`
--

-- --------------------------------------------------------

--
-- Table structure for table `cat_delegaciones`
--

CREATE TABLE IF NOT EXISTS `cat_delegaciones` (
  `iddelegacion` int(10) NOT NULL AUTO_INCREMENT,
  `delegacion` varchar(150) NOT NULL,
  `idcategorialocalidad` int(5) NOT NULL,
  `zona` varchar(20) NOT NULL,
  `ruta` varchar(50) NOT NULL,
  `distrito` varchar(2) NOT NULL,
  `seccion_electoral` varchar(10) NOT NULL,
  `mapa_localidad` int(5) NOT NULL,
  `riesgo_comunidad` varchar(10) NOT NULL,
  `idplantapotabilizadora` int(3) NOT NULL DEFAULT '0',
  `grado_marginacion` varchar(10) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL,
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL,
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`iddelegacion`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Delegaciones' AUTO_INCREMENT=195 ;

--
-- Dumping data for table `cat_delegaciones`
--

INSERT INTO `cat_delegaciones` (`iddelegacion`, `delegacion`, `idcategorialocalidad`, `zona`, `ruta`, `distrito`, `seccion_electoral`, `mapa_localidad`, `riesgo_comunidad`, `idplantapotabilizadora`, `grado_marginacion`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, '18 de Marzo (San Joaquín).', 1, 'URBANA', 'URBANA NORTE', '', '345 - 397 ', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(2, 'Acachapan y Colmena 1ª.', 5, 'RURAL', 'FRONTERA', 'XX', '450', 1, '', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(3, 'Acachapan y Colmena 2ª.', 5, 'RURAL', 'FRONTERA', 'XX', '', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(4, 'Acachapan y Colmena 3ª.', 5, 'RURAL', 'FRONTERA', 'XX', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(5, 'Acachapan y Colmena 4ª.', 5, 'RURAL', 'FRONTERA', 'XX', '441', 1, 'Alto', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(6, 'Acachapan y Colmena 5ª.', 5, 'RURAL', 'FRONTERA', '', '441', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(7, 'Adolfo López Mateos.', 1, 'URBANA', 'URBANA SUR', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(8, 'Agraria y La Isla.', 5, 'RURAL', 'TEAPA', 'V', '', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(9, 'Alvarado Colima 1ª.', 5, 'RURAL', 'TEAPA', '', '508', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(10, 'Alvarado Guarda Costa.', 5, 'RURAL', 'TEAPA', 'V', '', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(11, 'Alvarado Jimbal.', 5, 'RURAL', 'TEAPA', '', '', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(12, 'Alvarado Santa Irene 1ª.', 5, 'RURAL', 'TEAPA', '', '', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(13, 'Alvarado Santa Irene 2ª.', 5, 'RURAL', 'TEAPA', 'V', '', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(14, 'Anacleto Canabal 1ª.', 5, 'RURAL', 'CARDENAS', '', '465', 1, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(15, 'Anacleto Canabal 2ª.', 5, 'RURAL', 'CARDENAS', '', '464', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(16, 'Anacleto Canabal 3ª.', 5, 'RURAL', 'CARDENAS', '', '452', 1, 'Medio', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(17, 'Anacleto Canabal 4ª.', 5, 'RURAL', 'CARDENAS', '', '458', 1, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(18, 'Andrés Sánchez Magallanes.', 1, 'URBANA', 'URBANA NORTE', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(19, 'Aniceto (Tamulte de las Sabanas).', 5, 'RURAL', 'FRONTERA', 'XX', '423', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(20, 'Atasta de Serra.', 1, 'URBANA', 'URBANA NORTE', 'V', '314', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(21, 'Aztlán 1ª.', 5, 'RURAL', 'MACUSPANA', 'XX', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(22, 'Aztlán 2ª.', 5, 'RURAL', 'MACUSPANA', 'XX', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(23, 'Aztlán 3ª.', 5, 'RURAL', 'FRONTERA', '', '441', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(24, 'Aztlán 4ª (Sector Bajío).', 5, 'RURAL', 'FRONTERA', '', '441', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(25, 'Aztlán 4ª', 5, 'RURAL', 'FRONTERA', '', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(26, 'Aztlán 5ª, (Sector Palomillal).', 5, 'RURAL', 'FRONTERA', '', '441', 1, 'Alto', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(27, 'Bajío (La Cruz).', 5, 'RURAL', 'MACUSPANA', 'XX', '', 1, 'Bajo', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(28, 'Barrancas y Amate.', 5, 'RURAL', 'MACUSPANA', 'XX', '', 1, 'Bajo', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(29, 'Barrancas y Guanal (Ejido González).', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(30, 'Barrancas y Guanal (Ejido López Portillo).', 5, 'RURAL', 'MACUSPANA', '', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(31, 'Barrancas y Guanal (Tintillo).', 5, 'RURAL', 'MACUSPANA', 'XX', '', 0, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(32, 'Bonanza, Oropeza y Nueva Imagen.', 2, 'URBANA', 'URBANA SUR', '', '267', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(33, 'Boquerón 3ª, 4ª Y 5ª.', 5, 'RURAL', 'CARDENAS', '', '494 - 495 ', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(34, 'Buenavista Rio Nuevo 2ª.', 5, 'RURAL', 'CARDENAS', '', '476', 1, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(35, 'Buenavista Rio Nuevo 3ª.', 5, 'RURAL', 'CARDENAS', '', '477', 1, 'Medio', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(36, 'Buenavista Rio Nuevo 4ª.', 5, 'RURAL', 'CARDENAS', '', '475', 1, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(37, 'Buenavista 1ª, (Tamulte de las Sabanas).', 5, 'RURAL', 'FRONTERA', 'XX', '426', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(38, 'Buenavista 2ª, (Tamulte de las Sabanas).', 5, 'RURAL', 'FRONTERA', 'XX', '425', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(39, 'Buenavista Rio Nuevo 1ª.', 5, 'RURAL', 'CARDENAS', '', '467', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(40, 'Carrizal.', 1, 'URBANA', 'URBANA NORTE', '', '314 - 304', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(41, 'Carrizal.', 2, 'URBANA', 'URBANA SUR', '', '251 - 256', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(42, 'Casa Blanca 1ª.', 1, 'URBANA', 'URBANA SUR', 'XX', '254 - 267 ', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(43, 'Casa Blanca 2ª.', 1, 'URBANA', 'URBANA SUR', 'XX', '450', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(44, 'Censo.', 5, 'RURAL', 'TEAPA', '', '', 1, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(45, 'Coronel Traconis (La Isla).', 5, 'RURAL', 'MACUSPANA', '', '', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(46, 'Coronel Traconis 3ª Y 4ª (San Francisco).', 5, 'RURAL', 'MACUSPANA', 'XX', '', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(47, 'Coronel Traconis 2ª, (Zapote) y 5ª (San Rafael y Diego).', 5, 'RURAL', 'MACUSPANA', '', '', 1, 'Medio', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(48, 'Corozal.', 5, 'RURAL', 'MACUSPANA', 'XX', '', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(49, 'Corregidora Ortiz 1ª.', 5, 'RURAL', 'CARDENAS', '', '', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(50, 'Corregidora Ortiz 2ª.', 5, 'RURAL', 'CARDENAS', '', '469', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(51, 'Corregidora Ortiz 3ª.', 5, 'RURAL', 'CARDENAS', '', '469 - 475', 1, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(52, 'Corregidora Ortiz 4ª.', 5, 'RURAL', 'CARDENAS', '', '', 1, 'Bajo', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(53, 'Corregidora Ortiz 5ª.', 5, 'RURAL', 'CARDENAS', '', '475', 1, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(54, 'Chacte.', 5, 'RURAL', 'MACUSPANA', '', '', 1, 'Bajo', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(55, 'Chiquiguao 1ª.', 5, 'RURAL', 'MACUSPANA', '', '', 1, 'Bajo', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(56, 'Delegación 1, Centro.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(57, 'Delegación 2, Centro.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(58, 'Delegación 3, Centro.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(59, 'Delegación 4, Centro.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(60, 'Delegación 5, Centro.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(61, 'Delegación 6, Centro.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(62, 'Delicias.', 1, 'URBANA', 'URBANA NORTE', '', '397', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(63, 'Emiliano Zapata.', 5, 'RURAL', 'CARDENAS', '', '453', 0, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(64, 'Espejo I y Original.', 1, 'URBANA', 'URBANA NORTE', '', '304 - 315', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(65, 'Espejo II.', 1, 'URBANA', 'URBANA NORTE', '', '273', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(66, 'Espino (Tamulte de las Sabanas).', 5, 'RURAL', 'FRONTERA', 'XX', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(67, 'Estancia (Tamulte de las Sabanas).', 5, 'RURAL', 'FRONTERA', 'XX', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(68, 'Estancia Vieja 1ª.', 5, 'RURAL', 'CARDENAS', 'V', '', 1, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(69, 'Estancia Vieja 2ª.', 5, 'RURAL', 'CARDENAS', '', '', 1, 'Alto', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(70, 'Estanzuela 2ª.', 5, 'RURAL', 'TEAPA', '', '', 1, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(71, 'Estanzuela 1ª.', 5, 'RURAL', 'TEAPA', '', '500', 1, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(72, 'Fovisste I y II.', 2, 'URBANA', 'URBANA NORTE', 'V', '416', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(73, 'Framboyanes (Residencial).', 2, 'URBANA', 'URBANA SUR', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(74, 'Galaxias.', 2, 'URBANA', 'URBANA NORTE', '', '275', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(75, 'Gaviotas Sur y Gaviotas Sur (Sector San José).', 1, 'URBANA', 'URBANA NORTE', 'XX', '405 - 474 ', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(76, 'Gaviotas Norte (Sector Popular).', 1, 'URBANA', 'URBANA SUR', 'V', '291', 1, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(77, 'Gaviotas Norte.', 1, 'URBANA', 'URBANA NORTE', 'V', '356 - 358 ', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(78, 'Gaviotas Sur (Sector Armenia).', 1, 'URBANA', 'URBANA NORTE', 'XX', '', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(79, 'Gaviotas Sur (Sector Explanada).', 1, 'URBANA', 'URBANA NORTE', 'XX', '', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(80, 'Gil y Sáenz (El Águila).', 1, 'URBANA', 'URBANA NORTE', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(81, 'González 1ª (Sector Punta Brava).', 5, 'RURAL', 'CARDENAS', '', '', 0, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(82, 'González 1ª.', 5, 'RURAL', 'CARDENAS', '', '463', 0, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(83, 'González 2ª.', 5, 'RURAL', 'CARDENAS', '', '462', 0, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(84, 'González 3ª.', 5, 'RURAL', 'CARDENAS', '', '462', 0, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(85, 'González 4ª.', 5, 'RURAL', 'CARDENAS', '', '463', 0, '', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(86, 'Guadalupe Borja de Díaz Ordaz.', 1, 'URBANA', 'URBANA NORTE', 'V', '345', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(87, 'Guayabal.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(88, 'Guineo 1ª.', 5, 'RURAL', 'CARDENAS', '', '481', 0, 'Medio', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(89, 'Guineo 2ª.', 5, 'RURAL', 'CARDENAS', '', '481', 0, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(90, 'Heriberto Kehoe Vincent.', 2, 'URBANA', 'URBANA SUR', '', '267', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(91, 'Huapinol, Parrilla.', 5, 'RURAL', 'TEAPA', '', '474', 0, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(92, 'Huasteca 1ª.', 5, 'RURAL', 'TEAPA', 'V', '508', 0, 'Bajo', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(93, 'Huasteca 2ª.', 5, 'RURAL', 'TEAPA', '', '508', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(94, 'Hueso de Puerco.', 5, 'RURAL', 'TEAPA', 'V', '', 0, 'Bajo', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(95, 'Indeco (Cd. Industrial).', 1, 'URBANA', 'URBANA SUR', 'XX', '242 - 253 ', 1, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(96, 'Infonavit (Atasta de Serra).', 1, 'URBANA', 'URBANA NORTE', '', '369', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(97, 'Infonavit (Cd. Industrial).', 1, 'URBANA', 'URBANA SUR', 'XX', '241', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(98, 'Insurgentes (Cd. Industrial).', 2, 'URBANA', 'URBANA SUR', 'XX', '232', 0, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(99, 'Ismate y Chilapilla 1ª.', 5, 'RURAL', 'MACUSPANA', 'XX', '', 0, 'Alto', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(100, 'Ismate y Chilapilla 2ª.', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Alto', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(101, 'ISSET, El Encanto, Lomas de Ocuiltzapotlan 1ª y Los Ángeles.', 2, 'RURAL', 'FRONTERA', 'XX', '440', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(102, 'Ixtacomitan 1ª y 3ª.', 5, 'RURAL', 'CARDENAS', '', '470', 0, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(103, 'Ixtacomitan 2ª, 4ª y 5ª.', 5, 'RURAL', 'CARDENAS', '', '479', 0, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(104, 'Jesús García.', 1, 'URBANA', 'URBANA SUR', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(105, 'Jolochero 1ª, (Tamulte de las Sabanas).', 5, 'RURAL', 'FRONTERA', 'XX', '436', 0, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(106, 'José Ma. Pino Suarez - Tierra Colorada Etapa I.', 1, 'URBANA', 'URBANA SUR', 'XX', '249 - 234 ', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(107, 'José Ma. Pino Suarez - Tierra Colorada Etapa II (Asunción Castellanos).', 1, 'URBANA', 'URBANA SUR', 'XX', '', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(108, 'José Ma. Pino Suarez - Tierra Colorada Etapa III.', 1, 'URBANA', 'URBANA SUR', 'XX', '237', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(109, 'José Narciso Rovirosa.', 1, 'URBANA', 'URBANA NORTE', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(110, 'La Ceiba (Tamulte de las Sabanas).', 5, 'RURAL', 'FRONTERA', '', '436', 0, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(111, 'Lagartera 1ª y 2ª Secc.', 5, 'RURAL', 'FRONTERA', 'XX', '440 - 495', 0, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(112, 'Lagartera 1ª. Secc. (La Constitución).', 5, 'RURAL', 'FRONTERA', 'XX', '452 - 443', 0, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(113, 'Lázaro Cárdenas 1ª.', 5, 'RURAL', 'CARDENAS', '', '452', 0, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(114, 'Lázaro Cárdenas 2ª.', 5, 'RURAL', 'CARDENAS', '', '458', 0, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(115, 'Lima.', 5, 'RURAL', 'TEAPA', 'V', '', 0, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(116, 'Linda Vista.', 1, 'URBANA', 'URBANA NORTE', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(117, 'LUIS GIL PéREZ.', 6, 'RURAL', 'CARDENAS', 'V', '497 - 498', 0, 'Medio', 0, 'Medio', '189.133.199.9', 'dsl-189-133-199-9-dyn.prod-infinitum.com.mx', 0, '0000-00-00 00:00:00', 1, '2014-06-13 17:39:21'),
(118, 'Macultepec.', 6, 'RURAL', 'FRONTERA', 'XX', '429 - 427 ', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(119, 'Magisterial.', 1, 'URBANA', 'URBANA SUR', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(120, 'Manga Etapa I, (Gaviotas Sur).', 1, 'URBANA', 'URBANA SUR', 'XX', '', 1, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(121, 'Manga Etapa II, (Gaviotas Sur).', 1, 'URBANA', 'URBANA SUR', 'XX', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(122, 'Manga Etapa III, (Gaviotas Sur).', 1, 'URBANA', 'URBANA SUR', 'XX', '', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(123, 'Manga II.', 5, 'RURAL', 'MACUSPANA', '', '450 - 436 ', 0, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(124, 'Matillas (Sector Cocoyol, Ejido Socialista).', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(125, 'Matillas 4ª Secc.', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(126, 'Mayito.', 1, 'URBANA', 'URBANA SUR', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(127, 'Medellín y Pigua 3ª y 4ª.', 5, 'RURAL', 'FRONTERA', 'XX', '446', 0, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(128, 'Medellín y Madero 1ª.', 5, 'RURAL', 'FRONTERA', 'XX', '438', 0, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(129, 'Medellín y Madero 2ª.', 5, 'RURAL', 'FRONTERA', '', '440', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(130, 'Medellín y Madero 3ª.', 5, 'RURAL', 'FRONTERA', '', '438', 0, 'Medio', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(131, 'Medellín y Madero 4ª.', 5, 'RURAL', 'FRONTERA', '', '438', 0, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(132, 'Medellín y Pigua 1ª y 2ª.', 5, 'RURAL', 'FRONTERA', '', '232 - 443 ', 0, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(133, 'Miguel Hidalgo Etapa I.', 1, 'URBANA', 'URBANA NORTE', '', '372 - 470 ', 1, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(134, 'Miguel Hidalgo Etapa II.', 1, 'URBANA', 'URBANA NORTE', '', '477', 1, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(135, 'Miguel Hidalgo Etapa III.', 1, 'URBANA', 'URBANA NORTE', '', '398', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(136, 'Miraflores 1ª (Sector Arroyo Grande).', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Bajo', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(137, 'Miraflores 1ª.', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(138, 'Miraflores 2ª.', 5, 'RURAL', 'MACUSPANA', 'XX', '', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(139, 'Miraflores 3ª.', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Bajo', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(140, 'Miramar (Tamulte De Las Sabanas).', 5, 'RURAL', 'FRONTERA', 'XX', '', 0, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(141, 'Multi 80, 83 y 85.', 2, 'URBANA', 'URBANA NORTE', '', '275', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(142, 'Municipal (Constitución 1917).', 1, 'URBANA', 'URBANA SUR', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(143, 'Nueva Pensiones.', 1, 'URBANA', 'URBANA NORTE', 'V', '', 1, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(144, 'Nueva Villahermosa.', 1, 'URBANA', 'URBANA SUR', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(145, 'Ocuiltzapotlan.', 6, 'RURAL', 'FRONTERA', 'XX', '435 - 433 ', 0, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(146, 'Ocuiltzapotlan II, (Km. 15).', 2, 'RURAL', 'FRONTERA', '', '', 1, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(147, 'Pablo L. Sidar.', 5, 'RURAL', 'CARDENAS', '', '', 0, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(148, 'Pajonal.', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Medio', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(149, 'Palma.', 5, 'RURAL', 'MACUSPANA', 'XX', '', 0, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(150, 'Palmitas (Atasta De Serra).', 2, 'URBANA', 'URBANA NORTE', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(151, 'Parrilla II.', 2, 'RURAL', 'TEAPA', 'V', '', 1, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(152, 'Parrilla.', 6, 'RURAL', 'TEAPA', 'XX', '486 - 484 ', 0, 'Bajo', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(153, 'Paso Real de la Victoria.', 5, 'RURAL', 'FRONTERA', '', '430', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(154, 'Plátano y Cacao 1ª.', 5, 'RURAL', 'CARDENAS', '', '460', 0, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(155, 'Plátano y Cacao 2ª.', 5, 'RURAL', 'CARDENAS', '', '', 0, 'Medio', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(156, 'Plátano y Cacao 3ª.', 5, 'RURAL', 'CARDENAS', '', '', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(157, 'Plátano y Cacao 4ª.', 5, 'RURAL', 'CARDENAS', '', '460', 0, '', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(158, 'Playas del Rosario (Subteniente García).', 6, 'RURAL', 'TEAPA', 'V', '502 - 503 ', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(159, 'Plaza Villahermosa.', 2, 'URBANA', 'URBANA NORTE', 'V', '415', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(160, 'Plutarco Elías Calles (Curahueso).', 1, 'RURAL', 'TEAPA', 'V', '416', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(161, 'Plutarco Elías Calles 3 Ra., y Providencia.', 5, 'URBANA', 'TEAPA', '', '', 0, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(162, 'Dos Montes.', 4, 'RURAL', 'MACUSPANA', 'XX', '', 1, 'Bajo', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(163, 'Prados de Villahermosa.', 2, 'URBANA', 'URBANA SUR', '', '', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(164, 'Primero de Mayo.', 1, 'URBANA', 'URBANA NORTE', 'V', '404', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(165, 'Pueblo Nuevo de las Raíces.', 6, 'RURAL', 'TEAPA', 'V', '', 0, 'Bajo', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(166, 'Punta Brava.', 1, 'URBANA', 'URBANA NORTE', '', '467 - 411', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(167, 'Recreo.', 1, 'URBANA', 'URBANA SUR', '', '', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(168, 'Reforma.', 1, 'URBANA', 'URBANA SUR', 'V', '', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(169, 'Rio Tinto 1ª.', 5, 'RURAL', 'CARDENAS', '', '491', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(170, 'Rio Tinto 2ª.', 5, 'RURAL', 'CARDENAS', '', '491', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(171, 'Rio Tinto 3ª.', 5, 'RURAL', 'CARDENAS', '', '491', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(172, 'Rio Viejo 1ª.', 5, 'RURAL', 'CARDENAS', '', '480', 0, 'Medio', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(173, 'Rio Viejo 2ª.', 5, 'RURAL', 'CARDENAS', '', '', 0, 'Medio', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(174, 'Rio Viejo 3ª.', 5, 'RURAL', 'CARDENAS', '', '481', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(175, 'Ríos (Conjunto Habitacional).', 2, 'URBANA', 'URBANA NORTE', '', '', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(176, 'Rivera de las Raíces.', 5, 'RURAL', 'TEAPA', 'XX', '', 0, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(177, 'Rosas (Ocuiltzapotlan).', 2, 'RURAL', 'FRONTERA', 'XX', '', 0, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(178, 'Sabina.', 1, 'URBANA', 'URBANA NORTE', 'V', '418', 1, 'Alto', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(179, 'Santa Catalina.', 5, 'RURAL', 'CARDENAS', '', '', 0, '', 0, 'Alto', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(180, 'Tamulte de las Barrancas.', 1, 'URBANA', 'URBANA NORTE', 'V', '416 - 415 ', 1, 'Medio', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(181, 'Tamulte de las Sabanas.', 6, 'RURAL', 'FRONTERA', 'XX', '422 - 421', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(182, 'Tierra Amarilla 1ª y 2ª.', 5, 'RURAL', 'FRONTERA', '', '', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(183, 'Tocoal (Tamulte De Las Sabanas).', 5, 'RURAL', 'FRONTERA', '', '', 0, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(184, 'Torno Largo 1ª.', 5, 'RURAL', 'TEAPA', '', '474', 0, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(185, 'Torno Largo 2ª.', 5, 'RURAL', 'TEAPA', 'XX', '', 0, 'Alto', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(186, 'Torno Largo 3ª.', 5, 'RURAL', 'TEAPA', '', '', 0, 'Alto', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(187, 'Tumbulushal.', 5, 'RURAL', 'TEAPA', 'V', '', 0, 'Bajo', 0, 'Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(188, 'Villa Las Flores (Cd. Industrial).', 1, 'URBANA', 'URBANA SUR', 'XX', '', 0, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(189, 'Villa Las Fuentes (Tamulte de las Barrancas).', 2, 'URBANA', 'URBANA NORTE', '', '415', 1, 'Bajo', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(190, 'Villa Los Arcos.', 2, 'URBANA', 'URBANA NORTE', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(191, 'Vista Alegre.', 2, 'URBANA', 'URBANA NORTE', '', '', 1, '', 0, '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(192, 'Vuelta (Ejido La Jagua).', 5, 'RURAL', 'MACUSPANA', '', '', 0, 'Bajo', 0, 'Medio', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(193, 'Zapotal.', 5, 'RURAL', 'FRONTERA', '', '440', 0, '', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00'),
(194, 'Boquerón 1ª, Y 2ª.', 5, 'RURAL', 'CARDENAS', '', '494 - 495 ', 1, 'Alto', 0, 'Muy Bajo', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;