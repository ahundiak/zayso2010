<?php
/* --------------------------------------------
 * Had to explicitly save the data back into session while
 * doing a refresh picklist, everything else seems to work
 * folllowing the shift to a different session object
 */
class EventEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->eventEditData)) $data = $session->eventEditData;
        else {
            $models = $this->context->models;
            $user   = $this->context->user;
            
            $data = new SessionData();
            
            // probably should move to a default section
            $data->eventId      = 0;
            $data->unitId       = $user->unitId; /* For new fields */
            $data->yearId       = $user->yearId;
            $data->seasonTypeId = $user->seasonTypeId;
            $data->projectId    = $user->projectId;

            $data->point1 = EventPoint1Model::TYPE_YES;
            $data->point2 = EventPoint2Model::TYPE_NYP;
            
            // Mostly for documentation
            $data->eventDate      = $models->DateTimeModel->getToday();
            $data->eventTime      = DateTimeModel::TIME_TYPE_TBD;
            $data->eventDuration  = 90;
            $data->eventTypeId    = $models->EventType->TYPE_GAME;
            $data->scheduleTypeId = ScheduleTypeModel::TYPE_REGULAR_SEASON;
            
            $data->showBoy  = TRUE;
            $data->showGirl = TRUE;
            $data->showCoed = TRUE;
            
            $session->eventEditData = $data;   
        }
        if (!$data->projectId) $data->projectId = 28;
        
        $id = $request->getParam('id');
        if ($id >= 0) {
            if (isset($data->event)) {
                if ($data->event->id != $id) {
                    $data->event      = NULL;
                    $data->eventTeams = NULL;
                }
            }
            $data->eventId = $id;
        }
        $view = new EventEditView();
        
        $response->setBody($view->process(clone $data));
        
        return;
	}
    public function processActionPost()
    {
        
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $data     = $this->context->session->eventEditData;
        $models   = $this->context->models;
        $user     = $this->context->user;
        
        $id = $request->getPost('event_id');
                
        $submitDelete = $request->getPost('event_submit_delete');
        $submitCreate = $request->getPost('event_submit_create');
        $submitUpdate = $request->getPost('event_submit_update');
        $submitRefresh= $request->getPost('event_submit_refresh');
        
        // Check authorized
        if (!$user->isAdmin) {
            return $response->setRedirect($this->link('event_edit'));
        }
        
        if ($submitDelete) {
            $data->event      = NULL;
            $data->eventTeams = NULL;
            $confirm = $request->getPost('event_confirm_delete');
            if ($confirm) {
                $models->EventModel->delete($id); // die('event deleted, now where to go?');
                return $response->setRedirect($this->link('event_edit',0));
            }    
            return $response->setRedirect($this->link('event_edit',$id));
        }
        if ($submitRefresh) return $this->processRefresh();            
        if ($submitUpdate)  return $this->processUpdate();            
        if ($submitCreate)  return $this->processcreate();            
        
        return $response->setRedirect($this->link('event_edit',$id));
    }
    /* ------------------------------------------
     * Handles creating an event
     */
    protected function processCreate()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $data     = $this->context->session->eventEditData;
        $models   = $this->context->models;
    
        /* Grab the posted data */
        $datax = new SessionData();
        $this->getPostedData($datax);
        $eventx = $datax->event;
        
        /* Event is easy, form will not allow empty data except for field and thats okay for now */
        $event = $models->EventModel->newItem();

        $event->projectId       = $eventx->projectId;
        $event->unitId          = $eventx->unitId;
        $event->yearId          = $eventx->yearId;
        $event->eventTypeId     = $eventx->eventTypeId;
        $event->seasonTypeId    = $eventx->seasonTypeId;
        $event->scheduleTypeId  = $eventx->scheduleTypeId;
        $event->date            = $eventx->date;
        $event->time            = $eventx->time;
        $event->duration        = $eventx->duration;
        $event->fieldId         = $eventx->fieldId;
        $event->point1          = $eventx->point1;
        $event->point2          = $eventx->point2;
        
        /* Need to make sure have a fully loaded home team */
        if (isset($datax->eventTeams[EventTeamTypeModel::TYPE_HOME])) $eventTeamHomex = $datax->eventTeams[EventTeamTypeModel::TYPE_HOME];
        else {
            return $this->processRefresh();    
        }
        if (!$eventTeamHomex->unitId || !$eventTeamHomex->divisionId) {
            return $this->processRefresh();
        }
        // Should probably verify schedule team as well
        if ($eventTeamHomex->schTeamId) {
            $schTeam = $models->SchTeamModel->find($eventTeamHomex->schTeamId);
            if (($schTeam->unitId != $eventTeamHomex->unitId) || ($schTeam->divisionId != $eventTeamHomex->divisionId)) {
                return $this->processRefresh();
            }
        }
        // Should be safe to store the event
        $eventId = $models->EventModel->save($event);
        if (!$eventId) {
            return $this->processRefresh();
        }
        // Now do each valid event team
        foreach($datax->eventTeams as $eventTeamx)
        {
            // Defaults
            if ($eventTeamx->unitId     == SAME_AS_HOME) $eventTeamx->unitId     = $eventTeamHomex->unitId;
            if ($eventTeamx->divisionId == SAME_AS_HOME) $eventTeamx->divisionId = $eventTeamHomex->divisionId;
            
            // Build record to save
            $eventTeam = $models->EventTeamModel->newItem();
            $eventTeam->eventId         = $eventId;
            $eventTeam->yearId          = $event->yearId; // Basically ignored
            $eventTeam->eventTeamTypeId = $eventTeamx->eventTeamTypeId;
            $eventTeam->unitId          = $eventTeamx->unitId;
            $eventTeam->divisionId      = $eventTeamx->divisionId;
            $eventTeam->schTeamId       = $eventTeamx->schTeamId;

            // Verify sch team 
            if ($eventTeam->schTeamId) {
                $schTeam = $models->SchTeamModel->find($eventTeam->schTeamId);
                if (($schTeam->unitId != $eventTeam->unitId) || ($schTeam->divisionId != $eventTeam->divisionId)) {
                    $eventTeam->schTeamId = 0;
                }
            }
            // Must have schTeam except for home
            if (($eventTeam->schTeamId) || ($eventTeam->eventTeamTypeId != EventTeamTypeModel::TYPE_HOME)) {
                $models->EventTeamModel->save($eventTeam);
            }
        }
        /* Done */
        $data->eventId    = $eventId;
        $data->event      = NULL;
        $data->eventTeams = NULL;
        return $response->setRedirect($this->link('event_edit',$eventId));        
    }
    /* ------------------------------------------
     * Handles updating the event
     */
    protected function processUpdate()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $data     = $this->context->session->eventEditData;
        
        $models   = $this->context->models;
        
        $eventModel     = $models->Event;
        $eventTeamModel = $models->EventTeam;
        
        $eventTeamTypeHome = $eventTeamModel->typeHome;
        $eventTeamTypeAway = $eventTeamModel->typeAway;
        
        /* Grab the posted data */
        $datax = new SessionData();
        $this->getPostedData($datax);
        
        /* Update makes no sense without an id */
        $eventId = $datax->event->id;
        if (!$datax->event->id) {
            return $this->processRefresh();
        }
        /* Bring in existing event */
        $event = $eventModel->find($eventId);
        if (!$event->id) {
            die('Tried to update event that no longer exists ' . $eventId);    
        }
        
        /* Verify have a home record */
        if (isset($datax->eventTeams[$eventTeamTypeHome])) $eventTeamHomex = $datax->eventTeams[$eventTeamTypeHome];
        else {
            die('Event Update - no home team');
        }
        $searchEventTeam = new SearchData();
        $searchEventTeam->eventId = $eventId;
        $eventTeams = $models->EventTeamModel->search($searchEventTeam);
        if (!isset($eventTeams[$eventTeamHomex->id])) {
            die('Event Update - home team not in database');
        }
        
        /* Validate the changes */
        $eventx = $datax->event;
        $valid = TRUE;
        
        /* Adjust the admin unit */
        if ( $eventx->unitId == SAME_AS_HOME) $eventx->unitId = $eventTeamHomex->unitId;
        if (!$eventx->unitId) $valid = FALSE;
        
        /* Check the field */
        $valid = $this->processField($eventx,$eventTeamHomex);
        
        /* And commit */
        if ($valid) $eventModel->save($eventx);
        
        /* Now for the teams */
        foreach($datax->eventTeams as $eventTeamx) {
        
            $valid = TRUE;
            
            /* Always set some data just because */
            $eventTeamx->eventId = $eventId;
            $eventTeamx->yearId  = $event->yearId;
            
            // Defaults
            if ($eventTeamx->unitId     == SAME_AS_HOME) $eventTeamx->unitId     = $eventTeamHomex->unitId;
            if ($eventTeamx->divisionId == SAME_AS_HOME) $eventTeamx->divisionId = $eventTeamHomex->divisionId;
            
            // Verify any schedule team is ok
            if ($eventTeamx->schTeamId) {
                $schTeam = $models->SchTeamModel->find($eventTeamx->schTeamId);
                if (!$schTeam->id) $eventTeamx->schTeamId = 0;
                else {
                    if ($eventTeamx->unitId     != $schTeam->unitId)     $eventTeamx->schTeamId = 0;
                    if ($eventTeamx->divisionId != $schTeam->divisionId) $eventTeamx->schTeamId = 0;
                    
                    if ($eventx->yearId         != $schTeam->yearId)         $eventTeamx->schTeamId = 0;
                    if ($eventx->seasonTypeId   != $schTeam->seasonTypeId)   $eventTeamx->schTeamId = 0;
                    if ($eventx->scheduleTypeId != $schTeam->scheduleTypeId) $eventTeamx->schTeamId = 0;
                }
            } 
            // Make sure have required fields
            if (!$eventTeamx->unitId)     $valid = FALSE;
            if (!$eventTeamx->divisionId) $valid = FALSE;
            
            // New records require a team be picked
            if ($eventTeamx->id < 0) {
                $eventTeamx->id = NULL;
                if (($eventTeamx->eventTeamTypeId != $eventTeamTypeHome) &&
                    ($eventTeamx->eventTeamTypeid != $eventTeamTypeAway)) {
                    if (!$eventTeamx->schTeamId) $valid = FALSE;
                }
            }
            // Want to delete any extra records for which the sch team has been removed
            if ($eventTeamx->id > 0) {
                if (($eventTeamx->eventTeamTypeId != $eventTeamTypeHome) &&
                    ($eventTeamx->eventTeamTypeid != $eventTeamTypeAway)) {
                    if (!$eventTeamx->schTeamId) {
                        $models->EventTeamModel->delete($eventTeamx->id);
                        $valid = FALSE;
                    }
                }
            }
            // And commit
            if ($valid) $eventTeamModel->save($eventTeamx);  
        }
        /* Done */
        $data->eventId    = $eventId;
        $data->event      = NULL;
        $data->eventTeams = NULL;
        return $response->setRedirect($this->link('event_edit',$eventId));
    }
    protected function processField($eventx,$eventTeamHomex)
    {
        $models = $this->context->models;
        
        /* If a field is specified, make sure it exists and that it belongs to the field unit group */
        if ( $eventx->fieldUnitId == SAME_AS_HOME) $eventx->fieldUnitId = $eventTeamHomex->unitId;
        if (!$eventx->fieldUnitId)                 $eventx->fieldUnitId = $eventTeamHomex->unitId;
        
        if ($eventx->fieldId) {
            $fieldx = $models->FieldModel->find($eventx->fieldId);
            if (!$fieldx->id) $eventx->fieldId = 0;
            else {
                if ($fieldx->unitId      != $eventx->fieldUnitId) $eventx->fieldId = 0;
                
                // Think this will be okay
                //if ($fieldx->fieldSiteId != $eventx->fieldSiteId) $eventx->fieldId = 0;
            }
        }
        if ($eventx->fieldId) {
            return TRUE;
        }
        /* Mess around with locking in the field unit if no field is specified
         * event_field_unit_id
         * event_field_site_id
         * event_field_id
         * 
         * This is not really necessary as long as the administrator creates TBD field for each
         * region and each site.  The game schedule can select the correct TBD
         */

        /* Check any specified field site */
        if ($eventx->fieldSiteId) {
            $fieldSitex = $models->FieldSiteModel->find($eventx->fieldSiteId);
            if (!$fieldSitex->id) $eventx->fieldSiteId = 0;
            else {
                if ($fieldSitex->unitId != $eventx->fieldUnitId) $eventx->fieldSiteId = 0;     
            }
        }
        if ($eventx->fieldSiteId) {
            // die('Have field site');
            /* Have a valid field site but no field, default to the field site TBD field */
            
        }
        return TRUE;
    }
    /* ------------------------------------------
     * This basically stores a copy of the posted data
     * and then redirects back to the view so the form data can be refreshed
     */
    protected function processRefresh()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $data     = $this->context->session->eventEditData;
        
        $data = $this->getPostedData($data);
        
        $this->context->session->eventEditData = $data;

        return $response->setRedirect($this->link('event_edit',$data->event->id));
    }
    /* ----------------------------------------------
     * Grabs all the posted data for later processing
     */
    protected function getPostedData($data)
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $models   = $this->context->models;

        $event = $models->EventModel->newItemx();
        
        $event->id              = $request->getPost('event_id');
        $event->num             = $request->getPost('event_num');
        $event->projectId       = $request->getPost('event_project_id');
        
        $event->unitId          = $request->getPost('event_unit_id');
        $event->yearId          = $request->getPost('event_year_id');
        $event->eventTypeId     = $request->getPost('event_event_type_id');
        $event->seasonTypeId    = $request->getPost('event_season_type_id');
        $event->scheduleTypeId  = $request->getPost('event_schedule_type_id');
        
        $event->point1          = $request->getPost('event_point1');
        $event->point2          = $request->getPost('event_point2');
        
        $event->date            = $request->getPost('event_date_year') . 
                                  $request->getPost('event_date_month') . 
                                  $request->getPost('event_date_day');
                                  
        $event->time            = $request->getPost('event_date_hour') . 
                                  $request->getPost('event_date_minute');
                                  
        $event->duration        = $request->getPost('event_date_duration');

        $event->fieldUnitId     = $request->getPost('event_field_unit_id');
        $event->fieldSiteId     = $request->getPost('event_field_site_id');
        $event->fieldId         = $request->getPost('event_field_id');
                
        $eventTeamIds         = $request->getPost('event_team_ids');
        $eventTeamUnitIds     = $request->getPost('event_team_unit_ids');
        $eventTeamYearIds     = $request->getPost('event_team_year_ids');
        $eventTeamTypeIds     = $request->getPost('event_team_type_ids');
        $eventTeamTypeDescs   = $request->getPost('event_team_type_descs');
        $eventTeamEventIds    = $request->getPost('event_team_event_ids');
        $eventTeamDivisionIds = $request->getPost('event_team_division_ids');
        $eventTeamSchTeamIds  = $request->getPost('event_team_sch_team_ids');
        
        $eventTeams = array();
        foreach($eventTeamIds as $key => $value)
        {
            $eventTeam = $models->EventTeamModel->newItemx();
            $eventTeam->id                = $eventTeamIds        [$key];
            $eventTeam->unitId            = $eventTeamUnitIds    [$key];
            $eventTeam->yearId            = $eventTeamYearIds    [$key];
            $eventTeam->eventTeamTypeId   = $eventTeamTypeIds    [$key];
            $eventTeam->eventTeamTypeDesc = $eventTeamTypeDescs  [$key];
            $eventTeam->eventId           = $eventTeamEventIds   [$key];
            $eventTeam->divisionId        = $eventTeamDivisionIds[$key];
            $eventTeam->schTeamId         = $eventTeamSchTeamIds [$key];
            $eventTeams[$eventTeam->eventTeamTypeId] = $eventTeam;
        }
        $data->event      = $event;
        $data->eventTeams = $eventTeams;
        
        $data->showBoy  = $request->getPost('event_show_boy');
        $data->showGirl = $request->getPost('event_show_girl');
        $data->showCoed = $request->getPost('event_show_coed');
            
        /* Done */
        return $data;
    }
}
?>
