<?php

class AccountIndexCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
       
        // Must be authenticated
        $user = $this->context->user;
        if (!$user->isAuth) return $response->setRedirect($this->link('home_index'));
        
        // process
        if (isset($session->accountIndexData)) $data = $session->accountIndexData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            
            $data->schedDivUnitId = $user->unitId;
            $data->schedDivAgeId  = 12;
            
            $data->schedRefUnitId = $user->unitId;
            $data->schedRefAgeId  = 12;
            
            $session->accountIndexData = $data;   
        }
        $view = new AccountIndexView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    public function processActionPost()
    {            
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $session  = $this->context->session;
        
        $data = $session->accountIndexData;
        if ($request->getPost('sched_div_submit')) {
            $data->schedDivUnitId = $request->getPost('sched_div_unit_id');
            $data->schedDivAgeId  = $request->getPost('sched_div_age_id');
            return $response->setRedirect($this->link('sched_div_list',$data->schedDivUnitId,$data->schedDivAgeId));
        }
        if ($request->getPost('sched_ref_submit')) {
            $data->schedRefUnitId = $request->getPost('sched_ref_unit_id');
            $data->schedRefAgeId  = $request->getPost('sched_ref_age_id');
            return $response->setRedirect($this->link('sched_ref_list',$data->schedRefUnitId,$data->schedRefAgeId));
        }
        return $response->setRedirect($this->link('account_index'));
    }
}
?>
