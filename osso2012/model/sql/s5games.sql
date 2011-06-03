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
-- s5games game
-- G #	DATE	DIV	FIELD	TIME	R	TEAM H	HOME	TEAM A	AWAY

DROP DATABASE IF EXISTS s5games;
CREATE DATABASE s5games;

USE s5games;

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE         `accounts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aysoid`     char(20) default NULL,
  `uname`      char(20) default NULL,
  `upass`      char(20) default NULL,
  `fname`      char(20) default NULL,
  `lname`      char(20) default NULL,
  `email`      char(20) default NULL,
  `phonec`     char(20) default NULL,
  `verified`   char(20) default NULL,

  PRIMARY KEY  (`id`),
  UNIQUE  KEY  (`aysoid`),
  UNIQUE  KEY  (`uname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
