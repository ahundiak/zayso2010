<?php
  $data  = $this->data;
  $games = $data->games;
?>
Game,Date,Time,Field,Div,Bracket,Home Team,Away Team,Center,AR1,AR2,Assessor,Assessor 2
<?php
  $game = new \S5GamesApp\Schedule\Show\GameView($this);
  foreach($games as $gamex)
  {
    $game->set($gamex);

    $line = array();
    $line[] = $game->id;
    $line[] = $game->date;
    $line[] = $game->time;
    $line[] = $game->field;
    $line[] = $game->div;
    $line[] = $game->bracket;
    $line[] = $game->homeTeam;
    $line[] = $game->awayTeam;

    $persons = $game->personsx;
    foreach($persons as $person) {
      $line[] = $person['name'];
    }
    echo implode(',',$line) . "\n";
}
?>
