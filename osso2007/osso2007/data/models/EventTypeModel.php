<?php
class EventTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'event_type_id',
        'desc' => 'descx',
    );
}
class EventTypeTable extends BaseTable
{
    protected $tblName = 'event_type';
    protected $keyName = 'event_type_id';
    
    protected $mapClassName = 'EventTypeMap';
    
}
class EventTypeItem extends BaseItem
{
    protected $mapClassName = 'EventTypeMap';
}
class EventTypeModel extends BaseModel
{
    protected   $mapClassName = 'EventTypeMap';
    protected  $itemClassName = 'EventTypeItem';
    protected $tableClassName = 'EventTypeTable';
    
    const TYPE_GAME      = 1;
    const TYPE_PRACTICE  = 2;
    const TYPE_SCRIMMAGE = 3;
    const TYPE_JAMBOREE  = 4;
    const TYPE_UPS       = 5;
    const TYPE_TRYOUTS   = 6;
    const TYPE_OTHER     = 9;
    
	protected $pickList = array(
        self::TYPE_GAME      => 'Game',
        self::TYPE_PRACTICE  => 'Practice',
        self::TYPE_SCRIMMAGE => 'Scrimmage',
        self::TYPE_JAMBOREE  => 'Jamboree',
        self::TYPE_UPS       => 'Unifieds',
        self::TYPE_TRYOUTS   => 'Tryouts',
        self::TYPE_OTHER     => 'Other',
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
    function joinEventTypeDesc($select,$right)
    {
        $left = $right . '_event_type';
        
        $select->joinLeft(
            "event_type AS {$left}",
            "{$left}.event_type_id = {$right}.event_type_id",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
