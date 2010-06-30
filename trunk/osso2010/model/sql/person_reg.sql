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
-- Table structure for registered persons
--
DROP TABLE IF EXISTS `person_reg`;
CREATE TABLE         `person_reg`
(
  `id`                 int (10) unsigned NOT NULL auto_increment,
  `person_id`          int (10) unsigned default 0,

  `person_reg_type_id` int (10) unsigned default 0, -- 1=osso, 2=ayso
  `person_reg_num`     char(20) default NULL, -- aysoid
  `person_reg_year`    int (10) unsigned default 0,

  `fname`      char(20) default '',
  `lname`      char(20) default '',
  `nname`      char(20) default '',
  `mname`      char(20) default '',
  `sname`      char(20) default '', -- suffix

  `dob`        char (8) default NULL,
  `gender`     char (2) default NULL,
  `phone_home` char(20) default NULL,
  `phone_work` char(20) default NULL,  
  `phone_cell` char(20) default NULL,
  `email`      char(64) default NULL,
  `email2`     char(64) default NULL,

  `ts_created` char(16) default NULL,
  `ts_updated` char(16) default NULL,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`person_reg_type_id`,`person_reg_num`),
  KEY `i1` (`fname`,`lname`),
  KEY `i2` (`nname`,`lname`),
  KEY `i3` (`lname`,`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Table structure for reg_person certification
--
DROP TABLE IF EXISTS `person_reg_cert`;
CREATE TABLE         `person_reg_cert`
(
  `id`            int (10) unsigned NOT NULL auto_increment,
  `person_reg_id` int (10) unsigned NOT NULL,

   -- Checking
  `person_reg_num` char(20) default NULL,
  `fname`          char(20) default NULL,
  `lname`          char(20) default NULL,

  `cert_cat`   int (10) unsigned default NULL,
  `cert_type`  int (10) unsigned default NULL,
  `cert_desc`  char(40) default NULL,
  `cert_date`  char (8) default NULL,
  
  `ts_created` char(16) default NULL,
  `ts_updated` char(16) default NULL,

  PRIMARY KEY (`id`),
  UNIQUE  KEY  `i0` (`person_reg_id`,`cert_cat`),
  UNIQUE  KEY  `i1` (`person_reg_id`,`cert_type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Volunteers can belog to more that one region
-- Capture this, not sure how to use it
--
DROP TABLE IF EXISTS `person_reg_org`;
CREATE TABLE         `person_reg_org`
(
  `id`            int (10) unsigned NOT NULL auto_increment,
  `person_reg_id` int (10) unsigned NOT NULL,
  `org_id`        int (10) unsigned NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE  KEY  `i0` (`person_reg_id`,`org_id`),
  UNIQUE  KEY  `i1` (`org_id`,`person_reg_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
