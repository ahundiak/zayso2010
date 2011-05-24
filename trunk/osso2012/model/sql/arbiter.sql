use arbiter;

-- ===============================================
-- Games
DROP TABLE IF EXISTS games;
CREATE TABLE         games
(
  id      int(10) unsigned NOT NULL auto_increment,

  game_num int(10) unsigned NOT NULL,

  datex  char(20) NOT NULL,
  dow    char( 4) NOT NULL,
  timex  char(20) NOT NULL,
  sport  char(20) NOT NULL,
  levelx char(20) NOT NULL,
  bill   char(40) NOT NULL,
  site   char(40) NOT NULL,

  home_team  char(40) NOT NULL,
  away_team  char(40) NOT NULL,

  cr   char(40) NOT NULL,
  ar1  char(40) NOT NULL,
  ar2  char(40) NOT NULL,
  
  home_score  int(10) unsigned,
  away_score  int(10) unsigned,

  PRIMARY KEY  (id),
  UNIQUE  KEY  (game_num)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
