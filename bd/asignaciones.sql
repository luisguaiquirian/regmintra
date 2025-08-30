/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : regmintra

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-05-16 17:27:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for asignaciones
-- ----------------------------
DROP TABLE IF EXISTS `asignaciones`;
CREATE TABLE `asignaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial` text COLLATE utf8_spanish2_ci NOT NULL,
  `almacen_destino` int(11) NOT NULL,
  `beneficiados` int(11) NOT NULL,
  `precio` double DEFAULT NULL,
  `monto_total` double DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `id_producto_solicitado` int(11) NOT NULL,
  `cantidad_solicitud` int(11) NOT NULL,
  `cantidad_asignada` int(11) NOT NULL,
  `fec_reg` date NOT NULL,
  `fec_aprobado` date DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `almacen_destino` (`almacen_destino`),
  KEY `id_producto` (`id_producto`),
  KEY `id_producto_solicitado` (`id_producto_solicitado`),
  CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`almacen_destino`) REFERENCES `almacenes` (`id`),
  CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
-- Records of asignaciones
-- ----------------------------
INSERT INTO `asignaciones` VALUES ('1', '000000056', '13', '1', '5000', '25000', '7', '4', '6', '5', '2019-05-16', null, '2');
