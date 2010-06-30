<?php
class FieldListCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $session  = $this->context->session;
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        /* Init the session if needed */    
        if (isset($session->fieldListData)) $data = $session->fieldListData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            $data->unitId      = $user->unitId;
            $data->fieldSiteId = 0;  
            $session->fieldListData = $data;
        }
        $unitId      = $request->getParam('id');
        $fieldSiteId = $request->getParam('id2');
        
        if ($unitId      >= 0) $data->unitId      = $unitId;
        if ($fieldSiteId >= 0) $data->fieldSiteId = $fieldSiteId;
        
        /* Kick off the view */
        $view = new FieldListView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    public function processActionPost()
    {            
        $session  = $this->context->session; 
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        $session->fieldListData = $data = new SessionData();
        
        $data->unitId      = $request->getPost('field_unit_id');
        $data->fieldSiteId = $request->getPost('field_field_site_id');
        
        $response->setRedirect($this->link('field_list'));
        
        return;
    }
}
?>
