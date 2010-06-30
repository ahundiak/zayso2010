<?php
class HomeLogoutCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        if (Zend_Session::sessionExists()) {
            Zend_Session::destroy();
        }
        $this->getResponse()->setRedirect($this->link('home_index'));
    }
}
?>
