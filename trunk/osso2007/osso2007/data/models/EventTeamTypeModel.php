<?php
class EventTeamTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'event_team_type_id',
        'desc' => 'descx',
    );
}
class EventTeamTypeTable extends BaseTable
{
    protected $tblName = 'event_team_type';
    protected $keyName = 'event_team_type_id';
    
    protected $mapClassName = 'EventTeamTypeMap';
    
}
class EventTeamTypeItem extends BaseItem
{
    protected $mapClassName = 'EventTeamTypeMap';
}
class EventTeamTypeModel extends BaseModel
{
    protected   $mapClassName = 'EventTeamTypeMap';
    protected  $itemClassName = 'EventTeamTypeItem';
    protected $tableClassName = 'EventTeamTypeTable';
    
    const TYPE_HOME  = 1;
    const TYPE_AWAY  = 2;
    const TYPE_AWAY3 = 3;
    const TYPE_AWAY4 = 4;
    
    protected $pickList = array(
        self::TYPE_HOME  => 'Home',
        self::TYPE_AWAY  => 'Away',
        self::TYPE_AWAY3 => 'Away #3',
        self::TYPE_AWAY4 => 'Away #4',
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
    function joinEventTeamTypeDesc($select,$right)
    {
        $left = $right . '_event_team_type';
        
        $select->joinLeft(
            "event_team_type AS {$left}",
            "{$left}.event_team_type_id = {$right}.event_team_type_id",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
    function getEventTeamTypeIds($home = FALSE, $away = FALSE)
    {
        $ids = array();
        
        if ($home) $ids[] = self::TYPE_HOME;
        
        if ($away) {
            $ids[] = self::TYPE_AWAY;
            $ids[] = self::TYPE_AWAY3;
            $ids[] = self::TYPE_AWAY4;
        }
        if (count($ids) < 1) return NULL;
        return $ids;
    }
}
?>
