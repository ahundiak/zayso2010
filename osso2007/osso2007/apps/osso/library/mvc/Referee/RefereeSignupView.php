<?php
class RefereeSignupView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'Referee Signup';
        $this->tplContent = 'RefereeSignupTpl';
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
        
        // Default event person type pattern */
        $eventPersonTypes = array(
            $eventPersonTypeModel->TYPE_CR,
            $eventPersonTypeModel->TYPE_AR1,
            $eventPersonTypeModel->TYPE_AR2,
        );
        $eventPersons = array();
        
        foreach($eventPersonTypes as $eventPersonType) {
            if (isset($event->persons[$eventPersonType])) $eventPerson = $event->persons[$eventPersonType];
            else {
                $eventPerson = $eventPersonModel->newItem();
                $eventPerson->personTypeId = $eventPersonType;
            }
            $eventPerson = new Proj_View_Item($this,$eventPerson);
            $eventPerson->personTypeDesc = $eventPersonTypeModel->getDesc($eventPerson->personTypeId);
            $eventPersons[] = $eventPerson;
        }
        
        /* Fool with pick lists */
        $personPickList = array_merge(array('0' => 'Not Assigned'),$user->refereePickList);
        foreach($eventPersons as $eventPerson)
        {
            if ($eventPerson->personId && !isset($personPickList[$eventPerson->personId])) {
                $name = $eventPerson->personLastName . ', ' . $eventPerson->personFirstName;
                $eventPerson->personPickList = array($eventPerson->personId => $name);
            }
            else $eventPerson->personPickList = $personPickList;
        }
        
        /* And display */
        $this->event        = $event;
        $this->eventPersons = $eventPersons;
        
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
        
        $eventPersonTypes = array(
            $eventPersonTypeModel->TYPE_CR,
            $eventPersonTypeModel->TYPE_AR1,
            $eventPersonTypeModel->TYPE_AR2,
        );
        $eventPersons = $event->persons;
        
        $html = NULL;
        
        foreach($eventPersonTypes as $eventPersonType) {
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