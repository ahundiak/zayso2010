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
-- Table structure for physical teams
--
DROP TABLE IF EXISTS `team_phy`;
CREATE TABLE         `team_phy`
(
  `id`             int (10) unsigned NOT NULL auto_increment,
  `team_id`        int (10) unsigned default 0,
  `team_des`       char(20) default '',
  `team_key`       char(20) default '',

  `org_id`         int (10) unsigned default 0,
  `cal_year`       int (10) unsigned default 0,
  `sea_type_id`    int (10) unsigned default 0,
  `age`            int (10) unsigned default 0,
  `sex`            char( 2) default 'U',
  `num`            int (10) unsigned default 0,

  `name`       char(20) default '',
  `colors`     char(40) default '',

  `status`     int (10) unsigned default 1,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`team_id`),
  UNIQUE KEY    `i1` (`org_id`,`cal_year`,`sea_type_id`,`age`,`sex`,`num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- Table structure for schedule teams
--
DROP TABLE IF EXISTS `team_sch`;
CREATE TABLE         `team_sch`
(
  `id`             int (10) unsigned NOT NULL auto_increment,
  `team_phy_id`    int (10) unsigned default 0,
  `sch_type_id`    int (10) unsigned default 0,
  `descx`          char(20) default '',

  `org_id`         int (10) unsigned default 0,
  `cal_year`       int (10) unsigned default 0,
  `sea_type_id`    int (10) unsigned default 0,
  `age`            int (10) unsigned default 0,
  `sex`            char( 2) default 'U',
  `sortx`          int (10) unsigned default 0,

  `status`     int (10) unsigned default 1,

  PRIMARY KEY  (`id`),
  KEY     `i0` (`team_phy_id`),
  KEY     `i1` (`org_id`,`cal_year`,`sea_type_id`,`age`,`sex`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- Table structure for physical team person
--
DROP TABLE IF EXISTS `team_phy_person`;
CREATE TABLE         `team_phy_person`
(
  `id`             int (10) unsigned NOT NULL auto_increment,
  `team_phy_id`    int (10) unsigned default 0,
  `person_id`      int (10) unsigned default 0,
  `type_id`        int (10) unsigned default 0,

  PRIMARY KEY  (`id`),
  UNIQUE  KEY   `i0` (`team_phy_id`,`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

