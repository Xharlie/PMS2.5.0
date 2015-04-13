/*
 Navicat Premium Data Transfer

 Source Server         : Hotel_Dev
 Source Server Type    : MySQL
 Source Server Version : 50619
 Source Host           : localhost
 Source Database       : BI

 Target Server Type    : MySQL
 Target Server Version : 50619
 File Encoding         : utf-8

 Date: 03/17/2015 19:26:28 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `EmployeeSysAccess`
-- ----------------------------
DROP TABLE IF EXISTS `EmployeeSysAccess`;
CREATE TABLE `EmployeeSysAccess` (
  `id` varchar(15) NOT NULL COMMENT 'employee ID',
  `EMP_NM` varchar(255) DEFAULT NULL COMMENT 'employee name',
  `username` varchar(20) DEFAULT NULL COMMENT 'user name',
  `password` varchar(100) DEFAULT NULL COMMENT 'password',
  `EMP_SYS_LVL` int(2) DEFAULT NULL COMMENT 'employee system access level',
  `remember_token` varchar(100) DEFAULT NULL COMMENT 'session remember token',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `EmployeeSysAccess`
-- ----------------------------
BEGIN;
INSERT INTO `EmployeeSysAccess` VALUES ('1', '史蒂夫', 'steve', '$2a$04$WlTW/9N05r3vZWfxMNHhXe5Xq4mDvRP3Pm4GoYAN28PMIc4BMJ/Li', '1', 'c3ODF3PuwxfR3e2qhflS4alI2yKhf9s7rTYgDxxx6NICACUjAxBXpgy29899'), ('2', '提姆', 'tim', '$2a$10$2LkHYyPFRr2a7P7nTyAwoOqkoLR06Gi0elMqQjoFSSlMUX3KE97f.', '1', ''), ('3', '小刚', 'gang', 'GGG', '1', ''), ('4', '云中鹤', 'crane', 'CCC', '1', ''), ('5', '岳老二', 'second', 'SSS', '1', ''), ('6', '加州企业家', 'elon', 'EEE', '1', ''), ('7', '巴克利', 'charles', 'CCC', '1', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
