<?php
/* ----------------------------------------------
 * Basically a list of admin commands
 */
class AdminIndexCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $response = $this->getResponse();
        
        $data = new SessionData(); 
        $view = new AdminIndexView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
}
?>
