<?php
class AccountCreatedView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplPage    = 'MasterDelayTpl';
        $this->tplTitle   = 'Account Created';
        $this->tplContent = 'AccountCreatedTpl';
        
        $this->tplRedirectDelay = 3;
        $this->tplRedirectLink  = $this->link('home_login');
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->accountCreatedData = $data;
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
