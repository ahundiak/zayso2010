<?php
namespace ArbiterApp\RefStats;

/* 
 * Process a csv arbiter schedule and generate some stats
 */
class RowIndexes
{
  public $game,$date,$dow,$time;
  public $sport,$level;
  public $bill,$site,$homeTeam,$awayTeam;
  public $cr,$ar1,$ar2;
  public $homeScore,$awayScore;

  function __construct($header)
  {
    // \Cerad\Debug::dump($header);

    $this->game = array_search('Game',$header);

    $this->date = array_search('Date & Time',$header);
    $this->dow  = $this->date + 1;
    $this->time = $this->date + 2;

    $this->sport = array_search('Sport & Level',$header);
    $this->level = $this->sport + 1;

    $this->bill = array_search('Bill-To',$header);
    $this->site = array_search('Site',$header);
    $this->homeTeam = array_search('Home',$header);
    $this->awayTeam = array_search('Away',$header);

    $this->cr  = array_search('Officials',$header);
    $this->ar1 = $this->cr + 1;
    $this->ar2 = $this->cr + 2;

    $this->homeScore  = array_search('Score(H)',$header);
    $this->awayScore  = array_search('Score(A)',$header);

  }
}

class RefStatsProcess
{
  protected $services;

  public function __construct($services)
  {
    $this->services = $services;
  }
  public function import($csvFileName)
  {
    // Delete existing games
    $em = $this->services->em;
    $em->clear();
    $query = $em->createQuery('DELETE Arbiter\GameItem;');
    $query->getResult();

    // Setup counter
    $count = new \Cerad\CountListener($em);

    // Setup to read file
    $file = fopen($csvFileName,'rt');
    $header = fgetcsv($file);
    $rowIndexes = new RowIndexes($header);

    // Insert each record
    while ($line = fgetcsv($file))
    {
      $game = new \Arbiter\GameItem();

      $gameNum = (int)trim($line[$rowIndexes->game]);

      $game->setId     ($gameNum);
      $game->setGameNum($gameNum);

      $game->setDate (trim($line[$rowIndexes->date]));
      $game->setDow  (trim($line[$rowIndexes->dow]));
      $game->setTime (trim($line[$rowIndexes->time]));
      $game->setSport(trim($line[$rowIndexes->sport]));
      $game->setLevel(trim($line[$rowIndexes->level]));
      $game->setBill (trim($line[$rowIndexes->bill]));
      $game->setSite (trim($line[$rowIndexes->site]));

      $game->setHomeTeam(trim($line[$rowIndexes->homeTeam]));
      $game->setAwayTeam(trim($line[$rowIndexes->awayTeam]));

      $game->setCR (trim($line[$rowIndexes->cr]));
      $game->setAR1(trim($line[$rowIndexes->ar1]));
      $game->setAR2(trim($line[$rowIndexes->ar2]));

      if ($rowIndexes->homeScore) $game->setHomeScore((int)trim($line[$rowIndexes->homeScore]));
      if ($rowIndexes->awayScore) $game->setAwayScore((int)trim($line[$rowIndexes->awayScore]));

      if ($gameNum) $em->persist($game);

      // \Cerad\Debug::dump($game); die('game');
    }
    fclose($file);
    $em->flush();
    echo "Done flushing {$count->inserted}\n";
  }
}
class RefMetrics
{
  protected $gameCount = 0;
  protected $slotCount = 0;
  
  protected $dowCounts = array
  (
    'Mon' => 0,
    'Tue' => 0,
    'Wed' => 0,
    'Thu' => 0,
    'Fri' => 0,
    'Sat' => 0,
    'Sun' => 0,
  );
  protected $dateCounts  = array();
  protected $matchCounts = array();

  protected $referees = array();

  function processReferee($pos,$name)
  {
    if (!isset($this->referees[$name])) $this->referees[$name] = 0;
    $this->referees[$name]++;
  }
  function processRow($row)
  {
    // Ignore empty lines
    if (!$row->game) return;

    // Only high school
    //Cerad_Debug::dump($row); die();
    if ($row->sport != 'AHSAA') return;
    
    $this->gameCount++;

    if ($row->cr)
    {
      $this->slotCount++;
      if (isset($this->matchCounts[$row->cr])) $this->matchCounts[$row->cr]++;
      else                                     $this->matchCounts[$row->cr] = 1;
    }
    if ($row->ar1)
    {
      $this->slotCount++;
      if (isset($this->matchCounts[$row->ar1])) $this->matchCounts[$row->ar1]++;
      else                                      $this->matchCounts[$row->ar1] = 1;
    }
    if ($row->ar2)
    {
      $this->slotCount++;
      if (isset($this->matchCounts[$row->ar2])) $this->matchCounts[$row->ar2]++;
      else                                      $this->matchCounts[$row->ar2] = 1;
    }

    $this->dowCounts[$row->dow]++;

    if (isset($this->dateCounts[$row->date])) $this->dateCounts[$row->date]++;
    else                                      $this->dateCounts[$row->date] = 1;

  }
  function process($params)
  {
    $fileName = $params['innFileName'];

    $file = fopen($fileName,'rt');
    $header = fgetcsv($file);

    $row = new Row($header);
    while (($line = fgetcsv($file)))
    {
      $row->set($line);

      $this->processRow($row);
    }
    fclose($file);

    $slotCnt = $this->gameCount * 3;
    echo "Total Game Count: {$this->gameCount}\n";
    echo "Total Slot Count: {$this->slotCount} of {$slotCnt}\n";
    foreach($this->dowCounts as $dow => $count)
    {
      echo "DOW {$dow}: {$count}\n";
    }
    $maxCount = 0;
    foreach($this->dateCounts as $date => $count)
    {
      if ($count > $maxCount) $maxCount = $count;
    }
    echo "Max games in one day: {$maxCount}\n";

    $counts = array
    (
       1 => 0,
      10 => 0,
      20 => 0,
      30 => 0,
      40 => 0,
      50 => 0,
      60 => 0,
      70 => 0,
      80 => 0,
    );
    foreach($counts as $atLeast => $atLeastCount)
    {
      foreach($this->matchCounts as $count)
      {
        if ($count >= $atLeast) $counts[$atLeast]++;
      }
    }
    foreach($counts as $atLeast => $atLeastCount)
    {
      echo "At Least {$atLeast}: {$atLeastCount}\n";
    }
    die('Import complete');
    // Referee listing
    ksort($this->matchCounts);
    $fp = fopen('/home/ahundiak/datax/arbiter/AHSAARefereeCounts.csv','w');
    $hdr = array('fname','lname','count');
    fputcsv($fp,$hdr);
    foreach($this->matchCounts as $name => $count)
    {
      $names = explode(' ',$name);
      $fname = $names[0];
      array_shift($names);
      $lname = implode(' ',$names);

      $item = array('fname' => $fname, 'lname' => $lname, 'count' => $count);
      fputcsv($fp,$item);
    }
    fclose($fp);
  }
}
error_reporting(E_ALL);

// define('APP_CONFIG_HOME','/home/ahundiak/zayso2012/');
// define('APP_CONFIG_DATA','/home/ahundiak/datax/arbiter/AHSAA/Stats/Schedule20110414.csv');

$params = array
(
    'innFileName' => '/home/ahundiak/datax/arbiter/AHSAA/Stats/Schedule20110414.csv',
    'outFilename' => '/home/ahundiak/datax/arbiter/AHSAA/Stats/Stats20110414.csv',
);
$metrics = new RefMetrics(NULL);
$metrics->process($params);
?>
