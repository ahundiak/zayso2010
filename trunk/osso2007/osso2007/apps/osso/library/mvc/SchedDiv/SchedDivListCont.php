<?php
class SchedDivListCont extends Proj_Controller_Action 
{
    protected function initSessionData()
    {
        $user = $this->context->user;
        $data = new SessionData();
            
        $data->eventTypeId    = 0; // Should be game?
        $data->seasonTypeId   = $user->seasonTypeId;
        $data->scheduleTypeId = 0;
            
        $data->yearId         = $user->yearId;
        $data->unitId         = $user->unitId;
        $data->orderBy        = 1;
        $data->outputType     = 1;
            
        $data->showAge1       = 12;
        $data->showAge2       = -1;
        $data->showBoy        = TRUE;
        $data->showGirl       = TRUE;
        $data->showCoed       = TRUE;
        $data->showHome       = TRUE;
        $data->showAway       = TRUE;
            
        $date1 = $this->context->models->DateTimeModel->getToday();
        $date2 = '20100701'; //$this->context->models->DateTimeModel->getNextSunday();
            
        $data->dateYear1      = substr($date1,0,4);
        $data->dateYear2      = substr($date2,0,4);
        $data->dateMonth1     = substr($date1,4,2);
        $data->dateMonth2     = substr($date2,4,2);
        $data->dateDay1       = substr($date1,6,2);
        $data->dateDay2       = substr($date2,6,2);
        
        $data->teamId = 0;
        
        return $data;    
    }
    public function processAction()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();           
        $session  = $this->context->session;
        
        if (isset($session->schedDivListData)) $data = $session->schedDivListData;
        else {
            $data = $this->initSessionData();
            
            $session->schedDivListData = $data;
        }
        /* Pull divisions and possibly unit out of url */
        $id = $request->getParam('id');
        if ($id >= 0) $data->unitId = $id;
        $id = $request->getParam('id2');
        if ($id >= 0) {
            $data->showAge1 = $id;
            $data->ahowAge2 = -1;
        }
        /* Process */
        $view = new SchedDivListView();
        
        /* Always reset output type */
        $datax = clone $data;
        
        if ($data->outputType != 1)
        {
        	$data->outputType = 1;
        	$session->schedDivListData = $data;
        }
//Zend_Debug::dump($response); die();        
        $response->setBody($view->process($datax));
        
        return;
    }
    public function processActionPost()
    {            
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $redirect = $this->link('sched_div_list');
        $session  = $this->context->session;
        
        /* See if got here via the home page */
        $browse = $request->getPost('browse_schedules');
        if ($browse) {
            $data = $this->initSessionData();
            $data->unitId   = $request->getPost('browse_unit_id');
            $data->showAge1 = $request->getPost('browse_age_id');
            $session->schedDivListData = $data;
            return $response->setRedirect($redirect);
        }
        
        /* Extract search data */        
        $data = new SessionData();
        
        $data->eventTypeId    = $request->getPost('sched_div_event_type_id');
        $data->seasonTypeId   = $request->getPost('sched_div_season_type_id');
        $data->scheduleTypeId = $request->getPost('sched_div_schedule_type_id');
        $data->yearId         = $request->getPost('sched_div_year_id');
        $data->unitId         = $request->getPost('sched_div_unit_id');
        $data->orderBy        = $request->getPost('sched_div_order_by');
        $data->outputType     = $request->getPost('sched_div_output_type');
        $data->dateYear1      = $request->getPost('sched_div_date_year1');
        $data->dateYear2      = $request->getPost('sched_div_date_year2');
        $data->dateMonth1     = $request->getPost('sched_div_date_month1');
        $data->dateMonth2     = $request->getPost('sched_div_date_month2');
        $data->dateDay1       = $request->getPost('sched_div_date_day1');
        $data->dateDay2       = $request->getPost('sched_div_date_day2');
        $data->showAge1       = $request->getPost('sched_div_show_age1');
        $data->showAge2       = $request->getPost('sched_div_show_age2');
        $data->showBoy        = $request->getPost('sched_div_show_boy');
        $data->showGirl       = $request->getPost('sched_div_show_girl');
        $data->showCoed       = $request->getPost('sched_div_show_coed');
        $data->showHome       = $request->getPost('sched_div_show_home');
        $data->showAway       = $request->getPost('sched_div_show_away');
        $data->teamId         = $request->getPost('sched_div_team_id');
        
        $session->schedDivListData = $data;
        
        return $response->setRedirect($redirect);
    }
}
?>
