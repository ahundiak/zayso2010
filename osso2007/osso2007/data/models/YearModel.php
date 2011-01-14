<?php
class YearMap extends BaseMap
{
    protected $map = array(
        'id'    => 'reg_year_id',
        'desc'  => 'descx',
    );
}
class YearTable extends BaseTable
{
    protected $tblName = 'reg_year';
    protected $keyName = 'reg_year_id';
    
    protected $mapClassName = 'YearMap';
}
class YearItem extends BaseItem
{
    protected $mapClassName = 'YearMap';
}
class YearModel extends BaseModel
{
  protected   $mapClassName = 'YearMap';
  protected  $itemClassName = 'YearItem';
  protected $tableClassName = 'YearTable';
    
  protected $pickList = array
  (
    '11' => '2011',
    '10' => '2010',
     '9' => '2009', '8' => '2008', '7' => '2007', '6' => '2006',
     '5' => '2005', '4' => '2004', '3' => '2003', '2' => '2002', '1' => '2001',
    );
    public function getPickList() { return $this->pickList; }
    
    public function getId($desc) {
    	return array_search($desc,$this->pickList);
    }
    function getDesc($typeId)
    {
        if (isset($this->pickList[$typeId])) return $this->pickList[$typeId];
        return NULL;
    }
    function joinYearDesc($select,$right,$rightKey='reg_year_id')
    {
        $left = $right . '_year';
        
        $select->joinLeft(
            "reg_year AS {$left}",
            "{$left}.reg_year_id = {$right}.reg_year_id",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
