<?php
class ScheduleTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'schedule_type_id',
        'key'  => 'keyx',
        'desc' => 'descx',
    );
}
class ScheduleTypeTable extends BaseTable
{
    protected $tblName = 'schedule_type';
    protected $keyName = 'schedule_type_id';
    
    protected $mapClassName = 'ScheduleTypeMap';  
}
class ScheduleTypeItem extends BaseItem
{
    protected $mapClassName = 'ScheduleTypeMap';
}
class ScheduleTypeModel extends BaseModel
{
    protected   $mapClassName = 'ScheduleTypeMap';
    protected  $itemClassName = 'ScheduleTypeItem';
    protected $tableClassName = 'ScheduleTypeTable';

    const TYPE_REGULAR_SEASON    = 1;
    const TYPE_TOURNAMENT_REGION = 2;
    const TYPE_TOURNAMENT_AREA   = 3;
    const TYPE_TOURNAMENT_STATE  = 4;
    
    protected $pickList = array(
        self::TYPE_REGULAR_SEASON    => 'Regular Season',
        self::TYPE_TOURNAMENT_REGION => 'Tourn-Region',
        self::TYPE_TOURNAMENT_AREA   => 'Tourn-Area',
        self::TYPE_TOURNAMENT_STATE  => 'Tourn-State',
    );
    public function getPickList() { return $this->pickList; }
    
    function getDesc($typeId)
    {
        if (isset($this->pickList[$typeId])) return $this->pickList[$typeId];
        return NULL;
    }
    function joinScheduleTypeDesc($select,$right)
    {
        $left = $right . '_schedule_type';
        
        $select->joinLeft(
            "schedule_type AS {$left}",
            "{$left}.schedule_type_id = {$right}.schedule_type_id",
            array(
                "{$left}.keyx  AS {$left}_key",
                "{$left}.descx AS {$left}_desc",
            )
        );
    }   
}
?>
