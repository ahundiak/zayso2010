<?php
class AccountCreateView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Create Account';
        $this->tplContent = 'AccountCreateTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->accountCreateData = $data;
        
        $this->unitPickList = $models->UnitModel->getPickList();
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
