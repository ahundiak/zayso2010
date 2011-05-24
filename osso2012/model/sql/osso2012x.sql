-- MySQL dump 10.13  Distrib 5.1.41, for Win32 (ia32)
--
-- Host: localhost    Database: osso2012
-- ------------------------------------------------------
-- Server version	5.1.41

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
-- Table structure for table `account_person`
--

DROP TABLE IF EXISTS `account_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_person` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `validated` char(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_id` (`account_id`,`person_id`),
  UNIQUE KEY `person_id` (`person_id`,`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_person`
--

LOCK TABLES `account_person` WRITE;
/*!40000 ALTER TABLE `account_person` DISABLE KEYS */;
INSERT INTO `account_person` VALUES (7,7,7,'');
/*!40000 ALTER TABLE `account_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` char(40) NOT NULL,
  `upass` char(32) NOT NULL,
  `status` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (7,'ahundiak','password',1);
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned DEFAULT '0',
  `field_id` int(10) unsigned DEFAULT '0',
  `class_id` int(10) unsigned DEFAULT '0',
  `status_id` int(10) unsigned DEFAULT '0',
  `dt_beg` datetime DEFAULT NULL,
  `dt_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (3,1,3,1,1,'2011-03-31 13:30:00','2011-03-31 14:15:00');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_class`
--

DROP TABLE IF EXISTS `event_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_class` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key1` char(20) NOT NULL,
  `sort1` int(10) unsigned DEFAULT '0',
  `desc1` char(80) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key1` (`key1`),
  KEY `sort1` (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_class`
--

LOCK TABLES `event_class` WRITE;
/*!40000 ALTER TABLE `event_class` DISABLE KEYS */;
INSERT INTO `event_class` VALUES (1,'RG',1,'RG - Regular Game'),(2,'PP',2,'PP - Pool Play'),(3,'QF',3,'QF - Quarter Final'),(4,'SF',4,'SF - Semi Final'),(5,'F',5,'F  - Final'),(6,'CM',6,'CM - Consolation Match');
/*!40000 ALTER TABLE `event_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_field`
--

DROP TABLE IF EXISTS `event_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_field` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key1` char(20) NOT NULL,
  `sort1` int(10) unsigned DEFAULT '0',
  `desc1` char(80) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key1` (`key1`),
  KEY `sort1` (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_field`
--

LOCK TABLES `event_field` WRITE;
/*!40000 ALTER TABLE `event_field` DISABLE KEYS */;
INSERT INTO `event_field` VALUES (1,'Dublin 1',1,'Dublin 1'),(2,'Dublin 2',2,'Dublin 2'),(3,'John Hunt 1',3,'John Hunt 1'),(4,'John Hunt 2',4,'John Hunt 2');
/*!40000 ALTER TABLE `event_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_status`
--

DROP TABLE IF EXISTS `event_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key1` char(20) NOT NULL,
  `sort1` int(10) unsigned DEFAULT '0',
  `desc1` char(80) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key1` (`key1`),
  KEY `sort1` (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_status`
--

LOCK TABLES `event_status` WRITE;
/*!40000 ALTER TABLE `event_status` DISABLE KEYS */;
INSERT INTO `event_status` VALUES (1,'Normal',1,'Normal'),(2,'Canceled',2,'Canceled'),(3,'Rained Out',3,'Rained Out'),(4,'Suspended',4,'Suspended'),(5,'Forfeit',5,'Forfeit'),(6,'Planning',6,'Planning');
/*!40000 ALTER TABLE `event_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_type`
--

DROP TABLE IF EXISTS `event_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key1` char(20) NOT NULL,
  `sort1` int(10) unsigned DEFAULT '0',
  `desc1` char(80) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key1` (`key1`),
  KEY `sort1` (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_type`
--

LOCK TABLES `event_type` WRITE;
/*!40000 ALTER TABLE `event_type` DISABLE KEYS */;
INSERT INTO `event_type` VALUES (1,'Game',1,'Game'),(2,'Scrimmage',2,'Scrimmage'),(3,'Jamboree',3,'Jamboree'),(4,'Practice',4,'Practice'),(5,'Maintenance',5,'Maintenance');
/*!40000 ALTER TABLE `event_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `org`
--

DROP TABLE IF EXISTS `org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `org` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL,
  `key1` char(10) NOT NULL,
  `key2` char(10) NOT NULL,
  `abbv` char(10) NOT NULL,
  `desc1` char(30) NOT NULL,
  `desc2` char(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key1` (`key1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org`
--

LOCK TABLES `org` WRITE;
/*!40000 ALTER TABLE `org` DISABLE KEYS */;
/*!40000 ALTER TABLE `org` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `org_type`
--

DROP TABLE IF EXISTS `org_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `org_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key1` char(20) NOT NULL,
  `sort1` int(10) DEFAULT '0',
  `desc1` char(30) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key1` (`key1`),
  KEY `sort1` (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_type`
--

LOCK TABLES `org_type` WRITE;
/*!40000 ALTER TABLE `org_type` DISABLE KEYS */;
INSERT INTO `org_type` VALUES (1,'AYSO National',1,'AYSO National'),(2,'AYSO Section',2,'AYSO Section'),(3,'AYSO Area',3,'AYSO Area'),(4,'AYSO Region',4,'AYSO Region'),(11,'Sports Club',5,'Sports Club'),(12,'Sports League',6,'Sports League'),(21,'School',7,'School');
/*!40000 ALTER TABLE `org_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persons`
--

DROP TABLE IF EXISTS `persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(40) NOT NULL,
  `org_id` int(10) unsigned NOT NULL,
  `fname` char(40) NOT NULL,
  `lname` char(40) NOT NULL,
  `mname` char(40) NOT NULL,
  `nname` char(40) NOT NULL,
  `dob` char(8) NOT NULL,
  `gender` char(2) NOT NULL,
  `email` char(80) NOT NULL,
  `phone` char(12) NOT NULL,
  `status` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persons`
--

LOCK TABLES `persons` WRITE;
/*!40000 ALTER TABLE `persons` DISABLE KEYS */;
INSERT INTO `persons` VALUES (7,'99437977',0,'Art','Hundiak','','','','','','',1);
/*!40000 ALTER TABLE `persons` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-04-01 10:10:37
