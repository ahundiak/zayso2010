<?php
class SchTeamEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Schedule Team';
        $this->tplContent = 'SchTeamEditTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $search = new SearchData();
        $search->schTeamId = $data->schTeamId;
        $search->wantx     = TRUE;
        
        $schTeam = $models->SchTeamModel->searchOne($search);
        if (!$schTeam) {
            /* Really want to redirect to the list */
            $schTeam = $models->SchTeamModel->find(0);die('Trying to edit non-existing schedule team');
        }
        $this->schTeam = new Proj_View_Item($this,$schTeam);
        
        /* Physical team pick list */
        $search = new SearchData();
        $search->yearId       = $schTeam->yearId;
      //$search->unitId       = $schTeam->unitId;
      //$search->divisionId   = $schTeam->divisionId;
        $search->seasonTypeId = $schTeam->seasonTypeId;
        
        $this->phyTeamPickList = $models->PhyTeamModel->getPickList($search);
        
        /* And render it  */      
        return $this->renderx();        
    }
}
?>
