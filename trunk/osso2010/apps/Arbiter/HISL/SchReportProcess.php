<?php
/*
 * Process a csv arbiter schedule and generate some stats
 */
class Row
{
  public $game,$date,$dow,$time;
  public $sport,$level;
  public $bill,$site,$home,$away;
  public $cr,$ar1,$ar2;

  function __construct($header)
  {
    $this->header = $header;
    //Cerad_Debug::dump($header);

    $this->gameIndex = array_search('Game',$header);

    $this->dateIndex = array_search('Date & Time',$header);
    $this->dowIndex  = $this->dateIndex + 1;
    $this->timeIndex = $this->dateIndex + 2;

    $this->sportIndex = array_search('Sport & Level',$header);
    $this->levelIndex = $this->sportIndex + 1;

    $this->billIndex = array_search('Bill-To',$header);
    $this->siteIndex = array_search('Site',$header);
    $this->homeIndex = array_search('Home',$header);
    $this->awayIndex = array_search('Away',$header);

    $this->crIndex  = array_search('Officials',$header);
    $this->ar1Index = $this->crIndex + 1;
    $this->ar2Index = $this->crIndex + 2;

  }
  function set($data)
  {
    $this->game   = (int)trim($data[$this->gameIndex]);
    $this->date   =      trim($data[$this->dateIndex]);
    $this->dow    =      trim($data[$this->dowIndex]);
    $this->time   =      trim($data[$this->timeIndex]);

    $this->sport  =      trim($data[$this->sportIndex]);
    $this->level  =      trim($data[$this->levelIndex]);
    $this->bill   =      trim($data[$this->billIndex]);
    $this->site   =      trim($data[$this->siteIndex]);
    $this->home   =      trim($data[$this->homeIndex]);
    $this->away   =      trim($data[$this->awayIndex]);
    $this->cr     =      trim($data[$this->crIndex]);
    $this->ar1    =      trim($data[$this->ar1Index]);
    $this->ar2    =      trim($data[$this->ar2Index]);
  }
}
class Arbiter_HISL_SchReportProcess
{
  protected $gameCnt = 0;
  protected $teams = array();

  protected $schools = array
  (
    'CCA'            => array('paid' => 150, 'teams' => array('CCA')),
    'Excalibur'      => array('paid' => 150, 'teams' => array('Excalibur')),
    'Grace Lutheran' => array('paid' => 150, 'teams' => array('Grace Lutheran')),
    'Holy Family'    => array('paid' => 585, 'teams' => array('Holy Family 1','Holy Family 2','Holy Family 3','Holy Family 4')),
    'Holy Spirit'    => array('paid' => 900, 'teams' => array('Holy Spirit 1','Holy Spirit 2','Holy Spirit 3','Holy Spirit 4','Holy Spirit 6')),
    'Randolph'       => array('paid' => 570, 'teams' => array('Randolph Blue','Randolph Blue 4','Randolph Gray','Randolph Gray 3')),
    'St. John'       => array('paid' => 900, 'teams' => array('St. John 1','St. John 2','St. John 3','St. John 4','St. John 5','St. John 6')),
    'Union Chapel'   => array('paid' => 210, 'teams' => array('Union Chapel','Union Chapel 2')),
    'Valley'         => array('paid' => 150, 'teams' => array('Valley')),
    'Whitesburg'     => array('paid' =>   0, 'teams' => array('Whitesburg 1','Whitesburg 2','Whitesburg 3','Whitesburg 4')),
  );
  function processTeam($date,$teamKey)
  {
    $tmp = explode('/',$date);
    $time1 = mktime(0,0,0,$tmp[0],$tmp[1],$tmp[2]);
    $time2 = time();

    if (isset($this->teams[$teamKey])) $team = $this->teams[$teamKey];
    else
    {
      $team = array('total' => 0, 'played' => 0);
    }
    $team['total']++;

    if ($time1 < $time2) $team['played']++;
    
    $this->teams[$teamKey] = $team;
  }
  function processRow($row)
  {
    // Ignore empty lines
    if (!$row->game) return;

    // Only high school
    //Cerad_Debug::dump($row); die();
    if ($row->sport != 'HISL') return;

    $this->gameCount++;

    $this->processTeam($row->date,$row->home);
    $this->processTeam($row->date,$row->away);
    
    return;
  }
  function importCSV($fileName)
  {
    $file = fopen($fileName,'rt');
    $header = fgetcsv($file);

    $row = new Row($header);
    while (($line = fgetcsv($file)))
    {
      $row->set($line);

      $this->processRow($row);
    }
    fclose($file);

    // echo "Game count {$this->gameCount}\n";
    // Cerad_Debug::dump($this->teams);
    
  }
  function exportCSV($fileName = null)
  {
    $file = fopen($fileName,'w');

    $row = array('School','Teams','Games Total','Cost Total','Paid','Games Played','Balance');
    fputcsv($file,$row);

    $summary = array('paid' => 0, 'total' => 0, 'played' => 0);

    foreach($this->schools as $school => $data)
    {
      $teams = $data['teams'];
      $gamesTotal  = 0;
      $gamesPlayed = 0;
      foreach($teams as $team)
      {
        $gamesTotal  += $this->teams[$team]['total'];
        $gamesPlayed += $this->teams[$team]['played'];

      }
      $costTotal = $gamesTotal * 15;
      $paid = $data['paid'];
      $balance = $paid - ($gamesPlayed * 15);

      $row = array($school,count($teams),$gamesTotal,$costTotal,$paid,$gamesPlayed,$balance);

      $summary['paid']   += $paid;
      $summary['total']  += $gamesTotal;
      $summary['played'] += $gamesPlayed;

      fputcsv($file,$row);
    }
    $row = array();
    fputcsv($file,$row);

    $gamesTotal  = $summary['total' ] / 2;
    $gamesPlayed = $summary['played'] / 2;
    $paid        = $summary['paid'];
    $balance     = $paid - ($gamesPlayed * 30);

    $row = array('Summary','',$gamesTotal,$gamesTotal * 30,$paid,$gamesPlayed,$balance);
    fputcsv($file,$row);

    fclose($file);
  }
}
?>
