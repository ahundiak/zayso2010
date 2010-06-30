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

    $this->gameIndex = array_search('Game ',$header);

    $this->dateIndex = array_search('Date & Time ',$header);
    $this->dowIndex  = $this->dateIndex + 1;
    $this->timeIndex = $this->dateIndex + 2;

    $this->sportIndex = array_search('Sport & Level ',$header);
    $this->levelIndex = $this->sportIndex + 1;

    $this->billIndex = array_search('Bill-To ',$header);
    $this->siteIndex = array_search('Site ',$header);
    $this->homeIndex = array_search('Home ',$header);
    $this->awayIndex = array_search('Away ',$header);

    $this->crIndex  = array_search('Officials ',$header);
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
class Arbiter_Metrics_Metrics
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

  function processRow($row)
  {
    // Ignore empty lines
    if (!$row->game) return;

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
  function import($fileName)
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
  }
}
?>
