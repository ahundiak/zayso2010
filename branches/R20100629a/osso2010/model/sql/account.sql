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
-- Accounts
DROP TABLE IF EXISTS account;
CREATE TABLE         account
(
  id          int(10) unsigned NOT NULL auto_increment,
  account_person_id   int(10) default 0, -- Default member
  remember_me int(10) default 1, -- Implement remember me functionality

  user_name  char(40) NOT NULL,
  user_pass  char(32) NOT NULL,

  lname      char(40) default '',
  hint       char(40) default '',
  email      char(64) default '',
  status      int(10) unsigned default 1,

  PRIMARY KEY  (id),
  UNIQUE KEY    i0 (user_name)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Account Person
DROP TABLE IF EXISTS account_person;
CREATE TABLE         account_person
(
  id          int(10) unsigned NOT NULL auto_increment,
  account_id  int(10) default 0,
  person_id   int(10) default 0,
  org_id      int(10) default 0,

  fname       char(40) default '',
  lname       char(40) default '',

  PRIMARY KEY  (id),
  KEY           i0 (account_id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ===============================================
-- Account Reg Validation