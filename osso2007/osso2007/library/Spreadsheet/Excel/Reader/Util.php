<?php

require_once 'Spreadsheet/Excel/Reader.php';

class ExcelReaderIndexedRow {}

class ExcelReaderUtil
{
	static function loadFile($fileName)
	{
		$xlr = new Spreadsheet_Excel_Reader();
		$xlr->read($fileName);
		
		return $xlr;
	}
	static function getSheetNames($xlr)
	{
		$names = array();
    	foreach($xlr->sheets as $key => $sheet) {
   			$names[] = $xlr->boundsheets[$key]['name'];
    	}
		return $names;
	}
	static function getSheetCells($file,$sheetName)
	{
		if (is_object($file)) $xlr = $file;
		else                  $xlr = self::loadFile($file);
		
    	foreach($xlr->sheets as $key => $sheet) {
   			if ($xlr->boundsheets[$key]['name'] == $sheetName) return $sheet['cells'];
    	}
    	throw new Exception("Worksheet $sheetName not found");
	}
	static function getIndexes($row,$map)
	{
		$indexes = array();
		foreach($row as $offset => $column) {
			$column = trim(strtolower($column));
			
			foreach($map as $key => $names) {
				foreach($names as $name) {
					if ($column == $name) {
						$indexes[$key] = $offset;
					}
				}
			}
		}
		return $indexes;
	}
	static function getIndexedRow($data,$indexes)
	{
		$row = new 	ExcelReaderIndexedRow();
		foreach($indexes as $key => $index) {
			
			if (isset($data[$index])) $value = $data[$index];
			else                      $value = NULL;
			
			$row->$key = $value;
		}
		return $row;
	}
	static function getCellValue($data,$index,$default = NULL)
	{
		if (isset($data[$index])) return $data[$index];
		return $default;
	}	
}
?>
