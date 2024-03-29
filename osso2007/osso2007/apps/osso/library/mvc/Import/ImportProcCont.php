<?php
error_reporting(E_ALL);

class ImportProcCont extends Proj_Controller_Action
{
  public function processAction()
  {
    $response = $this->getResponse();

    $request  = $this->getRequest();
    $session  = $this->context->session;

    if (isset($session->importProcData)) $data = $session->importProcData;
    else
    {
      $user = $this->context->user;

      $data = new SessionData();
      $data->unitId       = $user->unitId;
      $data->yearId       = $user->yearId;
      $data->seasonTypeId = $user->seasonTypeId;

      $data->fileName = '';

      $session->importProcData = $data;
    }
         
    $view = new ImportProcView();

    $datax = clone $data;

    if ($data->message)
    {
      $data->message = NULL;
      $session->importProcData = $data;
    }
    $response->setBody($view->process($datax));
        
    return;
  }
  protected $map = array
  (
    'Osso2007_Schedule_SchImport'            => array('Date','Time','Field','HomeTeam','AwayTeam'),
    'Eayso_Reg_Main_RegMainImport'           => array('AYSOID',          'WorkPhoneExt',     'Membershipyear'),
    'Eayso_Reg_Cert_RegCertImport'           => array('AYSOID',          'CertificationDesc','CertDate'),
    'Eayso_Reg_Cert_Type_RegCertTypeImport'  => array('id','desc1','desc2','desc3','table reg_cert_type'),
    'Osso2007_Team_Phy_PhyTeamImport'        => array('TeamDesignation', 'TeamID',           'TeamAsstCoachFName'),
    'Osso2007_Team_Phy_PhyTeamRosterImport'  => array('Team Designation','Region #',         'Asst. Team Coach AYSO ID'),
  );
  protected function getImportClassName($tmpName,$fileName)
  {
    if (!strcmp($fileName,'AreaMasterSchedule20100816.xml')) return 'Osso2007_Schedule_SchImportMA';

    $fp = fopen($tmpName,'r');
    if (!$fp) return NULL;

    $header = fgetcsv($fp);
    fclose($fp);

    foreach($this->map as $class => $names)
    {
      $haveAll = true;
      foreach($names as $name)
      {
        if (array_search($name,$header) === FALSE) $haveAll = false;
      }
      if ($haveAll) return $class;
    }
    return NULL;
  }
  public function processActionPost()
  {
    $request  = $this->getRequest();
    $response = $this->getResponse();
    $session  = $this->context->session;

    $redirect = $this->link('import_proc');

    // Make sure authorized
    if (!$this->context->user->isAdminx)  return $response->setRedirect($redirect);

    // Pull data
    $data = new SessionData();
    $data->unitId       = $request->getPost('import_unit_id');
    $data->yearId       = $request->getPost('import_year_id');
    $data->seasonTypeId = $request->getPost('import_season_type_id');
    $data->fileName     = $_FILES['import_file']['name'];
    
    $session->importProcData = $data;

    $tmpName = $_FILES['import_file']['tmp_name'];
    if (!$tmpName) return $response->setRedirect($redirect);

    $importClassName = $this->getImportClassName($tmpName,$data->fileName);
    
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
