<?php
class SchedDivListView extends Proj_View
{
    function init()
    {
      parent::init();
        
      $this->tplTitle   = 'Division Schedules';
      $this->tplContent = 'SchedDivListTpl';
    }
    function process($data)
    {
      $models = $this->context->models;
      $user   = $this->context->user;
        
      $this->schedDivListData = $data;
                     
        $this->unitPickList         = $models->UnitModel        ->getPickList();
        $this->yearPickList         = $models->YearModel        ->getPickList();
        $this->divisionAgePickList  = $models->DivisionModel    ->getAgePickList();
        $this->eventTypePickList    = $models->EventTypeModel   ->getPickList();
        $this->seasonTypePickList   = $models->SeasonTypeModel  ->getPickList();
        $this->scheduleTypePickList = $models->ScheduleTypeModel->getPickList();

        /* Date processing */
        $dtModel = $models->DateTimeModel;
        $this->dateYearPickList  = $dtModel->getYearPickList ();
        $this->dateMonthPickList = $dtModel->getMonthPickList();
        $this->dateDayPickList   = $dtModel->getDayPickList  ();
        
        /* A few misc lists */
        $this->orderByPickList = array(
            '1' => 'Date,Time,Field',
            '2' => 'Date,Field,Time',
        );
        if ($user->isAdmin) {
            $this->outputTypePickList = array(
                '1' => 'Web',
                '2' => 'Spreadsheet',
            );
        }
        else {
            $this->outputTypePickList = array(
                '1' => 'Web',
                '2' => 'Spreadsheet',
            );
        }
        /* -------------------
         * First grab events of interest
         */
        $search = new SearchData();

        /* Date range */     
        $data->date1 = $date1 = $data->dateYear1 . $data->dateMonth1 . $data->dateDay1;
        $data->date2 = $date2 = $data->dateYear2 . $data->dateMonth2 . $data->dateDay2;
        if ($date1 < $date2) {
            $search->dateGE = $date1;
            $search->dateLE = $date2;
        }
        else {
            $search->dateGE = $date2;
            $search->dateLE = $date1;
        }
        $search->unitId = $data->unitId;
        
        if ($data->showAge1 == -1) $showAge1 = $data->showAge2;
        else                       $showAge1 = $data->showAge1;
        if ($data->showAge2 == -1) $showAge2 = $data->showAge1;
        else                       $showAge2 = $data->showAge2;
        
        $search->divisionId = $divisionIds = $models->DivisionModel->getDivisionIdsForAgeRange(
            $showAge1,
            $showAge2,
            $data->showBoy,
            $data->showGirl,
            $data->showCoed
        );
        $search->eventTeamTypeId = $models->EventTeamTypeModel->getEventTeamTypeIds(
            $data->showHome,
            $data->showAway
        );
        
        // Need to check if team is in the division and unit
        if ($data->teamId) {
        	$schTeam = $models->SchTeamModel->find($data->teamId);
        	
        	if (!in_array($schTeam->divisionId,$divisionIds)) $data->teamId = 0;
        	
        	if ($schTeam->unitId != $data->unitId) $data->teamId = 0;
        }
        $search->teamId = $data->teamId;

        $searchx = array
        (
          'date_le'            => $search->dateLE,
          'date_ge'            => $search->dateGE,
          'unit_id'            => (int)$search->unitId,
          'division_id'        =>      $search->divisionId,
          'event_team_type_id' =>      $search->eventTeamTypeId,
          'team_id'            => (int)$search->teamId,

        );
        $direct = new Osso2007_Event_EventDirect($this->context);
        $result = $direct->getDistinctIds($searchx);
        $eventIds = $result->rows;
//Cerad_Debug::dump($eventIds);
//     $eventIds = $models->EventTeamModel->searchDistinct($search);
//Cerad_Debug::dump($eventIds);
//die();
        /* Now the full query */
        $search = new SearchData();
        $search->eventId = $eventIds;
        $search->orderBy = $data->orderBy;
        
        $events = $models->EventModel->searchSchedule($search);
        
        // Build schedule team pick list
        $this->schTeamPickList = array();

        $search = new SearchData();
        $search->unitId       = $data->unitId;
        $search->yearId       = $data->yearId;
        $search->seasonTypeId = $data->seasonTypeId;
        $search->scheduleTypeId = 1;
        $search->divisionId   = $divisionIds;
        $search->wantx         = TRUE;
        $search->wantPhyTeam   = TRUE;
        $search->wantCoachHead = TRUE;
        
        $schTeams = $models->SchTeamModel->search($search);
        
        foreach($schTeams as $schTeam) 
        {
        	$phyTeam = $schTeam->phyTeam;
        	if ($phyTeam->id) 
        	{
        		$key   = $phyTeam->key;
        		$coach = $phyTeam->coachHead->fullName;
        	
        		$this->schTeamPickList[$schTeam->id] = $key . ' ' . $coach;
        	}
        }
        //Zend_Debug::dump($this->schTeamPickList); die();
        
        /* And display */
        $this->events = $events; // Zend::dump($schedEvents); die();
        
        /* Highlight referees and coaches */
        //Zend_Debug::dump($user); die();
        $this->personIds = $user->personIds;
        
        // return $this->renderx();

        $response = $this->context->response;

        if ($data->outputType == 1) 
        {
          $response->setBody($this->renderx());
          return;
        }
	ob_start();
        include 'SchedDivList.csv.php';
        $content = ob_get_clean();
        $response->setBody($content);
        $response->setFileHeaders('DivSchedule.csv');
        return;
    }
    function displayTeams($event)
    {
        $html = NULL;
        $teams = $event->teams;
        $personIds = $this->personIds;
        foreach($teams as $team) 
        {
        	if ($event->scheduleTypeId != 3) $desc = $this->escape($team->schedDesc);
        	else
        	{
        		$desc = $team->divisionDesc . ' ' . $team->schTeam->desc;
        		//Zend_Debug::dump($team); die();
            $desc = $team->schTeam->desc . ' ' . $team->phyTeam->unitKey . ' ' . $team->coachHead->lastName;
        	}     
            if (isset($personIds[$team->coachHead->id])) {
                $desc = '<span style="color: green">' . $desc . '</span>';
            }            
            if ($html) $html .= "<br />\n";
            $html .= $desc;
        }
        return $html;
    }
}
?>