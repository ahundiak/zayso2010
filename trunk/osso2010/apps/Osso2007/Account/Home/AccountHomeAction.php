<?php

class Osso2007_Account_Home_AccountHomeAction extends Osso2007_Action
{
  public function processGet($args)
  {
    $session  = $this->context->session;
       
    // Must be authenticated
    $user = $this->context->user;
    if (!$user->isAuth) return $this->context->response->setRedirect($this->link('home_index'));
        
    // process
    if (isset($session->accountHomeData)) $data = $session->accountHomeData;
    else
    {
      $user = $this->context->user;
      $data = new SessionData();
            
      $data->schedDivUnitId = $user->unitId;
      $data->schedDivAgeId  = 12;
            
      $data->schedRefUnitId = $user->unitId;
      $data->schedRefAgeId  = 12;
            
      $session->accountHomeData = $data;
    }
    $view = new Osso2007_Account_Home_AccountHomeView($this->context);
        
    $view->process(clone $data);
        
    return;
  }
  public function processPost($args)
  {            
    $request  = $this->context->request;
    $session  = $this->context->session;
    
    $data = $session->accountHomeData;
    if ($request->getPost('sched_div_submit'))
    {
      $data->schedDivUnitId = $request->getPost('sched_div_unit_id');
      $data->schedDivAgeId  = $request->getPost('sched_div_age_id');
      $session->accountHomeData = $data;
      return $this->redirect('sched_div_list',$data->schedDivUnitId,$data->schedDivAgeId);
    }
    if ($request->getPost('sched_ref_submit'))
    {
      $data->schedRefUnitId = $request->getPost('sched_ref_unit_id');
      $data->schedRefAgeId  = $request->getPost('sched_ref_age_id');
      $session->accountHomeData = $data;
      return $this->redirect('sched_ref_list',$data->schedRefUnitId,$data->schedRefAgeId);
    }
    return $this->redirect('account_home');
  }
}
?>
