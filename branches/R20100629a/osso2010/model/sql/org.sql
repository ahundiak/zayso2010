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
use osso;

-- ===============================================
-- Organuzation groups
DROP TABLE IF EXISTS org_group;
CREATE TABLE         org_group
(
  id       int(10) unsigned NOT NULL auto_increment,
  keyx    char(20) NOT NULL,
  sortx    int(10) not NULL,
  desc1   char(40) NOT NULL,
  desc2   char(80) default '',
  status   int(10) unsigned default 1,

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (keyx),
  KEY           i1 (sortx),
  KEY           i2 (desc1)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_group (id,keyx,sortx,desc1) VALUES
(1,'ALL',           1,'All Active Organizations'),
(2,'AYSO AREA 5C,F',2,'Area 5CF, North AL, South TN');

-- ===============================================
-- Organuzation groups entries
DROP TABLE IF EXISTS org_group_org;
CREATE TABLE         org_group_org
(
  id           int(10) unsigned NOT NULL auto_increment,
  org_group_id int(10) unsigned NOT NULL,
  org_id       int(10) unsigned NOT NULL,

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (org_group_id,org_id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_group_org (org_group_id,org_id) VALUES
(1,1),(1,4),(1,7),(1,11),(1,5),
(2,1),(2,4),(2,7),(2,11),(2,5);

