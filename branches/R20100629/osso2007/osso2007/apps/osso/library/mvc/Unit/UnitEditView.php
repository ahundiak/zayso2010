<?php
class UnitEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Region';
        $this->tplContent = 'UnitEditTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $item = $models->UnitModel->find($data->unitId);
        if (!$item->id) {
            $item->unitTypeId = UnitTypeModel::TYPE_AYSO_REGION;
        }
        $this->unitItem = new Proj_View_Item($this,$item);
        
        $this->unitTypePickList = $models->UnitTypeModel->getPickList();
               
        /* And render it  */      
        return $this->renderx();
    }
}
?>
