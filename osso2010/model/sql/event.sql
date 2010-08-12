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
-- Table structure for physical teams
--
DROP TABLE IF EXISTS `event`;
CREATE TABLE         `event`
(
  `id`         int (10) unsigned NOT NULL auto_increment,
  `group_id`   int (10) unsigned default 0,
  `group_num`  int (10) unsigned default 0,

  `dt_beg`     datetime,
  `dt_end`     datetime,

  `field_id`   int (10) unsigned default 0,
  `org_id`     int (10) unsigned default 0,

  `tbd`        char( 8) default '00000000', -- DATE TIME FIELD

  `notes`      char(40) default '',

  `status`     int (10) unsigned default 1,
  `points`     int (10) unsigned default 1,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`dt_beg`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO event (`id`,`dt_beg`) VALUES
(1,'2010-08-10 13:00'),
(2,'2010-08-10 14:30'),
(3,'2010-08-12 13:00'),
(4,'2010-08-12 14:30');

SELECT id,dt_beg FROM event WHERE DATE(dt_beg) = '2010-08-10';

SELECT id,dt_beg FROM event ORDER BY TIME(dt_beg);