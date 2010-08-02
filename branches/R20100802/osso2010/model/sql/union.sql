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
-- New databases
DROP DATABASE IF EXISTS eaysox;
CREATE DATABASE         eaysox;
GRANT ALL ON eaysox.* TO "impd"@"localhost";

DROP DATABASE IF EXISTS ossox;
CREATE DATABASE         ossox;
GRANT ALL ON ossox.* TO "impd"@"localhost";

FLUSH PRIVILEGES;

-- ==============================================
-- Master person
USE ossox;

DROP TABLE IF EXISTS `person`;
CREATE TABLE         `person`
(
  `id`         int (10) unsigned NOT NULL auto_increment,

  `fname`      char(20) default '',
  `lname`      char(20) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO person VALUES
(1,'Art',  'Hundiak'),
(2,'Ethan','Hundiak'),
(3,'Bill', 'Smith');


DROP TABLE IF EXISTS `person_reg`;
CREATE TABLE         `person_reg`
(
  `id`                  int(10) unsigned NOT NULL auto_increment,
  `person_reg_type_id`  int(10) unsigned default 1,
  `person_reg_num`     char(20) default '',
  `person_id`           int(10) unsigned default 0,

  PRIMARY KEY  (`id`),
  UNIQUE  KEY `i1` (`person_reg_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO person_reg VALUES
(1,1,'OSSO 1',1),
(3,1,'OSSO 3',3);

USE eaysox;
DROP TABLE IF EXISTS `person_reg`;
CREATE TABLE         `person_reg`
(
  `id`                  int(10) unsigned NOT NULL auto_increment,
  `person_reg_type_id`  int(10) unsigned default 1,
  `person_reg_num`     char(20) default '',
  `person_id`           int(10) unsigned default 0,

  PRIMARY KEY  (`id`),
  UNIQUE  KEY `i1` (`person_reg_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO person_reg VALUES
(1,2,'EAYSO 1',1),
(2,2,'EAYSO 2',2);

USE ossox;

DROP VIEW IF EXISTS pr_view;
CREATE VIEW         pr_view AS
SELECT * FROM ossox.person_reg
UNION
SELECT * FROM eaysox.person_reg
;

DROP VIEW IF EXISTS p_view;
CREATE VIEW         p_view AS
SELECT
  person.id    AS id,
  person.fname AS person_fname,
  person.lname AS person_lname,
  person_reg.person_reg_num     AS person_reg_num,
  person_reg.person_reg_type_id AS person_reg_type_id

FROM person
LEFT JOIN pr_view AS person_reg ON person_reg.person_id = person.id
;