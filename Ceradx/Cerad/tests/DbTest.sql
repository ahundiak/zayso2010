CREATE TABLE person
(
  person_id INT NOT NULL auto_increment,
  region_id INT,
  fname     CHAR(20),
  lname     CHAR(20),
  PRIMARY KEY (`person_id`)
);
INSERT INTO person (region_id,fname,lname) VALUES
(1,'Art','Hundiak'),
(1,'Ethan','Hundiak'),
(2,'Patrick','Streeter')
;

CREATE TABLE person2
(
  person_id INT NOT NULL auto_increment,
  region_id INT,
  fname     CHAR(20),
  lname     CHAR(20),
  PRIMARY KEY (`person_id`)
);
INSERT INTO person2 (region_id,fname,lname) VALUES
(1,'Art','Hundiak'),
(1,'Ethan','Hundiak'),
(2,'Patrick','Streeter'),
(2,'Joe','X2-1'),
(2,'Joe','X2-2'),
(3,'Patrick','X3-1'),
(3,'Patrick','X3-2'),
(4,'Patrick','X4-1')
;
