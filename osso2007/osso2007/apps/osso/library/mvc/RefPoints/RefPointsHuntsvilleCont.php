<?php
require_once 'mvc/RefPoints/RefPointsBaseCont.php';

class RefPointsHuntsvilleCont extends RefPointsBaseCont
{
  protected $redirect = 'ref_points_huntsville';
	   
  public function processAction()
  {
    $response = $this->getResponse();
        
    $session = $this->context->session;
        
    if (isset($session->refPointsHuntsvilleData)) $data = $session->refPointsHuntsvilleData;
    else
    {
      $user = $this->context->user;
      $data = new SessionData();
      $data->unitId       = 7; //$user->unitId;
      $data->yearId       = $user->yearId;
      $data->seasonTypeId = $user->seasonTypeId;
      $data->divisionId   = 0;
      $data->refereeId    = 0;
			
      $session->refPointsHuntsvilleData = $data;
    }
    $view = new RefPointsHuntsvilleView();
        
    $response->setBody($view->process(clone $data));
        
    return;
  }    
}
?>
