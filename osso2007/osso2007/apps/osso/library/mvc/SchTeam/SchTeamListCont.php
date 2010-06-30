<?php
class SchTeamListCont extends Proj_Controller_Action 
{
    public function processAction()
    {            
        $session = $this->context->session;
        
        if (isset($session->schTeamListData)) $data = $session->schTeamListData;
        else {
            $user = $this->context->user;
            $data = new SessionData;
            $data->unitId         = $user->unitId;
            $data->yearId         = $user->yearId;
            $data->seasonTypeId   = $user->seasonTypeId;
            $data->scheduleTypeId = ScheduleTypeModel::TYPE_REGULAR_SEASON;
            $data->alwaysSearch   = FALSE;    
            $session->schTeamListData = $data;
        }
        $view = new SchTeamListView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    public function processActionPost()
    {            
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $redirect = $this->link('sch_team_list');
        $session  = $this->context->session;
        $model    = $this->context->models->SchTeamModel;
        
        /* Check if teams need to be deleted */
        $submitDelete = $request->getPost('sch_team_submit_delete');
        if ($submitDelete) {
            $confirm = $request->getPost('sch_team_confirm_delete');
            if ($confirm) {
                $schTeamIds = $request->getPost('sch_team_delete_ids');
                $model->delete($schTeamIds);
            }
            return $response->setRedirect($redirect);
        }
        /* Extract search data */
        $data = new SessionData();
        $data->unitId         = $request->getPost('sch_team_unit_id');
        $data->yearId         = $request->getPost('sch_team_year_id');
        $data->divisionId     = $request->getPost('sch_team_division_id');
        $data->seasonTypeId   = $request->getPost('sch_team_season_type_id');
        $data->scheduleTypeId = $request->getPost('sch_team_schedule_type_id');
        $data->alwaysSearch   = TRUE;    
               
        $session->schTeamListData = $data;
        
        /* See if need to create any new teams */
        $submitCreate = $request->getPost('sch_team_submit_create');
        $numTeams     = $request->getPost('sch_team_num_to_create');
        
        $flag = TRUE;
        if (!$submitCreate)         $flag = FALSE;
        if ($numTeams < 1)          $flag = FALSE;
        if (!$data->unitId)         $flag = FALSE;
        if (!$data->yearId)         $flag = FALSE;
        if (!$data->divisionId)     $flag = FALSE;
        if (!$data->seasonTypeId)   $flag = FALSE;
        if (!$data->scheduleTypeId) $flag = FALSE;
        if (!$flag) return $response->setRedirect($redirect);
        
        $seqn = 1;
        for($cnt = 0; $cnt < $numTeams; $cnt++) 
        {
            $item = $model->find(0);
            $item->phyTeamId    = 0;
            $item->unitId         = $data->unitId;
            $item->yearId         = $data->yearId;
            $item->divisionId     = $data->divisionId;
            $item->seasonTypeId   = $data->seasonTypeId;
            $item->scheduleTypeId = $data->scheduleTypeId;
            $item->sort           = $seqn++;
            
            $schTeamId = $model->save($item);//echo "ST $schTeamId ";
        }
        return $response->setRedirect($redirect);
    }
}
?>
