-- DROP DATABASE IF EXISTS eayso2012;
-- CREATE DATABASE         eayso2012;
-- GRANT ALL ON eayso2012.* TO "impd"@"localhost";
-- FLUSH PRIVILEGES;

DROP TABLE IF EXISTS eayso2012.certification;
DROP TABLE IF EXISTS eayso2012.volunteer;

CREATE TABLE eayso2012.certification (
    aysoid      VARCHAR(20) NOT NULL, 
    cert_cat    INT NOT NULL, 
    cert_type   INT NOT NULL, 
    cert_date   VARCHAR(8) DEFAULT NULL, 
    INDEX   IDX_aysoid (aysoid), 
    PRIMARY KEY(aysoid, cert_cat)) ENGINE = InnoDB;

CREATE TABLE eayso2012.volunteer (
    id          VARCHAR(20) NOT NULL, 
    region      VARCHAR(20) DEFAULT NULL, 
    mem_year    integer     DEFAULT 0, 
    first_name  VARCHAR(40) NOT NULL, 
    last_name   VARCHAR(40) NOT NULL, 
    nick_name   VARCHAR(40) DEFAULT NULL, 
    middle_name VARCHAR(40) NOT NULL, 
    suffix      VARCHAR(20) NOT NULL, 
    dob         VARCHAR( 8) NOT NULL, 
    gender      VARCHAR( 2) NOT NULL, 
    email       VARCHAR(60) DEFAULT NULL, 
    home_phone  VARCHAR(20) DEFAULT NULL, 
    work_phone  VARCHAR(20) DEFAULT NULL, 
    cell_phone  VARCHAR(20) DEFAULT NULL, 
    status      VARCHAR(20) NOT NULL, 
    registered  VARCHAR( 8) NOT NULL, 
    changed     VARCHAR( 8) NOT NULL, 
    PRIMARY KEY(id)) ENGINE = InnoDB;

ALTER TABLE eayso2012.certification ADD FOREIGN KEY (aysoid) REFERENCES eayso2012.volunteer(id)