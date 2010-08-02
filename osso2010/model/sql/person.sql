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
-- Table structure for persons
--

use osso;

DROP TABLE IF EXISTS `person`;
CREATE TABLE         `person`
(
  `id`         int (10) unsigned NOT NULL auto_increment,

  `fname`      char(20) default '',
  `lname`      char(20) default '',
  `nname`      char(20) default '',
  `mname`      char(20) default '',
  `sname`      char(20) default '', -- suffix

  `dob`        char (8) default '',
  `gender`     char (2) default '',

  `status`     int (10) unsigned default 1,

  `ts_created` char(16) default NULL,
  `ts_updated` char(16) default NULL,

  PRIMARY KEY  (`id`),
  KEY `i1` (`fname`,`lname`),
  KEY `i2` (`nname`,`lname`),
  KEY `i3` (`lname`,`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- Table structure for registered persons
--
DROP TABLE IF EXISTS `person_reg`;
CREATE TABLE         `person_reg`
(
  `id`          int (10) unsigned NOT NULL auto_increment,

  `person_id`   int (10) unsigned default 0,

  `reg_type`    int (10) unsigned default 0, -- 1=osso, 2=ayso
  `reg_num`     char(20)          NOT NULL,  -- aysoid

  PRIMARY KEY  (`id`),
  UNIQUE  KEY   `i0` (`reg_type`,`reg_num`),
  UNIQUE  KEY   `i2` (person_id,`reg_type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
