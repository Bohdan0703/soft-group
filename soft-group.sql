/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : soft-group

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2017-01-26 11:48:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for address
-- ----------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `aid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) unsigned NOT NULL COMMENT 'Посилання на назву країни (країну)',
  `cityid` int(11) unsigned NOT NULL COMMENT 'Посилання на місто',
  `street` varchar(127) NOT NULL COMMENT 'Назва вулиці',
  `house` varchar(127) NOT NULL COMMENT 'Дім',
  `zip_code` varchar(32) NOT NULL,
  PRIMARY KEY (`aid`),
  UNIQUE KEY `aid` (`aid`) USING HASH,
  KEY `cid` (`cid`) USING HASH,
  KEY `address_ibfk_2` (`cityid`),
  CONSTRAINT `address_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `countries` (`cid`) ON UPDATE NO ACTION,
  CONSTRAINT `address_ibfk_2` FOREIGN KEY (`cityid`) REFERENCES `cities` (`cid`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Список усіх адресів, поле із назвою країни є писаланням на запис із таблиці, що містить список країн';

-- ----------------------------
-- Table structure for cities
-- ----------------------------
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `cid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список міст';

-- ----------------------------
-- Table structure for countries
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `cid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT 'Назва країни',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `cid` (`cid`) USING HASH,
  KEY `name` (`name`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Список усіх країн';

-- ----------------------------
-- Table structure for directors
-- ----------------------------
DROP TABLE IF EXISTS `directors`;
CREATE TABLE `directors` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `year_death` int(4) unsigned DEFAULT NULL,
  `nationality` int(11) unsigned NOT NULL COMMENT 'Посилання на країну',
  PRIMARY KEY (`did`),
  UNIQUE KEY `did` (`did`),
  KEY `uid` (`uid`),
  CONSTRAINT `directors_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for films
-- ----------------------------
DROP TABLE IF EXISTS `films`;
CREATE TABLE `films` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(11) unsigned NOT NULL COMMENT 'Посилання на автора (Режисер)',
  `gid` int(11) unsigned NOT NULL COMMENT 'Посилання на жанр',
  `duration` time NOT NULL,
  `year` smallint(4) unsigned NOT NULL COMMENT 'Рік видання',
  `budget` int(9) unsigned NOT NULL COMMENT 'бюджет',
  `sid` int(11) unsigned NOT NULL COMMENT 'Посилання на студію',
  `date_receipt_library` date NOT NULL COMMENT 'дата поступлення у фонд відеотеки',
  PRIMARY KEY (`fid`),
  KEY `year` (`year`),
  KEY `author` (`author`),
  KEY `gid` (`gid`),
  KEY `sid` (`sid`),
  CONSTRAINT `films_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`uid`) ON UPDATE NO ACTION,
  CONSTRAINT `films_ibfk_2` FOREIGN KEY (`gid`) REFERENCES `genres` (`gid`) ON UPDATE NO ACTION,
  CONSTRAINT `films_ibfk_3` FOREIGN KEY (`sid`) REFERENCES `studies` (`sid`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for genres
-- ----------------------------
DROP TABLE IF EXISTS `genres`;
CREATE TABLE `genres` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL DEFAULT '' COMMENT 'Назва жанру',
  PRIMARY KEY (`gid`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  UNIQUE KEY `gid` (`gid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for studies
-- ----------------------------
DROP TABLE IF EXISTS `studies`;
CREATE TABLE `studies` (
  `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Назва студії',
  `aid` int(11) unsigned NOT NULL COMMENT 'Посилання на адресу',
  `uid` int(11) unsigned NOT NULL COMMENT 'Посилання на контрактну особу',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `name` (`name`) USING HASH,
  UNIQUE KEY `sid` (`sid`),
  KEY `studies_users` (`uid`),
  KEY `aid` (`aid`),
  CONSTRAINT `studies_ibfk_1` FOREIGN KEY (`aid`) REFERENCES `address` (`aid`) ON UPDATE NO ACTION,
  CONSTRAINT `studies_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список усіх студій, поля із адресою і контактною особою - посиланнями на записи із інших таблиць';

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `year_birth` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `first_name` (`first_name`,`last_name`,`year_birth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список усіх користувачів';
