<?php
class UnitTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'unit_type_id',
        'desc' => 'descx',
    );
}
class UnitTypeTable extends BaseTable
{
    protected $tblName  = 'unit_type';
    protected $keyName  = 'unit_type_id';
    
    protected $mapClassName = 'UnitTypeMap';
}
class UnitTypeItem extends BaseItem
{
    protected $mapClassName = 'UnitTypeMap';
}
class UnitTypeModel extends BaseModel
{
    protected   $mapClassName = 'UnitTypeMap';
    protected  $itemClassName = 'UnitTypeItem';
    protected $tableClassName = 'UnitTypeTable';
    
    const TYPE_AYSO_NATIONAL = 1;
    const TYPE_AYSO_SECTION  = 2;
    const TYPE_AYSO_AREA     = 3;
    const TYPE_AYSO_REGION   = 4;
    const TYPE_SPORTS_CLUB   = 11;
    const TYPE_SPORTS_LEAGUE = 12;
    const TYPE_SCHOOL        = 21;
    
    protected $pickList = array(
        self::TYPE_AYSO_NATIONAL => 'AYSO National',
        self::TYPE_AYSO_SECTION  => 'AYSO Section',
        self::TYPE_AYSO_AREA     => 'AYSO Area',
        self::TYPE_AYSO_REGION   => 'AYSO Region',
        self::TYPE_SPORTS_CLUB   => 'Sports Club',
        self::TYPE_SPORTS_LEAGUE => 'Sports League',
        self::TYPE_SCHOOL        => 'School', 		  
    );
    public function getPickList() { return $this->pickList; }
    
    function getDesc($typeId)
    {
        if (isset($this->pickList[$typeId])) return $this->pickList[$typeId];
        return NULL;
    }
    function joinUnitTypeDesc($select,$right,$rightKey='unit_type_id')
    {
        $left = $right . '_unit_type';
        
        $select->joinLeft(
            "unit_type AS {$left}",
            "{$left}.unit_type_id = {$right}.{$rightKey}",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
