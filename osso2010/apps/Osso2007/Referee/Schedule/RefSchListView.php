<?php
class Osso2007_Referee_Schedule_RefSchListView extends Osso2007_View
{
  function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Referee Schedules';
    $this->tplContent = 'Osso2007/Referee/Schedule/RefSchListTpl.html.php';
  }
  function process($data)
  {
    $models = $this->context->models;
    $user   = $this->context->user;
        
    $this->schedRefListData = $data;
                     
    $this->unitPickList         = $models->UnitModel        ->getPickList();
    $this->yearPickList         = $models->YearModel        ->getPickList();
    $this->divisionAgePickList  = $models->DivisionModel    ->getAgePickList();
    $this->eventTypePickList    = $models->EventTypeModel   ->getPickList();
    $this->seasonTypePickList   = $models->SeasonTypeModel  ->getPickList();
    $this->scheduleTypePickList = $models->ScheduleTypeModel->getPickList();
    $this->eventPoint2PickList  = $models->EventPoint2Model ->getPickList();
        
    /* Date processing */
    $dtModel = $models->DateTimeModel;
    $this->dateYearPickList  = $dtModel->getYearPickList ();
    $this->dateMonthPickList = $dtModel->getMonthPickList();
    $this->dateDayPickList   = $dtModel->getDayPickList  ();
        
    /* A few misc lists */
    $this->orderByPickList = array
    (
      '1' => 'Date,Time,Field',
      '2' => 'Date,Field,Time',
    );
    if ($user->isAdmin)
    {
      $this->outputTypePickList = array
      (
        '1' => 'Web',
        '2' => 'Spreadsheet',
      );
    }
    else
    {
      $this->outputTypePickList = array
      (
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
        
        $search->divisionId = $models->DivisionModel->getDivisionIdsForAgeRange(
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
        $search->point2 = $data->point2;

        $searchx = array
        (
          'date_le'            => $search->dateLE,
          'date_ge'            => $search->dateGE,
          'unit_id'            => (int)$search->unitId,
          'division_id'        =>      $search->divisionId,
          'event_team_type_id' =>      $search->eventTeamTypeId,
          'point2'             => (int)$search->point2,
        );
        $direct = new Osso2007_Event_EventDirect($this->context);
        $result = $direct->getDistinctIds($searchx);
        $eventIds = $result->rows;
        
        // $eventIds = $models->EventTeamModel->searchDistinct($search);
        
        /* Now the full query */
        $search = new SearchData();
        $search->eventId = $eventIds;
        $search->orderBy = $data->orderBy;
        $search->wantEventPersons = TRUE;
        
        $events = $models->EventModel->searchSchedule($search);
        
        /* And display */
        $this->events = $events;
        
        /* Highlight referees and coaches */
        $this->personIds = array_keys($user->personIds); // Zend_Debug::dump($this->personIds);
        
        /* Referee list, eveyone for admin */
        $refereePickList = $user->refereePickList;
        
        if (!$data->show) $this->showRefereeIds = NULL;
        else 
        {
          if ($data->show > 0) $this->showRefereeIds = array($data->show);
          else {
            $this->showRefereeIds = array_keys($refereePickList);
            if (count($this->showRefereeIds) < 1) $this->showRefereeIds = NULL;
          }
          if ($this->showRefereeIds) $this->personIds = $this->showRefereeIds; // Maybe
        }
        
        // Everyone for admin
        if ($user->isAdmin) {
            $search = new SearchData();
            $search->volTypeId = array($models->VolType->TYPE_ADULT_REF,$models->VolType->TYPE_YOUTH_REF);
            $search->yearId  = $user->yearId;
            $search->seasonTypeId = $user->seasonTypeId;
            $refereePickList = $models->VolModel->getPersonPickList($search);
        }
        // Allow filtering for individual referee or account
        $this->showPickList = array(
        	 0 => 'All Referees',
        	-1 => 'My Account Referees',
        ) + $refereePickList;
        
        $response = $this->context->response;
        switch($data->outputType)
        {
          case 2:
            ob_start();
            include 'SchedRefList.csv.php';
            $response->setBody(ob_get_clean());
            $response->setFileHeaders('RefSchedule.csv');
            return;
        }
        $response->setBody($this->renderx());
        return;
    }
    function showEvent($event)
    {
    	// Any filters?
    	if (!$this->showRefereeIds) return TRUE;
    	//Zend_Debug::dump($this->showRefereeIds);
    	//Zend_Debug::dump($event->persons);
    	
    	foreach($event->persons AS $person)
    	{
    		if (in_array($person->personId,$this->showRefereeIds)) return TRUE;
    	}
    	return FALSE;
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
        	}     
             
            // $desc = $this->escape($team->schedDesc);
            
            if (in_array($team->coachHead->id,$personIds)) {
                $desc = '<span style="color: green">' . $desc . '</span>';
            }            
            if ($html) $html .= "<br />\n";
            $html .= $desc;
        }
        return $html;
    }
    function displayPersons($event)
    {
        $eventPersonModel     = $this->context->models->EventPerson;
        $eventPersonTypeModel = $this->context->models->EventPersonType;
        $unitModel            = $this->context->models->Unit;
        
        $teamHome = $event->teamHome;
        
        // Default event person type pattern */
        $eventPersonTypes[] = $eventPersonTypeModel->TYPE_CR;
        
        if ($teamHome->divisionId >= 7 || $teamHome->unitId != 4) {
            $eventPersonTypes[] = $eventPersonTypeModel->TYPE_AR1;
            $eventPersonTypes[] = $eventPersonTypeModel->TYPE_AR2;
        }
        if ($teamHome->divisionId >= 16) {
            $eventPersonTypes[] = $eventPersonTypeModel->TYPE_4TH;
        }
		$eventPersonTypes[] = $eventPersonTypeModel->TYPE_OBS;
		$eventPersonTypes[] = $eventPersonTypeModel->TYPE_MEN;
		$eventPersonTypes[] = $eventPersonTypeModel->TYPE_STB;
        /*
        $eventPersonTypes = array(
            $eventPersonTypeModel->TYPE_CR,
            $eventPersonTypeModel->TYPE_AR1,
            $eventPersonTypeModel->TYPE_AR2,
        );*/
		// For Winter
		if ($event->seasonTypeId == 2) {
        	$eventPersonTypes = array($eventPersonTypeModel->TYPE_CR);
		}
		$eventPersons = $event->persons;
        
        // No easy way to get youth or adult referee information
        // Should add youth or adult flag to event_person
        
        $html = NULL;
        
        $personIds = $this->personIds;
        
        $html = '<table style="margin: 0px; padding: 0px; ">' . "\n";
        
        $trStyle = 'style="margin: 0px; padding: 0px; "';
        
        $td1Style = 'style="margin: 0px; padding: 0px 4px 0px 1px; width: 60px; "';
        $td2Style = 'style="margin: 0px; padding: 0px;"';
        
        foreach($eventPersonTypes as $eventPersonType) 
        {
            if (isset($eventPersons[$eventPersonType])) $eventPerson = $eventPersons[$eventPersonType];
            else {
            	$eventPerson = NULL;
            	switch($eventPersonType)
            	{
            	case $eventPersonTypeModel->TYPE_CR:
            	case $eventPersonTypeModel->TYPE_AR1:
            	case $eventPersonTypeModel->TYPE_AR2:
            	
            		$eventPerson = $eventPersonModel->newItem();
                	$eventPerson->personTypeId = $eventPersonType;
            	}
            }
            if ($eventPerson) 
            {
            	// Zend_Debug::dump($eventPerson); die();
            	
            	$desc = $eventPersonTypeModel->getDescShort($eventPerson->personTypeId);
            	if ($eventPersonType == $eventPersonTypeModel->TYPE_CR || 1) 
            	{
            		$desc = $this->href($desc,'sched_ref_signup',$event->id);
            	}
            	$name = $eventPerson->personFirstName . ' ' . $eventPerson->personLastName;
            	
                if ($this->context->user->isAdmin && $event->scheduleTypeId == 3)
                {
            		$unit = $unitModel->findCached($eventPerson->personUnitId);
            		
            		if ($unit && $unit->key) $name = $unit->key . ' ' . $name;
            	}
            	$name = $this->escape($name);
            
            	if (in_array($eventPerson->personId,$personIds)) {
                	$name = '<span style="color: green">' . $name . '</span>';
            	}    
            	$html .= "<tr $trStyle><td $td1Style>$desc</td><td $td2Style>$name</td></tr>\n";
            }
            
        }
        $html .= "</table>\n";
        
        return $html;
    }
}
?>