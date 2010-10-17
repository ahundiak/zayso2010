<?php
class Cerad_ImportCount
{
  public $total    = 0;
  public $inserted = 0;
  public $updated  = 0;
  public $deleted  = 0;
}
class Cerad_Import
{
  protected $context;
  protected $db;

  protected $reader          = NULL;
  protected $readerClassName = 'Cerad_Reader_CSV';

  public $count;

  protected $countTotal  = 0;
  protected $countUpdate = 0;
  protected $countInsert = 0;

  protected $innFileName;

  public $allowUpdates = TRUE;
  
  function __construct($context)
  {
    $this->context = $context;
    $this->count = new Cerad_ImportCount();
    $this->init();
  }
  protected function init()
  {
  }
  public function process($params)
  {
    if (is_array($params)) $innFileName = $params['input_file_name'];
    else                   $innFileName = $params;

    $this->innFileName = $innFileName;
    
    if (!$this->reader)
    {
      $readerClassName = $this->readerClassName;
      $this->reader = new $readerClassName($this->context,NULL,$this);
    }
    $this->reader->process($innFileName);
  }
  public function processRowData($data)
  {
    
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
