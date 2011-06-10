<?php
namespace S5Games\Game;

class GameImport
{
  protected $services;
  public    $count = 0;

  protected $games = array();
  protected $errors = array();

  protected $map = array
  (
    0 => 'num',
    1 => 'date',
    2 => 'div',
    3 => 'time',
    4 => 'field',
    5 => 'type',
    6 => 'bracket',
    7 => 'home',
    8 => 'away',
  );
  protected $mapx = array // Not using
  (
    'Game #'  => 'num',
    'Day'     => 'date',
    'TIME'    => 'time',
    'Div'     => 'div',
    'Field'   => 'field',
    'Type'    => 'type',
    'Bracket' => 'bracket',
    'Home'    => 'home',
    'Away'    => 'away',
  );
  protected $fields = array(
    'JH1'  => 'JH 1',
    'JH2'  => 'JH 2',
    'JH3'  => 'JH 3',
    'JH4'  => 'JH 4',
    'JH5'  => 'JH 5',
    'JH6'  => 'JH 6',
    'JH7'  => 'JH 7',
    'JH8'  => 'JH 8',
    'JH9'  => 'JH 9',
    'JH10' => 'JH 5',  // Mistake in original schedule
    'JH5 / MM5' => 'JH 5*',
    'MM1'  => 'MM 1',
    'MM2'  => 'MM 2',
    'MM3'  => 'MM 3',
    'MM4'  => 'MM 4',
    'MM5'  => 'MM 5',
    'MM6'  => 'MM 6',
    'MM7'  => 'MM 7',
    'MM8'  => 'MM 8',
    'MM9'  => 'MM 9',
    'MM10' => 'MM10',    
  );
  public function __construct($services,$fileName = NULL)
  {
    $this->services = $services;
    $this->init();
    if ($fileName) $this->process($fileName);
  }
  protected function init()
  {
  }
  public function process($fileName)
  {
    // Clear existing entries
    $em = $this->services->emGames;
    $em->clear();
    $query = $em->createQuery('DELETE S5Games\Game\GameItem game');
    $query->getResult();

    $this->errors = array();
    $this->games  = array();

    $fp = fopen($fileName,'r');
    while($row = fgetcsv($fp,1000))
    {
      $this->processRow($row);
    }
    fclose($fp);

    // $this->validateTeams($this->games);
  }
  protected function processRow($row)
  {
    if (count($row) < 9) return;
    $item = array();
    foreach($this->map as $index => $name)
    {
      $item[$name] = trim($row[$index]);
    }
    $this->processRowData($item);
  }
  public function processRowData($data)
  {
    $num = (int)($data['num']);
    if (!$num) return;
    
    // Clean up time for sorting
    $time = $data['time'];
    if (strlen($time) == 3) $time = '0' . $time;

    // Division just because
    $div = 'U' . $data['div'];

/*
    // Clean up field for sorting
    if (!isset($this->fields[$data['game_field']]))
    {
      die($data['game_num'] . ' ' . $data['game_field']);
    }
    
    $data['game_field'] = $this->fields[$data['game_field']];
*/
    switch($data['bracket'])
    {
      case 'NO BRACKET' : $data['bracket'] = 'NA';     break;
      case 'FINAL'      : $data['bracket'] = 'FINALS'; break;
    }
		
    // And store
    $game = new \S5Games\Game\GameItem();
    $game->setNum     ($num);
    $game->setDate    ($data['date']);
    $game->setTime    ($time);
    $game->setDiv     ($div);
    $game->setField   ($data['field']);
    $game->setType    ($data['type']);
    $game->setBracket ($data['bracket']);
    $game->setHomeTeam($data['home']);
    $game->setAwayTeam($data['away']);

    $em = $this->services->emGames;
    $em->persist($game);
    $em->flush();

    // die('inserted');
    
    $this->count++;
    $this->games[] = $game;
  }
  // This was some old code used to check dates and times
  protected function validateFields($games)
  {
    $teams = array();

    // One entry for each game/team
    foreach($games as $game)
    {
      $home = $game; $home['team_key'] = $game['game_div'] . ' ' . $game['home_name'];
      $away = $game; $away['team_key'] = $game['game_div'] . ' ' . $game['away_name'];

      $teams[] = $home;
      $teams[] = $away;
    }
    usort($teams,array('ImportGames','compareTeams'));
  }
  protected function validateTeams($games)
  {
    $teams = array();

    // One entry for each game/team
    foreach($games as $game)
    {
      $home = $game; $home['team_key'] = $game['game_div'] . ' ' . $game['home_name'];
      $away = $game; $away['team_key'] = $game['game_div'] . ' ' . $game['away_name'];

      $teams[] = $home;
      $teams[] = $away;
    }
    usort($teams,array('ImportGames','compareTeams'));

    $max  = count($teams);
    $max1 = count($teams) - 1;
    $teamStats = array();
    for($i = 0; $i < $max; $i++)
    {
      $a = $teams[$i];
      $aKey = $a['team_key'];

      $gameType = $a['game_type'];
      if ($gameType == 'RR' || $gameType == 'PP')
      {
      if (!isset($teamStats[$aKey]))
      {
        $teamStats[$aKey] = array
        (
          'team_key'   => $aKey,
          'game_count' =>    0,
          'time_diff'  => 1000,
        );
      }
      $teamStats[$aKey]['game_count']++;

      if ($i < $max1)
      {
        $b = $teams[$i+1];
        $bKey = $b['team_key'];

        if ($aKey == $bKey)
        {
          if ($a['game_date'] == $b['game_date'])
          {
            $aTime = $a['game_time'];
            $bTime = $b['game_time'];
            if ($aTime == $bTime)
            {
              $error =
                "Two games for team with same time, " .
                "{$a['team_key']} {$a['game_num']},{$b['game_num']} " .
                "{$a['game_date']} {$a['game_time']} {$a['game_field']}\n";
              $this->errors[] = $error;
              echo $error;
            }
            else
            {
              $aTime = substr($aTime,0,2) * 60 + substr($aTime,2,2);
              $bTime = substr($bTime,0,2) * 60 + substr($bTime,2,2);

              $diff = $bTime - $aTime;
              if ($diff < $teamStats[$aKey]['time_diff']) $teamStats[$aKey]['time_diff'] = $diff;
              // echo "$aTime $bTime $diff\n";
            }
          }
        }
      }}
    }
    foreach($teamStats as $teamStat)
    {
      echo "{$teamStat['team_key']} {$teamStat['game_count']} {$teamStat['time_diff']}\n";
    }
    // Cerad_Debug::dump($teamStats);
  }
  static public function compareTeams($a,$b)
  {
    // Group team keys
    if ($a['team_key'] < $b['team_key']) return -1;
    if ($a['team_key'] > $b['team_key']) return  1;

    // Group days
    if ($a['game_date'] < $b['game_date']) return -1;
    if ($a['game_date'] > $b['game_date']) return  1;

    // Group time
    if ($a['game_time'] < $b['game_time']) return -1;
    if ($a['game_time'] > $b['game_time']) return  1;

    return 0;
  }
}
?>
