<?php
/* -----------------------------------------------
 * 16 Aug 2010
 * Converted to Reader approach with callback
 * 
 * 24 Aug 2008
 * Copied from osso2007/data/imports
 * Reads an xml spreadsheet with a title row and maps
 * the data based on the name of the row then calls processRowData
 */
class ReaderItem {}

class Cerad_Reader_XML2003
{
  protected $context  = NULL;
  protected $callback = NULL;

  protected $map  = array('FirstName' => 'fname','LastName' => 'lname');
  protected $mapx;

  protected $mapIndex = array();

  protected $headerRowProcessed = FALSE;

  public $workSheetNames = NULL;
  public $workSheetName  = NULL;

  public function __construct($context,$fileName = NULL,$callback = NULL)
  {
    $this->context  = $context;
    $this->callback = $callback;
       
    $this->init();
        
    if ($fileName) $this->process($fileName);    
  }
  protected function init() {}
    
  public function processRowHeader($cellNodes)
  {
    $map = $this->map;
    $indexes = array();
    for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++)
    {
      $cellNode  = $cellNodes->item($cellIndex);
      $dataNodes = $cellNode->getElementsByTagName('Data');
      $dataNode  = $dataNodes->item(0);
      if ($dataNode)
      {
        $dataNodeValue = trim($dataNode->nodeValue);
        if (isset($map[$dataNodeValue])) $indexes[$cellIndex] = $map[$dataNodeValue];
        //echo "$cellIndex $dataNodeValue\n";
      }
    }
    // Verify all columns are present
    $flag = FALSE;
    foreach($map as $key => $value)
    {
      if (array_search($value,$indexes) === FALSE)
      {    
        $flag = TRUE;
        echo "Missing index for $key\n";
      }
    }
    if ($flag) die();
        
    // Cleanup     
    $this->mapIndex = $indexes;
    $this->headerRowProcessed = TRUE;
  }
  public function processRow($cellNodes)
  {
    if (!$this->headerRowProcessed && $this->workSheetNames)
    {
      if (array_search($this->workSheetName,$this->workSheetNames) === FALSE) return;
      echo 'Processing ' . $this->workSheetName . "\n";
    }
    if (!$this->headerRowProcessed) return $this->processRowHeader($cellNodes);
            
    $indexes = $this->mapIndex;
    $data = array();
    foreach($indexes as $key => $value)
    {
      $data[$value] = NULL;
    }
    $dataIndex = 0;
    for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++)
    {
      $cellNode  = $cellNodes->item($cellIndex);
      $cellNodeIndex = $cellNode->getAttribute('ss:Index');
      if ($cellNodeIndex)
      {
        $dataIndex = $cellNodeIndex - 1;
      }
      $dataNodes = $cellNode->getElementsByTagName('Data');
      $dataNode  = $dataNodes->item(0);
      if ($dataNode)
      {
        $dataNodeValue = trim($dataNode->nodeValue);
        if (isset($indexes[$dataIndex]))
        {
          $data[$indexes[$dataIndex]] = $dataNodeValue;
        }
      }
      $dataIndex++;
    }
    $this->callback->processRowData($data);
  }
  public function process($fileName)
  {
    $xmlReader = new XMLReader();
    $flag = $xmlReader->open($fileName);
    if (!$flag) 
    {
      die($fileName);
      $this->errors[] = "Could not open $fileName";
      return;
    }   
    while($xmlReader->read())
    {
      if ($xmlReader->nodeType == XMLReader::ELEMENT)
      {
        // Work sheet name <Worksheet ss:Name="Sheet1">
        if ($xmlReader->name == 'Worksheet')
        {
          $this->workSheetName  = trim($xmlReader->getAttribute('ss:Name'));
          $this->headerRowProcessed = FALSE;
          // echo "{$this->workSheetName}\n";
        }
        if ($xmlReader->name == 'Row')
        {
          $rowNode   = $xmlReader->expand();
          $cellNodes = $rowNode->getElementsByTagName('Cell');
          $this->processRow($cellNodes);
        }
      }
    }
    $xmlReader->close();    
    return $this->processCompleted();
  }
  protected function processCompleted() {}

  function getDateFromExcelFormat($dtg)
  {
    return substr($dtg,0,4) . substr($dtg,5,2) . substr($dtg,8,2);
  }
}
?>
