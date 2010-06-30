<?php
class SchedRefSignupCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->refereeSignupData)) $data = $session->refereeSignupData;
        else {
            $data = new SessionData();
            $data->eventId = 0;
            $session->refereeSignupData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->eventId = $id;
        
        if (!$data->eventId) return $response->setRedirect($this->link('sched_ref'));
        
        $view = new SchedRefSignupView();
        
        $response->setBody($view->process(clone $data));
        
        return;
	}
    public function processActionPost()
    {   
        // Need to make sure user is a referee and has permission
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        $eventModel       = $this->context->models->Event;
        $eventPersonModel = $this->context->models->EventPerson;
        
        // Get the event
        $eventId = $request->getPost('referee_signup_event_id');
        $event = $eventModel->find($eventId);
        if (!$event->id) {
            die('Tried to update event that no longer exists ' . $eventId);    
        }
        // Update the processed state if necessary
        $point2 = $request->getPost('event_point2');
        
        if ($event->point2 != $point2) {
        	$event->point2  = $point2;
        	$eventModel->save($event);
        }
        if ($point2 != 1) {
        	$response->setRedirect($this->link('sched_ref_signup',$eventId));
        	return;
        }
        // Disable tournament signups
        /*
        if ($event->scheduleTypeId == 2) 
        {
            $user = $this->context->user;
        	if (!$user->isAdmin) {
        		$response->setRedirect($this->link('sched_ref_signup',$eventId));
        		return;
        	}
        }*/
        // Check the date
        /*
        $datex = $this->context->models->DateTime->getNextSunday();
        if (($event->date > $datex) && ($event->unitId == 4)) 
        {
            $user = $this->context->user;
        	if (!$user->isAdmin) {
        		$response->setRedirect($this->link('sched_ref_signup',$eventId));
        		return;
        	}
        }*/
        
        // Check for Huntsville
        /*
        if ($event->unitId == 7) {
            $user = $this->context->user;
        	if (!$user->isAdmin) {
        		$response->setRedirect($this->link('sched_ref_signup',$eventId));
        		return;
        	}
        } */
        // Get the event persons for the event
        $search = new SearchData();
        $search->eventId = $eventId;
        $eventPersons = $eventPersonModel->search($search);
        
        $eventPersonIds       = $request->getPost('referee_signup_event_person_ids');
        $eventPersonTypeIds   = $request->getPost('referee_signup_event_person_type_ids');
        $eventPersonPersonIds = $request->getPost('referee_signup_event_person_person_ids');
        foreach($eventPersonIds as $eventPersonId)
        {
            $eventPersonTypeId   = $eventPersonTypeIds  [$eventPersonId];
            $eventPersonPersonId = $eventPersonPersonIds[$eventPersonId];
            
            if (isset($eventPersons[$eventPersonId])) {
                $eventPerson = $eventPersons[$eventPersonId];
                if ($eventPerson->personId != $eventPersonPersonId) {
                    $eventPerson->personId  = $eventPersonPersonId;
                    $eventPersonModel->save($eventPerson);
                }
            }
            else {
                if ($eventPersonPersonId) {
                    $eventPerson = $eventPersonModel->newItem();
                    $eventPerson->personTypeId = $eventPersonTypeId;
                    $eventPerson->personId     = $eventPersonPersonId;
                    $eventPerson->eventId      = $eventId;
                    $eventPersonModel->save($eventPerson);
                }
            }
        }
        // Should do delete from team_person where person_id = 0
        
        /* Redirect */
        $response->setRedirect($this->link('sched_ref_signup',$eventId));
    }
}
?>
