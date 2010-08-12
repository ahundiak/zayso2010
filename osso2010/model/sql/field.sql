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
-- Table structure for sites i.e. parks and schools
--
DROP TABLE IF EXISTS `site`;
CREATE TABLE         `site`
(
  `id`         int (10) unsigned NOT NULL auto_increment,
  `org_id`     int (10) unsigned default 0,
  `descx`      char(60) default '',
  `address`    char(60) default '',
  `status`     int (10) unsigned default 1,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`descx`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- Table structure for fields
--
DROP TABLE IF EXISTS `site_field`;
CREATE TABLE         `site_field`
(
  `id`         int (10) unsigned NOT NULL auto_increment,
  `site_id`    int (10) unsigned default 0,
  `org_id`     int (10) unsigned default 0,
  `age`        int (10) unsigned default 0,
  `descx`      char(60) default '',
  `address`    char(60) default '',
  `status`     int (10) unsigned default 1,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`descx`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ==============================================
-- Table structure for field aliases
--
DROP TABLE IF EXISTS `site_field_alias`;
CREATE TABLE         `site_field_alias`
(
  `id`            int (10) unsigned NOT NULL auto_increment,
  `site_field_id` int (10) unsigned default 0,
  `alias`         char(60) default '',
  `master`        int (10) unsigned default 0,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
