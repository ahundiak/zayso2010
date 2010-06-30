<?php
class AccountListView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'List Accounts';
        $this->tplContent = 'AccountListTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $search = new SearchData();
        
        $search->searchLike = TRUE;
        
        $search->user  = $this->accountUser  = $data->accountUser;
        $search->name  = $this->accountName  = $data->accountName;
        $search->email = $this->accountEmail = $data->accountEmail;
        
        if ($search->user || $search->name || $search->email) {
            $this->accounts = $models->AccountModel->search($search);
        }
        else $this->accounts = array();
        
        /* Render it */
        return $this->renderx();
    }
}
?>
