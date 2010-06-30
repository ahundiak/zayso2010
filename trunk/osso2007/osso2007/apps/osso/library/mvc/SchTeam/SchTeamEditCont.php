<?php
class SchTeamEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $request = $this->getRequest();
        $session = $this->context->session;
        
        if (isset($session->schTeamEditData)) $data = $session->schTeamEditData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData();
            
            $data->schTeamId = 0;
            
            $session->schTeamEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->schTeamId = $id;
 
        $view = new SchTeamEditView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));

	    return;
	}
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $model    = $this->context->models->SchTeamModel;

        $submitUpdate = $request->getPost('sch_team_submit_update');
        if (!$submitUpdate) {
            return $response->setRedirect($this->link('sch_team_edit'));
        }      
        $itemId = $request->getPost('sch_team_id');      
        $item   = $model->find($itemId);
        if (!$item->id) {
            return $response->setRedirect($this->link('sch_team_list'));
        }
        $item->phyTeamId = $request->getPost('sch_team_phy_team_id');
        $item->sort      = $request->getPost('sch_team_sort');
        $item->desc      = $request->getPost('sch_team_desc');
        
        $model->save($item);

        /* Redirect back to Edit */        
        $response->setRedirect($this->link('sch_team_edit',$itemId));
    }
}
?>
