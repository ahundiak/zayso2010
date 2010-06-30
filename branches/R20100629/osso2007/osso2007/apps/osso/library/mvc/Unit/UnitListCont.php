<?php
class UnitListCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $session = $this->context->session;

        if (isset($session->unitListData)) $data = $session->unitListData;
        else {
            $session->unitListData = $data = new SessionData();
        }
        $view = new UnitListView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    public function processActionPost()
    {            
        $session = $this->context->session; 
        $request = $this->getRequest();
        
        $session->unitListData = $data = new SessionData();
        
        $response = $this->getResponse();
        $response->setRedirect($this->link('unit_list'));
        
        return;
    }
}
?>
