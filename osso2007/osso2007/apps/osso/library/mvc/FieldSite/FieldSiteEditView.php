<?php
class FieldSiteEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Field Site';
        $this->tplContent = 'FieldSiteEditTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $item = $models->FieldSiteModel->find($data->fieldSiteId);
        
        if (!$item->unitId) $item->unitId = $data->unitId;
        
        $this->fieldSiteItem = new Proj_View_Item($this,$item);
        $this->unitPickList = $models->UnitModel->getPickList();
                
        /* And render it  */      
        return $this->renderx();
    }
}
?>
