<?php
class EventPersonTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'event_team_type_id',
        'key'  => 'kayx',
        'desc' => 'descx',
    );
}
class EventPersonTypeTable extends BaseTable
{
    protected $tblName = 'event_person_type';
    protected $keyName = 'event_person_type_id';
    
    protected $mapClassName = 'EventPersonTypeMap';
    
}
class EventPersonTypeItem extends BaseItem
{
    protected $mapClassName = 'EventPersonTypeMap';
}
class EventPersonTypeModel extends BaseModel
{
    protected   $mapClassName = 'EventPersonTypeMap';
    protected  $itemClassName = 'EventPersonTypeItem';
    protected $tableClassName = 'EventPersonTypeTable';
    
    const TYPE_CR  = 10;
    const TYPE_AR1 = 11;
    const TYPE_AR2 = 12;
    
    const TYPE_4TH = 13;
    const TYPE_OBS = 14;
    const TYPE_MEN = 15;
    const TYPE_STB = 16;
    
    protected $pickList = array(
        self::TYPE_CR  => 'Center Referee',
        self::TYPE_AR1 => 'Assistant Referee 1',
        self::TYPE_AR2 => 'Assistant Referee 2',
        self::TYPE_4TH => '4th Official',
        self::TYPE_OBS => 'Observer',
        self::TYPE_MEN => 'Mentor',
        self::TYPE_STB => 'Stand By',
    );
    public function getPickList() { return $this->pickList; }
    
    public function __get($name)
    {
        $constName = "self::$name";
        
        if (defined($constName)) return constant($constName);
        
        return parent::__get($name);
    }
    
    function getDesc($typeId)
    {
        if (isset($this->pickList[$typeId])) return $this->pickList[$typeId];
        return NULL;
    }
    function getDescShort($typeId)
    {
        switch ($typeId) {
            case self::TYPE_CR:  return 'Center';
            case self::TYPE_AR1: return 'AR1';
            case self::TYPE_AR2: return 'AR2';
            case self::TYPE_4TH: return '4th';
            case self::TYPE_OBS: return 'Observer';
            case self::TYPE_MEN: return 'Mentor';
            case self::TYPE_STB: return 'Stand By';
        }
    }    
    function joinEventPersonTypeDesc($select,$right)
    {
        $left = $right . '_event_person_type';
        
        $select->joinLeft(
            "event_person_type AS {$left}",
            "{$left}.event_person_type_id = {$right}.event_person_type_id",
            array(
                "{$left}.keyx  AS {$left}_key",
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
