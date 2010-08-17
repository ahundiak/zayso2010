<?php
error_reporting(E_ALL);

class ReportProcCont extends Proj_Controller_Action
{
  protected function posted($data)
  {
    $reportClassName = NULL;
    switch($data->reportTypeId)
    {
      case 1: $reportClassName = 'Osso2007_Report_ReportTeamSummaryCSV'; break;
      case 2: $reportClassName = 'Osso2007_Report_ReportTeamKeysCSV';    break;
    }
    if (!$reportClassName) return NULL;

    $report = new $reportClassName($this->context);
    $result = $report->process($data);
    
    header('Pragma: public');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
    header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
    header ("Pragma: no-cache");
    header("Expires: 0");
    header('Content-Transfer-Encoding: none');
    header('Content-Type: text/csv;');

    //header('Content-Type: application/vnd.ms-excel;'); // This should work for IE & Opera
    //header("Content-type: application/x-msexcel"); // This should work for the rest

    header('Content-Disposition: attachment; filename="'. 'report.csv' .'"');

    return $result;

  }
  public function processAction()
  {
    $response = $this->getResponse();

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
  protected $map = array
  (
    'Eayso_Reg_Main_RegMainImport'          => array('AYSOID',          'WorkPhoneExt',     'Membershipyear'),
    'Eayso_Reg_Cert_RegCertImport'          => array('AYSO ID',         'CertificationDesc','Certification Date'),
    'Osso2007_Team_Phy_PhyTeamImport'       => array('TeamDesignation', 'TeamID',           'TeamAsstCoachFName'),
    'Osso2007_Team_Phy_PhyTeamRosterImport' => array('Team Designation','Region #',         'Asst. Team Coach AYSO ID'),
  );
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
    
    $tmpName = $_FILES['import_file']['tmp_name'];
    if (!$tmpName) return $response->setRedirect($redirect);

    $fp = fopen($tmpName,'r');
    if (!$fp)
    {
      $data->message = "Could not open file for reading";
      $session->importProcData = $data;
      return $response->setRedirect($redirect);
    }
    $header = fgetcsv($fp);
    fclose($fp);

    $importClassName = NULL;
    foreach($this->map as $class => $names)
    {
      $haveAll = true;
      foreach($names as $name)
      {
        if (array_search($name,$header) === FALSE) $haveAll = false;
      }
      if ($haveAll) $importClassName = $class;
    }
    if (!$importClassName)
    {
      $data->message = 'Could not determine file type';
      $session->importProcData = $data;
      return $response->setRedirect($redirect);
    }

    $import = new $importClassName($this->context);
    $import->process($tmpName);

    $data->message = $import->getResultMessage();

    $session->importProcData = $data;

    // Done
    $response->setRedirect($redirect);
    return;
  }
}
?>
