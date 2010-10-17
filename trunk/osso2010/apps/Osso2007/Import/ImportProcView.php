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
    $this->importProcData = $data;

    $repoOrg     = $this->context->repos->org;
    $repoMisc    = $this->context->repos->misc;
    $repoProject = $this->context->repos->project;

    $this->orgPickList        = $repoOrg->getPickList();
    $this->yearPickList       = $repoMisc->getYearPickList();
    $this->seasonTypePickList = $repoMisc->getSeasonTypePickList();
    $this->projectPickList    = $repoProject->getActiveProjectsPickList();
    
    /* Render it  */
    $this->renderPage();

    /* Remove any message */
    $this->dataRemove('importProcData','message',$data);
  }
}
?>
