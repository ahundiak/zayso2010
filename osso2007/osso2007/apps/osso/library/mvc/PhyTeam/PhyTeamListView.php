<?php
class PhyTeamListView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'List Physical Teams';
        $this->tplContent = 'PhyTeamListTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->phyTeamListData = $data;
                     
        $this->unitPickList       = $models->UnitModel      ->getPickList();
        $this->yearPickList       = $models->YearModel      ->getPickList();
        $this->divisionPickList   = $models->DivisionModel  ->getDivisionPickList();
        $this->seasonTypePickList = $models->SeasonTypeModel->getPickList();
        
        /* Query for physical teams if have enough data */
        $flag = TRUE;
        if (!$data->unitId)       $flag = FALSE;
        if (!$data->yearId)       $flag = FALSE;
        if (!$data->divisionId)   $flag = FALSE; /* This should become a range */
        if (!$data->seasonTypeId) $flag = FALSE;
        if ( $data->alwaysSearch) $flag = TRUE;
        if ($flag) {
            $data->wantx = TRUE;
            $data->wantCoachHead = TRUE;
            $this->phyTeams = $models->PhyTeamModel->search($data);
        }
        else $this->phyTeams = array();
//Zend::dump($this->phyTeams); die();        
        return $this->renderx();
    }
}
?>