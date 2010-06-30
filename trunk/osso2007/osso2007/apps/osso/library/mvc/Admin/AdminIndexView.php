<?php
class AdminIndexView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Zayso Admin Home Page';
        $this->tplContent = 'AdminIndexTpl';
    }
    function process($data)
    {
        $this->adminIndexData = $data;
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
