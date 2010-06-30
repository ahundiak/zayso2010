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


-- Remove a few extra team persons
delete from phy_team_person where phy_team_id IN (1130,1131,1132,1133);

-- ============================================
-- Reg year
DROP TABLE IF EXISTS `reg_year`;
CREATE TABLE `reg_year` (
  `reg_year_id` int(10) unsigned NOT NULL auto_increment,
  `descx`       int(10) unsigned default NULL,
  PRIMARY KEY  (`reg_year_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `reg_year` VALUES 
(1,2001),(2,2002),(3,2003),(4,2004),(5,2005),
(6,2006),(7,2007),(8,2008),(9,2009);

-- =========================================
-- Season Type
DROP TABLE IF EXISTS `season_type`;
CREATE TABLE `season_type` (
  `season_type_id` int(10) unsigned NOT NULL auto_increment,
  `descx` char(8) default NULL,
  PRIMARY KEY  (`season_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `season_type` VALUES
(1,'Fall'),(2,'Winter'),(3,'Spring'),(4,'Summer'); 

-- =========================================
-- Unit Type
DROP TABLE IF EXISTS `unit_type`;
CREATE TABLE `unit_type` (
  `unit_type_id` int(10) unsigned NOT NULL auto_increment,
  `descx`       char(20) default NULL,
  PRIMARY KEY  (`unit_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `unit_type` VALUES
( 1,'AYSO National'),
( 2,'AYSO Section'),
( 3,'AYSO Area'),
( 4,'AYSO Region'),
(11,'Sports Club'),
(12,'Sports League'),
(21,'School'); 

-- =========================================
-- Schedule Type
DROP TABLE IF EXISTS `schedule_type`;
CREATE TABLE `schedule_type` (
  `schedule_type_id` int(10) unsigned NOT NULL auto_increment,
  `keyx`             char( 4) default NULL,
  `descx`            char(20) default NULL,
  PRIMARY KEY  (`schedule_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `schedule_type` VALUES 
(1,'RS','Regular Season'),
(2,'RT','Tournament-Region'),
(3,'AT','Tournament-Area'),
(4,'ST','Tournament-State');

-- =========================================
-- Event Type
DROP TABLE IF EXISTS `event_type`;
CREATE TABLE `event_type` (
  `event_type_id`  int(10) unsigned NOT NULL auto_increment,
  `descx`         char(20) default NULL,
  PRIMARY KEY  (`event_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `event_type` VALUES 
(1,'Game'),
(2,'Practice'),
(3,'Scrimmage'),
(4,'Jamboree'),
(5,'Unified Practice'),
(6,'Tryouts'),
(9,'Other');

-- =========================================
-- Event Team Type
DROP TABLE IF EXISTS `event_team_type`;
CREATE TABLE `event_team_type` (
  `event_team_type_id`  int(10) unsigned NOT NULL auto_increment,
  `descx`               char(20) default NULL,
  PRIMARY KEY  (`event_team_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `event_team_type` VALUES 
(1,'Home'),
(2,'Away'),
(3,'Team #3'),
(4,'Team #4');

-- =========================================
-- Event Person Type
DROP TABLE IF EXISTS `event_person_type`;
CREATE TABLE `event_person_type` (
  `event_person_type_id` int(10) unsigned NOT NULL auto_increment,
  `keyx`                 char( 4) default NULL,
  `descx`                char(20) default NULL,
  PRIMARY KEY  (`event_person_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `event_person_type` VALUES 
(10,'CR', 'Center Referee'),
(11,'AR1','Assistant Referee 1'),
(12,'AR2','Assistant Referee 2');

-- =========================================
-- Phone Type
DROP TABLE IF EXISTS `phone_type`;
CREATE TABLE `phone_type` (
  `phone_type_id` int(10) unsigned NOT NULL auto_increment,
  `descx` char(8) default NULL,
  PRIMARY KEY  (`phone_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `phone_type` VALUES 
(1,'Home'),(2,'Work'),(3,'Cell'),(4,'Pager'),(5,'FAX');

-- =========================================
-- Email Type
DROP TABLE IF EXISTS `email_type`;
CREATE TABLE `email_type` (
  `email_type_id` int(10) unsigned NOT NULL auto_increment,
  `descx` char(8) default NULL,
  PRIMARY KEY  (`email_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `email_type` VALUES (1,'Home'),(2,'Work');

-- =========================================
-- Division
DROP TABLE IF EXISTS `division`;
CREATE TABLE `division` (
  `division_id` int(10)  unsigned NOT NULL auto_increment,
  `sortx`       int(10)  unsigned default NULL,
  `desc_pick`   char(20) default NULL,
  `desc_long`   char(20) default NULL,
  PRIMARY KEY  (`division_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `division` VALUES 
( 1, 1,'U06B','U06 Boys' ),
( 2, 2,'U06G','U06 Girls'),
( 3, 3,'U06C','U06 Coed' ),
( 4, 4,'U08B','U08 Boys' ),
( 5, 5,'U08G','U08 Girls'),
( 6, 6,'U08C','U08 Coed' ),
( 7, 7,'U10B','U10 Boys' ),
( 8, 8,'U10G','U10 Girls'),
( 9, 9,'U10C','U10 Coed' ),
(10,10,'U12B','U12 Boys' ),
(11,11,'U12G','U12 Girls'),
(12,12,'U12C','U12 Coed' ),
(13,13,'U14B','U14 Boys' ),
(14,14,'U14G','U14 Girls'),
(15,15,'U14C','U14 Coed' ),
(16,16,'U16B','U16 Boys' ),
(17,17,'U16G','U16 Girls'),
(18,18,'U16C','U16 Coed' ),
(19,19,'U19B','U19 Boys' ),
(20,20,'U19G','U19 Girls'),
(21,21,'U19C','U19 Coed' );

-- ===========================================================
-- Unit Table
DROP TABLE IF EXISTS `unit`;
CREATE TABLE `unit` (
  `unit_id`       int(10) unsigned NOT NULL auto_increment,
  `unit_type_id`  int(10) unsigned default NULL,
  `parent_id`     int(10) unsigned default NULL,
  `keyx`         char( 8) default NULL,
  `sortx`        char( 8) default NULL,
  `desc_pick`    char(24) default NULL,
  `prefix`       char( 8) default NULL,
  `desc_long`    char(40) default NULL,
  PRIMARY KEY  (`unit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `unit` VALUES 
( 1, 4,0,'R0894','Z40894','R0894 Monrovia',      'MON', 'Monrovia,Harvest,Tony AL'),
( 2, 4,0,'R0391','Z40391','R0391 McMinnville',   'MCM', 'McMinnville TN'),
( 3, 4,0,'R0402','Z40402','R0402 Ft Payne',      'FPAY','Ft Payne AL'),
( 4, 4,0,'R0498','Z40498','R0498 Madison',       'MAD', 'Madison AL'),
( 5, 4,0,'R0557','Z40557','R0557 Lincoln County','LC',  'South Lincoln, Fayetteville TN'),
( 6, 4,0,'R0778','Z40778','R0778 Arab',          'ARAB','Arab AL'),
( 7, 4,0,'R0160','Z40160','R0160 Huntsville',    'HSV', 'Huntsville AL'),
( 8, 4,0,'R0914','Z40914','R0914 East Limestone','EL',  'East Limestone AL'),
( 9, 4,0,'R0916','Z40916','R0916 Athens',        'ATH', 'Athens AL'),
(10, 4,0,'R0991','Z40991','R0991 Sewanne',       'SEW', 'Sewanne TN'),
(11, 4,0,'R1174','Z41174','R1174 Hazel Green',   'HG',  'Hazel Green, Meridianville AL'),
(12, 4,0,'R0890','Z40890','R0890 Lewisburg',     'LEW', 'Lewisburg AL'),
(13,11,0,'R9990','Z49990','R9990 Copar',         'COP', 'HSV CoPar'),
(14,11,0,'R9991','Z49991','R9991 Excalibur',     'EXC', 'Excalibur'),
(15, 4,0,'R9999','Z49999','R9999 Unknown',       'UNK', 'Unknown'),
(16, 4,0,'R1467','Z41467','R1467 Blue Water',    'BWS', 'Tony AL'),
(17, 4,0,'R9992','Z49992','R9992 Ardmore',       'ARD', 'Ardmore,AL'),
(18, 4,0,'R1464','Z41464','R1464 Chapel Hill',   'CH',  'Chapel Hill, TN'),
(19,11,0,'R9992','Z49992','R9992 VF',            'VF',  'VF'),
(20, 4,0,'R1062','Z41062','R1062 Albertville',   'ALB', 'Albertville AL');

-- ===========================================================
-- Vol Type
DROP TABLE IF EXISTS `vol_type`;
CREATE TABLE `vol_type` (
  `vol_type_id` int(10) unsigned NOT NULL auto_increment,
  `sortx`       int(10) unsigned default NULL,
  `keyx`        char( 4) default NULL,
  `descx`       char(40) default NULL,
  PRIMARY KEY  (`vol_type_id`),
  KEY `vol_type_i0` (`sortx`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `vol_type` VALUES 
( 1,50,'RC',  'Regional Commissioner'),
( 2,51,'ARC', 'Assistant Regional Commissioner'),
( 3,52,'RRA', 'Regional Referee Administrator'),
( 4,53,'ARA', 'Assistant Referee Administrator'),
( 5,54,'RCA', 'Regional Coach Administrator'),
( 6,55,'CVPA','Child/Volunteer Protection Advocate'),
( 7,56,'SA',  'Safety Advisor'),
( 8,57,'DRI', 'Director of Referee Instruction'),
( 9,58,'TRE', 'Treasurer'),

(10,40,'CR',  'Center Referee'),
(11,41,'AR1', 'Asssitant Referee 1'),
(12,42,'AR2', 'Asssitant Referee 2'),

(16,10,'HCO', 'Head Coach'),
(17,11,'ACO', 'Asst Coach'),
(18,12,'TM',  'Team Manager'),
(19,20,'AREF','Adult Referee'),
(20,21,'YREF','Youth Referee'),
(21,28,'PLA', 'Player'),
(22,15,'HCOR','Head Coach Recruit'),
(23,16,'ACOR','Asst Coach Recruit'),
(24,17,'TPR', 'Team Parent Recruit'),
(25,25,'ARER','Adult Referee Recruit'),
(26,26,'YRER','Youth Referee Recruit'),
(27,99,'ZADM','Zayso Administrator'),
(30,30,'EQP', 'Equipment Manager'),
(31,31,'DC',  'Division Coordinator'),
(32,32,'FC',  'Field Cooridinator'),
(33,33,'SC',  'Site Coordinator'),
(34,34,'GS',  'Game Scheduler'),
(35,35,'RS',  'Referee Scheduler');

-- ==============================================
-- Field Table
DROP TABLE IF EXISTS `field`;
CREATE TABLE `field` (
  `field_id`       int(10) unsigned     NOT NULL auto_increment,
  `field_site_id`  int(10) unsigned default NULL,
  `unit_id`        int(10) unsigned default NULL,
  `keyx`          char(20)          default NULL,
  `sortx`          int(10) unsigned default NULL,
  `descx`         char(20)          default NULL,
  PRIMARY KEY  (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `field` VALUES 
(  1, 1, 1,'HYDRICK',  201,'Westmin U12 West'),
(  2, 1, 1,'WESTMIN10',200,'Westmin U10 East'),
(  3, 1, 1,'WESTMINM',   4,'Westmin Middle'),
(  4, 1, 1,'WESTMINS',   5,'Westmin Small'),
(  5, 2, 1,'HARVEST',  210,'Harvest'),
(  6, 3, 1,'SPARKMAN', 100,'Sparkman'),
(  7, 5, 1,'BWSU12',   130,'BWS U10_12'),
(  8,11, 8,'CHELLEN',    1,'Camp Hellen'),
(  9, 5, 1,'BWSU10',     2,'BWS U10'),
( 10,21, 7,'MERRIMACK3',43,'Merrimack 3'),
( 11,15, 7,'HSV_UNK',    1,'HSV Unknown'),
( 12,12, 7,'JHUNT4',     4,'John Hunt 4'),
( 13, 7, 4,'PALMERLWR', 20,'Palmer Lower'),
( 14,21, 7,'MERRIMACK1', 41,'Merrimack 1'),
( 15, 7, 4,'PALMERINT',  30,'Palmer Int'),
( 16,14,12,'LEW',         1,'Lewisburg'),
( 17, 5, 1,'BWSU8',     540,'BWS U8'),
( 18, 4, 1,'PVU8N',       1,'PV U8 North'),
( 19, 4, 1,'PVU8S',       2,'PV U8 South'),
( 20, 4, 1,'PVU8E',       3,'PV U8 East'),
( 21, 4, 1,'PVU8W',       4,'PV U8 West'),
( 22, 5, 1,'BWSU6',     490,'BWS U6'),
( 23, 4, 1,'PVU6NE',    450,'Pine View U6 NE'),
( 24, 4, 1,'PVU6NW',    460,'Pine View U6 NW'),
( 25, 4, 1,'PVU6NC',    470,'Pine View U6 NC'),
( 26, 1, 1,'WESTMIN6',    3,'Westmin 6'),
( 31, 6, 4,'DUBLIN1',     1,'Dublin 1'),
( 32, 6, 4,'DUBLIN2',     2,'Dublin 2'),
( 33, 6, 4,'DUBLIN3',     3,'Dublin 3'),
( 34, 6, 4,'DUBLIN4',     4,'Dublin 4'),
( 35, 6, 4,'DUBLIN5',     5,'Dublin 5'),
( 41, 8, 5,'SL1',         1,'South Lincoln'),
( 42, 9, 5,'DAVIDSON3',   1,'Davidson #3'),
( 43,10,11,'SJ12',        2,'SJ U12-1'),
( 44,10,11,'SJ10',        1,'SJ U10-1'),
( 45, 8, 5,'SL2',         2,'South Lin'),
( 46,10,11,'SJ19',        4,'SJ U19-1'),
( 47,10,11,'SJ4',         9,'S Johnson'),
( 48,15, 1,'MON MID',   110,'Monrovia Middle'),
( 49, 4, 1,'PVU12',     120,'Pine View U12'),
( 50, 4, 1,'PVU8',      350,'Pine View U8'),
( 51, 1, 1,'WM U8W',    300,'Westmin U8 West'),
( 52, 1, 1,'WM U8M',    310,'Westmin U8 Middle'),
( 53, 1, 1,'WM U8E',    320,'Westmin U8 East'),
( 54, 1, 1,'WM U6F',    400,'Westmin U6 Front'),
( 55, 1, 1,'WM U6B',    410,'Westmin U6 Back'),
( 56,12, 7,'JHUNT9',      9,'John Hunt 9'),
( 57,12, 7,'JHUNT3',      3,'John Hunt 3'),
( 58,10,11,'SJ14',        3,'SJ U14-1'),
( 59,16,13,'MCGUCKEN',    1,'McGucken'),
( 60,16,13,'COVEPARK',    2,'Cove Park'),
( 61,14,12,'LEW2',        2,'Spring Place'),
( 62,12, 7,'JHUNT11',    11,'John Hunt 11'),
( 63,12, 7,'JHUNT25',    25,'John Hunt 25'),
( 64, 9, 5,'DAVIDSON4',   2,'Davidson #4'),
( 65,12, 7,'JHUNT8',      8,'John Hunt 8'),
( 66, 7, 4,'MAD_UNK',     2,'MAD Unknown'),
( 67,17,11,'HG10',       10,'HG Park U10-1'),
( 68, 7, 4,'PALMERUPR',  10,'Palmer Upper'),
( 69,12, 7,'JHUNT5',      5,'John Hunt 5'),
( 70, 4, 1,'PVU6',      480,'Pine View U6'),
( 71, 1, 1,'WM U6W',    402,'Westmin U6 North'),
( 72, 1, 1,'WM U6E',    404,'Westmin U6 South'),
( 73,18, 1,'END U8NE',  500,'Endeavor U8 NE'),
( 74,18, 1,'END U8SE',  510,'Endeavor U8 SE'),
( 75,18, 1,'END U8NW',  520,'Endeavor U8 NW'),
( 76,18, 1,'END U8SW',  530,'Endeavor U8 SW'),
( 77,19, 9,'ATHENS 4',   24,'Athens 4'),
( 78,19, 9,'ATHENS 3',   23,'Athens 3'),
( 79,22, 7,'ICEPLEX2',   32,'Ice Plex 2'),
( 80,12, 7,'JHUNT13',    13,'John Hunt 13'),
( 81, 7, 4,'PALMERMID',  15,'Palmer Middle'),
( 82,20, 3,'DIXIE',      28,'Dixie'),
( 83,19, 9,'ATHENS 2',   22,'Athens 2'),
( 84,19, 9,'ATHENS 1',   21,'Athens 1'),
( 85,12, 7,'JHUNT6',      6,'John Hunt 6'),
( 86,12, 7,'JHUNT7',      7,'John Hunt 7'),
( 87, 9, 5,'DAVIDSONA',   3,'Davidson A'),
( 88, 9, 5,'DAVIDSONB',   4,'Davidson B'),
( 89, 9, 5,'DAVIDSONC',   5,'Davidson C'),
( 90, 9, 5,'DAVIDSOND',   6,'Davidson D'),
( 91, 6, 4,'DUBLIN5',     6,'Dublin 5A'),
( 92, 6, 4,'DUBLIN5',     7,'Dublin 5B'),
( 93,22, 7,'ICEPLEX1',   31,'Ice Plex 1'),
( 94,12, 7,'JHUNT23',    23,'John Hunt 23'),
( 95,23, 6,'ARAB14',     50,'Arab SC 14'),
( 96,12, 7,'JHUNT12',    12,'John Hunt 12'),
( 97,10,11,'HG_HS',      10,'Hazel Green HS'),
( 98,24,16,'BWSU12',    130,'BWS U14'),
( 99,25,19,'CHAP MID',  130,'Chapman Middle'),
(100,26,20,'ALBERT 2',  130,'Albertville #2');

-- =================================================
-- Filed Site
DROP TABLE IF EXISTS `field_site`;
CREATE TABLE `field_site` (
  `field_site_id` int (10) unsigned NOT NULL auto_increment,
  `unit_id`       int (10) unsigned default NULL,
  `keyx`          char(20) default NULL,
  `sortx`         char(10) default NULL,
  `descx`         char(20) default NULL,
  PRIMARY KEY  (`field_site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `field_site` VALUES 
( 1, 1,'WESTMIN',  'Z40894 01', 'Westminster'),
( 2, 1,'HARVEST',  'Z40894 02', 'Harvest'),
( 3, 1,'SPARKMAN', 'Z40894 03', 'Sparkmen'),
( 4, 1,'PINEVIEW', 'Z40894 04', 'Pineview'),
( 5, 1,'BWS',      'Z40894 06', 'Blue Water Springs'),
( 6, 4,'DUBLIN',   'Z40498 01', 'Dublin'),
( 7, 4,'PALMER',   'Z40498 02', 'Palmer'),
( 8, 5,'SL',       'Z40557 01', 'South Lincoln'),
( 9, 5,'FAY',      'Z40557 02', 'Fayetville'),
(10,11,'HG1',      'Z41174 01', 'Sharon Johnson'),
(11, 8,'CHELEN',   'Z40914 01', 'Camp Hellen'),
(12, 7,'JHUNT',    'Z40160 01', 'John Hunt'),
(13, 4,'UNK1',     'Z40498 02', 'Unknown'),
(14,12,'LEW',      'Z41234 01', 'Lewisburg'),
(15, 1,'MON MID',  'Z40894 06', 'Monrovia Middle'),
(16,13,'COPAR',    'Z41235 01', 'Co Par'),
(17,11,'HG2',      'Z41174 02', 'Hazel Green Park'),
(18, 1,'ENDEAVOR', 'Z40894 05', 'Endeavor'),
(19, 9,'ATHENS',   'Z40916 01', 'Athens'),
(20, 3,'FTPAN',    'Z40402 01', 'Ft Payne'),
(21, 7,'MERRIMACK','Z40160 02', 'Merrimack'),
(22, 7,'ICE_PLEX', 'Z40160 03', 'Ice Plex'),
(23, 6,'ARAB01',   'Z40778 01', 'Arab'),
(24,16,'BWSX',     'Z41467 01', 'Blue Water Springs'),
(25,19,'CHAP MID', 'Z49992 01', 'Chapman Middle'),
(26,20,'ALBERT',   'Z41062 01', 'Albertville');
