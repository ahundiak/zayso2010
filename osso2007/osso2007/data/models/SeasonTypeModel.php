<?php
class SeasonTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'season_type_id',
        'desc' => 'descx',
    );
}
class SeasonTypeTable extends BaseTable
{
    protected $tblName  = 'season_type';
    protected $keyName  = 'season_type_id';
    
    protected $mapClassName = 'SeasonTypeMap'; 
}
class SeasonTypeItem extends BaseItem
{
    protected $mapClassName = 'SeasonTypeMap'; 
}
class SeasonTypeModel extends BaseModel
{
    protected   $mapClassName = 'SeasonTypeMap'; 
    protected  $itemClassName = 'SeasonTypeItem';
    protected $tableClassName = 'SeasonTypeTable';
    
    const TYPE_FALL   = 1;
    const TYPE_WINTER = 2;
    const TYPE_SPRING = 3;
    const TYPE_SUMMER = 4;

    protected $pickList = array(
        self::TYPE_FALL   => 'Fall',
        self::TYPE_WINTER => 'Winter',
        self::TYPE_SPRING => 'Spring',
        self::TYPE_SUMMER => 'Summer',
    );
    function getPickList() { return $this->pickList; }
    
    public function getId($desc) {
    	return array_search($desc,$this->pickList);
    }
    function getDesc($typeId)
    {
        if (isset($this->pickList[$typeId])) return $this->pickList[$typeId];
        return NULL;
    }
    function joinSeasonTypeDesc($select,$right)
    {
        $left = $right . '_season_type';
        
        $select->joinLeft(
            "season_type AS {$left}",
            "{$left}.season_type_id = {$right}.season_type_id",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    } 
}
?>
