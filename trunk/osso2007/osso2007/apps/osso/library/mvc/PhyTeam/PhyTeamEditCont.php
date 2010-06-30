<?php
class PhyTeamEditCont extends Proj_Controller_Action 
{
    protected $adminOnly = TRUE;
    
	public function processAction()
	{
        $request = $this->getRequest();
        $session = $this->context->session;
        
        if (isset($session->phyTeamEditData)) $data = $session->phyTeamEditData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData();
            
            $data->phyTeamId = 0;
            
            $session->phyTeamEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->phyTeamId = $id;
 
		$view = new PhyTeamEditView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));
        
	    return;
	}
    public function processActionPost()
    {
         
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        if (!$this->isAuthorized()) return $response->setRedirect($this->link('phy_team_edit'));
        
        $model    = $this->context->models->PhyTeamModel;

        $submitUpdate = $request->getPost('phy_team_submit_update');
        if (!$submitUpdate) {
            return $response->setRedirect($this->link('phy_team_edit'));
        }      
        $itemId = $request->getPost('phy_team_id');      
        $item   = $model->find($itemId);
        if (!$item->id) {
            return $response->setRedirect($this->link('phy_team_list'));
        }
        $item->divisionSeqNum = $request->getPost('phy_team_seq_num');
        $item->name           = $request->getPost('phy_team_name');
        $item->colors         = $request->getPost('phy_team_colors');
        
        $model->save($item);

        /* Update Related Tables */
        $this->processPersons($itemId);

        /* Redirect back to Edit */        
        $response->setRedirect($this->link('phy_team_edit',$itemId));
    }
    /* ------------------------------------------
     * Update the team people
     */
    protected function processPersons($phyTeamId)
    {
        $request = $this->getRequest();
        $model   = $this->context->models->PhyTeamPersonModel;
        
        if (!$phyTeamId) return;
        
        $itemIds        = $request->getPost('phy_team_person_ids');
        $itemPersonIds  = $request->getPost('phy_team_person_person_ids');
        $itemVolTypeIds = $request->getPost('phy_team_person_vol_type_ids');
        
        foreach($itemIds as $key => $itemId) {
            
            $itemPersonId  = $itemPersonIds [$key];
            $itemVolTypeId = $itemVolTypeIds[$key];
            
            /* Check for update */
            if ($itemId > 0) {
                
                /* No person means delete it */
                if (!$itemPersonId) $model->delete($itemId);
                else {
                    $item = $model->find($itemId);
                    $item->personId = $itemPersonId;
                    $model->save($item);
                }
            }
            else {
                if ($itemPersonId) {
                    $item = $model->find(0);
                    $item->personId  = $itemPersonId;
                    $item->volTypeId = $itemVolTypeId;
                    $item->phyTeamId = $phyTeamId;
                    $model->save($item);
                }
            }
        }
    }
}
?>
