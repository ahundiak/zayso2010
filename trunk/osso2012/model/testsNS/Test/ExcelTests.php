<?php

namespace Test;

use \Cerad\Debug;

class ExcelTests extends BaseTests
{
  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();

    // include 'PHPExcel.php';


    return;
  }
  function testReader()
  {
    $inputFileName = 'C:\home\ahundiak\datax\arbiter\AHSAA\Week09\SchedWeek09a.xls';

    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);

    $this->assertEquals('Excel5',$inputFileType);

    $reader = \PHPExcel_IOFactory::createReader($inputFileType);
    $reader->setReadDataOnly(true);

    // Test sheet names
    // setLoadSheetsOnly
    $sheetNames = $reader->listWorksheetNames($inputFileName);
    $this->assertEquals(1,count($sheetNames));
    $this->assertEquals('Sheet1',$sheetNames[0]);
    
    // Load it
    $excel  = $reader->load($inputFileName);

    // Sheets
    $count = $excel->getSheetCount();
    $this->assertEquals(1,$count);

    $sheet = $excel->getSheet(0); // getAllSheets
    $title = $sheet->getTitle();
    $this->assertEquals('Sheet1',$title);

    $maxRow = $sheet->getHighestRow();
    $maxCol = $sheet->getHighestColumn();
    $this->assertEquals('O',$maxCol); // ??? Letter O
    $this->assertEquals(34, $maxRow);

    // Actual dimension/formatting object
    // $rowDims = $sheet->getRowDimension(1);
    // $this->assertEquals(5,$rowDims);

    $rows = $sheet->getRowIterator();
    // $this->assertEquals(10,count($rows));
    foreach($rows as $row)
    {
      $cells = $row->getCellIterator();
     // $this->assertEquals(10,count($cells));
     foreach($cells as $cell)
     {
      $value = $cell->getValue();
      //$this->assertEquals('Game',$value);
      //die();
     }
      // Debug::dump($row); die();
    }
  }
}
?>
