<?php
class Osso2007_Import_ImportProcView extends Osso2007_FrontEnd_View
{
  function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Zayso Admin Import Page';
    $this->tplContent = 'Osso2007/Import/ImportProcTpl.html.php';
  }
  protected function dataRemove($dataSessionName,$dataName,$data)
  {
    if (!isset($data[$dataName])) return;
    $datax = $this->context->session->$dataSessionName;
    if (!isset($datax[$dataName])) return;
    unset($datax[$dataName]);
    $this->context->session->$dataSessionName = $datax;
  }
  function process($data)
  {
    $models = $this->context->models;
    $this->importProcData = $data;

    $this->unitPickList       = $models->UnitModel      ->getPickList();
    $this->yearPickList       = $models->YearModel      ->getPickList();
    $this->seasonTypePickList = $models->SeasonTypeModel->getPickList();
        
    /* Render it  */
    $this->renderPage();

    /* Remove any message */
    $this->dataRemove('importProcData','message',$data);
  }
}
?>
