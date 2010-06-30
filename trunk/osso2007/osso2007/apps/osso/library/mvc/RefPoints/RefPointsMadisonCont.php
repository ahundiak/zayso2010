<?php
require_once 'mvc/RefPoints/RefPointsBaseCont.php';

class RefPointsMadisonCont extends RefPointsBaseCont
{
	protected $redirect = 'ref_points_madison';
	
    public function processAction()
    {
        $response = $this->getResponse();
        
        $session = $this->context->session;
        
        if (isset($session->refPointsMadisonData)) $data = $session->refPointsMadisonData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            $data->unitId       = $user->unitId;
            $data->yearId       = $user->yearId;
            $data->seasonTypeId = $user->seasonTypeId;
            $data->divisionId   = 0;
			$data->refereeId    = 0;
			
            $session->refPointsMadisonData = $data;
        }
        $view = new RefPointsMadisonView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
}
?>
