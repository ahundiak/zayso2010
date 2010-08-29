<?php
error_reporting(E_ALL);

class ReportProcCont extends Proj_Controller_Action
{
  protected function posted($data)
  {
    $reportClassName = NULL;
    switch($data->reportTypeId)
    {
      case 10: $reportClassName = 'Osso2007_Report_ReportTeamSummaryCSV'; break;
      case 20: $reportClassName = 'Osso2007_Report_ReportTeamKeysCSV';    break;

      case 4: $reportClassName = 'Osso2007_Referee_RefereeUtilReport';   break;

      case 5: $reportClassName = 'Osso2007_Referee_Points_RefPointsMonrovia';   break;
      case 6: $reportClassName = 'Osso2007_Referee_Points_RefPointsMadison';    break;
    }
    if (!$reportClassName) return NULL;

    $params = array
    (
      'unit_id' => $data->unitId,
      'date_ge' => '20100801',
      'date_le' => '20101231',
    );
    $report = new $reportClassName($this->context);
    $result = $report->process($params);
    $file   = $report->getOutputFileName();

    header('Pragma: public');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
    header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
    header ("Pragma: no-cache");
    header("Expires: 0");
    header('Content-Transfer-Encoding: none');

    if (strstr($file,'.xls'))
    {
      header('Content-Type: text/xml;');
      header('Content-Type: application/vnd.ms-excel;'); // This should work for IE & Opera
      header("Content-type: application/x-msexcel"); // This should work for the rest

    }
    if (strstr($file,'.csv'))
    {
      header('Content-Type: text/csv;');
    }

    header('Content-Disposition: attachment; filename="'. $file .'"');

    return $result;

  }
  public function processAction()
  {
    $response = $this->getResponse();
// Cerad_Debug::dump($response); die();
    $request  = $this->getRequest();
    $session  = $this->context->session;

    if (isset($session->reportProcData)) $data = $session->reportProcData;
    else
    {
      $user = $this->context->user;

      $data = new SessionData();
      $data->unitId       = $user->unitId;
      $data->yearId       = $user->yearId;
      $data->seasonTypeId = $user->seasonTypeId;
      $data->reportTypeId = 0;
      $data->posted       = false;

      $session->reportProcData = $data;
    }
         
    $view = new ReportProcView();

    $datax = clone $data;

    if ($data->posted)
    {
      $data->posted = false;
      $session->reportProcData = $data;
      $result = $this->posted($datax);
      if ($result)
      {
        $response->setBody($this->posted($datax));
        return;
      }
    }
    if ($data->message)
    {
      $data->message = NULL;
      $session->reportProcData = $data;
    }
    $response->setBody($view->process($datax));
        
    return;
  }
  public function processActionPost()
  {
    $request  = $this->getRequest();
    $response = $this->getResponse();
    $session  = $this->context->session;

    $redirect = $this->link('report_proc');

    // Make sure authorized
    if (!$this->context->user->isAdminx)  return $response->setRedirect($redirect);

    // Pull data
    $data = new SessionData();
    $data->unitId       = $request->getPost('report_unit_id');
    $data->yearId       = $request->getPost('report_year_id');
    $data->seasonTypeId = $request->getPost('report_season_type_id');
    $data->reportTypeId = $request->getPost('report_type_id');
    $data->posted       = true;
    
    // $data->fileName     = $_FILES['import_file']['name'];
    
    $session->reportProcData = $data;
    return $response->setRedirect($redirect);
    
  }
}
?>
