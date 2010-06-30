<?php
class EmailTypeMap extends BaseMap
{
    protected $map = array(
        'id'   => 'email_type_id',
        'desc' => 'descx',
    );
}
class EmailTypeTable extends BaseTable
{
    protected $tblName = 'email_type';
    protected $keyName = 'email_type_id';
    
    protected $mapClassName = 'EmailTypeMap';
}
class EmailTypeItem extends BaseItem
{
    protected $mapClassName = 'EmailTypeMap';
}
class EmailTypeModel extends BaseModel
{
    protected   $mapClassName = 'EmailTypeMap';
    protected  $itemClassName = 'EmailTypeItem';
    protected $tableClassName = 'EmailTypeTable';
    
    const TYPE_HOME = 1;
    const TYPE_WORK = 2;
    
    protected $pickList = array(
        self::TYPE_HOME  => 'Home',
	    self::TYPE_WORK  => 'Work',
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
    function joinEmailTypeDesc($select,$right)
    {
        $left = $right . '_email_type';
        
        $select->joinLeft(
            "email_type AS {$left}",
            "{$left}.email_type_id = {$right}.email_type_id",
            array(
                "{$left}.descx AS {$left}_desc",
            )
        );
    }
}
?>
