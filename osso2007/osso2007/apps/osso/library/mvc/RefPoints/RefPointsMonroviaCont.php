<?php
require_once 'mvc/RefPoints/RefPointsBaseCont.php';

class RefPointsMonroviaCont extends RefPointsBaseCont
{
  protected $redirect = 'ref_points_monrovia';
	   
  public function processAction()
  {
    $response = $this->getResponse();
        
    $session = $this->context->session;
        
    if (isset($session->refPointsMonroviaData)) $data = $session->refPointsMonroviaData;
    else
    {
      $user = $this->context->user;
      $data = new SessionData();
      $data->unitId       = $user->unitId;
      $data->yearId       = $user->yearId;
      $data->seasonTypeId = $user->seasonTypeId;
      $data->divisionId   = 0;
      $data->refereeId    = 0;
			
      $session->refPointsMonroviaData = $data;
    }
    $view = new RefPointsMonroviaView();
        
    $response->setBody($view->process(clone $data));
        
    return;
  }    
}
?>
