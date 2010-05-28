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
DROP TABLE IF EXISTS `s5games_teams`;
CREATE TABLE         `s5games_teams` (
  `id`         int (10) unsigned NOT NULL auto_increment,
  `region`     int (10) unsigned default NULL,
  `div`        char (8) default NULL,
  `name`       char(20) default NULL,
  `colors`     char(20) default NULL,
  `status`     char(10) default NULL,
  `notes`      char(80) default NULL,

  PRIMARY KEY  (`id`),
  KEY `s5games_teams_i1` (`region`,`div`),
  KEY `s5games_teams_i2` (`div`,`region`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `s5games_teams` AUTO_INCREMENT = 1000;

DROP TABLE IF EXISTS `s5games_team_persons`;
CREATE TABLE         `s5games_team_persons` (
  `id`       int (10) unsigned NOT NULL auto_increment,
  `team_id`  int (10) unsigned,
  `type_id`  int (10) unsigned,
  `aysoid`   char(20) default NULL,
  `fname`    char(20) default NULL,
  `lname`    char(20) default NULL,
  `nname`    char(20) default NULL,
  `notes`    char(80) default NULL,


  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;