<?php
class FieldSiteListCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $session  = $this->context->session;
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        if (isset($session->fieldSiteListData)) $data = $session->fieldSiteListData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            $data->unitId = $user->unitId;
            $data->name   = NULL;
            $session->fieldSiteListData = $data;   
        }
        $unitId = $request->getParam('id');
        if ($unitId >= 0) $data->unitId = $unitId;
        
        $view = new FieldSiteListView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    public function processActionPost()
    {            
        $session = $this->context->session; 
        $request = $this->getRequest();
        
        $data = new SessionData();
        $data->unitId = $request->getPost('field_site_unit_id');
        $data->name   = $request->getPost('field_site_name');
        $session->fieldSiteListData = $data;
        
        $response = $this->getResponse();
        $response->setRedirect($this->link('field_site_list'));
        
        return;
    }
}
?>
