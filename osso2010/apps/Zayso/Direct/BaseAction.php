<?php
class Direct_BaseAction
{
	protected $context = NULL;
	
	static $indexRecords    = 'records';
	static $indexTotalCount = 'totalCount';
	static $indexSuccess    = 'success';
	
	function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}
  
  protected function wrapResults($rows = array(),$success = true)
  {
  	switch(count($rows))
  	{
  		case 0:
  			$total = 0;
  			$records = null;
  			break;
  		case 1:
  			$total = 1;
  			$records = $rows[0];
  			break;
  		default;
  		  $total = count($rows);
  		  $records = $rows;
  	}
    $total = count($rows);
    $records = $rows;
  	return array
    (
      self::$indexSuccess    => $success,
      self::$indexTotalCount => $total,
      self::$indexRecords    => $records
    );
  }
}