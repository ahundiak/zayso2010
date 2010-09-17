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
-- Project Item
DROP TABLE IF EXISTS project_item;
CREATE TABLE         project_item
(
  id         int(10) unsigned NOT NULL auto_increment,
  project_id int(10) unsigned default 0,
  item_id    int(10) unsigned default 0,
  type_id    int(10) unsigned default 0,
  sort1      int(10) unsigned default 0,
  status     int(10) unsigned default 1,
  desc1     char(80) default '',

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (type_id,project_id,item_id),
  UNIQUE KEY    i1 (type_id,item_id,project_id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Project Link Types
DROP TABLE IF EXISTS `project_item_type`;
CREATE TABLE         `project_item_type`
(
  id       int (10) unsigned NOT NULL auto_increment,
  key1     char(10) NOT NULL,
  desc1    char(80) default '',
  PRIMARY KEY  (`id`),
  UNIQUE  KEY `i1` (`key1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO project_item_type (id,key1,desc1) VALUES
(1,'ORG',   'Organization' ),
(2,'TEAM',  'Physical Team'),
(3,'PITCH', 'Field'        ),
(4,'PERSON','Person'       );
