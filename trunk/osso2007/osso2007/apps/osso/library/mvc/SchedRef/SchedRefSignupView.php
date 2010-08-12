<?php
class SchedRefSignupView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'Referee Signup';
        $this->tplContent = 'SchedRefSignupTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        $user   = $this->context->user;
        
        $eventPersonModel     = $models->EventPerson;
        $eventPersonTypeModel = $models->EventPersonType;
                
        /* Now the full query */
        $search = new SearchData();
        $search->eventId = $data->eventId;
        $search->wantEventPersons = TRUE;
        
        $event = $models->EventModel->searchScheduleOne($search);
        if (!$event) die('Event for referee Signup Not Found');
        
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
        
		// Save for display
		$this->eventPersonTypes = $eventPersonTypes;
        
        $eventPersons = array();
        
        $eventPersonId = -1;
        foreach($eventPersonTypes as $eventPersonType) {
            if (isset($event->persons[$eventPersonType])) $eventPerson = $event->persons[$eventPersonType];
            else {
                $eventPerson = $eventPersonModel->newItem();
                $eventPerson->id = $eventPersonId--;
                $eventPerson->personTypeId = $eventPersonType;
            }
            $eventPerson = new Proj_View_Item($this,$eventPerson);
            $eventPerson->personTypeDesc = $eventPersonTypeModel->getDesc($eventPerson->personTypeId);
            $eventPersons[] = $eventPerson;
        }
        
        /* Referee list, eveyone for admin */
        if (!$user->isAdmin || 1) $refereePickList = $user->refereePickList;
        else {
            $search = new SearchData();
            $search->volTypeId = array($models->VolType->TYPE_ADULT_REF,$models->VolType->TYPE_YOUTH_REF);
            $search->yearId       = array(8,9,10);  // $user->yearId;
            $search->seasonTypeId = array(1,2,3); // $user->seasonTypeId;
            $refereePickList = $models->VolModel->getPersonPickList($search);
        }
        /* Fool with pick lists */
        $personPickList = array('0' => 'Not Assigned') + $refereePickList;
        foreach($eventPersons as $eventPerson)
        {
            if ($eventPerson->personId && !isset($personPickList[$eventPerson->personId])) {
                $name = $eventPerson->personLastName . ', ' . $eventPerson->personFirstName;
                $eventPerson->personPickList = array($eventPerson->personId => $name);
            }
            else $eventPerson->personPickList = $personPickList;
        }
        
        /* And display */
        $this->user         = $user;
        $this->event        = $event;
        $this->eventPersons = $eventPersons;
        $this->eventPoint2PickList = $models->EventPoint2Model->getPickList();
        
        return $this->renderx();
    }
    function displayTeams($event)
    {
        $html = NULL;
        foreach($event->teams as $team) {
            
            $desc = $team->schedDesc;
            
            if ($html) $html .= "<br />\n";
            $html .= $this->escape($desc);
        }
        return $html;
    }
    function displayPersons($event)
    {
        $eventPersonModel     = $this->context->models->EventPerson;
        $eventPersonTypeModel = $this->context->models->EventPersonType;
        
        $eventPersons = $event->persons;
        
        $html = NULL;
        
        foreach($this->eventPersonTypes as $eventPersonType) {
            if (isset($eventPersons[$eventPersonType])) $eventPerson = $eventPersons[$eventPersonType];
            else {
                $eventPerson = $eventPersonModel->newItem();
                $eventPerson->personTypeId = $eventPersonType;
            }
            $desc = $eventPersonTypeModel->getDescShort($eventPerson->personTypeId);
            $name = $eventPerson->personFirstName . ' ' . $eventPerson->personLastName;
            
            if ($html) $html .= "<br />\n";
            $html .= $desc . ' ' . $this->escape($name);
        }
        return $html;
    }
}
?>