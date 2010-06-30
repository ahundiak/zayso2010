<?php
class MemberMap extends BaseMap
{
    protected $map = array(
        'id'        => 'member_id',
        'accountId' => 'account_id',
        'personId'  => 'person_id',
        'unitId'    => 'unit_id',
        'name'      => 'member_name',
        'pass'      => 'member_pass',
        'level'     => 'level',
        'status'    => 'status',
    );
    protected $mapx = array(
        'unitDesc' => 'unit_desc',
        'personFirstName' => 'person_fname',
        'personLastName'  => 'person_lname',
    );
}
class MemberTable extends BaseTable
{
    protected $tblName = 'member';
    protected $keyName = 'member_id';
    
    protected $mapClassName = 'MemberMap';

    public function deleteForAccountId($id)
    {
        return $this->db->delete($this->tblName,'account_id',$id);
    }    
}
class MemberItem extends BaseItem
{
    protected $mapClassName = 'MemberMap';
    
    protected $account = NULL;
    
    public function __get($name)
    {
        switch($name) 
        {
            case 'firstName': return $this->name;
            
            case 'personName':
              $name = $this->personFirstName;
              if ($name) $name .= ' ';
              $name .= $this->personLastName;
              return $name;
              
            case 'account': return $this->account;
        }
        return parent::__get($name);
    }
    public function addAccount($account)
    {
        $this->account = $account;
    }
}
class MemberModel extends BaseModel
{
    protected   $mapClassName = 'MemberMap';
    protected  $itemClassName = 'MemberItem';
    protected $tableClassName = 'MemberTable';
    
    function search($search)
    {
        $select = new Proj_Db_Select($this->db);
        
        $alias = 'member';
        
        $this->fromAll($select,$alias);

        $models = $this->context->models;
        
        if ($search->wantx) {
            $models->UnitModel->joinUnitDesc($select,$alias);
        }
        if ($search->wantPerson) {
            $models->PersonModel->joinPersonDesc($select,$alias);
        }
        if ($search->wantAccount) {
            $models->AccountModel->joinAccount($select,'memberaccount','member');
        }
        if ($search->memberId)  $select->where("{$alias}.member_id  IN (?)",$search->memberId);
        if ($search->accountId) $select->where("{$alias}.account_id IN (?)",$search->accountId);
        if ($search->personId)  $select->where("{$alias}.person_id  IN (?)",$search->personId);
        if ($search->name)      $select->where("{$alias}.member_name =  ?", $search->name);
        if ($search->level)     $select->where("{$alias}.level       =  ?", $search->level);
        if ($search->status)    $select->where("{$alias}.status     IN (?)",$search->status);
        
        $select->order("{$alias}.account_id,{$alias}.member_name");
        
        $rows = $this->db->fetchAll($select);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,$alias);
            if ($search->wantAccount) {
                $item->addAccount($models->AccountModel->create($row,'memberaccount'));
            }
            $items[$item->id] = $item; 
        }
        return $items;
    }
    function deleteForAccountId($id)
    {
        if (is_object($id)) $id = $id->id;
        return $this->table->deleteForAccountId($id);
    }  
}
?>
