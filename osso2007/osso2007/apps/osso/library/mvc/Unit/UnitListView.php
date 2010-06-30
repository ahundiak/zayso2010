<?php
class UnitListView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'List Regions';
        $this->tplContent = 'UnitListTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $this->unitListData = $data;
        
        $data->wantx = TRUE;
        $this->unitItems = $unitItems = $models->UnitModel->search($data);

        /* Render it */        
        return $this->renderx();
    }
}
?>
