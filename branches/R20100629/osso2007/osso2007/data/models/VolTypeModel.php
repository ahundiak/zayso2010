<?php
class VolTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'vol_type_id',
        'sort' => 'sortx',
        'key'  => 'keyx',
        'desc' => 'descx',
    );
}
class VolTypeTable extends BaseTable
{
    protected $tblName = 'vol_type';
    protected $keyName = 'vol_type_id';
    
    protected $mapClassName = 'VolTypeMap';
}
class VolTypeItem extends BaseItem
{
    protected $mapClassName = 'VolTypeMap';
}
class VolTypeModel extends BaseModel
{
    protected   $mapClassName = 'VolTypeMap';
    protected  $itemClassName = 'VolTypeItem';
    protected $tableClassName = 'VolTypeTable';
    
    const TYPE_DIV_COORD   = 31;
    const TYPE_HEAD_COACH  = 16;
    const TYPE_ASST_COACH  = 17;
    const TYPE_TEAM_PARENT = 18;
    const TYPE_ADULT_REF   = 19;
    const TYPE_YOUTH_REF   = 20;
    const TYPE_GAME_SCHEDULER    = 34;
    const TYPE_REFEREE_SCHEDULER = 35;
    const TYPE_ZADM        = 27;
    
    
    protected $pickList = array(
        self::TYPE_HEAD_COACH  => 'Head Coach',
        self::TYPE_ASST_COACH  => 'Asst Coach',
        self::TYPE_TEAM_PARENT => 'Team Parent',
        self::TYPE_DIV_COORD   => 'Div Coordinator',
        self::TYPE_ADULT_REF   => 'Adult Referee',
        self::TYPE_YOUTH_REF   => 'Youth Referee',
        self::TYPE_GAME_SCHEDULER    => 'Game Scheduler',
        self::TYPE_REFEREE_SCHEDULER => 'Referee Scheduler',
        self::TYPE_ZADM              => 'Zayso Administrator',
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
    function joinVolTypeDesc($select,$right,$rightKey='vol_type_id')
    {
        $left = $right . '_vol_type';
        
        $select->joinLeft(
            "vol_type AS {$left}",
            "{$left}.vol_type_id = {$right}.{$rightKey}",
            array(
                "{$left}.keyx  AS {$left}_key",
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
