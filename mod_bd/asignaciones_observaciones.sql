-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2019 a las 05:02:49
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
-- Estructura de tabla para la tabla `asignaciones_observaciones`
--

CREATE TABLE `asignaciones_observaciones` (
  `id` int(11) NOT NULL,
  `desde` text NOT NULL,
  `accion` text NOT NULL,
  `observacion` text NOT NULL,
  `id_asignacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones_observaciones`
--
ALTER TABLE `asignaciones_observaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_asignaciones` (`id_asignacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones_observaciones`
--
ALTER TABLE `asignaciones_observaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones_observaciones`
--
ALTER TABLE `asignaciones_observaciones`
  ADD CONSTRAINT `FK_asignaciones` FOREIGN KEY (`id_asignacion`) REFERENCES `asignaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
