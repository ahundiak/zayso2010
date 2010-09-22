<?php
error_reporting(E_ALL);

class Osso2007_Import_ImportProcAction extends Osso2007_FrontEnd_Action
{
  public function processGet($args)
  {
    $session  = $this->context->session;

    if (isset($session->importProcData)) $data = $session->importProcData;
    else                                 $data = null;
    if (!is_array($data)) $data = null;
    if (!$data)
    {
      $user = $this->context->user;

      $data = array
      (
        'unit_id'         => $user->unitId,
        'year_id'         => $user->yearId,
        'season_type_id'  => $user->seasonTypeId,
        'file_name'       => '',
      );
      $session->importProcData = $data;
    }
    $view = new Osso2007_Import_ImportProcView($this->context);

    $view->process($data);
        
    return;
  }
  protected $map = array
  (
    'Osso2007_Team_Sch_SchTeamImport'        => array('Region', 'Div','Schedule Team','Physical Team'),
    'Osso2007_Schedule_Import_SchImport'     => array('Date','Time','Field','Home Team','Away Team','Type','Number'),
    'Eayso_Reg_Main_RegMainImport'           => array('AYSOID','WorkPhoneExt','Membershipyear'),
    'Eayso_Reg_Cert_RegCertImport'           => array('AYSOID','CertificationDesc','CertDate'),
    'Eayso_Reg_Cert_Type_RegCertTypeImport'  => array('id','desc1','desc2','desc3','table reg_cert_type'),
    'Osso2007_Team_Phy_PhyTeamImport'        => array('TeamDesignation', 'TeamID','TeamAsstCoachFName'),
    'Osso2007_Team_Phy_PhyTeamRosterImport'  => array('Team Designation','Region #','Asst. Team Coach AYSO ID'),
    'Osso2007_Project_ProjectImport'         => array('project_table'),
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
  public function processPost($args)
  {
    $request  = $this->context->request;
    $response = $this->context->response;
    $session  = $this->context->session;

    $redirect = 'import_proc';

    // Make sure authorized
    if (!$this->context->user->isAdmin)  return $response->setRedirect($redirect);

    // Pull data
    $data = array
    (
      'unit_id'        => $request->getPostInt('import_unit_id'),
      'year_id'        => $request->getPostInt('import_year_id'),
      'season_type_id' => $request->getPostInt('import_season_type_id'),
      'file_name'     => $_FILES['import_file']['name'],
    );
    // $session->importProcData = $data;

    $tmpName = $_FILES['import_file']['tmp_name'];
    if (!$tmpName) return $response->setRedirect($redirect);

    $importClassName = $this->getImportClassName($tmpName,$data['file_name']);
    
    if (!$importClassName)
    {
      $data['message'] = 'Could not determine file type';
      $session->importProcData = $data;
      return $response->setRedirect($redirect);
    }

    $import = new $importClassName($this->context);
    $import->process($tmpName);

    $data['message'] = $import->getResultMessage();

    $session->importProcData = $data;

    // Done
    return $response->setRedirect($redirect);
  }
}
?>
