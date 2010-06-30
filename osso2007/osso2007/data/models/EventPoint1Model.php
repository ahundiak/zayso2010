<?php
class EventPoint1Map extends BaseMap
{
    protected $map = array(
        'id'   => 'event_point1_id',
        'desc' => 'descx',
    );
}
class EventPoint1Table extends BaseTable
{
    protected $tblName = 'event_point1';
    protected $keyName = 'event_point1_id';
    
    protected $mapClassName = 'EventPoint1Map';
}
class EventPoint1Item extends BaseItem
{
    protected $mapClassName = 'EventPoint1Map';
}
class EventPoint1Model extends BaseModel
{
    protected   $mapClassName = 'EventPoint1Map';
    protected  $itemClassName = 'EventPoint1Item';
    protected $tableClassName = 'EventPoint1Table';
    
    const TYPE_YES = 1;
    const TYPE_NO  = 2;
    
	protected $pickList = array(
        self::TYPE_YES      => 'Yes',
        self::TYPE_NO       => 'No',
    );
    public function getPickList() { return $this->pickList; }
    
    function getDesc($typeId)
    {
        if (isset($this->pickList[$typeId])) return $this->pickList[$typeId];
        return NULL;
    }
    public function __get($name)
    {
        $constName = "self::$name";
        
        if (defined($constName)) return constant($constName);
        
        return parent::__get($name);
    }
    function joinEventPoint1Desc($select,$right)
    {
        $left = $right . '_event_point1';
        
        $select->joinLeft(
            "event_point1 AS {$left}",
            "{$left}.event_point1_id = {$right}.event_point1",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
