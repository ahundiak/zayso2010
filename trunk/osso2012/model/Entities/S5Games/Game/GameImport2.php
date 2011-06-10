<?php
namespace S5Games\Game;

class GameImport2
{
  protected $services;
  public    $count = 0;

  public function __construct($services,$fileName = NULL)
  {
    $this->services = $services;
    $this->init();
    if ($fileName) $this->process($fileName);
  }
  protected function init()
  {
  }
  protected function getCells($row)
  {
    $cells  = array();
    $cellsx = $row->getCellIterator();
    foreach($cellsx as $cell)
    {
      $cells[$cell->getColumn()] = trim($cell->getValue());
    }
    return $cells;
  }
  protected $div = '';
  protected $time = '';
  protected $games;

  protected $info = array
  (
    'U10' => array(
        'C' => array('date' => 'FRI','field' => 'Field 10a'),
        'D' => array('date' => 'FRI','field' => 'Field 10b'),
        'E' => array('date' => 'FRI','field' => 'Field 10c'),

        'G' => array('date' => 'SAT','field' => 'Field 10a'),
        'H' => array('date' => 'SAT','field' => 'Field 10b'),
        'I' => array('date' => 'SAT','field' => 'Field 10c'),

        'K' => array('date' => 'SUN','field' => 'Field 10a'),
        'L' => array('date' => 'SUN','field' => 'Field 10b'),
        'M' => array('date' => 'SUN','field' => 'Field 10c'),
    ),
    'U12' => array(
        'C' => array('date' => 'FRI','field' => 'Field  3'),
        'D' => array('date' => 'FRI','field' => 'Field  4'),
        'E' => array('date' => 'FRI','field' => 'Field  5'),
        'F' => array('date' => 'FRI','field' => 'Field  6'),

        'H' => array('date' => 'SAT','field' => 'Field  3'),
        'I' => array('date' => 'SAT','field' => 'Field  4'),
        'J' => array('date' => 'SAT','field' => 'Field  5'),
        'K' => array('date' => 'SAT','field' => 'Field  6'),

        'M' => array('date' => 'SUN','field' => 'Field  3'),
        'N' => array('date' => 'SUN','field' => 'Field  4'),
        'O' => array('date' => 'SUN','field' => 'Field  5'),
        'P' => array('date' => 'SUN','field' => 'Field  6'),
    ),
    'U14' => array(
        'C' => array('date' => 'FRI','field' => 'Field  1'),
        'D' => array('date' => 'FRI','field' => 'Field  2'),
        'F' => array('date' => 'SAT','field' => 'Field  1'),
        'G' => array('date' => 'SAT','field' => 'Field  2'),
        'I' => array('date' => 'SUN','field' => 'Field  1'),
        'J' => array('date' => 'SUN','field' => 'Field  2'),
    ),
    'U16' => array(
        'C' => array('date' => 'FRI','field' => 'Field  8'),
        'D' => array('date' => 'FRI','field' => 'Field  7'),
        'F' => array('date' => 'SAT','field' => 'Field  8'),
        'G' => array('date' => 'SAT','field' => 'Field  7'),
        'I' => array('date' => 'SUN','field' => 'Field  7'),
    ),
    'U19' => array(
        'C' => array('date' => 'FRI','field' => 'Field  9'),
        'D' => array('date' => 'FRI','field' => 'Field  7'),
        'F' => array('date' => 'SAT','field' => 'Field  9'),
        'G' => array('date' => 'SAT','field' => 'Field  7'),
        'I' => array('date' => 'SUN','field' => 'Field  9'),
    ),
  );
  protected function processTime($time)
  {
    if (($time > .31) && ($time < .32)) return '0730';
    if (($time > .36) && ($time < .37)) return '0845';
    if (($time > .37) && ($time < .38)) return '0900';
    if (($time > .38) && ($time < .39)) return '0915';
    if (($time > .41) && ($time < .42)) return '1000';
    if (($time > .43) && ($time < .44)) return '1030';
    if (($time > .45) && ($time < .46)) return '1100';
    if (($time > .46) && ($time < .47)) return '1115';
    if (($time > .49) && ($time < .51)) return '1200';
    if (($time > .52) && ($time < .53)) return '1230';
    if (($time > .53) && ($time < .54)) return '1245';
    if (($time > .56) && ($time < .57)) return '1330';
    if (($time > .57) && ($time < .58)) return '1345';
    if (($time > .60) && ($time < .61)) return '1430';
    if (($time > .62) && ($time < .63)) return '1500';
    if (($time > .67) && ($time < .68)) return '1615';
    if (($time > .68) && ($time < .69)) return '1630';

    die('Time ' . $time);
  }
  protected function processGameSheetRowAway($cells)
  {
    // Only want home and away stuff
    if (!isset($cells['B'])) return;
    if ($cells['B'] != 'Away') return;

    $em = $this->services->emGames;

    foreach($this->info[$this->div] as $col => $data)
    {
      if (isset($cells[$col]) && $cells[$col])
      {
        $team = $cells[$col];
        $game = $this->games[$col];
        $game->setAwayTeam($team);
        $em->persist($game);
      }
    }
    $em->flush();
  }
  protected function processGameSheetRowHome($cells)
  {
    // Only want home and away stuff
    if (!isset($cells['B'])) return;
    if ($cells['B'] != 'Home') return;
    
    $time = $this->processTime($cells['A']);
    $this->time = $time;

    $games = array();

    foreach($this->info[$this->div] as $col => $data)
    {
      if (isset($cells[$col]) && $cells[$col])
      {
        $team = $cells[$col];
        $game = new \S5Games\Game\GameItem();

        $game->setNum(++$this->count);
        $game->setDate($data['date']);
        $game->setTime($time);
        $game->setField($data['field']);
        $game->setHomeTeam($team);
        $game->setDiv($this->div . 'B');
        $game->setType('PP');

        if (isset($this->teams[$this->div][$team]))
        {
          $item = $this->teams[$this->div][$team]; //print_r($item); die();
          $game->setDiv    ($item['div']);
          $game->setBracket($item['brac']);
        }
        else echo "Missing {$this->div} $team\n";
        $games[$col] = $game;
      }
    }
    $this->games = $games;
  }
  protected function processGameSheet($sheet)
  {
    $rows = $sheet->getRowIterator();
    $row1 = $rows->current(); $rows->next();
    $row2 = $rows->current(); $rows->next();

    $cells1 = $this->getCells($row1);
    $cells2 = $this->getCells($row2);

    $this->div = $cells1['A'];

    //print_r($cells1);
    //print_r($cells2);

    while($rows->valid())
    {
      $row = $rows->current(); $rows->next();
      $cells = $this->getCells($row);
      $this->processGameSheetRowHome($cells);
      $this->processGameSheetRowAway($cells);
    }
  }
  protected function processTeamSheetRow($cells)
  {
    if (!isset($cells['A'])) return;
    $age = $cells['A'];
    if (!$age) return;

    $div   = $cells['B'];
    if (isset($cells['C'])) $index = $cells['C'];
    else                    $index = '';
    $team  = $cells['D'];
    $brac  = $cells['E'];

    $item = array
    (
      'age'   => $age,
      'div'   => $div,
      'index' => $index,
      'team'  => $team,
      'brac'  => $brac,
    );
    $this->teams[$age][$team] = $item;
  }
  protected function processTeamSheet($sheet)
  {
    $rows = $sheet->getRowIterator();
    $row1 = $rows->current(); $rows->next();

    $cells1 = $this->getCells($row1);
    //print_r($cells1);

    $this->teams = array();

    while($rows->valid())
    {
      $row = $rows->current(); $rows->next();
      $cells = $this->getCells($row);
      $this->processTeamSheetRow($cells);
    }
  }
  public function process($inputFileName)
  {
    // Clear existing entries
    $em = $this->services->emGames;
    $em->clear();
    $query = $em->createQuery('DELETE S5Games\Game\GameItem game');
    $query->getResult();

    /*
    $game = new \S5Games\Game\GameItem();
    $game->setNum(1);
    $em->persist($game);
    $em->flush();
    die('done');
    */
    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);

    $reader = \PHPExcel_IOFactory::createReader($inputFileType);
    $reader->setReadDataOnly(true);

    $excel = $reader->load($inputFileName);


    $sheet = $excel->getSheet(6);
    $this->processTeamSheet($sheet);
     
    $sheet = $excel->getSheet(5);  // U19
    $this->processGameSheet($sheet);

    $sheet = $excel->getSheet(4);  // U16
    $this->processGameSheet($sheet);

    $sheet = $excel->getSheet(3); // U14
    $this->processGameSheet($sheet);

    $sheet = $excel->getSheet(2); // U12
    $this->processGameSheet($sheet);

    $sheet = $excel->getSheet(1); // U10
    $this->processGameSheet($sheet);

    echo "Inserted {$this->count} games\n";

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
