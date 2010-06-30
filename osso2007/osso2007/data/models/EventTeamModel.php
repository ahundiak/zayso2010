<?php
class EventTeamMap extends BaseMap
{
    protected $map = array(
        'id'              => 'event_team_id',
        'eventId'         => 'event_id',
        'schTeamId'       => 'team_id',
        'eventTeamTypeId' => 'event_team_type_id',
        'yearId'          => 'reg_year_id',
        'unitId'          => 'unit_id',
        'divisionId'      => 'division_id',
        'score'           => 'score',
    );
    protected $mapx = array(
        'year'              => 'year_desc',
        'unitKey'           => 'unit_key',
        'unitDesc'          => 'unit_desc',
        'divisionDesc'      => 'division_desc',
        'eventTeamTypeDesc' => 'event_team_type_desc',
    );
}
class EventTeamTable extends BaseTable
{
	protected $tblName = 'event_team';
	protected $keyName = 'event_team_id';
    
    protected $mapClassName = 'EventTeamMap';
    
    public function deleteForEventId($id)
    {
        return $this->db->delete($this->tblName,'event_id',$id);
    }    
}
class EventTeamItem extends BaseItem
{
    protected $mapClassName = 'EventTeamMap';
    
    protected $rels = array();
    
    public function __get($name)
    {
        switch($name) 
        {   
            case 'key':       return $this->getTeamKey();
            case 'schedDesc': return $this->getSchedDesc();
            
            case 'event':
            case 'schTeam':
            case 'phyTeam':
            case 'coachHead':
                if (isset($this->rels[$name])) return $this->rels[$name];
                return NULL;
        }
        return parent::__get($name);;
    }
    public function __set($name,$value)
    {
        switch($name) 
        {   
            case 'event':
            case 'schTeam':
            case 'phyTeam':
            case 'coachHead':
                $this->rels[$name] = $value;
                return;
        }
        return parent::__set($name,$value);;
    }
    protected function getTeamKey()
    {
        // Use physical team if have one
        $phyTeam = $this->phyTeam;
        if ($phyTeam && $phyTeam->id) return $phyTeam->key;
        
        // Use schedule team if have one
        $schTeam = $this->schTeam;
        if ($schTeam && $schTeam->id) return $schTeam->key;
        
        // Build from what we know
        $unit = $this->unitKey;
        $div  = $this->divisionDesc;
          
        if ((!$unit) || (!$div)) return NULL;

        $key = $unit . '-' . $div;
        
        return $key;
    }
    protected function getSchedDesc()
    {
        if (isset($this->data['schedDesc'])) return $this->data['schedDesc'];
        
        $key = NULL;
        if (!$key) $key = $this->phyTeam->key;
        if (!$key) $key = $this->schTeam->key;
        if (!$key) $key = $this->key;
        
        if (!$this->schTeam->phyTeamId) {
            $suffix = $this->schTeam->desc;
        }
        else $suffix = $this->coachHead->name;
        
        $desc = $key . ' ' . $suffix;
        
        $this->data['schedDesc'] = $desc;
        
        return $desc;
    }
}
class EventTeamModel extends BaseModel
{
    protected   $mapClassName = 'EventTeamMap';
    protected  $itemClassName = 'EventTeamItem';
    protected $tableClassName = 'EventTeamTable';
    
