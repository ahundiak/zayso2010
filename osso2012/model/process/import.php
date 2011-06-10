<?php
error_reporting(E_ALL);

require 'Process.php';

class ProcessImport extends Process
{
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
  protected function importS5GamesSchedule()
  {
    $datax = $this->config['datax'];

    $import = new \S5Games\Game\GameImport2($this->services);

    $import->process($datax . 's5games/Schedule20110610a.xls');
    
  }
  public function process()
  {
    $this->importS5GamesSchedule();
    return;

    $datax = $this->config['datax'] . 'eayso/latest/';

/*
    $params = array
    (
      'input_file_name' => $datax . 'Players0894.csv',
    );
    $import = new \AYSO\Player\PlayerImport($this->services);
    $import->process($params);
    echo $import->getResultMessage() . "\n";
    

    $params = array
    (
      'input_file_name' => $datax . 'Teams0894.csv',
    );
    $import = new \AYSO\Team\TeamImport($this->services);
    $import->process($params);
    echo $import->getResultMessage() . "\n";
*/
    $params = array
    (
      'input_file_name' => $datax . 'Rosters0894x.csv',
    );
    $import = new \AYSO\Team\TeamPlayerImport($this->services);
    $import->process($params);
    echo $import->getResultMessage() . "\n";

    return;
  }
}
$config  = require '../config/buffy.php';
$process = new ProcessImport($config);
$process->process();
?>
