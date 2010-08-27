<?php
class ReportProcView extends Proj_View
{
  function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Zayso Admin Report Page';
    $this->tplContent = 'ReportProcTpl';
  }
  function process($data)
  {
    $models = $this->context->models;
    $this->reportProcData = $data;

    $this->unitPickList       = $models->UnitModel      ->getPickList();
    $this->yearPickList       = $models->YearModel      ->getPickList();
    $this->seasonTypePickList = $models->SeasonTypeModel->getPickList();
    $this->reportTypePickList = array
    (
        1 => 'Team Summary',
        2 => 'Team Scheduling Keys',
        3 => 'Coach Contact Info',
        4 => 'Referee Utilization',
        5 => 'Referee Points Monrovia',
    );

    /* And render it  */      
    return $this->renderx();
  }
}
?>
