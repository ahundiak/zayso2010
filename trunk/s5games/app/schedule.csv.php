<?php
  header('Pragma: public');
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
  header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
  header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
  header ("Pragma: no-cache");
  header("Expires: 0");
  header('Content-Transfer-Encoding: none');
//header('Content-Type: application/vnd.ms-excel;'); // This should work for IE & Opera
//header("Content-Type: application/x-msexcel"); // This should work for the rest
  header("Content-Type: text/csv");
  header('Content-Disposition: attachment; filename="'. 'schedule2010.csv' .'"');
?>
Game,Date,Time,Field,Bracket,Home Team,Away Team
<?php foreach($tpl->games as $game)
{
  echo "{$game->id},{$game->date},{$game->time},{$game->field},{$game->bracket},{$game->homeName},{$game->awayName}\n";
}
?>
