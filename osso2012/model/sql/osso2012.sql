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
-- New database
-- DROP DATABASE IF EXISTS osso2012;
-- CREATE DATABASE osso2012;
-- GRANT ALL ON osso2012.* TO "impd"@"localhost";
-- FLUSH PRIVILEGES;

use osso2012;

-- ===============================================
-- Accounts
DROP TABLE IF EXISTS accounts;
CREATE TABLE         accounts
(
  id      int(10) unsigned NOT NULL auto_increment,

  uname  char(40) NOT NULL,
  upass  char(32) NOT NULL,

  status  int(10) unsigned default 1,

  PRIMARY KEY  (id),
  UNIQUE KEY   (uname)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Persons
DROP TABLE IF EXISTS persons;
CREATE TABLE         persons
(
  id      int(10) unsigned NOT NULL auto_increment,

  guid   char(40) NOT NULL,

  org_id  int(10) unsigned NOT NULL,

  fname  char(40) NOT NULL,
  lname  char(40) NOT NULL,
  mname  char(40) NOT NULL,
  nname  char(40) NOT NULL,

  dob    char( 8) NOT NULL,
  gender char( 2) NOT NULL,

  email  char(80) NOT NULL,
  phone  char(12) NOT NULL,

  status  int(10) unsigned default 1,

  PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===================================================
-- Account Person
DROP TABLE IF EXISTS account_person;
CREATE TABLE         account_person
(
  id          int(10) unsigned NOT NULL auto_increment,

  account_id  int(10) unsigned NOT NULL,
  person_id   int(10) unsigned NOT NULL,

  validated   char(40) NOT NULL,

-- level  int(10) unsigned default 1,

  PRIMARY KEY  (id),
  UNIQUE KEY   (account_id,person_id),
  UNIQUE KEY   (person_id,account_id)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Organizations
DROP TABLE IF EXISTS org;
CREATE TABLE         org
(
  id          int(10) unsigned NOT NULL auto_increment,
  type_id     int(10) unsigned NOT NULL,
  key1       char(10) NOT NULL,
  key2       char(10) NOT NULL, -- Original osso2007 key for now
  abbv       char(10) NOT NULL, -- abbreviation
  desc1      char(30) NOT NULL,
  desc2      char(60) NOT NULL,

  PRIMARY KEY  (id),
  UNIQUE KEY   (key1)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS org_type;
CREATE TABLE         org_type
(
  id          int(10) unsigned NOT NULL auto_increment,
  key1       char(20) NOT NULL,
  sort1       int(10) default 0,
  desc1      char(30) default '',

  PRIMARY KEY  (id),
  UNIQUE KEY   (key1),
  KEY          (sort1)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_type (id,sort1,key1,desc1) VALUES
( 1,1,'AYSO National','AYSO National'),
( 2,2,'AYSO Section', 'AYSO Section'),
( 3,3,'AYSO Area',    'AYSO Area'),
( 4,4,'AYSO Region',  'AYSO Region'),
(11,5,'Sports Club',  'Sports Club'),
(12,6,'Sports League','Sports League'),
(21,7,'School',       'School');

-- ===============================================
-- Event Classification
DROP TABLE IF EXISTS  event_class;
CREATE TABLE          event_class
(
  id       int (10) unsigned NOT NULL auto_increment,
  key1     char(20) NOT NULL,
  sort1     int(10) unsigned default 0,
  desc1    char(80) default '',
  PRIMARY KEY  (`id`),
  UNIQUE  KEY  (`key1`),
  KEY          (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO event_class (id,sort1,key1,desc1) VALUES
(1,1,'RG','RG - Regular Game'),
(2,2,'PP','PP - Pool Play'),
(3,3,'QF','QF - Quarter Final'),
(4,4,'SF','SF - Semi Final'),
(5,5,'F', 'F  - Final'),
(6,6,'CM','CM - Consolation Match');

-- ===============================================
-- Event Types
DROP TABLE IF EXISTS  event_type;
CREATE TABLE          event_type
(
  id       int (10) unsigned NOT NULL auto_increment,
  key1     char(20) NOT NULL,
  sort1     int(10) unsigned default 0,
  desc1    char(80) default '',
  PRIMARY KEY  (`id`),
  UNIQUE  KEY  (`key1`),
  KEY          (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO event_type (id,sort1,key1,desc1) VALUES
(1,1,'Game',       'Game'),
(2,2,'Scrimmage',  'Scrimmage'),
(3,3,'Jamboree',   'Jamboree'),
(4,4,'Practice',   'Practice'),
(5,5,'Maintenance','Maintenance');

-- ===============================================
-- Event Status
DROP TABLE IF EXISTS  event_status;
CREATE TABLE          event_status
(
  id       int (10) unsigned NOT NULL auto_increment,
  key1     char(20) NOT NULL,
  sort1     int(10) unsigned default 0,
  desc1    char(80) default '',
  PRIMARY KEY  (`id`),
  UNIQUE  KEY  (`key1`),
  KEY          (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO event_status (id,sort1,key1,desc1) VALUES
(1,1,'Normal',    'Normal'),
(2,2,'Canceled',  'Canceled'),
(3,3,'Rained Out','Rained Out'),
(4,4,'Suspended', 'Suspended'),
(5,5,'Forfeit',   'Forfeit'),
(6,6,'Planning',  'Planning');

-- ===============================================
-- Event Fields
DROP TABLE IF EXISTS  event_field;
CREATE TABLE          event_field
(
  id       int (10) unsigned NOT NULL auto_increment,
  key1     char(20) NOT NULL,
  sort1     int(10) unsigned default 0,
  desc1    char(80) default '',
  PRIMARY KEY  (`id`),
  UNIQUE  KEY  (`key1`),
  KEY          (`sort1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO event_field (id,sort1,key1,desc1) VALUES
(1,1,'Dublin 1',    'Dublin 1'),
(2,2,'Dublin 2',    'Dublin 2'),
(3,3,'John Hunt 1', 'John Hunt 1'),
(4,4,'John Hunt 2', 'John Hunt 2');

-- ===============================================
-- Event
DROP TABLE IF EXISTS  event;
CREATE TABLE          event
(
  id        int(10) unsigned NOT NULL auto_increment,

  type_id   int(10) unsigned default 0,
  field_id  int(10) unsigned default 0,
  class_id  int(10) unsigned default 0,
  status_id int(10) unsigned default 0,

  dt_beg     datetime,
  dt_end     datetime,

  PRIMARY KEY  (`id`),
  KEY (`dt_beg`,`dt_end`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `event` VALUES
(1,1,3,1,1,'2011-03-30 13:30:00','2011-03-31 14:15:00'),
(2,1,3,1,1,'2011-03-30 15:30:00','2011-03-31 16:15:00'),
(3,1,3,1,1,'2011-03-30 17:30:00','2011-03-31 18:15:00'),
(4,1,3,1,1,'2011-03-31 13:30:00','2011-03-31 14:15:00'),
(5,1,3,1,1,'2011-03-31 13:30:00','2011-03-31 14:15:00');

-- ===============================================
-- Event Team
DROP TABLE IF EXISTS  event_team;
CREATE TABLE          event_team
(
  id          int(10) unsigned NOT NULL auto_increment,

  event_id    int(10) unsigned default 0,
  org_id      int(10) unsigned default 0,
  sch_team_id int(10) unsigned default 0,

  league    char(20),
  age       char(20),
  gender    char(20),
  levelx    char(20),

  typex     char(20),
  indexx     int(10) unsigned default 0,
  score      int(10) unsigned default 0,

  PRIMARY KEY  (`id`)
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
