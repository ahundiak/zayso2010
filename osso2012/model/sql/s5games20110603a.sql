-- MySQL dump 10.13  Distrib 5.1.47, for redhat-linux-gnu (x86_64)
--
-- Host: db.telavant.com    Database: s5games
-- ------------------------------------------------------
-- Server version	5.0.84-b18-log

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
-- Not dumping tablespaces as no INFORMATION_SCHEMA.FILES table on this server
--

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aysoid` char(20) default NULL,
  `uname` char(40) default NULL,
  `upass` char(40) default NULL,
  `fname` char(20) default NULL,
  `lname` char(20) default NULL,
  `email` char(40) default NULL,
  `phonec` char(20) default NULL,
  `verified` char(20) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `aysoid` (`aysoid`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'99437977','ahundiak','zzz','Art','Hundiak','ahundiak@gmail.com','256.457.5943','No'),(2,'94039405','DarrylT','gor1dar1','Darryl','Thompson','darryl.thompson5@gmail.com','864-216-1235','No'),(3,'51563588','ehundiak','zzz','Ethan','Hundiak','ehundiak@gmail.com','256.457.5943','No'),(4,'59172591','chundiak','zzz','Cassie','Hundiak','ahundiak@gmail.com','256.457.5943','No');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_person`
--

DROP TABLE IF EXISTS `game_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_person` (
  `game_person_id` int(10) unsigned NOT NULL auto_increment,
  `aysoid` char(20) default NULL,
  `fname` char(20) default NULL,
  `lname` char(20) default NULL,
  `region` int(10) unsigned default NULL,
  `status` int(10) unsigned default NULL,
  `game_num` int(10) unsigned default NULL,
  `pos_id` int(10) unsigned default NULL,
  `ass_id` int(10) unsigned default NULL,
  `notes` char(40) default NULL,
  PRIMARY KEY  (`game_person_id`),
  UNIQUE KEY `game_person_i0` (`game_num`,`pos_id`),
  KEY `game_person_i1` (`fname`,`lname`),
  KEY `game_person_i2` (`lname`,`fname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_person`
--

LOCK TABLES `game_person` WRITE;
/*!40000 ALTER TABLE `game_person` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_person_log`
--

DROP TABLE IF EXISTS `game_person_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_person_log` (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `log_ts` char(20) default NULL,
  `log_user` char(20) default NULL,
  `log_host` char(20) default NULL,
  `game_person_id` int(10) unsigned default NULL,
  `aysoid` char(20) default NULL,
  `fname` char(20) default NULL,
  `lname` char(20) default NULL,
  `region` int(10) unsigned default NULL,
  `status` int(10) unsigned default NULL,
  `game_num` int(10) unsigned default NULL,
  `pos_id` int(10) unsigned default NULL,
  `ass_id` int(10) unsigned default NULL,
  `notes` char(40) default NULL,
  PRIMARY KEY  (`log_id`),
  KEY `game_person_log_i1` (`log_ts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_person_log`
--

LOCK TABLES `game_person_log` WRITE;
/*!40000 ALTER TABLE `game_person_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_person_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `game_num` int(10) unsigned NOT NULL,
  `game_date` char(8) default NULL,
  `game_time` char(8) default NULL,
  `game_div` char(4) default NULL,
  `game_field` char(12) default NULL,
  `game_type` char(4) default NULL,
  `game_bracket` char(20) default NULL,
  `home_name` char(20) default NULL,
  `away_name` char(20) default NULL,
  PRIMARY KEY  (`game_num`),
  KEY `game_i1` (`game_date`,`game_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-03 13:08:36
