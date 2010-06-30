<?php
/* ----------------------------------------------
 * Referee points page
 */
class RefPointsBaseCont extends Proj_Controller_Action 
{
    protected $enableTeamSelection = true;
    protected $redirect = 'ref_points_xxx';
       
    public function processSubmitAdd()
    {        
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $session  = $this->context->session;
        
        $redirect = $this->link($this->redirect);
        
        if (!$this->enableTeamSelection) return $response->setRedirect($redirect);
        
        $models   = $this->context->models;
        $phyTeamRefereeModel = $models->PhyTeamRefereeModel;
        
        /* Extract search data */        
        $data = new SessionData();
        $data->unitId       = $request->getPost('ref_points_add_unit_id');
        $data->yearId       = $request->getPost('ref_points_add_year_id');
        $data->seasonTypeId = $request->getPost('ref_points_add_season_type_id');
        $data->divisionId   = $request->getPost('ref_points_add_division_id');
        $data->refereeId    = $request->getPost('ref_points_add_referee_id');
        
        $session->refPointsIndexData = $data;
//Zend_Debug::dump($data);   die();     
        $refereeId = $request->getPost('ref_points_add_referee_id');
        $phyTeamId = $request->getPost('ref_points_add_team_id');
        
        // Need both a referee and a team to get anywhere
        if (!$refereeId || !$phyTeamId) return $response->setRedirect($redirect);
        
        // See if already have some
        $search = new SearchData();
        $search->refereeId    = $refereeId;
        $search->unitId       = $data->unitId;
        $search->yearId       = $data->yearId;
        $search->seasonTypeId = $data->seasonTypeId;
        
        $teams = $phyTeamRefereeModel->search($search);
        
        // No more than three teams
        if (count($teams) >= 3) return $response->setRedirect($redirect);
        
        $sortx = 0;
        foreach($teams as $team) {
        	if ($team->phyTeamId == $phyTeamId) return $response->setRedirect($redirect);  // No dups
        	if ($team->priRegular > $sortx) $sortx = $team->priRegular;
        }
        $sortx++;
        if ($sortx > 3) return $response->setRedirect($redirect);  // Priority messed up
        
        // For the new record
        $teamx = $phyTeamRefereeModel->find(0);
        $teamx->refereeId  = $refereeId;
        $teamx->phyTeamId  = $phyTeamId;
        
        $teamx->priRegular = $sortx;
        $teamx->priTourn   = $sortx;
        
        $teamx->maxRegular = 0;
        $teamx->maxTourn   = 0;
        $teamx->unitId       = $data->unitId;
        $teamx->yearId       = $data->yearId;
        $teamx->seasonTypeId = $data->seasonTypeId;
       
        // Default to max points
		$maxRegular = array(
			 7 => 12,  8 => 12,  9 => 12, // U10
			10 => 12, 11 => 12, 12 => 12, // U12
			13 => 15, 14 => 15, 15 => 15, // U14
			16 => 20, 17 => 20, 18 => 20, // U16
			19 => 20, 20 => 20, 21 => 20, // U19
		);
 		$maxTourn = array(
			 7 => 4,  8 => 4,  9 => 4, // U10
			10 => 4, 11 => 4, 12 => 4, // U12
			13 => 5, 14 => 5, 15 => 5, // U14
			16 => 6, 17 => 6, 18 => 6, // U16
			19 => 6, 20 => 6, 21 => 6, // U19
		);
        $phyTeam = $models->PhyTeamModel->find($phyTeamId);
        $divId = $phyTeam->divisionId;
        if (isset($maxRegular[$divId])) $teamx->maxRegular = $maxRegular[$divId];
        if (isset($maxTourn  [$divId])) $teamx->maxTourn   = $maxTourn  [$divId];
        
        $phyTeamRefereeModel->save($teamx);
        
        return $response->setRedirect($redirect);
        
    }
    public function processSubmitUpdate()
    {            
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $session  = $this->context->session;
        
        $redirect = $this->link($this->redirect);
        
        $models   = $this->context->models;
        $phyTeamRefereeModel = $models->PhyTeamRefereeModel;
        
        // Have any deletes ? */
        $deleteIds = $request->getPost('ref_team_deletes');
        if (is_array($deleteIds) && (count($deleteIds) > 0))
        {
        	if (!$this->enableTeamSelection) return $response->setRedirect($redirect);
        
        	$phyTeamRefereeModel->delete($deleteIds);
        	
        	// Really need to check the remaining records and adjust priority
        	return $response->setRedirect($redirect);
        	
        }
        $refTeamIds         = $request->getPost('ref_team_ids');
        $refTeamPriRegulars = $request->getPost('ref_team_pri_regulars');
        $refTeamPriTourns   = $request->getPost('ref_team_pri_tourns');
        $refTeamMaxRegulars = $request->getPost('ref_team_max_regulars');
        $refTeamMaxTourns   = $request->getPost('ref_team_max_tourns');
        
        foreach($refTeamIds as $id)
        {
        	$refTeam = $phyTeamRefereeModel->find($id);
        	if ($refTeam->id) 
        	{
        	    $refTeam->priRegular = $refTeamPriRegulars[$id];
        		$refTeam->priTourn   = $refTeamPriTourns  [$id];
        	    $refTeam->maxRegular = $refTeamMaxRegulars[$id];
        	    $refTeam->maxTourn   = $refTeamMaxTourns  [$id];
        	    
        		$phyTeamRefereeModel->save($refTeam);
        	}
        }
        return $response->setRedirect($redirect);
    }
    public function processActionPost()
    {            
        $request  = $this->getRequest();
        
        /* Check if we need to link a team */
        $submitAdd = $request->getPost('ref_points_add_submit');
        if ($submitAdd) return $this->processSubmitAdd();
        
        $submitUpdate = $request->getPost('ref_teams_submit_update');
        if ($submitUpdate) return $this->processSubmitUpdate();
        
        $redirect = $this->link($this->redirect);
        
        return $response->setRedirect($redirect);
                
    }
}
?>
