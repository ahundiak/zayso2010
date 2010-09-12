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

-- ===============================================
-- Projects
-- Maybe want auto add of teams flag?  Or possibly done with project type class
DROP TABLE IF EXISTS project;
CREATE TABLE         project
(
  id             int(10) unsigned NOT NULL auto_increment,
  type_id        int(10) unsigned default 0,
  event_num      int(10) unsigned default 0,
  mem_year       int(10) unsigned default 0,
  cal_year       int(10) unsigned default 0,
  season_type_id int(10) unsigned default 0,
  admin_org_id   int(10) unsigned default 0,
  sport_type_id  int(10) unsigned default 0,
  sort1          int(10) unsigned default 0,
  status         int(10) unsigned default 1,

  desc1       char(80) default '',
  date_beg    char( 8) default '',
  date_end    char( 8) default '',

  PRIMARY KEY  (id),
  UNIQUE  KEY   i0 (cal_year,season_type_id,type_id,admin_org_id,sport_type_id),
          KEY   i1 (status)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Project Organizations
DROP TABLE IF EXISTS project_org;
CREATE TABLE         project_org
(
  id         int(10) unsigned NOT NULL auto_increment,
  project_id int(10) unsigned default 0,
  org_id     int(10) unsigned default 0,
  type_id    int(10) unsigned default 0,
  sort1      int(10) unsigned default 0,
  status     int(10) unsigned default 1,
  desc1     char(80) default '',

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (project_id,org_id),
  UNIQUE KEY    i1 (org_id,project_id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Project Teams
DROP TABLE IF EXISTS project_team;
DROP TABLE IF EXISTS project_org_team;
CREATE TABLE         project_org_team
(
  id             int(10) unsigned NOT NULL auto_increment,
  project_org_id int(10) unsigned default 0,
  team_id        int(10) unsigned default 0,
  type_id        int(10) unsigned default 0,
  sort1          int(10) unsigned default 0,
  status         int(10) unsigned default 1,
  desc1         char(80) default '',

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (project_org_id,team_id),
  UNIQUE KEY    i1 (team_id,project_org_id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Project Types
DROP TABLE IF EXISTS `project_type`;
CREATE TABLE         `project_type`
(
  id       int (10) unsigned NOT NULL auto_increment,
  key1     char(10) NOT NULL,
  desc1    char(80) default '',
  class1    int(10) unsigned default 0,
  status    int(10) unsigned default 1,
  PRIMARY KEY  (`id`),
  UNIQUE  KEY `i1` (`key1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO project_type (id,key1,desc1,class1,status) VALUES
(1,'RS','Regular Season',        1,1),
(2,'RT','AYSO Region Tournament',2,1),
(3,'AT','AYSO Area Tournament',  2,1),
(4,'ST','AYSO State Tournament', 2,1),
(5,'SG','AYSO Section Games',    3,1),
(6,'NG','AYSO National Games',   3,1);

