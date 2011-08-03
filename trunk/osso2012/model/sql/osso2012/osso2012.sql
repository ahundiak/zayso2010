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

USE osso2012;

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
(54,'National Games 2014','Future'),

(60,'Section 5 Games 2010','Past'),
(61,'Section 5 Games 2011','Active'),
(62,'Section 5 Games 2012','Future'),

(28,'Area 5 C/F Fall 2010','Past'),
(41,'Area 5 C/F Fall 2011','Active'),
(42,'Area 5 C/F Fall 2012','Future');

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
