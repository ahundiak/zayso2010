<?php
class PhoneTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'phone_type_id',
        'desc' => 'descx',
    );
}
class PhoneTypeTable extends BaseTable
{
    protected $tblName  = 'phone_type';
    protected $keyName  = 'phone_type_id';
    
    protected $mapClassName = 'PhoneTypeMap';
}
class PhoneTypeItem extends BaseItem
{
    protected $mapClassName = 'PhoneTypeMap';
}
class PhoneTypeModel extends BaseModel
{
    protected   $mapClassName = 'PhoneTypeMap';
    protected  $itemClassName = 'PhoneTypeItem';
    protected $tableClassName = 'PhoneTypeTable';

    const TYPE_HOME  = 1;
    const TYPE_WORK  = 2;
    const TYPE_CELL  = 3;
    const TYPE_PAGER = 4;
    const TYPE_FAX   = 5;
    
    protected $pickList = array(
        self::TYPE_HOME  => 'Home',
        self::TYPE_WORK  => 'Work',
        self::TYPE_CELL  => 'Cell',
        self::TYPE_PAGER => 'Pager',
        self::TYPE_FAX   => 'FAX',
    );
    function getPickList() { return $this->pickList; }
    
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
    function joinPhoneTypeDesc($select,$right)
    {
        $left = $right . '_phone_type';
        
        $select->joinLeft(
            "phone_type AS {$left}",
            "{$left}.phone_type_id = {$right}.phone_type_id",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    } 
}
?>
