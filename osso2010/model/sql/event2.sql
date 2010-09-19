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
-- Event Classification
DROP TABLE IF EXISTS `event_class`;
CREATE TABLE         `event_class`
(
  id       int (10) unsigned NOT NULL auto_increment,
  key1     char(10) NOT NULL,
  desc1    char(80) default '',
  PRIMARY KEY  (`id`),
  UNIQUE  KEY `i1` (`key1`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO event_class (id,key1,desc1) VALUES
(1,'RG','RG - Regular Game'),
(2,'PP','PP - Pool Play'),
(3,'QF','QF - Quarter Final'),
(4,'SF','SF - Semi Final'),
(5,'F', 'F  - Final'),
(6,'CM','CM - Consolation Match');

ALTER TABLE event
ADD COLUMN class_id int(10) unsigned default 1
AFTER event_type_id;

UPDATE event SET class_id = 1;
