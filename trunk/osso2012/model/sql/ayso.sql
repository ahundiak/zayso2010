USE ayso;

-- ==================================================================
DROP TABLE IF EXISTS ayso.players;

CREATE TABLE ayso.players
(
  id            VARCHAR(20) NOT NULL,
  region_id     INT         NOT NULL,
  fname         VARCHAR(20) NOT NULL DEFAULT '',
  lname         VARCHAR(20) NOT NULL DEFAULT '',
  mname         VARCHAR(20) NOT NULL DEFAULT '',
  nname         VARCHAR(20) NOT NULL DEFAULT '',
  suffix        VARCHAR(20) NOT NULL DEFAULT '',
  phone_home    VARCHAR(20) NOT NULL DEFAULT '',
  email         VARCHAR(40) NOT NULL DEFAULT '',
  dob           VARCHAR( 8) NOT NULL DEFAULT '',
  gender        VARCHAR( 2) NOT NULL DEFAULT '',
  jersey_size   VARCHAR(20) NOT NULL DEFAULT '',
  jersey_number INT         NOT NULL DEFAULT -1,
  PRIMARY KEY(id)
) ENGINE = InnoDB;

-- ==================================================================
DROP TABLE IF EXISTS ayso.teams;

CREATE TABLE ayso.teams
(
  id         VARCHAR(20) NOT NULL,
  region_id  INT         NOT NULL,
  mem_year   INT         NOT NULL,
  program    VARCHAR(20) NOT NULL DEFAULT '',
  desig      VARCHAR(20) NOT NULL DEFAULT '',
  division   VARCHAR( 4) NOT NULL DEFAULT '',
  gender     VARCHAR( 2) NOT NULL DEFAULT '',
  team_name  VARCHAR(20) NOT NULL DEFAULT '',
  colors     VARCHAR(20) NOT NULL DEFAULT '',
  PRIMARY KEY(id),
  UNIQUE KEY  i0 (region_id,mem_year,program,desig)
) ENGINE = InnoDB;

-- ==================================================================
DROP TABLE IF EXISTS ayso.team_player;

CREATE TABLE ayso.team_player
(
  id             INT unsigned NOT NULL auto_increment,
  team_id        VARCHAR(20)  NOT NULL,
  player_id      VARCHAR(20)  NOT NULL,
  jersey_number  INT          NOT NULL DEFAULT -1,
  PRIMARY KEY(id),
  UNIQUE KEY  i0 (team_id,player_id)
) ENGINE = InnoDB;
