<?php
class HomeLogoutCont extends Proj_Controller_Action 
{
  public function processAction()
  {
    $this->context->session->user = NULL;
    $this->context->session->destroy();
    $this->getResponse()->setRedirect($this->link('home_index'));
  }
}
?>
