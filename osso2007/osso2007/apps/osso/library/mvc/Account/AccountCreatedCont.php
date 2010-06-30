<?php

class AccountCreatedCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->accountCreatedData)) $data = $session->accountCreatedData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            
            $data->accountUser   = NULL;
            $data->accountName   = NULL;
            $data->accountEmail  = NULL;
            
            $data->memberName   = NULL;
            $data->memberUnitId = $user->unitId;
            
            $data->message = NULL;
            
            $session->accountCreatedData = $data;   
        }
        $id = $request->getParam('id');
        
        $view = new AccountCreatedView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
}
?>
