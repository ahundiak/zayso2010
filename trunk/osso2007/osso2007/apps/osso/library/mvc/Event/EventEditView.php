<?php
class EventEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Event';
        $this->tplContent = 'EventEditTpl';
    }
    /* --------------------------------------------
     * Set of empty teams
     * Overwritten with any existing teams
     */
    function createEmptyEventTeams($event)
    {
        $models = $this->context->models;
        $eventModel         = $models->Event;
        $eventTeamModel     = $models->EventTeam;
        $eventTeamTypeModel = $models->EventTeamType;
        
        /* Default set of types, for games might limit to just one away */
        $eventTeamTypeIds[] = $eventTeamModel->typeHome;
        $eventTeamTypeIds[] = $eventTeamModel->typeAway;
        
        if ($event->eventTypeId != $eventModel->typeGame) {
            $eventTeamTypeIds[] = $eventTeamModel->typeAway3;
            $eventTeamTypeIds[] = $eventTeamModel->typeAway4;
        }
        $eventTeamId = -1;
        $eventTeams  = array();
        foreach($eventTeamTypeIds as $eventTeamTypeId)
        {
            $eventTeam = $eventTeamModel->newItemx(); // Need place holders for description
            $eventTeam->id = $eventTeamId--;
            
            $eventTeam->eventTeamTypeId   = $eventTeamTypeId;
            $eventTeam->eventTeamTypeDesc = $eventTeamTypeModel->getDesc($eventTeamTypeId);
            
            $eventTeam->unitId  = $event->unitId;
            $eventTeam->yearId  = $event->yearId;
            $eventTeam->eventId = $event->id;
            
            $eventTeam->divisionId = 0;
            $eventTeams[$eventTeamTypeId] = $eventTeam;
        }
        return $eventTeams;
    }
    /* ------------------------------------------
     * Loads all the data for an existing event
     * Returns TRUE on success setting $this->event and $this->eventTeams
     * FALSE on any problems
     */
    function loadExistingEvent($eventId)
    {
        //die('loadExistingEvent');
        $models = $this->context->models;
        
        /* Make sure id is valid */
        if ($eventId <= 0) return FALSE;
        
        /* Search for the event and related info */
        $search = new SearchData();
        $search->wantx   = TRUE;
        $search->eventId = $eventId;
        
        $event = $models->EventModel->searchOne($search);
        if (!$event) return FALSE;
        
        /* Make up default list of eventTeams */
        $eventTeams = $this->createEmptyEventTeams($event);
        
        /* Always have at least a home team */
        $search = new SearchData();
        $search->wantx   = TRUE;
        $search->eventId = $eventId;
        $eventTeamsExisting = $models->EventTeamModel->search($search);
        if (count($eventTeamsExisting) < 1) {
            // This is bad
            die("In loadExistingEvent, no teams for $eventId");
            return FALSE;
        }
        foreach($eventTeamsExisting as $eventTeam) {
            $eventTeams[$eventTeam->eventTeamTypeId] = $eventTeam;
        }
        
        /* Might need to process the same as here? */
        
        // All okay
        $this->event      = $event;
        $this->eventTeams = $eventTeams; //Zend_Debug::dump($eventTeams); die();
        return TRUE;
    }
    function loadNewEvent($data)
    {
        $models = $this->context->models;
        
        $event = $models->EventModel->find(0);
        
        $event->id             = 0;
        $event->unitId         = $data->unitId;
        $event->yearId         = $data->yearId;
        
        $event->fieldId        = 0;
        
        $event->eventTypeId    = $data->eventTypeId;
        $event->seasonTypeId   = $data->seasonTypeId;
        $event->scheduleTypeId = $data->scheduleTypeId;
            
        $event->date           = $data->eventDate;
        $event->time           = $data->eventTime;
        $event->duration       = $data->duration;
        
        $event->point1         = $data->point1;
        $event->point2         = $data->point2;
        
        /* Might want to do some sanity checking here just to make sure all the defaults came */
        
        /* The teams */
        $eventTeams = $this->createEmptyEventTeams($event);
        
        // All okay
        $this->event      = $event;
        $this->eventTeams = $eventTeams;
        return TRUE;
    }          
    function process($data)
    {
        $models = $this->context->models;
        $eventTeamModel = $models->EventTeamModel;
        
        /* Existing, new or refreshed */
        if (isset($data->event)) {
            $this->event  = $data->event;
            if (count($data->eventTeams) < 4) {
                $eventTeams = $this->createEmptyEventTeams($data->event);
                foreach($data->eventTeams as $eventTeam) {
                    $eventTeams[$eventTeam->eventTeamTypeId] = $eventTeam;
                }
                $this->eventTeams = $eventTeams;
            }
            else $this->eventTeams = $data->eventTeams;
        }
        else {
            $eventId = $data->eventId;
            if ($eventId > 0) {
                $flag = $this->loadExistingEvent($eventId);
                if (!$flag) $eventId = 0; // Record went away
            }
            if ($eventId == 0) {
                $flag = $this->loadNewEvent($data);
            }
        }
        $event = $this->event;
        
        // Wrap event teams in a view item
        $eventTeams = array();
        foreach($this->eventTeams as $eventTeam) {
            $eventTeams[$eventTeam->eventTeamTypeId] = new Proj_View_Item($this,$eventTeam);
        }
        $this->eventTeams = $eventTeams;
        
        // This is the base information, should be good to go */
        $eventTeamHome = $eventTeams[$eventTeamModel->typeHome];
        
        // Adjust administrative unit
        if ($event->unitId == $eventTeamHome->unitId) $event->unitId = SAME_AS_HOME;
        
        // Adjust default field unit id to match home team
        if (!$event->fieldId) {
            // Might still have fieldSiteUnitId from refreshed data */
            $event->fieldUnitId = SAME_AS_HOME;
        }
        if ($event->fieldUnitId == $eventTeamHome->unitId) $event->fieldUnitId = SAME_AS_HOME;
        if ($event->fieldUnitId == SAME_AS_HOME) $fieldUnitId = $eventTeamHome->unitId;
        else                                     $fieldUnitId = $event->fieldUnitId;
        if ($fieldUnitId) {
            $fieldSitePickList = $models->FieldSiteModel->getPickList($fieldUnitId);
            if (!isset($fieldSitePickList[$event->fieldSiteId])) $event->fieldSiteId = 0;
            
            $fieldPickList = $models->FieldModel->getPickList($fieldUnitId,$event->fieldSiteId);
            if (!isset($fieldPickList[$event->fieldId])) $event->fieldId = 0;
        }
        else {
            // Cannot have existing field if the unit was not set
            // Need more work here
            $event->fieldId         = 0;
            $event->fieldUnitId     = 0;
            $event->fieldSiteId     = 0;
            $event->fieldSiteUnitId = 0;
            
            $fieldPickList     = array();
            $fieldSitePickList = array();
        }
        $this->fieldPickList     = $fieldPickList;
        $this->fieldSitePickList = $fieldSitePickList;
        
        // Query any existing schedule teams
        $searchSchTeams = new SearchData();
        $searchSchTeams->yearId         = $event->yearId;
        $searchSchTeams->seasonTypeId   = $event->seasonTypeId;
        $searchSchTeams->scheduleTypeId = $event->scheduleTypeId;
        $searchSchTeams->unitId     = 0;
        $searchSchTeams->divisionId = 0;
        $schTeamPickListLast = array();

        // Limit gender of teams shown 
        $this->showBoy  = $data->showBoy;
        $this->showGirl = $data->showGirl;
        $this->showCoed = $data->showCoed;
              
        // Adjust the rest of the team types
        foreach($eventTeams as $eventTeam)
        {
            // SAME_AS_HOME
            if ($eventTeam->eventTeamTypeId != $eventTeamModel->typeHome) {
                
                if (!$eventTeam->divisionId) $eventTeam->divisionId = SAME_AS_HOME;
                
                if ( $eventTeam->unitId     == $eventTeamHome->unitId)     $eventTeam->unitId     = SAME_AS_HOME;
                if ( $eventTeam->divisionId == $eventTeamHome->divisionId) $eventTeam->divisionId = SAME_AS_HOME;
                
            }
            // Determine specific unit and division for schedule teams
            if ($eventTeam->unitId == SAME_AS_HOME) $searchUnitId = $eventTeamHome->unitId;
            else                                    $searchUnitId = $eventTeam->unitId;
            
            if ($eventTeam->divisionId == SAME_AS_HOME) $searchDivisionId = $eventTeamHome->divisionId;
            else                                        $searchDivisionId = $eventTeam->divisionId;
            
            // Only query if have actual unit and division
            if (($searchUnitId < 1) || ($searchDivisionId < 1)) $schTeamPickList = array();
            else {
                // Same as last query
                if (($searchUnitId ==  $searchSchTeams->unitId) && ($searchDivisionId == $searchSchTeams->divisionId)) $schTeamPickList = $schTeamPickListLast;
                else {
                	// Pick all teams in division regardless of gender
                	// This generates the list correctly but messes up the validation
                	$divDesc = $models->DivisionModel->getDivisionDesc($searchDivisionId);
                	$divAge  = (int) substr($divDesc,1,2); 
                	$divIds = $models->DivisionModel->getDivisionIdsForAgeGroup($divAge,$this->showBoy,$this->showGirl,$this->showCoed);
                	
                    // New query
                    $searchSchTeams->unitId     = $searchUnitId;   
                    $searchSchTeams->divisionId = $searchDivisionId; // $divIds
                    $schTeamPickListLast = $schTeamPickList = $models->SchTeamModel->getPickList($searchSchTeams);   
                }
            }
            // Store the pick list and make sure any current schTeamid is valid
            // Might be better to have a different awway of schTeamPickLists
            // Really don't want to carry around this arround?
            $eventTeam->schTeamPickList = $schTeamPickList;
            if (!isset($schTeamPickList[$eventTeam->schTeamId])) $eventTeam->schTeamId = 0;
        }        

        /* Bunch of standard pick lists */
        $this->unitPickList         = $models->UnitModel        ->getPickList();
        $this->yearPickList         = $models->YearModel        ->getPickList();
        $this->divisionPickList     = $models->DivisionModel    ->getDivisionPickList();
        $this->eventTypePickList    = $models->EventTypeModel   ->getPickList();
        $this->seasonTypePickList   = $models->SeasonTypeModel  ->getPickList();
        $this->scheduleTypePickList = $models->ScheduleTypeModel->getPickList();
        
        $dateTimeModel = $models->DateTimeModel;
        $this->dateYearPickList     = $dateTimeModel->getYearPickList ();
        $this->dateMonthPickList    = $dateTimeModel->getMonthPickList();
        $this->dateDayPickList      = $dateTimeModel->getDayPickList  ();
        $this->dateHourPickList     = $dateTimeModel->getHourPickList ();
        $this->dateMinutePickList   = $dateTimeModel->getMinutePickList();
        $this->dateDurationPickList = $dateTimeModel->getDurationPickList();

        $this->eventPoint1PickList  = $models->EventPoint1Model->getPickList();
        $this->eventPoint2PickList  = $models->EventPoint2Model->getPickList();
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
