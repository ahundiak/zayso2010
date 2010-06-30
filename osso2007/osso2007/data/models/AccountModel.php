<?php
class AccountMap extends BaseMap
{
    protected $map = array(
        'id'        => 'account_id',
        'user'      => 'account_user',
        'pass'      => 'account_pass',
        'name'      => 'account_name',
        'email'     => 'email',
        'status'    => 'status',
    );
}
class AccountTable extends BaseTable
{
    protected $tblName = 'account';
    protected $keyName = 'account_id';

    protected $mapClassName = 'AccountMap';
    
}
class AccountItem extends BaseItem
{   
    protected $mapClassName = 'AccountMap';
    protected $members = array();
    
    public function __get($name)
    {
        switch($name) 
        {
            case 'userName': return $this->user;
            case 'lastName': return $this->name;
        }
        return parent::__get($name);
    }
}
class AccountModel extends BaseModel
{
    protected   $mapClassName = 'AccountMap';
    protected  $itemClassName = 'AccountItem';    
    protected $tableClassName = 'AccountTable';
    
    function search($search)
    {
        $select = new Proj_Db_Select($this->db);
        
        $alias = 'account';
        
        $this->fromAll($select,$alias);
        
        if ($search->accountId) $select->where("{$alias}.account_id IN (?)",$search->accountId);

        if ($search->searchLike) {        
            if ($search->user)      $select->where("{$alias}.account_user  LIKE ?",$search->user  . '%');
            if ($search->name)      $select->where("{$alias}.account_name  LIKE ?",$search->name  . '%');
            if ($search->email)     $select->where("{$alias}.email         LIKE ?",$search->email . '%');
        }
        else {
            if ($search->user)      $select->where("{$alias}.account_user  = ?",$search->user);
            if ($search->name)      $select->where("{$alias}.account_name  = ?",$search->name);
            if ($search->email)     $select->where("{$alias}.email         = ?",$search->email);
        }
        if ($search->pass)      $select->where("{$alias}.account_pass  = ?",$search->pass);
        if ($search->status)    $select->where("{$alias}.status     IN (?)",$search->status);
        
        $select->order("{$alias}.account_user");
        
        $rows = $this->db->fetchAll($select);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,$alias);
            $items[$item->id] = $item;
        }
        return $items;
    }
    function joinAccount($select,$alias,$right)
    {        
        $select->joinLeft(
            "account AS $alias",
            "$alias.account_id = $right.account_id",
            $this->table->getAliasedColumnNames($alias)
        );
    }    
    function delete($id)
    {
        $this->context->models->MemberModel->deleteForAccountId($id);
        return parent::delete($id);
    }   
}
?>
