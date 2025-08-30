/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : regmintra

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-05-16 17:27:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for almacenes
-- ----------------------------
DROP TABLE IF EXISTS `almacenes`;
CREATE TABLE `almacenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `fec_reg` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`) USING BTREE,
  KEY `nivel` (`nivel`) USING BTREE,
  CONSTRAINT `almacenes_ibfk_1` FOREIGN KEY (`nivel`) REFERENCES `almacenes_nivel` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
-- Records of almacenes
-- ----------------------------
INSERT INTO `almacenes` VALUES ('7', '1', 'J', '6101', 'Almacen principal de baterias', '100/RENDON', '1', '1', '4', '02934333724', '02934333724', '3', '7', '2019-02-19');
INSERT INTO `almacenes` VALUES ('8', '1', 'J', 'ALMNAC001', 'COVENCAUCHO URACHICHE', 'A 500 MTS DE AUTOPISTA YARACUY URACHICHE', '20', '6', '1', '02888888888', '04265555555', '1', '7', '2019-03-07');
INSERT INTO `almacenes` VALUES ('9', '2', 'J', 'ALMEST001', 'COVENCAUCHO CUMANAGOTO', 'ZONA IND SAN LUIS SECTOR CUMANAGOTO', '17', '9', '2', '02999999999', '04266666666', '1', '7', '2019-02-19');
INSERT INTO `almacenes` VALUES ('10', '2', 'J', 'E21ALM02', 'ALMACEN BATERIA ZULIA ', 'SDFAAAFSDFFSD', '21', '18', '3', '21213121221', '54546546546', '3', '7', '2019-03-06');
INSERT INTO `almacenes` VALUES ('11', '1', 'J', 'ALMNAC0002', 'PROPATRIA FONTUR', 'DISTRITO CAPITAL, PATIOS DE PROPATRIA (METRO DE CARACAS)', '1', '1', '1', '02888888888', '04265555555', '1', '7', '2019-03-07');
INSERT INTO `almacenes` VALUES ('12', '2', 'J', '00002', 'aceites vargas', 'vargas', '24', '1', '7', '04147779611', '04265809095', '2', '7', '2019-04-27');
INSERT INTO `almacenes` VALUES ('13', '2', 'J', 'NEU0001V', 'NEUMATICOS VARGAS', 'LA GUAIRA VARGAS', '24', '1', '5', '02555555555', '04266666666', '1', '7', '2019-05-04');
INSERT INTO `almacenes` VALUES ('14', '2', 'J', '5UC73', 'DEPOSUCRE', 'CASCAJAL', '17', '9', '1', '02934521258', '04145659999', '1', '7', '2019-05-14');
INSERT INTO `almacenes` VALUES ('15', '2', 'J', '989759898', 'SUCRERENTAL', 'LLANADA PALO FINO EXTRA LINE', '17', '9', '1', '02934511555', '01412595895', '2', '7', '2019-05-14');
INSERT INTO `almacenes` VALUES ('16', '1', 'J', '898989898989', 'CSDVVDS', 'VSDVDVDSV', '17', '15', '1', '25423534534', '35345435345', '1', '7', '2019-05-15');
