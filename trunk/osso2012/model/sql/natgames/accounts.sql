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

-- =============================================
-- Accounts

USE natgames;

DROP TABLE IF EXISTS `account`;
CREATE TABLE         `account`
(
  `id` int(10) unsigned NOT NULL auto_increment,
  `uname`      char(40) default NULL,
  `upass`      char(40) default NULL,
  `status`     char(20) default NULL,

  PRIMARY KEY  (`id`),
  UNIQUE  KEY  (`uname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `account_person`;
CREATE TABLE         `account_person`
(
  `id` int(10) unsigned NOT NULL auto_increment,
  `account_id` int(10) unsigned NOT NULL,
  `person_id`  int(10) unsigned NOT NULL,
  `rel_id`     int(10) unsigned NOT NULL,

  `verified`   char(20) default NULL,
  `status`     char(20) default NULL,

  PRIMARY KEY  (`id`),
  UNIQUE  KEY  (`account_id`,`person_id`),
  UNIQUE  KEY  (`person_id`,`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `person`;
CREATE TABLE         `person`
(
  `id` int(10) unsigned NOT NULL auto_increment,

  `fname`   char(20) default NULL,
  `lname`   char(20) default NULL,
  `nname`   char(20) default NULL,
  `email`   char(40) default NULL,
  `phonec`  char(20) default NULL,

  `verified`   char(20) default NULL,
  `status`     char(20) default NULL,
  `org_key`    char(20) default NULL,

  PRIMARY KEY  (`id`),
  KEY          (`lname`,`fname`),
  KEY          (`lname`,`nname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `person_reg`;
CREATE TABLE  `person_reg`
(
  `id` int(10) unsigned NOT NULL auto_increment,

  `person_id`  int(10) unsigned NOT NULL,

  `reg_type`  char(20) default NULL,
  `reg_key`   char(32) default NULL,

  `verified`  char(20) default NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY  (`reg_key`),
  KEY         (`person_id`,`reg_key`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===================================================================
-- Project stuff
DROP TABLE IF EXISTS `project`;
CREATE TABLE         `project`
(
  `id`      int(10) unsigned NOT NULL auto_increment,
  `desc1`  char(40) default NULL,
  `status` char(20) default NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO project VALUES
(50,'National Games 2010','Past'),
(52,'National Games 2012','Active'),
(54,'National Games 2014','Future');

DROP TABLE IF EXISTS `project_person`;
CREATE TABLE         `project_person`
(
  `id` int(10) unsigned NOT NULL auto_increment,

  `project_id` int(10) unsigned NOT NULL,
  `person_id`  int(10) unsigned NOT NULL,

  `status`     char(20) default NULL,
  `datax`      text NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY  (`project_id`,`person_id`),
  UNIQUE KEY  (`person_id`, `project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ================================================
-- Project sequence numbers
DROP TABLE IF EXISTS  project_seqn;
CREATE TABLE          project_seqn
(
  id          int(10) unsigned NOT NULL auto_increment,
  version     int(10) unsigned default 0,

  project_id  int(10) unsigned default 0,
  key1        char(20),
  seqn        int(10) unsigned default 0,

  PRIMARY KEY (`id`),
  UNIQUE KEY  (`project_id`,`key1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===================================================================
-- Leave game stuff in for now
DROP TABLE IF EXISTS `games`;
CREATE TABLE         `games` (
  `game_num`     int (10) unsigned NOT NULL,
  `game_date`    char( 8) default NULL,
  `game_time`    char( 8) default NULL,
  `game_div`     char( 4) default NULL,
  `game_field`   char(12) default NULL,
  `game_type`    char( 4) default NULL,
  `game_bracket` char(20) default NULL,
  `home_name`    char(20) default NULL,
  `away_name`    char(20) default NULL,

  PRIMARY KEY   (`game_num`),
  KEY `game_i1` (`game_date`,`game_time`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- s5games game_person
--
DROP TABLE IF EXISTS `game_person`;
CREATE TABLE         `game_person` (
  `game_person_id` int(10) unsigned NOT NULL auto_increment,
  `aysoid`     char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `region`     int (10) unsigned default NULL,
  `status`     int (10) unsigned default NULL,
  `game_num`   int (10) unsigned default NULL,  
  `pos_id`     int (10) unsigned default NULL,
  `ass_id`     int (10) unsigned default NULL,
  `notes`      char(40) default NULL, 

  PRIMARY KEY  (`game_person_id`),
  UNIQUE KEY `game_person_i0` (`game_num`,`pos_id`),
  KEY `game_person_i1` (`fname`,`lname`),
  KEY `game_person_i2` (`lname`,`fname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- s5games log
--
DROP TABLE IF EXISTS `game_person_log`;
CREATE TABLE         `game_person_log` 
(
  `log_id`      int(10) unsigned NOT NULL auto_increment,
  `log_ts`      char(20) default NULL,
  `log_user`    char(20) default NULL,
  `log_host`    char(20) default NULL,
  
  `game_person_id` int(10) unsigned default NULL,
  `aysoid`     char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `region`     int (10) unsigned default NULL,
  `status`     int (10) unsigned default NULL,
  `game_num`   int (10) unsigned default NULL,  
  `pos_id`     int (10) unsigned default NULL,
  `ass_id`     int (10) unsigned default NULL,
  `notes`      char(40) default NULL, 

  PRIMARY KEY  (`log_id`),
  KEY `game_person_log_i1` (`log_ts`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

