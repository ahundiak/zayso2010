<?php
class EventPersonMap extends PersonMap
{
    protected $map = array(
        'id'           => 'event_person_id',
        'eventId'      => 'event_id',
        'personId'     => 'person_id',
        'personTypeId' => 'event_person_type_id',
        'yearId'       => 'reg_year_id',
        'status'       => 'status',
    );
    protected $mapx = array(
        'personTypeKey'   => 'event_person_type_key',
        'personTypeDesc'  => 'event_person_type_desc',
        'personLastName'  => 'person_lname',
        'personFirstName' => 'person_fname',
        'personUnitId'    => 'person_unit_id',
    );
}
class EventPersonTable extends BaseTable
{
	protected $tblName = 'event_person';
	protected $keyName = 'event_person_id';
    
    protected $mapClassName = 'EventPersonMap';
    
    public function deleteForEventId($id)
    {
        return $this->db->delete($this->tblName,'event_id',$id);
    }    
}
class EventPersonItem extends BaseItem
{
    protected $mapClassName = 'EventPersonMap';
    
    public $event  = NULL;
    public $person = NULL;
}
class EventPersonModel extends BaseModel
{
    protected   $mapClassName = 'EventPersonMap';
    protected  $itemClassName = 'EventPersonItem';
    protected $tableClassName = 'EventPersonTable';
        
    function search($search)
    {   
        $select = new Proj_Db_Select($this->db);
        $models = $this->context->models;
        
        $alias  = 'event_person';
        
        $wantEvent  = FALSE;
        $wantPerson = FALSE;
        
        $this->fromAll($select,$alias);
        
        if ($search->wantx) {
            $models->EventPersonTypeModel->joinEventPersonTypeDesc($select,$alias);
            $models->PersonModel         ->joinPersonDesc         ($select,$alias);
        }
        if ($search->wantEvent) {
            $wantEvent = TRUE;
            $models->EventModel->joinAll($select,'eventx',$alias);
        }    
        if ($search->wantPerson) {
            $wantPerson = TRUE;
            $models->PersonModel->joinPersonName($select,'personx',$alias);
        }    
        if ($search->eventId)   $select->where("{$alias}.event_id             IN (?)",$search->eventId);
        if ($search->personId)  $select->where("{$alias}.person_id            IN (?)",$search->personId);
        if ($search->volTypeId) $select->where("{$alias}.event_person_type_id IN (?)",$search->volTypeId);
        
        $select->order(array(
            "{$alias}.event_id",
            "{$alias}.event_person_type_id",
            "{$alias}.person_id",
        ));
               
        $rows = $this->db->fetchAll($select);// Zend::dump($rows);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,$alias);
            if ($wantEvent) {
                $item->event = $models->EventModel->create($row,'eventx');
            }
            if ($wantPerson) {
                $item->person = $models->PersonModel->create($row,'personx');
            }
            $items[$item->id] = $item;
        }
        return $items; 
    }
    function searchDistinct($search)
    {
        $select = new Proj_Db_Select($this->db);
        $models = $this->context->models;
        
        $select->distinct();
        $select->from('event_person AS event_person','event_person.event_id AS id');
        $select->joinLeft('event AS event','event_person.event_id = event.event_id');
        
        if ($search->personId) $select->where('event_person.person_id IN (?)',$search->personId);
        if ($search->dateGE)   $select->where('event.event_date >= ?',        $search->dateGE);
        if ($search->dateLE)   $select->where('event.event_date <= ?',        $search->dateLE);
        
        $rows = $this->db->fetchAll($select); //Zend::dump($rows);
        $ids  = array();
        foreach($rows as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
    public function joinPersonForType($select,$alias,$right,$volTypeId)
    {        
        $select->joinLeft(
            "event_person AS $alias",
            "$alias.person_id = $right.person_id AND $alias.event_person_type_id = $volTypeId",
            $this->table->getAliasedColumnNames($alias)
        );
    }
    function deleteForEventId($id)
    {
        if (is_object($id)) $id = $id->id;
        return $this->table->deleteForEventId($id);
    }  
}
?>
