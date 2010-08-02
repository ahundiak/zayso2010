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
-- Table structure for registered persons data
--
DROP TABLE IF EXISTS `reg_main`;
CREATE TABLE         `reg_main`
(
  `id`         int (10) unsigned NOT NULL auto_increment,

  `reg_type`   int (10) unsigned default 0,
  `reg_num`    char(20)          NOT NULL,
  `reg_year`   int (10) unsigned default 0,

  `fname`      char(20) default '',
  `lname`      char(20) default '',
  `nname`      char(20) default '',
  `mname`      char(20) default '',
  `sname`      char(20) default '', -- suffix

  `dob`        char (8) default '',
  `sex`        char (2) default '',
  `status`     int (10) unsigned default 1,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`reg_type`,`reg_num`),
  KEY           `i1` (`lname`,`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Table structure for reg_cert certification
--
DROP TABLE IF EXISTS `reg_cert`;
CREATE TABLE         `reg_cert`
(
  `id`         int (10) unsigned NOT NULL auto_increment,

  `reg_type`   int (10) unsigned default 0,
  `reg_num`    char(20)          NOT NULL,

  -- Cert info
  `catx`   int (10) unsigned default 0,
  `typex`  int (10) unsigned default 0,
  `datex`  char (8)          default '',
  `yearx`  int (10) unsigned default 0, -- effective for
 
  PRIMARY KEY (`id`),
  UNIQUE  KEY  `i0` (`reg_type`,`reg_num`,`catx`),
  UNIQUE  KEY  `i1` (`catx`,`reg_type`,`reg_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Table structure for reg_prop - additional properties
--
DROP TABLE IF EXISTS `reg_prop`;
CREATE TABLE         `reg_prop`
(
  `id`         int (10) unsigned NOT NULL auto_increment,

  `reg_type`   int (10) unsigned default 0,
  `reg_num`    char(20)          NOT NULL,

  `typex`   int (10) unsigned default 0,
  `indexx`  int (10) unsigned default 1,
  `valuex` char (80)          default '',

  `flag1`  int (10) unsigned default 1,
  `flag2`  int (10) unsigned default 0,

  PRIMARY KEY (`id`),
  UNIQUE  KEY  `i0` (`reg_type`,`reg_num`,`typex`,`indexx`),
  UNIQUE  KEY  `i1` (`typex`,`indexx`,`reg_type`,`reg_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Volunteers can belog to more that one region
-- Capture this, not sure how to use it
-- Note that the org id has to be universal
--
DROP TABLE IF EXISTS `reg_org`;
CREATE TABLE         `reg_org`
(
  `id`         int (10) unsigned NOT NULL auto_increment,

  `reg_type`   int (10) unsigned default 0,
  `reg_num`    char(20)          NOT NULL,

  `org_id`     int (10) unsigned NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE  KEY  `i0` (`reg_type`,`reg_num`,`org_id`),
  UNIQUE  KEY  `i1` (`org_id`,`reg_type`,`reg_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Probably don't really need this in all databases
DROP TABLE IF EXISTS `reg_type`;
CREATE TABLE         `reg_type`
(
  `id`    int (10) unsigned NOT NULL auto_increment,
  `keyx`  char(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE  KEY `i1` (`keyx`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO reg_type VALUES
(101,'osso'),
(102,'ayso vol'),
(202,'ayso player');
