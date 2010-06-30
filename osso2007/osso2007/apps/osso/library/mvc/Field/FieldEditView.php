<?php
class FieldEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Field';
        $this->tplContent = 'FieldEditTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        $field = $models->FieldModel->find($data->fieldId);
        
        if (!$field->fieldSiteId) $field->fieldSiteId = $data->fieldSiteId;
        if (!$field->unitId) {
            if (!$field->fieldSiteId) $field->unitId = $data->unitId;
            else {
                $fieldSite = $models->FieldSite->find($field->fieldSiteId);
                $field->unitId = $fieldSite->unitId;
            }
        }
        $this->unitPickList      = $models->UnitModel     ->getPickList();
        $this->fieldSitePickList = $models->FieldSiteModel->getPickList($field->unitId);
        
        $this->fieldItem = new Proj_View_Item($this,$field);
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
