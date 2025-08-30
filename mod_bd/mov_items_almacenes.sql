-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2019 a las 05:05:34
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
-- Estructura de tabla para la tabla `mov_items_almacenes`
--

CREATE TABLE `mov_items_almacenes` (
  `id` int(11) NOT NULL,
  `id_mov` int(11) NOT NULL,
  `id_inventario` int(11) NOT NULL,
  `fec_salida` date DEFAULT NULL,
  `cantidad` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  `id_asignacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mov_items_almacenes`
--
ALTER TABLE `mov_items_almacenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mov_items` (`id_mov`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mov_items_almacenes`
--
ALTER TABLE `mov_items_almacenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mov_items_almacenes`
--
ALTER TABLE `mov_items_almacenes`
  ADD CONSTRAINT `fk_mov_items` FOREIGN KEY (`id_mov`) REFERENCES `mov_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
