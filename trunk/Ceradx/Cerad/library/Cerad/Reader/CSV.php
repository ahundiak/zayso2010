<?php
class Cerad_Reader_CSV
{
  protected $context  = NULL;
  protected $callback = NULL;
  protected $errors = NULL;
  protected $ts;

  protected $map  = array('FirstName' => 'fname','LastName' => 'lname');
  protected $mapx;

  protected $mapIndex = array();

  public function __construct($context,$fileName = NULL,$callback = NULL)
  {
    $this->context  = $context;
    $this->callback = $callback;
    $this->init();
    if ($fileName) $this->process($fileName);
  }
  protected function init()
  {
    $this->ts = $this->context->getTimeStamp();
  }
  public function getResultMessage() {}
  public function getErrors() { return $this->errors; }

  protected function getCSV($fp)
  {
    $data = fgetcsv($fp);
    
    if (!is_array($data)) return $data;

    foreach($data as $key => $value)
    {
      $data[$key] = trim($value);
    }
    return $data;
  }
  public function process($fileName)
  {
    $fp = fopen($fileName,'r');
    if (!$fp)
    {
      $this->errors[] = "Unable to open $fileName for reading";
      die($fileName);
      return;
    }
    
    $header = $this->getCSV($fp);
    $this->processRowHeader($header);
    if (count($this->errors)) return;

    while($row = $this->getCSV($fp))
    {
      // Cerad_Debug::dump($row); die();
      $data = array();
      foreach($this->mapIndex as $index => $name)
      {
        if ($index < 0)
        {
          if (isset($this->mapx[$name]['default'])) $default = $this->mapx[$name]['default'];
          else                                      $default = NULL;
          $data[$name] = $default;
        }
        else
        {
          $value = $row[$index];
          if (!$value)
          {
            if (isset($this->mapx[$name]['default'])) $value = $this->mapx[$name]['default'];
          }
          $data[$name] = $value;
        }
      }
      $this->processRowData($data);
    }
    fclose($fp);
  }
  protected function processRowData($row)
  {
    if ($this->callback) return $this->callback->processRowData($row);
  }
  protected function processRowHeader($header)
  {
    $this->mapIndex = array();
    $optionalIndex = 0;

    foreach($this->map as $csvName => $sqlName)
    {
      $csvIndex = array_search($csvName,$header);
      if ($csvIndex !== FALSE) $this->mapIndex[$csvIndex] = $sqlName;
      else
      {
        if (isset($this->mapx[$sqlName]) && !$this->mapx[$sqlName]['required'])
        {
          $this->mapIndex[--$optionalIndex] = $sqlName;
        }
        else
        {
          $this->errors[] = "*** Unable to find {$csvName} in header row.";
        }
      }
    }
  }
}

?>
