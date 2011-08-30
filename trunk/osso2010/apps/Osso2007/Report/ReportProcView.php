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
      case 1: $reportClassName = 'Osso2007_Report_ReportTeamSummaryCSV'; break;
      case 2: $reportClassName = 'Osso2007_Report_ReportTeamKeysCSV';    break;

      case 3: $reportClassName = 'Osso2007_Team_Coach_CoachContactReport';   break;
      case 4: $reportClassName = 'Osso2007_Referee_RefereeUtilReport';       break;

      case 5: $reportClassName = 'Osso2007_Referee_Points_RefPointsMonrovia';   break;
      case 6: $reportClassName = 'Osso2007_Referee_Points_RefPointsMadison';    break;

      case 7: $reportClassName = 'Osso2007_Project_ProjectSync2';    break;
    }
    if (!$reportClassName) return FALSE;

    $params = array
    (
      'project_id' => $data['project_id'],
      'unit_id'    => $data['org_id'],
      'date_ge'    => '20110801',
      'date_le'    => '20111231',
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
      if ($result) 
      {
        switch($data['report_type_id'])
        {
            case 1: $reportFileName = 'TeamSummary.csv';        break;
            case 2: $reportFileName = 'TeamKeys.csv';           break;
            case 3: $reportFileName = 'CoachContactReport.csv'; break;
            case 4: $reportFileName = 'RefereeUtilReport.csv';  break;
            case 5: $reportFileName = 'RefPointsMonrovia.csv';  return; break;
            case 6: $reportFileName = 'RefPointsMadison.csv';   return; break;
            case 7: $reportFileName = 'ProjectSync.csv';        break;
        }
            $this->context->response->setBody($result);
            $this->context->response->setFileHeaders($reportFileName);
            return;
      }
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
