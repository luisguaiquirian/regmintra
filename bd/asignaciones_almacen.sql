/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : regmintra

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-05-16 17:27:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for asignaciones_almacen
-- ----------------------------
DROP TABLE IF EXISTS `asignaciones_almacen`;
CREATE TABLE `asignaciones_almacen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_asignacion` int(11) NOT NULL,
  `id_inventario` int(11) NOT NULL,
  `id_almacen` int(11) NOT NULL,
  `cantidad_retiro` int(11) NOT NULL,
  `post_cantidad` int(11) NOT NULL,
  `previa_cantidad` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `id_almacen` (`id_almacen`),
  KEY `id_inventario` (`id_inventario`),
  KEY `id_asignacion` (`id_asignacion`),
  CONSTRAINT `asignaciones_almacen_ibfk_1` FOREIGN KEY (`id_almacen`) REFERENCES `almacenes` (`id`),
  CONSTRAINT `asignaciones_almacen_ibfk_2` FOREIGN KEY (`id_inventario`) REFERENCES `inventario` (`id`),
  CONSTRAINT `asignaciones_almacen_ibfk_3` FOREIGN KEY (`id_asignacion`) REFERENCES `asignaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
-- Records of asignaciones_almacen
-- ----------------------------
INSERT INTO `asignaciones_almacen` VALUES ('1', '1', '2', '7', '5', '4995', '5000', '8');
