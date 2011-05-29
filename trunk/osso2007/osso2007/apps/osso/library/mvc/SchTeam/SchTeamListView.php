<?php
class SchTeamListView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'List Schedule Teams';
        $this->tplContent = 'SchTeamListTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->schTeamListData = $data;
                     
        $this->unitPickList         = $models->UnitModel        ->getPickList();
        $this->yearPickList         = $models->YearModel        ->getPickList();
        $this->divisionPickList     = $models->DivisionModel    ->getDivisionPickList();
        $this->seasonTypePickList   = $models->SeasonTypeModel  ->getPickList();
        $this->scheduleTypePickList = $models->ScheduleTypeModel->getPickList();

        /* Query for schedule teams if have enough data */
        $flag = TRUE;
        if (!$data->unitId)         $flag = FALSE;
        if (!$data->yearId)         $flag = FALSE;
        if (!$data->divisionId)     $flag = FALSE; /* This should become a range */
        if (!$data->seasonTypeId)   $flag = FALSE;
        if (!$data->scheduleTypeId) $flag = FALSE;
        if ( $data->alwaysSearch)   $flag = TRUE;
        if ($flag) {
            $data->wantx = TRUE;
            $data->wantPhyTeam   = TRUE;
            $data->wantCoachHead = TRUE;
            $this->schTeams = $models->SchTeamModel->search($data);
        }
        else $this->schTeams = array();
        
        //Zend::dump($this->schTeams); die();        
        return $this->renderx();
    } 
}
?>