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
-- Table structure for eayso registered volunteer
--
DROP TABLE IF EXISTS `eayso_vol`;
CREATE TABLE         `eayso_vol` (
  `eayso_vol_id` int(10) unsigned NOT NULL auto_increment,
  `aysoid`     char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `nname`      char(20) default NULL,
  `region`     int (10) unsigned default NULL,
  `season`     char(10) default NULL,
  `status`     char(10) default NULL,
  `dob`        char (8) default NULL,
  `gender`     char (2) default NULL,
  `phone_home` char(20) default NULL,
  `phone_work` char(20) default NULL,  
  `phone_cell` char(20) default NULL,
  `email`      char(64) default NULL,    

  PRIMARY KEY  (`eayso_vol_id`),
  UNIQUE KEY `eayso_vol_i0` (`aysoid`),
  KEY `eayso_vol_i1` (`fname`,`lname`),
  KEY `eayso_vol_i2` (`lname`,`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- Table structure for eayso pre-registered volunteer
--
DROP TABLE IF EXISTS `eayso_vol_pre`;
CREATE TABLE         `eayso_vol_pre` (
  `eayso_vol_pre_id` int(10) unsigned NOT NULL auto_increment,
  `aysoidpre`  char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `nname`      char(20) default NULL,
  `region`     int (10) unsigned default NULL,
  `season`     char(10) default NULL,
  `status`     char(10) default NULL,
  `dob`        char (8) default NULL,
  `gender`     char (2) default NULL,
  `phone_home` char(20) default NULL,
  `phone_work` char(20) default NULL,  
  `phone_cell` char(20) default NULL,
  `email`      char(64) default NULL,    
  `changed_date` char ( 8) default NULL,
  `changed_by`   char (20) default NULL,

  PRIMARY KEY  (`eayso_vol_pre_id`),
  UNIQUE KEY `eayso_vol_pre_i0` (`aysoidpre`),
  KEY `eayso_vol_pre_i1` (`fname`,`lname`),
  KEY `eayso_vol_pre_i2` (`lname`,`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- Table structure to link pre and registered tables
--
DROP TABLE IF EXISTS `eayso_vol_link`;
CREATE TABLE         `eayso_vol_link` (
  `eayso_vol_link_id` int(10) unsigned NOT NULL auto_increment,
  `aysoidpre`  char(20) default NULL,
  `aysoid`     char(20) default NULL,
  `status`     char(10) default NULL,

  PRIMARY KEY  (`eayso_vol_link_id`),
  UNIQUE KEY `eayso_vol_link_i0` (`aysoidpre`,`aysoid`),
  KEY `eayso_vol_link_i1` (`aysoid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Table structure for eayso volunteer certification
--
DROP TABLE IF EXISTS `eayso_vol_cert`;
CREATE TABLE         `eayso_vol_cert` (
  `eayso_vol_cert_id` int(10) unsigned NOT NULL auto_increment,
  `aysoid`             char(20) default NULL,
  `cert_type`          int (10) unsigned default NULL,
  `cert_desc`          char(40) default NULL,
  `cert_date`          char (8) default NULL,

  PRIMARY KEY (`eayso_vol_cert_id`),
  UNIQUE  KEY  `eayso_vol_cert_i0` (`aysoid`,`cert_desc`,`cert_date`),
          KEY  `eayso_vol_cert_i1` (`cert_desc`,`cert_date`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

