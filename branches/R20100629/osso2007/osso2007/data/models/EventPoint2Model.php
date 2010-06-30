<?php
class EventPoint2Map extends BaseMap
{
    protected $map = array(
        'id'   => 'event_point2_id',
        'desc' => 'descx',
    );
}
class EventPoint2Table extends BaseTable
{
    protected $tblName = 'event_point2';
    protected $keyName = 'event_point2_id';
    
    protected $mapClassName = 'EventPoint2Map';
}
class EventPoint2Item extends BaseItem
{
    protected $mapClassName = 'EventPoint2Map';
}
class EventPoint2Model extends BaseModel
{
    protected   $mapClassName = 'EventPoint2Map';
    protected  $itemClassName = 'EventPoint2Item';
    protected $tableClassName = 'EventPoint2Table';
    
    const TYPE_NYP    = 1;
    const TYPE_PLAYED = 2;
    const TYPE_PPA    = 3;
    const TYPE_PNP    = 4;
    const TYPE_WNBP   = 5;
    
	protected $pickList = array(
        self::TYPE_NYP      => 'Not yet processed',
      //self::TYPE_PLAYED   => 'Has been played',
        self::TYPE_PPA      => 'Processed, points awarded',
        self::TYPE_PNP      => 'Processed, no points awarded',
      //self::TYPE_WNBP     => 'Will not be processed',
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
        $left = $right . '_event_point2';
        
        $select->joinLeft(
            "event_point2 AS {$left}",
            "{$left}.event_point2_id = {$right}.event_point2",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
