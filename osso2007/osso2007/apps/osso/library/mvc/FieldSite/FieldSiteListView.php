<?php
class FieldSiteListView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'List Field Sites';
        $this->tplContent = 'FieldSiteListTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->fieldSiteListData = $data;
                       
        $this->unitPickList = $models->UnitModel->getPickList();
        
        $this->fieldSites = $fieldSites = $models->FieldSiteModel->search($data);

        /* Render it */        
        return $this->renderx();
    }
}
?>
