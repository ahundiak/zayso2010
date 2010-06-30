<?php
class AccountIndexView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Account Home';
        $this->tplContent = 'AccountIndexTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->accountIndexData = $data;
        
        $this->unitPickList = $models->UnitModel->getPickList();
        $this->agePickList  = $models->Division->getAgePickList();
        
        $this->account = $this->context->user->account;
        
        $search = new SearchData();
        $search->accountId = $this->account->id;
        $search->wantx       = TRUE;
        $search->wantPerson  = TRUE;
        $search->wantAccount = TRUE;
        
        $this->members = $models->MemberModel->search($search);
        
        //Zend_Debug::dump($this->members); die(0);
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
