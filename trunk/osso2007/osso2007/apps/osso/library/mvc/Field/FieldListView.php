<?php
class FieldListView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'List Fields';
        $this->tplContent = 'FieldListTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
                       
        $this->unitPickList = $models->UnitModel->getPickList();
        
        /* Check for field site but no unit */
        if ((!$data->unitId) && ($data->fieldSiteId)) {
            $fieldSiteItem = $models->FieldSiteModel->find($data->fieldSiteId);
            $data->unitId = $fieldSiteItem->unitId;    
        }
        /* Veirfy field site is in sync with unit */     
        if ($data->unitId) {            
            $this->fieldSitePickList = $models->FieldSiteModel->getPickList($data->unitId);            
            if ($data->fieldSiteId) {
                if (!isset($this->fieldSitePickList[$data->fieldSiteId])) $data->fieldSiteId = 0;
            }
        }
        else $this->fieldSitePickList = array();
        
        $this->fieldListData = $data;
        $data->wantx = TRUE;  
        $this->fields = $fields = $models->FieldModel->search($data);

        /* Render it */
        return $this->renderx();
    }
}
?>
