<?php
error_reporting(E_ALL);

class Sync
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
    // Sync Person Table
    // $process = new Osso2007_Person_PersonSync($this->context);
/*
    $process = new Osso_Project_ProjectSync($this->context);
    $process->process();
    echo $process->getResultMessage();
    */
    $xmlFileName = $this->config['datax'] . 'MonroviaPoints.xml';
    
    $process = new Osso2007_Referee_Points_RefPointsMonrovia($this->context);
    $process->process(array('xmlFileName' => $xmlFileName));
    echo $process->getResultMessage() . "\n";

    $xmlFileName = $this->config['datax'] . 'MadisonPoints.xml';

    $process = new Osso2007_Referee_Points_RefPointsMadison($this->context);
    $process->process(array('xmlFileName' => $xmlFileName));
    echo $process->getResultMessage() . "\n";
  }
}
$config = require '../config/config.php';
$process = new Sync($config);
$process->execute();
?>
