<?php
class EventMap extends BaseMap
{
    protected $map = array(
        'id'             => 'event_id',
    	'num'            => 'event_num',
        'projectId'      => 'project_id',
        'yearId'         => 'reg_year_id',
        'seasonTypeId'   => 'season_type_id',
        'scheduleTypeId' => 'schedule_type_id',
        'eventTypeId'    => 'event_type_id',
        'classId'        => 'class_id',
        'unitId'         => 'unit_id',
        'status'         => 'status',
        'fieldId'        => 'field_id',
        'date'           => 'event_date',
        'time'           => 'event_time',
        'duration'       => 'event_duration',
    	'point1'         => 'point1',
        'point2'         => 'point2',
    );
    protected $mapx = array(
        'year'             => 'year_desc',     
        'unitKey'          => 'unit_key',
        'unitDesc'         => 'unit_desc',
        'eventTypeDesc'    => 'event_type_desc',
        'seasonTypeDesc'   => 'season_type_desc',
        'scheduleTypeKey'  => 'schedule_type_key',
        'scheduleTypeDesc' => 'schedule_type_desc',

        /* Need field sort information as well */        
        'fieldDesc'        => 'field_desc',
        'fieldUnitId'      => 'field_unit_id',
        'fieldSiteId'      => 'field_field_site_id',
        'fieldSiteDesc'    => 'field_field_site_desc',
        'fieldSiteUnitId'  => 'field_field_site_unit_id',
    );
}
class EventTable extends BaseTable 
{
    protected $tblName = 'event';
    protected $keyName = 'event_id';
    
    protected $mapClassName = 'EventMap';  
}
class EventItem extends BaseItem
{
    protected $mapClassName = 'EventMap';
    
    protected $teams   = array();
    protected $persons = array();
    
    public function addTeam($team) {
        $this->teams[$team->eventTeamTypeId] = $team;
    }
    public function addPerson($person) {
        $this->persons[$person->personTypeId] = $person;
    }
    public function __get($name) {
        switch($name) {
            case 'teams':   return $this->teams;
            case 'persons': return $this->persons;
            
            case 'teamHome':
                return $this->teams[EventTeamTypeModel::TYPE_HOME];

            case 'teamAway':
                return $this->teams[EventTeamTypeModel::TYPE_AWAY];

            case 'divisionDesc': 
                return $this->teamHome->divisionDesc;  
        }
        return parent::__get($name);
    }
}
class EventModel extends BaseModel
{
    protected   $mapClassName = 'EventMap';
    protected  $itemClassName = 'EventItem';
    protected $tableClassName = 'EventTable';
    
