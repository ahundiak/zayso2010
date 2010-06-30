<?php
error_reporting(E_ALL);

class Import
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

    $this->context = new Cerad_Context($this->config);
  }
  public function execute()
  {
    $datax = $this->config['datax'];

    // eayso2007 units
    
    //$path  = $datax . 'osso/';

  // Psrt of the transfer process
  //$import = new Org_Import_Unit($this->context);
  //$import->process($path . 'Unitx.csv');
  //echo $import->getResultMessage() . "\n";

  //$import = new Org_Import_Region($this->context);
  //$import->process($path . 'Regionx.csv');
  //echo $import->getResultMessage() . "\n";

    // Master organization update ***
    /*
    $import = new Org_Import_Org($this->context);
    $import->process($datax . 'osso/Orgx.csv');
    echo $import->getResultMessage() . "\n";
    */

    // Persons
    /*
    $import = new Osso_Person_Import_PersonImport($this->context);
    $import->process($datax . 'osso/Personx.csv');
    echo $import->getResultMessage() . "\n";
    */

    // Accounts
    $import = new Osso2007_Account_AccountImport($this->context);
    $import->process($datax . 'osso/Account2007x.csv');
    echo $import->getResultMessage() . "\n";

    // Volunteers
    $path  = $datax . 'eayso/';

    $files = array(
      '20100626/Vols2008.csv',
      '20100626/Vols2009.csv',
      '20100626/Vols2010.csv'
    );
    $files = array();
    foreach($files as $file)
    {
      $import = new Eayso_Import_Vol($this->context);
      $import->process($path . $file);
      echo $import->getResultMessage() . "\n";;
    }

    // Referee Certs
    $path  = $datax . 'eayso/20100626/';
    $files = array(
      'CertsRefereeNational.csv',
      'CertsRefereeNational2.csv',
      'CertsRefereeAdvanced.csv',
      'CertsRefereeIntermediate.csv',
      'CertsRefereeRegional.csv',
      'CertsRefereeRegionalSafeHaven.csv',
      'CertsRefereeU08.csv',
      'CertsRefereeU08SafeHaven.csv',
      'CertsRefereeAssistant.csv',
      'CertsRefereeAssistantSafeHaven.csv',
      'CertsRefereeSafeHaven.csv',
      'CertsRefereeSafeHavenZ.csv',
      'CertsAssessor.csv',
      'CertsAssessorNational.csv',
    );
    $files = array();
    foreach($files as $file)
    {
      $import = new Eayso_Import_VolCert($this->context);
      $import->process($path . $file);
      echo $import->getResultMessage() . "\n";;
    }
    // Coaching Certs
    $path  = $datax . 'eayso/20100626/';
    $files = array(
//      'CertsCoachNational.csv',
//      'CertsCoachAdvanced.csv',
//      'CertsCoachAdvancedCC.csv',
//      'CertsCoachIntermediate.csv',
//      'CertsCoachIntermediateCC.csv',
//      'CertsCoachSafeHaven.csv',
//      'CertsCoachSafeHavenZ.csv',
      'CertsCoachU06.csv',
      'CertsCoachU06Z.csv',
      'CertsCoachU08.csv',
      'CertsCoachU08Z.csv',
      'CertsCoachU10.csv',
      'CertsCoachU10Z.csv',
      'CertsCoachU12.csv',
      'CertsCoachVIP.csv',
      'CertsCoachB.csv',
    );
    $files = array();
    foreach($files as $file)
    {
      $import = new Eayso_Import_VolCert($this->context);
      $import->process($path . $file);
      echo $import->getResultMessage() . "\n";;
    }
  }
}
$config = require '../config/config.php';
$import = new Import($config);
$import->execute();
echo "Import complete\n";
?>
