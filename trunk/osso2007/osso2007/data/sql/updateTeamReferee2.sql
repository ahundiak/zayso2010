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
/*
DROP TABLE IF EXISTS `phy_team_referee`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `phy_team_referee` (
  `phy_team_referee_id` int(10) unsigned NOT NULL auto_increment,
  `phy_team_id` int(10) unsigned default NULL,
  `referee_id` int(10) unsigned default NULL,
  `sortx` int(10) unsigned default NULL,
  `max_regular` int(10) unsigned default NULL,
  `max_tourn` int(10) unsigned default NULL,
  PRIMARY KEY  (`phy_team_referee_id`),
  KEY `phy_team_referee_i0` (`phy_team_id`),
  KEY `phy_team_referee_i1` (`referee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1381 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;
*/

/* Chnages 2008
ALTER TABLE phy_team_referee CHANGE sortx pri_regular integer;

ALTER TABLE phy_team_referee ADD pri_tourn integer AFTER pri_regular;

UPDATE phy_team_referee SET pri_tourn = pri_regular;
*/

/* ---------------------------------------
 * 02 Aug 2009
 * Add year and season to the table
 */
ALTER TABLE phy_team_referee ADD unit_id        integer AFTER referee_id; 
ALTER TABLE phy_team_referee ADD reg_year_id    integer AFTER unit_id; 
ALTER TABLE phy_team_referee ADD season_type_id integer AFTER reg_year_id;

UPDATE phy_team_referee SET unit_id        = 4;
UPDATE phy_team_referee SET reg_year_id    = 8;
UPDATE phy_team_referee SET season_type_id = 1;