    function __get($name)
    {
        switch($name) {
            case 'typeHome':  return EventTeamTypeModel::TYPE_HOME;
            case 'typeAway':  return EventTeamTypeModel::TYPE_AWAY;
            case 'typeAway3': return EventTeamTypeModel::TYPE_AWAY3;
            case 'typeAway4': return EventTeamTypeModel::TYPE_AWAY4;
        }
        return parent::__get($name);
    }
    function search($search)
    {   
        $select = new Proj_Db_Select($this->db);
        $models = $this->context->models;
        
        $alias      = 'event_team';
        $aliasEvent = 'eventx';
        
        $wantEvent = FALSE;
        $wantTeam  = FALSE;
        $wantCoachHead = FALSE;
        
        $models->EventTeamModel->fromAll($select,$alias);
        
        if ($search->wantx) $this->joinWantx($select,$alias);
        
        if ($search->wantEvent) {
            $wantEvent = TRUE;
            $eventModel = $models->EventModel;
            $eventModel->joinAll  ($select,$aliasEvent,$alias);
            $eventModel->joinWantx($select,$aliasEvent,$alias);
        }    
        if ($search->wantTeam) {
            $wantTeam = TRUE;
            $schTeamModel = $models->SchTeamModel;
            $phyTeamModel = $models->PhyTeamModel;
            $schTeamModel->joinAll  ($select,'schteam',$alias,'team_id');
            $phyTeamModel->joinAll  ($select,'phyteam','schteam');
            $phyTeamModel->joinWantx($select,'phyteam');
        }
        if ($search->wantCoachHead) {
            $wantCoachHead = TRUE;
            $phyTeamPersonModel = $models->PhyTeamPersonModel;
            $personModel        = $models->PersonModel;
            $unitModel          = $models->UnitModel;
            $phyTeamPersonModel->joinPhyTeamForType($select,'phyteampersoncoachhead','phyteam',VolTypeModel::TYPE_HEAD_COACH);
                   $personModel->joinAll           ($select,'personcoachhead','phyteampersoncoachhead');
                     $unitModel->joinUnitDesc      ($select,'personcoachhead');
            
        }
        if ($search->eventId)         $select->where("{$alias}.event_id           IN (?)",$search->eventId);
        if ($search->eventTeamTypeId) $select->where("{$alias}.event_team_type_id IN (?)",$search->eventteamTypeId);
        if ($search->schTeamId)       $select->where("{$alias}.team_id            IN (?)",$search->schTeamId);
        if ($search->yearId)          $select->where("{$alias}.reg_year_id        IN (?)",$search->yearId);
        if ($search->unitId)          $select->where("{$alias}.unit_id            IN (?)",$search->unitId);
        if ($search->divisionId)      $select->where("{$alias}.division_id        IN (?)",$search->divisionId);

        if ($search->dateGE) $select->where('eventx.event_date >= ?',$search->dateGE);
        if ($search->dateLE) $select->where('eventx.event_date <= ?',$search->dateLE);
        
        $ordered = FALSE;
        if ($search->orderByEventDateTimeField) {
            $ordered = TRUE;
            $select->order(array(
                "{$aliasEvent}.event_date",
                "{$aliasEvent}.event_time",
                "{$aliasEvent}.fieldSiteId",
                "{$aliasEvent}.field_id",
            ));
        }            
        if (!$ordered) {
            $select->order(array(
                "{$alias}.event_id",
                "{$alias}.event_team_type_id",
            ));
        }
          
        $rows  = $this->db->fetchAll($select);// Zend::dump($rows);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,$alias);
            if ($wantEvent) {
                $item->event = $eventModel->create($row,$aliasEvent);
            }
            if ($wantTeam) {
                $item->schTeam = $schTeamModel->create($row,'schteam');
                
                $item->phyTeam = $phyTeamModel->create($row,'phyteam');
            }
            if ($wantCoachHead) {
                $item->coachHead = $personModel->create($row,'personcoachhead');
            }
            $items[$item->id] = $item;
        }
        return $items; 
    }
    function searchDistinct($search)
    {
        $select = new Proj_Db_Select($this->db);
        
        $select->distinct();
        
        $select->from('event_team AS event_team','event_team.event_id AS id');
        
        $select->joinLeft('event AS event','event.event_id = event_team.event_id');
        
        if ($search->dateGE)          $select->where('event.event_date >= ?',               $search->dateGE);
        if ($search->dateLE)          $select->where('event.event_date <= ?',               $search->dateLE);
        if ($search->fieldId)         $select->where('event.field_id                IN (?)',$search->fieldId); 
        if ($search->eventTypeId)     $select->where('event.event_type_id           IN (?)',$search->eventTypeId); 
        if ($search->scheduleTypeId)  $select->where('event.schedule_type_id        IN (?)',$search->scheduleTypeId); 
        if ($search->point2)          $select->where('event.point2                  IN (?)',$search->point2); 
        
        if ($search->teamId)          $select->where('event_team.team_id            IN (?)',$search->teamId);
        
        if ($search->unitId)          $select->where('event_team.unit_id            IN (?)',$search->unitId);
        if ($search->divisionId)      $select->where('event_team.division_id        IN (?)',$search->divisionId);
        if ($search->eventTeamTypeId) $select->where('event_team.event_team_type_id IN (?)',$search->eventTeamTypeId);
        
        if ($search->phyTeamId) {
            $select->joinLeft('sch_team AS sch_team','sch_team.sch_team_id = event_team.team_id');
            $select->where('sch_team.phy_team_id IN (?)',$search->phyTeamId); 
        }
        $rows = $this->db->fetchAll($select); //Zend::dump($rows);
        $ids  = array();
        foreach($rows as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
    function joinEventTeamForType($select,$alias,$right,$eventTeamTypeId)
    {        
        $select->joinLeft(
            "event_team AS $alias",
            "$alias.event_id = $right.event_id AND $alias.event_team_type_id = $eventTeamTypeId",
            $this->table->getAliasedColumnNames($alias)
        );
    }
    function joinEventTeamToEvent($select,$alias,$right)
    {        
        $select->joinLeft(
            "event_team AS $alias",
            "$alias.event_id = $right.event_id",
            $this->table->getAliasedColumnNames($alias)
        );
    }
    function joinWantx($select,$alias)
    {
        $models = $this->context->models;
        $models->UnitModel         ->joinUnitDesc    ($select,$alias);
        $models->YearModel         ->joinYearDesc    ($select,$alias);
        $models->DivisionModel     ->joinDivisionDesc($select,$alias);
        $models->EventTeamTypeModel->joinEventTeamTypeDesc($select,$alias);
    }    
    function deleteForEventId($id)
    {
        if (is_object($id)) $id = $id->id;
        return $this->table->deleteForEventId($id);
    }  
}
?>