    function __get($name) {
        switch($name) {
            
            case 'typeGame':      return EventTypeModel::TYPE_GAME;
            case 'typePractice':  return EventTypeModel::TYPE_PRACTICE;
            case 'typeScrimmage': return EventTypeModel::TYPE_SCRIMMAGE;
            case 'typeJamboree':  return EventTypeModel::TYPE_JAMBOREE;
            case 'typeUPS':       return EventTypeModel::TYPE_UPS;
            case 'typeTryouts':   return EventTypeModel::TYPE_TRYOUTS;
            case 'typeOther':     return EventTypeModel::TYPE_OTHER;
        }
        return parent::__get($name);
    }
    public function search($search)
    {
        $wantPhyTeam   = FALSE;
        $wantCoachHead = FALSE;
        $wantCoachAsst = FALSE;
        $wantTeamMgr   = FALSE;
        
        $select = new Proj_Db_Select($this->db);
        $models = $this->context->models;
        
        $alias  = 'event';
        $this->fromAll($select,$alias);
        
        if ($search->wantx) $this->joinWantx($select,$alias);
                             
        if ($search->projectId)      $select->where("{$alias}.project_id       IN (?)",$search->projectId);
        if ($search->yearId)         $select->where("{$alias}.reg_year_id      IN (?)",$search->yearId);
        if ($search->eventTypeId)    $select->where("{$alias}.event_type_id    IN (?)",$search->eventTypeId);
        if ($search->seasonTypeId)   $select->where("{$alias}.season_type_id   IN (?)",$search->seasonTypeId);
        if ($search->scheduleTypeId) $select->where("{$alias}.schedule_type_id IN (?)",$search->scheduleTypeId);
        if ($search->unitId)         $select->where("{$alias}.unit_id          IN (?)",$search->unitId);
        if ($search->fieldId)        $select->where("{$alias}.field_id         IN (?)",$search->fieldId);
        if ($search->eventId)        $select->where("{$alias}.event_id         IN (?)",$search->eventId);
        if ($search->dateGE)         $select->where("{$alias}.event_date       >=  ? ",$search->dateGE);
        if ($search->dateLE)         $select->where("{$alias}.event_date       <=  ? ",$search->dateLE);
        if ($search->date)           $select->where("{$alias}.event_date        =  ? ",$search->date);
        if ($search->time)           $select->where("{$alias}.event_time        =  ? ",$search->time);
        
        if ($search->point1)         $select->where("{$alias}.point1           IN (?)",$search->point1);
        if ($search->point2)         $select->where("{$alias}.point2           IN (?)",$search->point2);
        
        $select->order(array(
            "{$alias}.reg_year_id",
            "{$alias}.season_type_id",
            "{$alias}.schedule_type_id",
            "{$alias}.unit_id",
        ));               
        $rows = $this->db->fetchAll($select);// Zend::dump($rows);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,$alias);
            $items[$item->id] = $item;
        }
        return $items;      
    }
    /* ---------------------------------------------------
     * Customized search routine for extracting enough information
     * to display a schedule.  It assumes that a distinct list of
     * event_ids has already been produced.
     */
    public function searchSchedule($search)
    {
        // Make sure have at least one eventId
        $eventIds = $search->eventId;
        if (!$eventIds) return array();
        if (is_array($eventIds) && (count($eventIds) < 1)) return array();
        
        $models = $this->context->models;
        $eventModel         = $models->EventModel;
        $eventTeamModel     = $models->EventTeamModel;
        $eventPersonModel   = $models->EventPersonModel;
        $schTeamModel       = $models->SchTeamModel;
        $phyTeamModel       = $models->PhyTeamModel;
        $phyTeamPersonModel = $models->PhyTeamPersonModel;
        $personModel        = $models->PersonModel;
        $phoneModel         = $models->PhoneModel;
        
        $select = new Proj_Db_Select($this->db);
        
        $aliasEvent      = 'event';
        $aliasEventTeam  = 'eventteam';
        $aliasSchTeam    = 'schteam';
        $aliasPhyTeam    = 'phyteam';
        
        $aliasCoachHeadLink   = 'coachheadlink';
        $aliasCoachHeadPerson = 'coachheadlinkperson';
        
        $aliasCoachHeadPersonPhoneHome = 'coachheadlinkpersonphonehome';
//      $aliasCoachHeadPersonPhoneWork = 'coachheadlinkpersonphonework';
//      $aliasCoachHeadPersonPhoneCell = 'coachheadlinkpersonphonecell';
        
        // Self join
        $this->fromAll  ($select,$aliasEvent);
        $this->joinWantx($select,$aliasEvent);
        
        // Team and coach joins
        $eventTeamModel->joinEventTeamToEvent($select,$aliasEventTeam,$aliasEvent);
        $eventTeamModel->joinWantx           ($select,$aliasEventTeam);
        
        $schTeamModel->joinAll  ($select,$aliasSchTeam,$aliasEventTeam,'team_id');
        $schTeamModel->joinWantx($select,$aliasSchTeam);
        
        $phyTeamModel->joinAll  ($select,$aliasPhyTeam,$aliasSchTeam);
        $phyTeamModel->joinWantx($select,$aliasPhyTeam);
        
        $phyTeamPersonModel->joinPhyTeamForType($select,$aliasCoachHeadLink,  $aliasPhyTeam, VolTypeModel::TYPE_HEAD_COACH);
        $personModel       ->joinPersonName    ($select,$aliasCoachHeadPerson,$aliasCoachHeadLink);
        
        $phoneModel->joinPhonePersonForType($select,
            $aliasCoachHeadPersonPhoneHome,
            $aliasCoachHeadPerson,
            PhoneTypeModel::TYPE_HOME);
/*            
        $phoneModel->joinPhonePersonForType($select,
            $aliasCoachHeadPersonPhoneWork,
            $aliasCoachHeadPerson,
            PhoneTypeModel::TYPE_WORK);
            
        $phoneModel->joinPhonePersonForType($select,
            $aliasCoachHeadPersonPhoneCell,
            $aliasCoachHeadPerson,
            PhoneTypeModel::TYPE_CELL);
  */      
        /* Conditions and sorting */
        $select->where("{$aliasEvent}.event_id IN (?)",$eventIds);
        
        switch($search->orderBy) {
            
            case 2:
                $select->order(array(
                    "{$aliasEvent}_event_date",
                    "{$aliasEvent}_field_sort",
                    "{$aliasEvent}_event_time",
                    "{$aliasEvent}_event_id",                // Ensure events are grouped together
                    "{$aliasEventTeam}_event_team_type_id",  // Ensures HOME is first
                ));
            case 1:
            default:
                $select->order(array(
                    "{$aliasEvent}_event_date",
                    "{$aliasEvent}_event_time",
                    "{$aliasEvent}_field_sort",
                    "{$aliasEvent}_event_id",                // Ensure events are grouped together
                    "{$aliasEventTeam}_event_team_type_id",  // Ensures HOME is first
                ));
        }
        
        // Main Query            
        $rows = $this->db->fetchAll($select); // Zend::dump($rows);
        $events = array();
        foreach($rows as $row)
        { 
            $eventId = $row[$aliasEvent . '_event_id'];
            if (isset($events[$eventId])) $event = $events[$eventId];
            else {
                $event = $this->create($row,$aliasEvent);
                $events[$eventId] = $event;
            }
            //Zend_Debug::dump($row); die();
            $eventTeam = $eventTeamModel->create($row,$aliasEventTeam);
            $event->addTeam($eventTeam);
            
            $eventTeam->event     = $event;
            $eventTeam->schTeam   = $schTeamModel->create($row,$aliasSchTeam);
            $eventTeam->phyTeam   = $phyTeamModel->create($row,$aliasPhyTeam);
            $eventTeam->coachHead =  $personModel->create($row,$aliasCoachHeadPerson); 
            
            $phoneHome = $phoneModel->create($row,$aliasCoachHeadPersonPhoneHome);
//            $phoneWork = $phoneModel->create($row,$aliasCoachHeadPersonPhoneWork);
//            $phoneCell = $phoneModel->create($row,$aliasCoachHeadPersonPhoneCell);
            
            $eventTeam->coachHead->addPhone($phoneHome);
//            $eventTeam->coachHead->addPhone($phoneWork);
//            $eventTeam->coachHead->addPhone($phoneCell);
        }
        // Query for event persons (officials)
        if (!$search->wantEventPersons) return $events;
        
        $searchEventPerson = new SearchData();
        $searchEventPerson->eventId    = $eventIds;
        $searchEventPerson->wantx      = TRUE;
      //$searchEventPerson->wantPerson = TRUE; // wantx already gets person's name
        $eventPersons = $eventPersonModel->search($searchEventPerson); //Zend::dump($eventPersons);
        foreach($eventPersons as $eventPerson)
        {
            $events[$eventPerson->eventId]->addPerson($eventPerson);
        }
        return $events;          
    }
    public function searchScheduleOne($search)
    {
        $items = $this->searchSchedule($search); // Zend_Debug::dump($search); die();
        if (count($items) != 1) return NULL;
        return array_shift($items);
    }
    public function searchByDateTimeField($date,$time,$fieldId)
    {
        $search = new SearchData();
        $search->date = $date;
        $search->time = $time;
        $search->fieldId = $fieldId;
        $items = $this->search($search); // Zend_Debug::dump($items); die();
        if (count($items) < 1) return NULL;
        $event = array_shift($items);

        // Grab the teams
        $search = new SearchData();
        $search->eventId = $event->id;        
        $items = $this->context->models->EventTeamModel->search($search);
        foreach($items as $item) {
            $event->addTeam($item);
        }
        // Zend_Debug::dump($event);         
        return $event;  
    }
    public function joinWantx($select,$alias)
    {
        $models = $this->context->models;
        
        $models->UnitModel        ->joinUnitDesc        ($select,$alias);
        $models->YearModel        ->joinYearDesc        ($select,$alias);
        $models->EventTypeModel   ->joinEventTypeDesc   ($select,$alias);
        $models->SeasonTypeModel  ->joinSeasonTypeDesc  ($select,$alias);
        $models->ScheduleTypeModel->joinScheduleTypeDesc($select,$alias);
        $models->FieldModel       ->joinFieldDesc       ($select,$alias);
    }
    function delete($id)
    {
        $this->context->models->EventTeamModel  ->deleteForEventId($id);
        $this->context->models->EventPersonModel->deleteForEventId($id);
        return parent::delete($id);
    }
    function getNextEventNum($projectId)
    {
        $sql = "SELECT max(event_num) AS next FROM event WHERE project_id = :project_id";
        $row = $this->db->fetchRow($sql,array('project_id' => $projectId));
        return $row['next'] + 1;
    }
}
?>
