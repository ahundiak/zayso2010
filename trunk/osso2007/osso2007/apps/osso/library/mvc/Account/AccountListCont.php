<?php

class AccountListCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $session  = $this->context->session;
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        /* Init the session if needed */    
        if (isset($session->accountListData)) $data = $session->accountListData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            $data->accountUnitId = $user->unitId;
            $data->accountUser   = NULL;
            $data->accountName   = NULL;
            $data->accountEmail  = NULL;
            $session->accountListData = $data;
        }
        
        /* Kick off the view */
        $view = new AccountListView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
	public function processActionPost()
	{
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $model    = $this->context->models->AccountModel;
        
        $submitSearch = $request->getPost('account_submit_search');
        if ($submitSearch) {
            
            $data = $this->context->session->accountListData;
             
            $data->accountUser  = $request->getPost('account_user');
            $data->accountName  = $request->getPost('account_name');
            $data->accountEmail = $request->getPost('account_email');
            
            $this->context->session->accountListData = $data;
            return $response->setRedirect($this->link('account_list'));
            
        }
		
	}		
}
?>
