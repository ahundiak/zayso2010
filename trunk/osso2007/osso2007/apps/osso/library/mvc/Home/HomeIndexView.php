<?php
class HomeIndexView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Zayso Home Page';
        $this->tplContent = 'HomeIndexTpl';
        $this->tplLogin   = 'HomeLoginTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->homeIndexData = $data;
        $this->homeLoginData = $data;

        $this->unitPickList = $models->UnitModel->getPickList();
        $this->agePickList  = $models->Division->getAgePickList();
               
        /* And render it  */      
        return $this->renderx();
    }
}
?>
