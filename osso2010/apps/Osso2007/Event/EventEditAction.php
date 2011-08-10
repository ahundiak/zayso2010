<?php
class Osso2007_Event_EventEditAction extends Osso2007_Action
{
  protected function initSessionData()
  {
    $user = $this->context->user;
    $data = new SessionData();
            
    $data->eventTypeId    = 0; // Should be game?
    $data->seasonTypeId   = $user->seasonTypeId;
    $data->scheduleTypeId = 0;
    $data->point2         = 0;
        
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
    $data->showAway       = FALSE;
           
    $date1 = $this->context->models->DateTime->getToday();
    $date2 = $this->context->models->DateTime->getNextSunday();
       
    $data->dateYear1      = substr($date1,0,4);
    $data->dateYear2      = substr($date2,0,4);
    $data->dateMonth1     = substr($date1,4,2);
    $data->dateMonth2     = substr($date2,4,2);
    $data->dateDay1       = substr($date1,6,2);
    $data->dateDay2       = substr($date2,6,2);
        
    $data->show = 0;
        
    return $data;    
  }
  public function processGet($args)
  {
    //Cerad_Debug::dump($args); die('Event Edit Action');
    $data = new SessionData();

    if (isset($args[0])) $data->eventId = $args[0];
    else                 $data->eventId = 0;

    $view = new Osso2007_Event_EventEditView($this->context);

    $view->process(clone($data));

    return;

    $request  = $this->context->request;
    $session  = $this->context->session;
        
    if (isset($session->schedRefListData)) $data = $session->schedRefListData;
    else
    {
      $data = $this->initSessionData();
      $session->schedRefListData = $data;
    }
    /* Pull divisions and possibly unit out of url */
    if (isset($args[0])) $id = $args[0];
    else                 $id = -1;

    if ($id >= 0) $data->unitId = $id;
        
    if (isset($args[1])) $id = $args[1];
    else                 $id = -1;

    if ($id >= 0)
    {
      $data->showAge1 = $id;
      $data->ahowAge2 = -1;
    }
    $datax = clone $data;

    // Check for Spreadsheet
    if ($data->outputType != 1)
    {
      $data->outputType = 1;
      $session->schedRefListData = $data;
    }
    $view = new Osso2007_Referee_Schedule_RefSchListView($this->context);

    $view->process($datax);
        
    return;
  }
  public function processPost($args)
  {            
    $session  = $this->context->session;
    $request  = $this->context->request;
    $response = $this->context->response;
    
    $redirect = $this->link('sched_ref_list');
       
    /* See if got here via the home page */
    $browse = $request->getPost('browse_schedules');
    if ($browse)
    {
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
    $data->point2         = $request->getPost('event_point2');
    $data->show           = $request->getPost('sched_div_show');

    // Check output type
    $data->outputType = 1;
    $spreadsheet = $request->getPost('sched_ref_submit_spreadsheet');
    if ($spreadsheet) $data->outputType = 2;
        
    $session->schedRefListData = $data;
        
    return $response->setRedirect($redirect);
  }
}
?>
