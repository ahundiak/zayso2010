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

  function __construct($context)
  {
    $this->context = $context;
    $this->count = new Cerad_ImportCount();
    $this->init();
  }
  protected function init()
  {
  }
  public function process($innFileName)
  {
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
}
?>
