<?php
class PhyTeamListCont extends Proj_Controller_Action 
{
    public function processAction()
    {            
        $session = $this->context->session;
        
        if (isset($session->phyTeamListData)) $data = $session->phyTeamListData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            $data->unitId       = $user->unitId;
            $data->yearId       = $user->yearId;
            $data->seasonTypeId = $user->seasonTypeId;
            $data->divisionId   = 0;
            $data->alwaysSearch = FALSE;
            $session->phyTeamListData = $data;
        }
        $view = new PhyTeamListView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    public function processActionPost()
    {            
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $session  = $this->context->session;
        
        $redirect = $this->link('phy_team_list');
        
        $models       = $this->context->models;
        $phyTeamModel = $models->PhyTeamModel;
        $schTeamModel = $models->SchTeamModel;
        
        /* Check if teams need to be deleted */
        $submitDelete = $request->getPost('phy_team_submit_delete');
        if ($submitDelete) {
            $confirm = $request->getPost('phy_team_confirm_delete');
            if ($confirm) {
                $phyTeamIds = $request->getPost('phy_team_delete_ids');
                $phyTeamModel->delete($phyTeamIds);
            }
            return $response->setRedirect($redirect);
        }
        /* Extract search data */        
        $data = new SessionData();
        $data->unitId       = $request->getPost('phy_team_unit_id');
        $data->yearId       = $request->getPost('phy_team_year_id');
        $data->seasonTypeId = $request->getPost('phy_team_season_type_id');
        $data->divisionId   = $request->getPost('phy_team_division_id');
        $data->alwaysSearch = TRUE;    
        $session->phyTeamListData = $data;
        
        /* Check if we need to create teams */
        $submitCreate = $request->getPost('phy_team_submit_create');
        $numTeams     = $request->getPost('phy_team_num_to_create');
        
        $flag = TRUE;
        if (!$submitCreate)       $flag = FALSE;
        if ($numTeams < 1)        $flag = FALSE;
        if (!$data->unitId)       $flag = FALSE;
        if (!$data->yearId)       $flag = FALSE;
        if (!$data->divisionId)   $flag = FALSE;
        if (!$data->seasonTypeId) $flag = FALSE;
        if (!$flag) return $response->setRedirect($redirect);
        
        $seqn = $phyTeamModel->getHighestSeqNum($data) + 1;
        for($cnt = 0; $cnt < $numTeams; $cnt++) {
            
            /* Create the physical team */
            $item = $phyTeamModel->find(0);
            $item->unitId       = $data->unitId;
            $item->yearId       = $data->yearId;
            $item->seasonTypeId = $data->seasonTypeId;
            $item->divisionId   = $data->divisionId;
            $item->divisionSeqNum = $seqn++;
            $phyTeamId = $phyTeamModel->save($item);//echo "PT $phyTeamId ";
            
            /* And an associated schedule team */
            $item = $schTeamModel->find(0);
            $item->phyTeamId      = $phyTeamId;
            $item->unitId         = $data->unitId;
            $item->yearId         = $data->yearId;
            $item->divisionId     = $data->divisionId;
            $item->seasonTypeId   = $data->seasonTypeId;
            $item->scheduleTypeId = ScheduleTypeModel::TYPE_REGULAR_SEASON;
            $item->sort           = $seqn;
            $schTeamId = $schTeamModel->save($item);//echo "ST $schTeamId ";
        }
        return $response->setRedirect($redirect);
    }
}
?>
