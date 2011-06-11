-- Per email from Marlene
UPDATE games SET
  game_bracket = 'Brac 2',
  home_name = 'Nielsen-605',
  away_name = 'Stovall-894'
WHERE game_num = 53;

UPDATE games SET
  game_div     = 'U14B',
  game_bracket = 'NA',
  home_name    = 'Mize-337',
  away_name    = 'Rossetti-498'
WHERE game_num = 64;

DELETE FROM game_person WHERE game_num = 53;
DELETE FROM game_person WHERE game_num = 64;
