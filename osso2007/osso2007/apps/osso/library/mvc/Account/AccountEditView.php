<?php
class AccountEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Account';
        $this->tplContent = 'AccountEditTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $account = $models->AccountModel->find($data->accountId);
        
        $this->accountItem = new Proj_View_Item($this,$account);
        
        $search = new SearchData();
        $search->accountId   = $account->id;
        $search->wantx       = TRUE;
        $search->wantPerson  = TRUE;
        $search->wantAccount = TRUE;
        
        $this->members = $models->MemberModel->search($search);
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
