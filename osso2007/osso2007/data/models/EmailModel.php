<?php
class EmailMap extends BaseMap
{
    protected $map = array(
        'id'          => 'email_id',
        'emailTypeId' => 'email_type_id',
        'personId'    => 'person_id',
        'address'     => 'address',
    );
    protected $mapx = array(
        'emailTypeDesc' => 'email_type_desc',
    );
}
class EmailTable extends BaseTable
{
    protected $tblName = 'email';
    protected $keyName = 'email_id';
    
    protected $mapClassName = 'EmailMap';
}
class EmailItem extends BaseItem
{
    protected $mapClassName = 'EmailMap';
}
class EmailModel extends BaseModel
{
    protected   $mapClassName = 'EmailMap';
    protected  $itemClassName = 'EmailItem';
    protected $tableClassName = 'EmailTable';
    
    public function search($search)
    {
        $select = new Proj_Db_Select($this->db);
        
        $this->fromAll($select,'email');
        
        if ($search->wantx) {
            $this->context->models->EmailTypeModel->joinEmailTypeDesc($select,'email');
        }
        if ($search->emailId)  $select->where('email.email_id  IN (?)',$search->emailId);
        if ($search->personId) $select->where('email.person_id IN (?)',$search->personId);
        
        $select->order('email.email_type_id');
        
        $rows  = $this->db->fetchAll($select);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,'email');
            $items[$item->id] = $item;
        }
        return $items;
    }
    function joinEmailPersonForType($select,$alias,$right,$typeId)
    {        
        $select->joinLeft(
            "email AS $alias",
            "$alias.person_id = $right.person_id AND $alias.email_type_id = $typeId",
            $this->table->getAliasedColumnNames($alias)
        );
    }
}

?>
