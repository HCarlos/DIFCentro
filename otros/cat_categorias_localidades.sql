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
-- Table structure for table `cat_categorias_localidades`
--

CREATE TABLE IF NOT EXISTS `cat_categorias_localidades` (
  `idcategorialocalidad` int(2) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(15) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `host` varchar(100) NOT NULL,
  `creado_por` int(5) NOT NULL,
  `creado_el` datetime NOT NULL,
  `modi_por` int(5) NOT NULL,
  `modi_el` datetime NOT NULL,
  PRIMARY KEY (`idcategorialocalidad`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Catálogo de Categoría de Localidade' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cat_categorias_localidades`
--

INSERT INTO `cat_categorias_localidades` (`idcategorialocalidad`, `categoria`, `ip`, `host`, `creado_por`, `creado_el`, `modi_por`, `modi_el`) VALUES
(1, 'COLONIA', '189.132.192.113', 'dsl-189-132-192-113-dyn.prod-infinitum.com.mx', 60, '2014-06-12 12:37:44', 0, '0000-00-00 00:00:00'),
(2, 'EJIDO', '189.132.192.113', 'dsl-189-132-192-113-dyn.prod-infinitum.com.mx', 60, '2014-06-12 12:38:01', 0, '0000-00-00 00:00:00'),
(3, 'FRACCIONAMIENTO', '189.132.192.113', 'dsl-189-132-192-113-dyn.prod-infinitum.com.mx', 60, '2014-06-12 12:38:14', 0, '0000-00-00 00:00:00'),
(4, 'POBLADO', '189.132.192.113', 'dsl-189-132-192-113-dyn.prod-infinitum.com.mx', 60, '2014-06-12 12:38:30', 0, '0000-00-00 00:00:00'),
(5, 'RANCHERIA', '189.132.192.113', 'dsl-189-132-192-113-dyn.prod-infinitum.com.mx', 60, '2014-06-12 12:38:58', 0, '0000-00-00 00:00:00'),
(6, 'VILLA', '189.132.192.113', 'dsl-189-132-192-113-dyn.prod-infinitum.com.mx', 60, '2014-06-12 12:39:10', 60, '2014-06-12 13:48:55'),
(7, 'EN TUMBULUSHAL', '189.133.113.99', 'dsl-189-133-113-99-dyn.prod-infinitum.com.mx', 60, '2014-06-17 12:50:52', 0, '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
