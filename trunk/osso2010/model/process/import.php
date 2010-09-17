<?php
error_reporting(E_ALL);

class ProcessImport
{
  protected $config;
  protected $context;
  function __construct($config)
  {
    $this->config = $config;
    $this->init();
  }
  protected function init()
  {
    $ws = $this->config['ws'];
    ini_set('include_path','.' .
       PATH_SEPARATOR . $ws . 'osso2010/model/classes' .
       PATH_SEPARATOR . $ws . 'Cerad/library'
    );
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();

    $this->context = new Osso2007_Context($this->config);
  }
  protected function importEayso($datax)
  {
    // 8300 reg_main initial import
    //
    // Volunteers
    $path  = $datax . 'eayso/20100811/';

    $files = array(
      'Vols2008.csv',
      'Vols2009.csv',
      'Vols2010.csv'
    );
    //$files = array();
    foreach($files as $file)
    {
      $import = new Eayso_Reg_Main_RegMainImport($this->context);
      $import->process($path . $file);
      echo $import->getResultMessage() . "\n";;
    }
    // Certs
    $files = array(
      'CertsReferee.csv',
      'CertsRefereex.csv',
      'CertsRefereeSafeHaven.csv',
      'CertsCoach.csv',
      'CertsCoachSafeHaven.csv',
    );
    $files = array();
    foreach($files as $file)
    {
      $import = new Eayso_Reg_Cert_RegCertImport($this->context);
      $import->process($path . $file);
      echo $import->getResultMessage() . "\n";;
    }
    return;
  }
  protected function importPersons($datax)
  {
    $import = new Osso_Person_PersonImport($this->context);
    $import->process($datax . 'osso/Person.csv');
    echo $import->getResultMessage() . "\n";
  }
  protected function importTeams($datax)
  {
    $import = new Osso_Team_Phy_PhyTeamImport($this->context);
    $import->process($datax . 'eayso/teams/Teams0498.csv');
    echo $import->getResultMessage() . "\n";
  }
  protected function importTeams2007($datax)
  {
    $import = new Osso2007_Team_Phy_PhyTeamImport($this->context);
    $import->process($datax . 'eayso/20100813/Teams0557.csv');
    echo $import->getResultMessage() . "\n";

    $import = new Osso2007_Team_Phy_PhyTeamRosterImport($this->context);
    $import->process($datax . 'eayso/20100813/Teams0894.csv');
    echo $import->getResultMessage() . "\n";

  }
  protected function importSchedules2007($datax)
  {
    $import = new Osso2007_Schedule_SchImport($this->context);
  //$import->allowUpdates = FALSE;
    $import->process($datax . 'eayso/20100815/Schedule0894.csv');
    echo $import->getResultMessage() . "\n";
  }
  protected function importMasterAreaSchedule($datax)
  {
    $import = new Osso2007_Schedule_SchImportMA($this->context);
  //$import->allowUpdates = FALSE;
    $import->process($datax . 'eayso/20100815/AreaMasterSchedule20100816.xml');
    echo $import->getResultMessage() . "\n";
  }
  public function execute()
  {
    $datax = $this->config['datax'];

  //$this->importEayso($datax);
  //$this->importPersons($datax);
  //$this->importTeams2007($datax);
  //$this->importSchedules2007($datax);
  //$this->importMasterAreaSchedule($datax);

//    $import = new Osso2007_Team_Sch_SchTeamImport($this->context);
//    $import->process($datax . 'Schedules/RT1.csv');
//    echo $import->getResultMessage() . "\n";

    $params = array
    (
        'input_file_name' => $datax . 'Schedules/RT1.csv',
        'project_id'      => 31,
    );
    $import = new Osso2007_Schedule_Import_SchImport($this->context);
    $import->process($params);
    echo $import->getResultMessage() . "\n";

    return;
  }
}
$config = require '../config/config.php';
$process = new ProcessImport($config);
$process->execute();
echo "Import complete\n";
?>
