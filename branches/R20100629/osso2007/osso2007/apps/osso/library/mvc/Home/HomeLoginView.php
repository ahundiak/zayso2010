<?php
class HomeLoginView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Zayso Home Login Page';
        $this->tplContent = 'HomeLoginTpl';
    }
    function process($data)
    {
        $this->homeLoginData = $data;
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
