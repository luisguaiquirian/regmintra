-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2019 a las 05:03:17
-- Versión del servidor: 10.1.39-MariaDB
-- Versión de PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `regmintra2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenes`
--

CREATE TABLE `almacenes` (
  `id` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  `referencia` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` char(150) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish2_ci NOT NULL,
  `estado` int(11) NOT NULL,
  `municipio` int(11) NOT NULL,
  `parroquia` int(11) NOT NULL,
  `telefono` char(11) COLLATE utf8_spanish2_ci NOT NULL,
  `tel_contac` char(11) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_rubro` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT '7',
  `fec_reg` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `almacenes`
--

INSERT INTO `almacenes` (`id`, `nivel`, `referencia`, `codigo`, `nombre`, `direccion`, `estado`, `municipio`, `parroquia`, `telefono`, `tel_contac`, `id_rubro`, `estatus`, `fec_reg`) VALUES
(7, 1, 'J', '6101', 'Almacen principal de baterias', '100/RENDON', 1, 1, 4, '02934333724', '02934333724', 3, 7, '2019-02-19'),
(8, 1, 'J', 'ALMNAC001', 'COVENCAUCHO URACHICHE', 'A 500 MTS DE AUTOPISTA YARACUY URACHICHE', 20, 6, 1, '02888888888', '04265555555', 1, 7, '2019-03-07'),
(9, 2, 'J', 'ALMEST001', 'COVENCAUCHO CUMANAGOTO', 'ZONA IND SAN LUIS SECTOR CUMANAGOTO', 17, 9, 2, '02999999999', '04266666666', 1, 7, '2019-02-19'),
(10, 2, 'J', 'E21ALM02', 'ALMACEN BATERIA ZULIA ', 'SDFAAAFSDFFSD', 21, 18, 3, '21213121221', '54546546546', 3, 7, '2019-03-06'),
(11, 1, 'J', 'ALMNAC0002', 'PROPATRIA FONTUR', 'DISTRITO CAPITAL, PATIOS DE PROPATRIA (METRO DE CARACAS)', 1, 1, 1, '02888888888', '04265555555', 1, 7, '2019-03-07'),
(12, 2, 'J', '00002', 'aceites vargas', 'vargas', 24, 1, 7, '04147779611', '04265809095', 2, 7, '2019-04-27'),
(13, 2, 'J', 'NEU0001V', 'NEUMATICOS VARGAS', 'LA GUAIRA VARGAS', 24, 1, 5, '02555555555', '04266666666', 1, 7, '2019-05-04'),
(14, 2, 'J', '5UC73', 'DEPOSUCRE', 'CASCAJAL', 17, 9, 1, '02934521258', '04145659999', 1, 7, '2019-05-14'),
(15, 2, 'J', '989759898', 'SUCRERENTAL', 'LLANADA PALO FINO EXTRA LINE', 17, 9, 1, '02934511555', '01412595895', 2, 7, '2019-05-14'),
(16, 1, 'J', '898989898989', 'CSDVVDS', 'VSDVDVDSV', 17, 15, 1, '25423534534', '35345435345', 1, 7, '2019-05-15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`) USING BTREE,
  ADD KEY `nivel` (`nivel`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `almacenes`
--
ALTER TABLE `almacenes`
  ADD CONSTRAINT `almacenes_ibfk_1` FOREIGN KEY (`nivel`) REFERENCES `almacenes_nivel` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
