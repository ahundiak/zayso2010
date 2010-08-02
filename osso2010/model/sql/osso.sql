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
DROP DATABASE IF EXISTS osso;
CREATE DATABASE osso;
GRANT ALL ON osso.* TO "impd"@"localhost";
FLUSH PRIVILEGES;
USE osso;

-- ===============================================
-- Organizations
DROP TABLE IF EXISTS org;
CREATE TABLE org
(
  id          int(10) unsigned NOT NULL auto_increment,
  org_type_id int(10) unsigned NOT NULL,
  keyx       char(10) NOT NULL,
  keyxx      char(10) NOT NULL, -- Original osso2007 key for now
  abbv       char(10) NOT NULL, -- abbreviation
  desc1      char(30) NOT NULL,
  desc2      char(60) NOT NULL,

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (keyx)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE org_type
(
  id          int(10) unsigned NOT NULL auto_increment,
  keyx       char(20) NOT NULL,

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (keyx)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_type VALUES
( 1,'AYSO National'),
( 2,'AYSO Section'),
( 3,'AYSO Area'),
( 4,'AYSO Region'),
(11,'Sports Club'),
(12,'Sports League'),
(21,'School');
