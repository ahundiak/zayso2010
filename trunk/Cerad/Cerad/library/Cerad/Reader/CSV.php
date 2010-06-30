<?php
class Cerad_Reader_CSV
{
  protected $context = NULL;

  protected $map  = array('FirstName' => 'fname','LastName' => 'lname');
  protected $mapx = array();

  public function __construct($context,$fileName = NULL)
  {
    $this->context = $context;
    $this->init();
    if ($fileName) $this->process($fileName);
  }
  protected function init()
  {
  }
  public function process($fileName)
  {
    $fp = fopen($fileName,'r');

    $header = fgetcsv($fp);
    $this->processRowHeader($header);

    while($row = fgetcsv($fp))
    {
      // Cerad_Debug::dump($row); die();
      $data = array();
      foreach($this->mapx as $index => $name)
      {
        $data[$name] = $row[$index];
      }
      $this->processRowData($data);
    }
    fclose($fp);
  }
  protected function processRowHeader($row)
  {
    foreach($this->map as $csvName => $sqlName)
    {
      $csvIndex = array_search($csvName,$row);
      if ($csvIndex === FALSE)
      {
        echo "*** Unable to find {$csvName} in header row.\n";
      }
      else
      {
        $this->mapx[$csvIndex] = $sqlName;
      }
    }
  }
}

?>
