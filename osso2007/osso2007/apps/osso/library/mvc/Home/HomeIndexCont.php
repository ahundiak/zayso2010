<?php
class HomeIndexCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $request  = $this->getRequest();
        $response = $this->getResponse();
                
        /* Allows coming in with a unit id in the url */
        $unitId = $request->getParam('id');
        
        /* Otherwise go with the user defaults */
        if ($unitId <= 0) $unitId = $this->context->user->unitId;
        
        $data = new SessionData();
        $data->unitId = $unitId;
        $data->ageId  = 12;
        
        $view = new HomeIndexView();
        $response->setBody($view->process(clone $data));
        
	    return;
	}
}
?>
