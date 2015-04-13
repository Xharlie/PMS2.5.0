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

 Date: 03/01/2015 22:17:54 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `Customers`
-- ----------------------------
DROP TABLE IF EXISTS `Customers`;
CREATE TABLE `Customers` (
  `SSN` char(18) NOT NULL COMMENT 'Goverment ID for a guest, could be passport number as well',
  `RM_TRAN_ID` int(10) NOT NULL COMMENT 'room transaction id, linked to each current room ',
  `CUS_NAME` varchar(30) DEFAULT NULL COMMENT 'customer name',
  `RM_ID` int(6) DEFAULT NULL COMMENT 'room id ',
  `MEM_ID` int(10) DEFAULT NULL COMMENT 'member id ',
  `TREATY_ID` varchar(20) DEFAULT NULL COMMENT 'treaty id ',
  `PHONE` varchar(15) DEFAULT NULL COMMENT 'phone number',
  `PROVNCE` varchar(10) DEFAULT NULL COMMENT 'derived from ssn',
  `POINTS` bigint(15) DEFAULT NULL,
  `MEM_TP` varchar(10) DEFAULT NULL,
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'remark',
  PRIMARY KEY (`SSN`,`RM_TRAN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `DepositReceipt`
-- ----------------------------
--  DROP TABLE IF EXISTS `DepositReceipt`;
--  CREATE TABLE `DepositReceipt` (
--  `DPST_ID` int(20) NOT NULL,
--  `RM_TRAN_ID` int(20) DEFAULT NULL,
--  `DPST_AMNT` float(20,2) DEFAULT NULL,
--  `DPST_TSTMP` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
--  PRIMARY KEY (`DPST_ID`)
--  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Employee`
-- ----------------------------
DROP TABLE IF EXISTS `Employee`;
CREATE TABLE `Employee` (
  `EMP_ID` varchar(15) NOT NULL COMMENT 'employee ID',
  `EMP_NM` varchar(30) DEFAULT NULL COMMENT 'employee name',
  `EMP_SSN` char(18) NOT NULL COMMENT 'employee government ID',
  `EMP_TP` varchar(30) DEFAULT NULL COMMENT 'employee type',
  `EMP_GP` varchar(30) DEFAULT NULL COMMENT 'employee group',
  `EMP_IN_DT` date DEFAULT NULL COMMENT 'employee join date',
  `EMP_MN_DYOFF` int(3) DEFAULT NULL COMMENT 'employee remain day off for the year',
  `EMP_SHFT` varchar(10) DEFAULT NULL COMMENT 'shift name',
  `EMP_PRFMCE` int(2) DEFAULT NULL COMMENT 'employee performance,0-100',
  PRIMARY KEY (`EMP_ID`,`EMP_SSN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `remember_token` varchar(100) NOT NULL COMMENT 'session remember token',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `EmployeeType`
-- ----------------------------
DROP TABLE IF EXISTS `EmployeeType`;
CREATE TABLE `EmployeeType` (
  `EMP_TP` varchar(100) NOT NULL COMMENT 'employee type',
  `EMP_GP` varchar(100) DEFAULT NULL COMMENT 'employee group',
  `EMP_DUTY_RMRK` varchar(255) DEFAULT NULL COMMENT 'employee duty description',
  `EMP_BS_SALRY` float(15,2) DEFAULT NULL COMMENT 'employee base salary',
  `EMP_DFLT_SYS_ACC` int(2) DEFAULT NULL COMMENT 'employee default access level',
  PRIMARY KEY (`EMP_TP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `HistoryChInRecord`
-- ----------------------------
DROP TABLE IF EXISTS `HistoryChInRecord`;
CREATE TABLE `HistoryChInRecord` (
  `SSN` char(18) NOT NULL COMMENT 'government issued id',
  `TREATY_ID` varchar(20) DEFAULT NULL COMMENT 'treaty id ',
  `CH_IN_DATE` date DEFAULT NULL COMMENT 'check in date',
  `CH_OT_DATE` date DEFAULT NULL COMMENT 'check out date',
  `RM_TP` varchar(10) DEFAULT NULL COMMENT 'room type',
  `HOTEL_ID` varchar(10) DEFAULT NULL COMMENT 'hotel id ',
  PRIMARY KEY (`SSN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `HistoryCustm`
-- ----------------------------
-- DROP TABLE IF EXISTS `HistoryCustm`;
-- CREATE TABLE `HistoryCustm` (
--  `SSN` char(18) NOT NULL,
--  `NM` varchar(30) DEFAULT NULL,
--  `GEN` varchar(10) DEFAULT NULL,
--  `DOB` date DEFAULT NULL,
--  `MEM_ID` varchar(10) DEFAULT NULL,
--  `ADDRSS_ID` int(10) DEFAULT NULL,
--  `TIMES` int(10) DEFAULT NULL,
--  PRIMARY KEY (`SSN`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `HotelInfo`
-- ----------------------------
--  DROP TABLE IF EXISTS `HotelInfo`;
--  CREATE TABLE `HotelInfo` (
--  `HOTEL_ID` int(23) NOT NULL,
--  `ADDRRS` varchar(255) DEFAULT NULL,
--  `CITY` varchar(255) DEFAULT NULL,
--  `PRNCE` varchar(255) DEFAULT NULL,
--  `CHIEF_OF_HOTEL` varchar(255) DEFAULT NULL,
--  PRIMARY KEY (`HOTEL_ID`)
--  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `InfoCenter`
-- ----------------------------
--  DROP TABLE IF EXISTS `InfoCenter`;
--  CREATE TABLE `InfoCenter` (
--  `MSG_INDX` int(23) NOT NULL,
--  `MSG_TYPE` char(255) DEFAULT NULL,
--  `MSG_RMRK` varchar(255) DEFAULT NULL,
--  `MSG_STMP` timestamp(6) NULL DEFAULT NULL,
--  `MSG_STATUS` char(255) DEFAULT NULL,
--  `RM_ID??` varchar(255) DEFAULT NULL,
--  PRIMARY KEY (`MSG_INDX`)
--  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Location`
-- ----------------------------
-- DROP TABLE IF EXISTS `Location`;
-- CREATE TABLE `Location` (
--  `ADDRSS_ID` varchar(255) NOT NULL DEFAULT '',
--  `CITY` varchar(255) DEFAULT NULL,
--  `PRVNCE` varchar(255) DEFAULT NULL,
--  `COUNTRY` varchar(255) DEFAULT NULL,
--  PRIMARY KEY (`ADDRSS_ID`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `MemCheckInHistory`
-- ----------------------------
-- DROP TABLE IF EXISTS `MemCheckInHistory`;
-- CREATE TABLE `MemCheckInHistory` (
--  `会员号` int(23) NOT NULL,
--  `入住日期` date DEFAULT NULL,
--  `退房日期` date DEFAULT NULL,
--  `酒店号` int(23) NOT NULL,
--  `住单号` int(23) NOT NULL,
--  `协议号` int(23) DEFAULT NULL,
--  PRIMARY KEY (`会员号`,`酒店号`,`住单号`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `MemTran`
-- ----------------------------
DROP TABLE IF EXISTS `MemTran`;
CREATE TABLE `MemTran` (
  `MEM_TRAN_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'member transaction id ',
  `MEM_ID` int(10) unsigned DEFAULT NULL COMMENT 'member id',
  `EMP_ID` varchar(20) DEFAULT NULL COMMENT 'employee id',
  `MEM_TSTMP` datetime(2) DEFAULT CURRENT_TIMESTAMP(2) COMMENT 'time stamp of change or insert',
  `FEE_AMNT` float(10,2) DEFAULT NULL COMMENT 'fee amount',
  `FEE_TP` varchar(10) DEFAULT NULL COMMENT 'fee type ',
  `RMRK` varchar(250) DEFAULT NULL COMMENT 'remark',
  `FILLED` varchar(5) DEFAULT NULL COMMENT 'paid or not',
  PRIMARY KEY (`MEM_TRAN_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `MemTranDeposit`
-- ----------------------------
DROP TABLE IF EXISTS `MemTranDeposit`;
CREATE TABLE `MemTranDeposit` (
  `MEM_DEPO_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'member deposit id ',
  `MEM_TRAN_ID` int(15) DEFAULT NULL COMMENT 'memeber transaction id, linked to MemTran',
  `PAY_MTHD` varchar(10) DEFAULT NULL COMMENT 'pay method',
  `EMP_ID` int(20) DEFAULT NULL COMMENT 'employee id for this transaction',
  `MEM_ID` int(10) DEFAULT NULL COMMENT 'member id ',
  `PAY_AMNT` float(10,2) DEFAULT NULL COMMENT 'amount paid',
  `RMRK` varchar(250) DEFAULT NULL COMMENT 'remark',
  PRIMARY KEY (`MEM_DEPO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `MemberInfo`
-- ----------------------------
DROP TABLE IF EXISTS `MemberInfo`;
CREATE TABLE `MemberInfo` (
  `MEM_ID` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT 'member id',
  `SSN` char(18) DEFAULT NULL COMMENT 'government id',
  `MEM_NM` varchar(30) DEFAULT NULL COMMENT 'member name ',
  `MEM_GEN` varchar(10) DEFAULT NULL COMMENT 'member generation',
  `MEM_DOB` date DEFAULT NULL COMMENT 'member date of birth',
  `PROV` varchar(20) DEFAULT NULL COMMENT 'province',
  `CITY` varchar(20) DEFAULT NULL COMMENT 'city',
  `ADDRS` varchar(60) DEFAULT NULL COMMENT 'address',
  `PHONE` varchar(15) DEFAULT NULL COMMENT 'phone',
  `IN_DT` date DEFAULT NULL COMMENT 'become member date',
  `EMAIL` varchar(150) DEFAULT NULL COMMENT 'email',
  `TIMES` int(10) DEFAULT NULL COMMENT 'visited times',
  `POINTS` bigint(15) DEFAULT NULL COMMENT 'points',
  `MEM_TP` varchar(10) DEFAULT NULL COMMENT 'member type',
  `SSN_TP` varchar(10) DEFAULT NULL COMMENT 'ssn type',
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'remark',
  `EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id of whom register this member',
  PRIMARY KEY (`MEM_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9291225 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `MemberType`
-- ----------------------------
DROP TABLE IF EXISTS `MemberType`;
CREATE TABLE `MemberType` (
  `MEM_TP` varchar(10) DEFAULT NULL COMMENT 'member type',
  `DISCOUNT_RATE` float(4,2) DEFAULT NULL COMMENT 'discount rate percent , should divide 100',
  `ID` int(4) NOT NULL COMMENT 'id',
  `MEM_IN_FEE` float(10,2) DEFAULT NULL COMMENT 'member fee',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `PenaltyAcct`
-- ----------------------------
DROP TABLE IF EXISTS `PenaltyAcct`;
CREATE TABLE `PenaltyAcct` (
  `PEN_BILL_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'penalty bill id',
  `RM_TRAN_ID` int(10) DEFAULT NULL COMMENT 'room tran id of who cause the penalty',
  `PNLTY_PAY_AMNT` float(12,2) DEFAULT NULL COMMENT 'penalty pay amount',
  `PAYER_NM` varchar(30) DEFAULT NULL COMMENT 'payer''s name',
  `PAYER_PHONE` varchar(15) DEFAULT NULL COMMENT 'payer phone',
  `BILL_TSTMP` datetime DEFAULT NULL COMMENT 'bill generated datetime',
  `ORGN_ACCT_ID` varchar(15) DEFAULT NULL COMMENT 'organization account id',
  `PAY_METHOD` varchar(10) DEFAULT NULL COMMENT 'pay method',
  `EMP_ID` varchar(20) DEFAULT NULL COMMENT 'employee id operating this penalty',
  `TKN_RM_TRAN_ID` int(10) DEFAULT NULL COMMENT 'the room transaction if of who pay the penalty',
  `FILLED` varchar(2) DEFAULT NULL COMMENT 'penalty paid or not',
  `BRK_EQPMT_RMRK` varchar(255) DEFAULT NULL COMMENT 'broken equipment remark',
  PRIMARY KEY (`PEN_BILL_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ProductInTran`
-- ----------------------------
DROP TABLE IF EXISTS `ProductInTran`;
CREATE TABLE `ProductInTran` (
  `STR_TRAN_ID` int(20) NOT NULL COMMENT 'store transaction id',
  `PROD_ID` varchar(10) NOT NULL COMMENT 'product id',
  `PROD_QUAN` int(8) DEFAULT NULL COMMENT 'product purchased quantity',
  PRIMARY KEY (`STR_TRAN_ID`,`PROD_ID`),
  KEY `fk_ProductInTran` (`PROD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='product id and store transaction id mapping';

-- ----------------------------
--  Table structure for `ProductInfo`
-- ----------------------------
DROP TABLE IF EXISTS `ProductInfo`;
CREATE TABLE `ProductInfo` (
  `PROD_ID` varchar(10) NOT NULL COMMENT 'product id',
  `PROD_NM` varchar(20) DEFAULT NULL COMMENT 'product name',
  `PROD_COST` float(12,2) DEFAULT NULL COMMENT 'product cost',
  `PROD_AVA_QUAN` int(8) DEFAULT NULL COMMENT 'product available quantity',
  `PROD_TP` varchar(20) DEFAULT NULL COMMENT 'product type',
  `PROD_PRICE` float(12,2) DEFAULT NULL COMMENT 'product price',
  `ROOM_BAR` varchar(2) DEFAULT NULL COMMENT 'is or not in room food bar',
  PRIMARY KEY (`PROD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ReservationRoom`
-- ----------------------------
DROP TABLE IF EXISTS `ReservationRoom`;
CREATE TABLE `ReservationRoom` (
  `RESV_ID` int(20) NOT NULL COMMENT 'reservation id',
  `RM_TP` varchar(10) NOT NULL DEFAULT '' COMMENT 'room type that reserved',
  `RM_QUAN` int(5) DEFAULT NULL COMMENT 'room quantity',
  `RESV_DAY_PAY` float(10,2) DEFAULT NULL COMMENT 'reservation price per day',
  `STATUS` char(10) NOT NULL DEFAULT 'F' COMMENT 'status such as canceled, no show ,filled, 预定,预付',
  PRIMARY KEY (`RESV_ID`,`RM_TP`,`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Reservations`
-- ----------------------------
DROP TABLE IF EXISTS `Reservations`;
CREATE TABLE `Reservations` (
  `RESV_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'reservation id',
  `RESV_TMESTMP` datetime DEFAULT NULL COMMENT 'reserve time',
  `RESV_WAY` varchar(30) DEFAULT NULL COMMENT 'reserve chanel',
  `CHECK_IN_DT` date DEFAULT NULL COMMENT 'check in date',
  `RESV_LATEST_TIME` time DEFAULT NULL COMMENT 'reserve keep latest time',
  `CHECK_OT_DT` date DEFAULT NULL COMMENT 'check out date',
  `RESVER_NAME` varchar(30) DEFAULT NULL COMMENT 'reserver name',
  `RESVER_PHONE` varchar(15) DEFAULT NULL COMMENT 'phone',
  `MEMBER_ID` int(10) DEFAULT NULL COMMENT 'member id',
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'reservation remark',
  `TREATY_ID` varchar(15) DEFAULT NULL COMMENT 'treaty id',
  `RESVER_EMAIL` varchar(80) DEFAULT NULL COMMENT 'reserver email',
  `PRE_PAID` float(12,2) DEFAULT '0.00' COMMENT 'prepaid money',
  PRIMARY KEY (`RESV_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ReserveDepositAcct`
-- ----------------------------
--  DROP TABLE IF EXISTS `ReserveDepositAcct`;
--  CREATE TABLE `ReserveDepositAcct` (
--  `RESV_DEPO_ID` int(20) NOT NULL AUTO_INCREMENT,
--  `RESV_ID` int(20) DEFAULT NULL,
--  `DEPO_AMNT` float(12,2) DEFAULT NULL,
--  `PAY_METHOD` varchar(10) DEFAULT NULL,
--  `DEPO_TSTMP` datetime DEFAULT NULL,
--  `ORGN_ACCT_ID` varchar(15) DEFAULT NULL,
--  `EMP_ID` varchar(20) DEFAULT NULL,
--  PRIMARY KEY (`RESV_DEPO_ID`)
--  ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `RoomAcct`
-- ----------------------------
DROP TABLE IF EXISTS `RoomAcct`;
CREATE TABLE `RoomAcct` (
  `RM_BILL_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'room bill id',
  `RM_TRAN_ID` int(10) DEFAULT NULL COMMENT 'room transaction id ',
  `RM_PAY_AMNT` float(12,2) DEFAULT NULL COMMENT 'room bill payment amount',
  `BILL_TSTMP` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'room bill timestamp',
  `ORGN_ACCT_ID` int(15) DEFAULT NULL COMMENT 'organization account id',
  `RM_PAY_METHOD` varchar(10) DEFAULT NULL COMMENT 'room payment method',
  `EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id',
  `TKN_RM_TRAN_ID` int(10) DEFAULT NULL COMMENT 'room id that take responsibililty for the bill',
  `FILLED` varchar(2) DEFAULT NULL COMMENT 'filled or not',
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'remark',
  PRIMARY KEY (`RM_BILL_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `RoomDepositAcct`
-- ----------------------------
DROP TABLE IF EXISTS `RoomDepositAcct`;
CREATE TABLE `RoomDepositAcct` (
  `RM_DEPO_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'room deposit id',
  `RM_TRAN_ID` int(10) DEFAULT NULL COMMENT 'room transaction id',
  `DEPO_AMNT` float(10,2) DEFAULT NULL COMMENT 'deposit amount',
  `PAY_METHOD` varchar(10) DEFAULT NULL COMMENT 'pay method',
  `DEPO_TSTMP` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'deposit happend time',
  `ORGN_ACCT_ID` int(15) DEFAULT NULL COMMENT 'organization account id',
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'remark',
  `EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id operating the deposit',
  PRIMARY KEY (`RM_DEPO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `RoomOccupation`
-- ----------------------------
DROP TABLE IF EXISTS `RoomOccupation`;
CREATE TABLE `RoomOccupation` (
  `DATE` date NOT NULL COMMENT 'date of occupation',
  `RM_TP` varchar(10) NOT NULL COMMENT 'room type',
  `RESV_QUAN` int(6) DEFAULT NULL COMMENT 'reservation quantity',
  `CHECK_QUAN` int(6) DEFAULT NULL COMMENT 'room already been checked quantity',
  PRIMARY KEY (`RM_TP`,`DATE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `RoomStoreTran`
-- ----------------------------
DROP TABLE IF EXISTS `RoomStoreTran`;
CREATE TABLE `RoomStoreTran` (
  `RM_TRAN_ID` int(23) DEFAULT NULL COMMENT 'room transaction id',
  `STR_TRAN_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'store transaction id',
  `RM_ID` int(20) DEFAULT NULL COMMENT 'room id',
  `TKN_RM_TRAN_ID` int(23) DEFAULT NULL COMMENT 'room transaction id that take the bill',
  `FILLED` varchar(2) DEFAULT NULL COMMENT 'filled or not',
  PRIMARY KEY (`STR_TRAN_ID`),
  KEY `fk_RoomStoreTran` (`RM_TRAN_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `RoomTran`
-- ----------------------------
DROP TABLE IF EXISTS `RoomTran`;
CREATE TABLE `RoomTran` (
  `RM_ID` varchar(7) DEFAULT NULL COMMENT 'room id',
  `CHECK_IN_DT` date DEFAULT NULL COMMENT 'check in date',
  `CHECK_OT_DT` date DEFAULT NULL COMMENT 'check out date',
  `RM_TRAN_ID` int(10) NOT NULL AUTO_INCREMENT COMMENT 'room transaction date',
  `RM_AVE_PRCE` float(12,2) DEFAULT NULL COMMENT 'room average price',
  `DPST_RMN` float(12,2) DEFAULT NULL COMMENT 'deposit remain',
  `RSRV_PAID_DYS` int(5) DEFAULT NULL COMMENT 'reservation paid days',
  `CHECK_TP` varchar(10) DEFAULT NULL COMMENT 'check in type, rource type : member or walk in, etc',
  `TREATY_ID` varchar(15) DEFAULT NULL COMMENT 'treaty id',
  `MEM_ID` int(10) DEFAULT NULL COMMENT 'member id',
  `CONN_RM_TRAN_ID` int(10) DEFAULT NULL COMMENT 'master room transaction id',
  `CARDS_NUM` int(3) DEFAULT NULL COMMENT 'cards number',
  `DPST_FIXED` float(12,2) DEFAULT NULL COMMENT 'fixed deposit',
  `LEAVE_TM` time DEFAULT NULL COMMENT 'leave time',
  `TMP_PLAN_ID` int(10) DEFAULT NULL COMMENT 'hour room plan id',
  `FILLED` varchar(2) DEFAULT NULL COMMENT 'filled or not, T or F',
  PRIMARY KEY (`RM_TRAN_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Rooms`
-- ----------------------------
DROP TABLE IF EXISTS `Rooms`;
CREATE TABLE `Rooms` (
  `RM_ID` varchar(7) NOT NULL COMMENT 'room id',
  `RM_TRAN_ID` int(10) DEFAULT NULL COMMENT 'room transaction id',
  `RM_CONDITION` varchar(10) DEFAULT NULL COMMENT 'room condition',
  `RM_TP` varchar(10) DEFAULT NULL COMMENT 'room type',
  `RM_CHNG_TSTMP` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'room changing time',
  `FLOOR` varchar(10) DEFAULT NULL COMMENT 'floor name',
  `FLOOR_ID` int(5) DEFAULT NULL COMMENT 'floor id',
  `PHONE` varchar(15) DEFAULT NULL COMMENT 'phone number of room',
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'remark',
  PRIMARY KEY (`RM_ID`),
  KEY `RM_TRAN_ID` (`RM_TRAN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
--  Table structure for `Rooms_copy`
-- ----------------------------
-- DROP TABLE IF EXISTS `Rooms_copy`;
-- CREATE TABLE `Rooms_copy` (
--  `RM_ID` int(20) NOT NULL,
--  `RM_TRAN_ID` int(20) DEFAULT NULL,
--  `RM_CONDITION` varchar(20) CHARACTER SET armscii8 DEFAULT NULL,
--  `RM_TP` varchar(20) DEFAULT NULL,
--  `RM_CHNG_TSTMP` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
--  PRIMARY KEY (`RM_ID`),
--  KEY `RM_TP` (`RM_TP`),
--  KEY `RM_TRAN_ID` (`RM_TRAN_ID`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ShiftDefinition`
-- ----------------------------
DROP TABLE IF EXISTS `ShiftDefinition`;
CREATE TABLE `ShiftDefinition` (
  `SHFT_DSGN_ID` int(5) NOT NULL COMMENT 'user designed shift id',
  `SHFT_DSGN_NM` varchar(10) DEFAULT NULL COMMENT 'user designed shift name',
  `SHFT_STD_ST_TME` time DEFAULT NULL COMMENT 'shift standard start time',
  `SHFT_STD_END_TME` time DEFAULT NULL COMMENT 'shift standard end time',
  `NXT_SHFT_DSGN_ID` int(5) DEFAULT NULL COMMENT 'designed next shift id  ',
  `NXT_SHFT_NM` varchar(10) DEFAULT NULL COMMENT 'designed next shift name',
  PRIMARY KEY (`SHFT_DSGN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Shifts`
-- ----------------------------
DROP TABLE IF EXISTS `Shifts`;
CREATE TABLE `Shifts` (
  `SHFT_ID` int(5) NOT NULL AUTO_INCREMENT COMMENT 'shift id',
  `SHFT_ST_TSTMP` datetime DEFAULT NULL COMMENT 'shift start timestamp',
  `SHFT_PSS_EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id of who passing shift',
  `SHFT_RCVR_EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id of who receiving shift',
  `SHFT_NM` varchar(30) DEFAULT NULL COMMENT 'shift name',
  `SHFT_ST_CSH` float(12,2) DEFAULT NULL COMMENT 'shift start cash',
  `SHFT_END_CSH` float(12,2) DEFAULT NULL COMMENT 'shift end cash',
  `SHFT_CSH_ADD` float(12,2) DEFAULT NULL COMMENT 'shift cash added',
  PRIMARY KEY (`SHFT_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10005 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `StoreTransaction`
-- ----------------------------
DROP TABLE IF EXISTS `StoreTransaction`;
CREATE TABLE `StoreTransaction` (
  `STR_TRAN_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'store transaction id',
  `STR_TRAN_TSTAMP` datetime DEFAULT NULL COMMENT 'store transaction time',
  `STR_PAY_METHOD` varchar(10) DEFAULT NULL COMMENT 'store pay method',
  `STR_PAY_AMNT` float(12,2) DEFAULT NULL COMMENT 'store pay amount',
  `ORGN_ACCT_ID` int(15) DEFAULT NULL COMMENT 'organization account id',
  `EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id',
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'remark',
  PRIMARY KEY (`STR_TRAN_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `TempRmSetting`
-- ----------------------------
DROP TABLE IF EXISTS `TempRmSetting`;
CREATE TABLE `TempRmSetting` (
  `RM_TP` varchar(10) DEFAULT NULL COMMENT 'room type',
  `PLAN_COV_MIN` int(4) DEFAULT NULL COMMENT 'plan covered minutes',
  `PLAN_COV_PRCE` float(12,2) DEFAULT NULL COMMENT 'plan covered price',
  `PNLTY_PR_MIN` float(12,2) DEFAULT NULL COMMENT 'penalty per extra minutes',
  `PLAN_ID` int(15) NOT NULL AUTO_INCREMENT COMMENT 'plan id',
  PRIMARY KEY (`PLAN_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Tickets`
-- ----------------------------
DROP TABLE IF EXISTS `Tickets`;
CREATE TABLE `Tickets` (
  `TCKT_ID` int(10) NOT NULL COMMENT 'ticket id',
  `EMGNCY_LVL` varchar(10) DEFAULT NULL COMMENT 'emergency level',
  `ISS_TSTMP` datetime DEFAULT NULL COMMENT 'issued time',
  `CLS_DT` date DEFAULT NULL COMMENT 'closed date',
  `TTL` varchar(30) DEFAULT NULL COMMENT 'title',
  `ISS_EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id of who issued the ticket',
  `ISS_EMP_NM` varchar(30) DEFAULT NULL COMMENT 'employee name of who issued the ticket',
  `ASSGN_EMP_ID` varchar(15) DEFAULT NULL COMMENT 'employee id of who is assigned the ticket to ',
  `ASSGN_EMP_NM` varchar(30) DEFAULT NULL COMMENT 'employee name of who is assigned the ticket to ',
  `RMRK` varchar(255) DEFAULT NULL COMMENT 'remark',
  PRIMARY KEY (`TCKT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Time`
-- ----------------------------
-- DROP TABLE IF EXISTS `Time`;
-- CREATE TABLE `Time` (
--  `DATE` int(23) NOT NULL,
--  `YEAR` int(23) DEFAULT NULL,
--  `MONTH` int(23) DEFAULT NULL,
--  `DAY` int(23) DEFAULT NULL,
--  `WEEKDAY` int(23) DEFAULT NULL,
--  `QUARTER` int(23) DEFAULT NULL,
--  PRIMARY KEY (`DATE`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `Treaty`
-- ----------------------------
DROP TABLE IF EXISTS `Treaty`;
CREATE TABLE `Treaty` (
  `TREATY_ID` varchar(15) NOT NULL COMMENT 'treaty id',
  `CORP_NM` varchar(30) DEFAULT NULL COMMENT 'corporation name',
  `TREATY_TP` varchar(15) DEFAULT NULL COMMENT 'treaty type',
  `CORP_PHONE` varchar(15) DEFAULT NULL COMMENT 'corporation phone number',
  `CONTACT_NM` varchar(30) DEFAULT NULL COMMENT 'contact name',
  `CONTACT_PHONE` varchar(15) DEFAULT NULL COMMENT 'contact phone number',
  `DISCOUNT` float(5,2) DEFAULT NULL COMMENT 'discount',
  `RMARK` varchar(255) DEFAULT NULL COMMENT 'remark',
  PRIMARY KEY (`TREATY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
