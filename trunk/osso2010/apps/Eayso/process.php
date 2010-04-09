<?php
ERROR_REPORTING(E_ALL);

define('ZAYSO2010_CONFIG_HOME','/home/impd/zayso2010/');
define('ZAYSO2010_CONFIG_DATA','/home/impd/zayso2010/datax/');

ini_set('include_path','.:' .
        ZAYSO2010_CONFIG_HOME . 'Cerad/library');

require_once 'Cerad/Loader.php';
Cerad_Loader::registerAutoload();

$xmlFileName  = ZAYSO2010_CONFIG_DATA . 'SimpleTest01.xml';
$xmlFileNamex = basename($xmlFileName,'.xml') . 'x.xml';

$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->load($xmlFileName);
$doc->formatOutput = true;
$doc->save($xmlFileNamex);

$sheetNodes = $doc->getElementsByTagName('Worksheet');
foreach($sheetNodes as $sheetNode)
{
  $name = $sheetNode->getAttribute('ss:Name');
  echo "Sheet $name\n";
  $rowNodes = $sheetNode->getElementsByTagName('Row');
  $rowIndex = 0;
  foreach($rowNodes as $rowNode)
  {
    $rowIndex++;
    echo "  Row {$rowIndex}\n";

    $cellNodes = $rowNode->getElementsByTagName('Cell');
    $cellIndex = 0;
    foreach($cellNodes as $cellNode)
    {
      if ($cellNode->hasAttribute('ss:Index')) $cellIndex = (int)$cellNode->getAttribute('ss:Index');
      else                                     $cellIndex++;
    
      $dataNodes = $cellNode->getElementsByTagName('Data');
      $dataIndex = 0;
      foreach($dataNodes as $dataNode)
      {
        $dataIndex++;
        $type = $dataNode->getAttribute('ss:Type');
        $value = trim($dataNode->nodeValue);
        echo "    Col $cellIndex $dataIndex $type [$value]\n";
      }
    }
  }
}
echo "Testing xml {$sheetNodes->length}\n";

$doc
?>
