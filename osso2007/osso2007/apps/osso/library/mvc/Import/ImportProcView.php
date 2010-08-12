<?php
class ImportProcView extends Proj_View
{
  function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Zayso Admin Import Page';
    $this->tplContent = 'ImportProcTpl';
  }
  function process($data)
  {
    $models = $this->context->models;
    $this->importProcData = $data;

    $this->unitPickList       = $models->UnitModel      ->getPickList();
    $this->yearPickList       = $models->YearModel      ->getPickList();
    $this->seasonTypePickList = $models->SeasonTypeModel->getPickList();
        
    /* And render it  */      
    return $this->renderx();
  }
}
?>
