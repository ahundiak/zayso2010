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

DELETE FROM person_reg WHERE person_reg_type_id = 1;

DROP TABLE IF EXISTS `person_reg_type`;
CREATE TABLE         `person_reg_type`
(
  `id`    int (10) unsigned NOT NULL auto_increment,
  `keyx`  char(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE  KEY `i1` (`keyx`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO person_reg_type VALUES
(1,'osso'),
(2,'ayso vol'),
(3,'ayso player');
