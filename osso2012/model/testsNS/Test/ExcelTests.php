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
  public function testS5Games()
  {
    $inputFileName = 'C:\home\ahundiak\datax\s5games\Schedule20110610.xls';

    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);

    $this->assertEquals('Excel5',$inputFileType);

    $reader = \PHPExcel_IOFactory::createReader($inputFileType);
    $reader->setReadDataOnly(true);

    // Test sheet names
    // setLoadSheetsOnly
    $sheetNames = $reader->listWorksheetNames($inputFileName);
    $this->assertEquals(6,count($sheetNames));
    $this->assertEquals('Team list',$sheetNames[0]);

    // Load it
    $excel = $reader->load($inputFileName);

    // Sheets
    $count = $excel->getSheetCount();
    $this->assertEquals(6,$count);

    $sheet = $excel->getSheet(4); // getAllSheets
    $title = $sheet->getTitle();
    // $this->assertEquals('Team list',$title);

    $rows = $sheet->getRowIterator();

    $rowCount = 0;
    foreach($rows as $row)
    {
      $rowCount++;
      echo 'Row ' . $rowCount . "\n";
      $cells = $row->getCellIterator();

      foreach($cells as $cell)
      {
        $value = $cell->getValue();
        echo 'Row ' . $cell->getRow() . ' Cell ' . $cell->getColumn() . ' ' . $value . "\n";
      }
      // Debug::dump($row); die();
    }

  }
}
?>
