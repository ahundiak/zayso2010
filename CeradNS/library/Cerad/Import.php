<?php

namespace Cerad;

class ImportCount
{
  public $total    = 0;
  public $inserted = 0;
  public $updated  = 0;
  public $deleted  = 0;
}
class Import
{
  protected $services;
  protected $params;

  protected $reader          = NULL;
  protected $readerClassName = 'Cerad\Reader\CSV';

  public    $count;

  protected $innFileName;

  public $allowUpdates = TRUE;
  
  function __construct($services)
  {
    $this->services = $services;
    $this->count = new ImportCount();
    $this->init();
  }
  protected function init() {}

  public function getResultMessage()
  {
    $name  = get_class($this);
    $count = $this->count;

    $results = sprintf("%s, Total: %d, Inserted: %d, Updated: %d,",
            $name,
            $count->total,
            $count->inserted,
            $count->updated);

    return $results;
  }
  protected function processBegin() {}
  protected function processEnd  () {}

  public function process($params)
  {
    $this->params = $params;

    $this->processBegin();

    if (is_array($params)) $innFileName = $params['input_file_name'];
    else                   $innFileName = $params;

    $this->innFileName = $innFileName;
    
    if (!$this->reader)
    {
      $readerClassName = $this->readerClassName;
      $this->reader = new $readerClassName($this);
    }
    $this->reader->process($innFileName);

    $this->processEnd();
  }
  public function processRowData($data)
  {
    die('Import processRowData');
  }
  // 1899-12-31T12:00:00.000
  protected function getDateFromExcelFormat($dtg)
  {
    return substr($dtg,0,4) . substr($dtg,5,2) . substr($dtg,8,2);
  }
  protected function getTimeFromExcelFormat($dtg)
  {
    return substr($dtg,11,2) . substr($dtg,14,2);
  }

}
?>
