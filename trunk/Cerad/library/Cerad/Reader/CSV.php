<?php
class Cerad_Reader_CSV
{
  protected $context  = NULL;
  protected $callback = NULL;
  protected $errors = NULL;
  protected $ts;

  protected $map  = array('FirstName' => 'fname','LastName' => 'lname');
  protected $mapOptional = array(); // Optional
  protected $mapDefaults = array();

  protected $mapIndexes  = array();

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
      foreach($this->mapIndexes as $index => $name)
      {
        if ($index < 0)
        {
          if (isset($this->mapDefaults[$name])) $value = $this->mapDefaults[$name];
          else                                  $value = NULL;
          $data[$name] = NULL;
        }
        else
        {
          $value = $row[$index];
          if (!$value)
          {
            if (isset($this->mapDefaults[$name])) $value = $this->mapDefaults[$name];
          }
        }
        $data[$name] = $value;
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
    $this->mapIndexes = array();
    $optionalIndex = 0;

    foreach($this->map as $csvName => $sqlName)
    {
      $csvIndex = array_search($csvName,$header);
      if ($csvIndex !== FALSE) $this->mapIndexes[$csvIndex] = $sqlName;
      else
      {
        $this->errors[] = "*** Unable to find {$csvName} in header row.";
      }
    }
    foreach($this->mapOptional as $csvName => $sqlName)
    {
      $csvIndex = array_search($csvName,$header);
      if ($csvIndex !== FALSE) $this->mapIndexes[$csvIndex]        = $sqlName;
      else                     $this->mapIndexes[--$optionalIndex] = $sqlName;
    }
  }
}
?>
