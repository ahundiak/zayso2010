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
DROP DATABASE IF EXISTS session;
CREATE DATABASE session;
GRANT ALL ON session.* TO "impd"@"localhost";
FLUSH PRIVILEGES;
USE   session;

-- ==============================================
-- Table structure for eayso registered volunteer
--
DROP TABLE IF EXISTS `session_data`;
CREATE TABLE         `session_data`
(
  `id`         int (10) unsigned NOT NULL auto_increment,
  `keyx`       char(32) default NULL, -- session_id
  `name`       char(20) default NULL,
  `item`           text NOT NULL,
  `ts_created` char(16) default NULL,
  `ts_updated` char(16) default NULL,

  PRIMARY KEY  (`id`),
  UNIQUE KEY    `i0` (`keyx`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ================================================
-- Try the duplicate key stuff
INSERT INTO session_data

(keyx,name,item,ts_created,ts_updated)

VALUES('session_id','name1','the item data','201001','201001')

ON DUPLICATE KEY UPDATE item=VALUES(item),ts_updated=VALUES(ts_updated);

INSERT INTO session_data

(keyx,name,item,ts_created,ts_updated)

VALUES('session_id','name1','the item data 2','201001','201002')

ON DUPLICATE KEY UPDATE item=VALUES(item),ts_updated=VALUES(ts_updated);


SELECT * FROM session_data;
DELETE   FROM session_data;
