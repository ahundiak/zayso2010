-- MySQL dump 10.10
--
-- Host: db.telana.com    Database: osso2007
-- ------------------------------------------------------
-- Server version	5.0.27

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

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `ref_avail`;
CREATE TABLE `ref_avail` 
(
  `ref_avail_id` int(11) NOT NULL auto_increment,
  
  `group_id`  int(11) default '1',
  `person_id` int(11) default '0',
--  `aysoid`   char(10) NOT NULL,
--  `region`    int(11) default '0',
  
-- gender dob update
    
  `div_cr_id` int(11) default '0',
  `div_ar_id` int(11) default '0',
  
  `phone_home` char(40) default NULL,
  `phone_work` char(40) default NULL,
  `phone_cell` char(40) default NULL,
  
  `email_home` char(40) default NULL,
  `email_work` char(40) default NULL,

  `avail_day1` int(11) default '0',
  `avail_day2` int(11) default '0',
  `avail_day3` int(11) default '0',
  `avail_day4` int(11) default '0',
  `avail_day5` int(11) default '0',
  `avail_day6` int(11) default '0',

  `team_id1` int(11) default '0',
  `team_id2` int(11) default '0',
  `team_id3` int(11) default '0',

--  `season`        char(20) NOT NULL,                    
--  `safe_haven`    char(20) NOT NULL,
--  `referee_badge` char(20) NOT NULL,
  
  `modified` char(16)   NOT NULL,
  `notes` varchar(1000) NOT NULL,
  
  PRIMARY KEY  (`ref_avail_id`),
  
  UNIQUE  KEY `ref_key` (`group_id`,`person_id`)
  
) ENGINE=MyISAM AUTO_INCREMENT=788 DEFAULT CHARSET=latin1;
