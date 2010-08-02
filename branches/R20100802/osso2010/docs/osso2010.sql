-- ==============================
-- Run as root
DROP DATABASE IF EXISTS osso2010;
CREATE DATABASE osso2010;
GRANT ALL ON    osso2010.* TO "impd"@"localhost";
FLUSH PRIVILEGES;
USE osso2010;

-- ==============================
-- Sport types
DROP TABLE IF EXISTS sport_type;
CREATE TABLE         sport_type
(
  id     int(10) unsigned NOT NULL auto_increment,
  keyx  char(20) NOT NULL,
  sortx  int(10) unsigned,
  descx char(40) NOT NULL,

  PRIMARY KEY  (id),
  UNIQUE KEY sport_type_i0 (keyx),
  KEY        sport_type_i1 (descx)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO sport_type VALUES
(1,'soccer',  1,'Soccer'),
(2,'baseball',2,'Baseball'),
(3,'football',3,'Football');

-- ====================================
-- General organization category
DROP TABLE IF EXISTS org_cat;
CREATE TABLE         org_cat
(
  id     int(10) unsigned NOT NULL auto_increment,
  keyx  char(20) NOT NULL,
  sortx  int(10) unsigned,
  descx char(40) NOT NULL,

  PRIMARY KEY  (id),
  UNIQUE KEY org_cat_i0 (keyx),
  KEY        org_cat_i1 (descx)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_cat VALUES
(1,'OSSO',1,'OSSO'),
(2,'AYSO',2,'AYSO'),
(3,'USSF',3,'USSF'),
(4,'NFHS',4,'NFHS');

-- ====================================
-- Master organization table
DROP TABLE IF EXISTS org;
CREATE TABLE         org
(
  id         int(10) unsigned NOT NULL auto_increment,
  org_cat_id int(10) unsigned NOT NULL,
  keyx      char(20) NOT NULL,
  sortx      int(10) unsigned,
  desc1     char(40) NOT NULL,
  desc2     char(60) NOT NULL,
  abbv      char( 8),
  status     int(10) unsigned default 1,

  PRIMARY KEY  (id),
  UNIQUE KEY org_i0 (org_cat_id,keyx),
  KEY        org_i1 (desc1)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org VALUES
( 1,2,'R0894',1,'R0894 Monrovia',  '','',1),
( 2,2,'R0498',1,'R0498 Madison',   '','',1),
( 3,2,'R0160',1,'R0894 Huntsville','','',1),

(8001,2,'NAT', 1,'AYSO National',  '','',1),
(8011,2,'S05', 1,'AYSO Section 5', '','',1),
(8012,2,'S11', 1,'AYSO Section 11','','',1),
(8041,2,'A05C',1,'AYSO Area 5C',   '','',1),
(8042,2,'A05F',1,'AYSO Area 5F',   '','',1),

(8101,3,'USSF',1,'United States Soccer','','',1),
(8111,3,'AYSA',1,'Alabama Youth Soccer','','',1),
(8121,3,'VFC', 1,'AYSO Area 5F',   '','',1),
(8132,3,'HFC', 1,'AYSO Area 5F',   '','',1),

(8301,4,'NFHS', 1,'Nat Fed State High School','','',1),
(8311,4,'AHSAA',1,'Alabama High School',      '','',1),

(9999,1,'OSSO', 1,'My Program',      '','',1);

-- ====================================
-- Link organizations to sports
DROP TABLE IF EXISTS org_sport;
CREATE TABLE         org_sport
(
  id            int(10) unsigned NOT NULL auto_increment,
  org_id        int(10) unsigned NOT NULL,
  sport_type_id int(10) unsigned NOT NULL,

  PRIMARY KEY  (id),
  UNIQUE KEY org_sport_i0 (org_id,sport_type_id),
  UNIQUE KEY org_sport_i1 (sport_type_id,org_id)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ====================================
-- Organizations that register people
DROP TABLE IF EXISTS org_reg;
CREATE TABLE         org_reg
(
  id       int(10) unsigned NOT NULL auto_increment,
  org_id   int(10) unsigned NOT NULL,
  keyx    char(20) NOT NULL,

  PRIMARY KEY    (id),
  UNIQUE  KEY i0 (keyx)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_reg VALUES
(1,9999,'OSSO'),
(2,8001,'AYSO'),
(3,8101,'USSF'),
(4,8301,'NFHS');

-- ====================================
-- Group of organizations
DROP TABLE IF EXISTS org_group;
CREATE TABLE         org_group
(
  id       int(10) unsigned NOT NULL auto_increment,
  keyx    char(20) NOT NULL,
  descx   char(40) NOT NULL,

  PRIMARY KEY    (id),
  UNIQUE  KEY i0 (keyx)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_group VALUES
(1,'AREA 5C 5F','North Alabama Rec Soccer'),
(2,'AREA 5C',   'AYSO Area 5C'),
(3,'AREA 5F',   'AYSO Area 5F');

-- ====================================
-- Organization group members
DROP TABLE IF EXISTS org_group_org;
CREATE TABLE         org_group_org
(
  id           int(10) unsigned NOT NULL auto_increment,
  org_group_id int(10) unsigned NOT NULL,
  org_id       int(10) unsigned NOT NULL,
  status       int(10) unsigned default 1,

  PRIMARY KEY    (id),
  UNIQUE  KEY i0 (org_group_id,org_id)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO org_group_org VALUES
(NULL,1,1,1),
(NULL,1,2,1),
(NULL,1,3,1);

-- ====================================
-- Master Person Table
DROP TABLE IF EXISTS person;
CREATE TABLE         person
(
  id       int(10) unsigned NOT NULL auto_increment,
  fname   char(20) DEFAULT '',
  lname   char(20) DEFAULT '',
  nname   char(20) DEFAULT '',
  mname   char(20) DEFAULT '',
  suffix  char(20) DEFAULT '',
  gender  char( 1) DEFAULT 'X',
  dob     char( 8) DEFAULT 'UNKNOWN',
  status   int(10) unsigned default 1,

  PRIMARY KEY    (id),
  KEY i0 (lname,fname)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ====================================
-- Link person to one or more organizations
DROP TABLE IF EXISTS person_org;
CREATE TABLE         person_org
(
  id        int(10) unsigned NOT NULL auto_increment,
  person_id int(10) unsigned NOT NULL,
  org_id    int(10) unsigned NOT NULL,
  status    int(10) unsigned default 1,

  PRIMARY KEY    (id),
  UNIQUE  KEY i0 (person_id,org_id)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO person_org VALUES
(NULL,1,1,1),
(NULL,1,2,1),
(NULL,1,3,1);

-- ====================================
-- Link person to one or more registration organizations
DROP TABLE IF EXISTS person_org_reg;
CREATE TABLE         person_org_reg
(
  id         int(10) unsigned NOT NULL auto_increment,
  person_id  int(10) unsigned NOT NULL,
  org_reg_id int(10) unsigned NOT NULL,
  reg_id     char(20)         NOT NULL, -- Organizations unique id, aysoid 8 digits
  reg_year   int(10) unsigned NOT NULL, -- Last year registered

  phone_home  char(20) DEFAULT '',
  phone_work  char(20) DEFAULT '',
  phone_cell  char(20) DEFAULT '',
  email1      char(40) DEFAULT '',
  email2      char(40) DEFAULT '',

  status     int(10) unsigned default 1,

  PRIMARY KEY    (id),
  UNIQUE  KEY i0 (person_id,org_reg_id),
  UNIQUE  KEY i0 (reg_id,   org_reg_id)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ====================================
-- Link person to certification
DROP TABLE IF EXISTS person_org_reg_cert;
CREATE TABLE         person_org_reg_cert
(
  id                 int(10) unsigned NOT NULL auto_increment,
  person_org_reg_id  int(10) unsigned NOT NULL,

  cert_cat     int(10) unsigned NOT NULL,
  cert_type    int(10) unsigned NOT NULL,
  cert_desc   char(20) DEFAULT '',
  cert_date   char( 8) DEFAULT '',

  status     int(10) unsigned default 1,

  PRIMARY KEY    (id),
          KEY i0 (person_org_reg_id)

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ============================================
-- Probably have cert categories and type lookup tables
