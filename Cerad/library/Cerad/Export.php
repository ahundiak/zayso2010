<?php
class Cerad_Export
{
  protected $context;
  protected $db;
  protected $dbName = null;

  protected $outFileName = null;
  protected $countTotal = 0;
  
  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    if ($this->dbName)
    {
      $dbName = $this->dbName;
      $this->db = $this->context->$dbName;
    }
  }
  public function getResultMessage() {}
  
  protected function processContent($fp)
  {   
  }
  public function process($outFileName)
  {
    $this->outFileName = $outFileName;

    $fp = fopen($outFileName,'w');
    if (!$fp) return;

    $this->processContent($fp);
    
    fclose($fp);
  }
}
?>
