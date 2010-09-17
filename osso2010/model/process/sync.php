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

    $this->context = new Osso2007_Context($this->config);
  }
  public function execute()
  {
    // Sync Project Stuff
    $process = new Osso2007_Project_ProjectSync2($this->context);
    $process->process(array());
    echo $process->getResultMessage() . "\n";
  }
}
$config = require '../config/config.php';
$sync = new Sync($config);
$sync->execute();
?>
