/*
 Navicat Premium Data Transfer

 Source Server         : XharlieLocal
 Source Server Type    : MySQL
 Source Server Version : 50623
 Source Host           : localhost
 Source Database       : BI

 Target Server Type    : MySQL
 Target Server Version : 50623
 File Encoding         : utf-8

 Date: 03/31/2015 16:04:06 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `RoomsTypes`
-- ----------------------------
DROP TABLE IF EXISTS `RoomsTypes`;
CREATE TABLE `RoomsTypes` (
  `RM_TP` varchar(10) NOT NULL COMMENT 'room type',
  `SUGG_PRICE` float(12,2) DEFAULT NULL COMMENT 'suggested price',
  `RM_QUAN` int(6) DEFAULT NULL COMMENT 'room qantity',
  `CUS_QUAN` int(3) DEFAULT NULL COMMENT 'customer quantity',
  `LEAST_DPST` float(12,2) DEFAULT NULL COMMENT 'lower limit of deposit ',
  `RM_PROD_RMRK` varchar(255) DEFAULT NULL COMMENT 'room product remark',
  PRIMARY KEY (`RM_TP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `RoomsTypes`
-- ----------------------------
BEGIN;
INSERT INTO `RoomsTypes` VALUES ('三人间', '258.00', '6', '3', '0.00', null), ('商务大床', '188.00', '7', '2', '0.00', null), ('商务标准', '198.00', '4', '2', '0.00', null), ('团购单人间', '98.00', '5', '1', '0.00', null), ('团购大床房', '98.00', '5', '2', '0.00', null), ('团购标准间', '98.00', '6', '2', '0.00', null), ('大床房', '168.00', '51', '2', '0.00', null), ('家庭房', '218.00', '5', '3', '0.00', null), ('标准房', '178.00', '28', '2', '0.00', null), ('特惠房', '98.00', '8', '2', '0.00', null), ('电脑经济房', '168.00', '1', '2', '0.00', null), ('经济房', '148.00', '1', '2', '0.00', null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
