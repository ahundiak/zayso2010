<?php
class MemberEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Account Member';
        $this->tplContent = 'MemberEditTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $search = new SearchData();
        $search->memberId = $data->memberId;
        $search->wantPerson = TRUE;
        
        $member = $models->MemberModel->searchOne($search);
        if (!$member) {
            $member =  $models->MemberModel->newItem();
            $member->unitId    = $data->unitId;
            $member->accountId = $this->context->user->account->id;
            $member->level  = 2;
            $member->status = 1;
        }
        $this->memberItem = new Proj_View_Item($this,$member);
        
        $account = $models->AccountModel->find($member->accountId);
        
        $this->accountItem = new Proj_View_Item($this,$account);
       
        $this->unitPickList = $models->UnitModel->getPickList();
        
        /* Setup for volunteer searching */
        if ($data->personLastName) $personLastName = $data->personLastName;
        else                       $personLastName = $account->name;
        
        if ($data->personUnitId)   $personUnitId = $data->personUnitId;
        else                       $personUnitId = $member->unitId;
        
        $this->personLastName = $personLastName;
        $this->personUnitId   = $personUnitId;
        
        /* Now need to query the people */
        if (!$personLastName) $personItems = array();
        else {
            $data = new SearchData();
            $data->wantx = TRUE;
            $data->lname = $personLastName;
            $data->unitId = $personUnitId;
            $personItems = $models->PersonModel->search($data);
        }
        $this->personItems = $personItems;
             
        /* And render it  */      
        return $this->renderx();
    }
}
?>
