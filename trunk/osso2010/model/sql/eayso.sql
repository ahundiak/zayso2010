/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- ==============================================
-- New database
DROP DATABASE IF EXISTS eayso;
CREATE DATABASE eayso;
GRANT ALL ON eayso.* TO "impd"@"localhost";
FLUSH PRIVILEGES;
USE eayso;

-- ==============================================
-- Table structure for eayso registered volunteer
--
DROP TABLE IF EXISTS `eayso_vol`;
CREATE TABLE         `eayso_vol`
(
  `id`          int(10) unsigned NOT NULL auto_increment,
  `aysoid`     char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `nname`      char(20) default NULL,
  `mname`      char(20) default NULL,
  `suffix`     char(20) default NULL,
  `region`     int (10) unsigned default NULL,
  `reg_year`   char(10) default NULL,
  `dob`        char (8) default NULL,
  `gender`     char (2) default NULL,
  `phone_home` char(20) default NULL,
  `phone_work` char(20) default NULL,  
  `phone_cell` char(20) default NULL,
  `email`      char(64) default NULL,    
  `source`     char(20) default NULL, -- eayso_vol, eayso_cert
  `ts_created` char(16) default NULL,
  `ts_updated` char(16) default NULL,


  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`aysoid`),
  KEY `i1` (`fname`,`lname`),
  KEY `i2` (`nname`,`lname`),
  KEY `i3` (`lname`,`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Table structure for eayso volunteer certification
--
DROP TABLE IF EXISTS `eayso_vol_cert`;
CREATE TABLE         `eayso_vol_cert`
(
  `id`         int (10) unsigned NOT NULL auto_increment,
  `region`     int (10) unsigned default NULL,
  `aysoid`     char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `cert_cat`   int (10) unsigned default NULL,
  `cert_type`  int (10) unsigned default NULL,
  `cert_desc`  char(40) default NULL,
  `cert_date`  char (8) default NULL,
  `source`     char(20) default NULL, -- eayso_cert
  `ts_created` char(16) default NULL,
  `ts_updated` char(16) default NULL,

  PRIMARY KEY (`id`),
  UNIQUE  KEY  `i0` (`aysoid`,`cert_desc`),
  UNIQUE  KEY  `i1` (`aysoid`,`cert_cat`,`cert_type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Volunteers can belog to more that one region
-- Capture this, not sure how to use it
--
DROP TABLE IF EXISTS `eayso_vol_region`;
CREATE TABLE         `eayso_vol_region`
(
  `id`         int (10) unsigned NOT NULL auto_increment,
  `region`     int (10) unsigned default NULL,
  `aysoid`     char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `source`     char(20) default NULL, -- eayso_cert
  `ts_created` char(16) default NULL,
  `ts_updated` char(16) default NULL,

  PRIMARY KEY (`id`),
  UNIQUE  KEY  `i0` (`aysoid`,`region`),
  UNIQUE  KEY  `i1` (`region`,`aysoid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
