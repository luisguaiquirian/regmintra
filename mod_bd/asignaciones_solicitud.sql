-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2019 a las 05:02:58
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
-- Estructura de tabla para la tabla `asignaciones_solicitud`
--

CREATE TABLE `asignaciones_solicitud` (
  `id` int(11) NOT NULL,
  `id_asignacion` int(11) NOT NULL,
  `id_detalle` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones_solicitud`
--
ALTER TABLE `asignaciones_solicitud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_asignacion` (`id_asignacion`),
  ADD KEY `id_detalle` (`id_detalle`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones_solicitud`
--
ALTER TABLE `asignaciones_solicitud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones_solicitud`
--
ALTER TABLE `asignaciones_solicitud`
  ADD CONSTRAINT `asignaciones_solicitud_ibfk_1` FOREIGN KEY (`id_asignacion`) REFERENCES `asignaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asignaciones_solicitud_ibfk_2` FOREIGN KEY (`id_detalle`) REFERENCES `detalles_solicitudes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
