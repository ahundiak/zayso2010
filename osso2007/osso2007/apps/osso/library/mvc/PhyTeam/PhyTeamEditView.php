<?php
class PhyTeamEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Physical Team';
        $this->tplContent = 'PhyTeamEditTpl';
    }
    function process($data)
    {
        $models  = $this->context->models;
        
        $phyTeamPersonModel = $models->PhyTeamPersonModel;
        $phyTeamModel       = $models->PhyTeamModel;
        $volTypeModel       = $models->VolTypeModel;
        $volModel           = $models->VolModel;
        
        $search = new SearchData();
        $search->phyTeamId = $data->phyTeamId;
        $search->wantx     = TRUE;
        
        $phyTeam = $phyTeamModel->searchOne($search);
        if (!$phyTeam) {
            /* Really want to redirect to the list */
            $phyTeam = $phyTeamModel->find(0);die('Trying to edit non-existing physical team');
        }
        $this->phyTeam = new Proj_View_Item($this,$phyTeam);
        
        /* Want a list of three basic team positions */
        $volTypeIds = array(VolTypeModel::TYPE_HEAD_COACH, VolTypeModel::TYPE_ASST_COACH, VolTypeModel::TYPE_TEAM_PARENT);
        $phyTeamPersonId = -1;
        foreach($volTypeIds as $volTypeId) {
            $phyTeamPerson = new Proj_View_Item($this,$phyTeamPersonModel->find(0));
            $phyTeamPerson->phyTeamPersonId = $phyTeamPersonId--;
            $phyTeamPerson->volTypeId       = $volTypeId;
            $phyTeamPerson->volTypeDesc     = $volTypeModel->getDesc($volTypeId);
            $phyTeamPersons[$volTypeId] = $phyTeamPerson;
        }
        $search = new SearchData();
        $search->phyTeamId = $phyTeam->id;
        $search->wantx     = TRUE;
        if ($phyTeam->id) $phyTeamPersonsExisting = $phyTeamPersonModel->search($search);
        else              $phyTeamPersonsExisting = array();
        foreach($phyTeamPersonsExisting as $phyTeamPerson) {
            $phyTeamPersons[$phyTeamPerson->volTypeId] = new Proj_View_Item($this,$phyTeamPerson);
        }
        /* Need assorted person pick lists */
        $search = new SearchData();
        $search->yearId       = $phyTeam->yearId;
        $search->unitId       = $phyTeam->unitId;
        $search->divisionId   = $phyTeam->divisionId;
        $search->seasonTypeId = $phyTeam->seasonTypeId;
        
        foreach($volTypeIds as $volTypeId) {
            $search->volTypeId = $volTypeId;
            if ($phyTeam->id) $personPickList = $volModel->getPersonPickList($search); 
            else              $personPickList = array();
            $phyTeamPersons[$volTypeId]->personPickList = $personPickList;
        }
        $this->phyTeamPersons = $phyTeamPersons;
        
//Zend::dump($phyTeamPersons); die();
       
        /* And render it  */      
        return $this->renderx();        
    }
}
?>
