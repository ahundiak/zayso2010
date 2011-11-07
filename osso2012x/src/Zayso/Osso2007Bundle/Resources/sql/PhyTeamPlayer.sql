 CREATE TABLE phy_team_player (
    id INT AUTO_INCREMENT NOT NULL,
    phy_team_id INT DEFAULT NULL,
    fname VARCHAR(20) DEFAULT NULL,
    lname VARCHAR(20) DEFAULT NULL,
    aysoid VARCHAR(20) NOT NULL,
    jersey INT DEFAULT NULL,
    INDEX IDX_PHY_TEAM (phy_team_id),
    PRIMARY KEY(id)) ENGINE = InnoDB;
