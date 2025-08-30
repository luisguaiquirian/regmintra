-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2019 a las 05:05:48
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
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` char(200) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish2_ci NOT NULL,
  `marca` text COLLATE utf8_spanish2_ci NOT NULL,
  `modelo` text COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `subtipo` int(11) NOT NULL,
  `presentacion` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT '7',
  `fec_reg` date NOT NULL,
  `precio` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `descripcion`, `marca`, `modelo`, `tipo`, `subtipo`, `presentacion`, `estatus`, `fec_reg`, `precio`) VALUES
(6, '1000', 'Bateria 1000 amp', 'duncan', '1000', 3, 10, 1, 7, '2019-02-19', 10000),
(7, 'NNSU001', ' 175/70/13', 'GOODYEAR', 'EAGLE', 1, 12, 1, 7, '2019-02-19', 10000),
(8, 'LM0001', '25W50', 'PDV', 'PRIMIUN', 2, 13, 2, 7, '2019-02-20', 10000),
(9, 'BJ0001', 'BUJIA R12', 'CHAMPION', 'CHAMPION R12', 17, 16, 1, 7, '2019-03-06', 10000),
(10, 'NA0001', '750-R16', 'IMPORTADO', 'ENCAVA', 1, 11, 1, 7, '2019-03-07', 10000),
(11, 'NA0002', '825-20', 'IMPORTADO', 'ENCAVA', 1, 11, 1, 7, '2019-03-07', 10000),
(12, 'NA0003', '245/70-R16', 'IMPORTADO', 'TRONCAL', 1, 13, 1, 7, '2019-03-07', 10000),
(13, 'NA0004', '31/10-R15', 'IMPORTADO', 'TRONCAL', 1, 13, 1, 7, '2019-03-07', 10000);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`) USING BTREE,
  ADD KEY `productos_ibfk_4` (`tipo`) USING BTREE,
  ADD KEY `productos_ibfk_1` (`estatus`) USING BTREE,
  ADD KEY `productos_ibfk_2` (`presentacion`) USING BTREE,
  ADD KEY `productos_ibfk_3` (`subtipo`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `FK_presentacion` FOREIGN KEY (`presentacion`) REFERENCES `presentaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
