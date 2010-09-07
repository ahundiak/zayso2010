<?php
class Osso2007_Report_ReportProcView extends Osso2007_View
{
  protected function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Zayso Admin Report Page';
    $this->tplContent = 'Osso2007/Report/ReportProcTpl.html.php';
  }
  protected function posted($data)
  {
    $reportClassName = NULL;
    switch($data['report_type_id'])
    {
      case 10: $reportClassName = 'Osso2007_Report_ReportTeamSummaryCSV'; break;
      case 20: $reportClassName = 'Osso2007_Report_ReportTeamKeysCSV';    break;

      case 4: $reportClassName = 'Osso2007_Referee_RefereeUtilReport';   break;

      case 5: $reportClassName = 'Osso2007_Referee_Points_RefPointsMonrovia';   break;
      case 6: $reportClassName = 'Osso2007_Referee_Points_RefPointsMadison';    break;

      case 7: $reportClassName = 'Osso2007_Project_ProjectSync';    break;
    }
    if (!$reportClassName) return FALSE;

    $params = array
    (
      'unit_id' => $data['org_id'],
      'date_ge' => '20100801',
      'date_le' => '20101231',
    );
    $report = new $reportClassName($this->context);

    $result = $report->process($params);

    return $result;
  }
  public function process($data)
  {
    if ($data['posted'])
    {
      $datax = $this->context->session->reportProcData;
      $datax['posted'] = false;
      $this->context->session->reportProcData = $datax;
      
      $result = $this->posted($data);
      if ($result) return;
    }

    // Usual page
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
        6 => 'Referee Points Madison RS',
    );
    if ($this->context->user->personId == 1)
    {
      $this->reportTypePickList[7] = 'Project Sync Report';
    }
    /* And render it  */      
    return $this->context->response->setBody($this->renderPage());
  }
}
?>
