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
class Arbiter_HASL_PayProcess
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected $gameCnt = 0;
  protected $refs = array();

  protected function processRef($sport,$level,$pos,$name)
  {
    if (!$name) return;

    $pay = $this->payRates[$sport][$level][$pos];

    if (isset($this->refs[$name])) $ref = $this->refs[$name];
    else
    {
      $ref = array('pay' => 0, 'cr' => 0, 'ar' => 0);
    }
    $ref['pay'] += $pay;
    if (substr($pos,0,2) == 'cr') $ref['cr']++;
    if (substr($pos,0,2) == 'ar') $ref['ar']++;

    $this->refs[$name] = $ref;
  }
  function processRow($row)
  {
    // Ignore empty lines
    if (!$row->game) return;

    // Only high school
    //Cerad_Debug::dump($row); die();
    if ($row->sport != 'USSF - HASL') return;

    $this->gameCount++;

    // Count referees
    $cnt = 0;
    if ($row->cr)  $cnt++;
    if ($row->ar1) $cnt++;
    if ($row->ar2) $cnt++;

    if (!$cnt) return;

    $this->gameCount++;

    $this->processRef($row->sport,$row->level,'cr' . $cnt,$row->cr);
    $this->processRef($row->sport,$row->level,'ar' . $cnt,$row->ar1);
    $this->processRef($row->sport,$row->level,'ar' . $cnt,$row->ar2);

    return;
  }
  function importCSV($fileName)
  {
    $this->loadPayRates();
    
    $file = fopen($fileName,'rt');
    $header = fgetcsv($file);

    $row = new Row($header);
    while (($line = fgetcsv($file)))
    {
      $row->set($line);

      $this->processRow($row);
    }
    fclose($file);
  //Cerad_Debug::dump($this->refs); die();
  }
  function exportCSV($fileName = null)
  {
    $refs = $this->refs;
    $names = array_keys($refs);
    sort(&$names);

    $file = fopen($fileName,'w');
    $row = array('Referee','Pay','CR Count','AR Count');
    fputcsv($file,$row);

    foreach($names as $name)
    {
      $ref = $refs[$name];
      $row = array($name,$ref['pay'],$ref['cr'],$ref['ar']);

      fputcsv($file,$row);
    }
    $row = array();
    fputcsv($file,$row);

    fclose($file);
  }
  protected $payRates = array();

  function loadPayRates()
  {
    $ws = $this->context->config['ws'];
    $fileName = $ws . 'osso2010/apps/Arbiter/HASL/RefPayRates.csv';

    $file = fopen($fileName,'rt');
    $header = fgetcsv($file);

    $row = new PayRow($header);
    while (($line = fgetcsv($file)))
    {
      $row->set($line);
      $data = $row->data;
      $this->payRates[$data['sport']][$data['level']] = $data;
    }
    fclose($file);
    // Cerad_Debug::dump($this->payRates); die();
  }
}
class PayRow
{
  // public $sport,$level,$cr3,$ar3,$cr2,$ar2,$cr1;

  public $data = array();

  protected $indexes = array();
  protected $header  = null;

  function __construct($header)
  {
    $this->header = $header;
    //Cerad_Debug::dump($header);

    $this->indexes['sport'] = array_search('Sport',$header);
    $this->indexes['level'] = array_search('Level',$header);
    $this->indexes['cr1']   = array_search('CR1',  $header);
    $this->indexes['cr2']   = array_search('CR2',  $header);
    $this->indexes['cr3']   = array_search('CR3',  $header);
    $this->indexes['ar2']   = array_search('AR2',  $header);
    $this->indexes['ar3']   = array_search('AR3',  $header);
  }
  function set($data)
  {
    $indexes = $this->indexes;

    $this->data['sport'] =      trim($data[$indexes['sport']]);
    $this->data['level'] =      trim($data[$indexes['level']]);
    $this->data['cr1']   = (int)trim($data[$indexes['cr1']]);
    $this->data['cr2']   = (int)trim($data[$indexes['cr2']]);
    $this->data['cr3']   = (int)trim($data[$indexes['cr3']]);
    $this->data['ar2']   = (int)trim($data[$indexes['ar2']]);
    $this->data['ar3']   = (int)trim($data[$indexes['ar3']]);
  }
}
?>
