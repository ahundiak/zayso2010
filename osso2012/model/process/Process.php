<?php
error_reporting(E_ALL);

use Cerad\ClassLoader;

class Process
{
  protected $config;
  protected $services;

  function __construct($config)
  {
    $this->config = $config;
    $this->init();
  }
  protected function init()
  {
    $ws = $this->config['ws'];

    require $ws . 'CeradNS/library/Cerad/ClassLoader.php';

    ClassLoader::createNS('Cerad',    $ws . 'CeradNS/library');
  //ClassLoader::createUS('PHPUnit',  $ws . 'PHPUnit-3.5');
    ClassLoader::createUS('PHPExcel', $ws . 'PHPExcel/Classes');
    ClassLoader::createNS('Doctrine', $ws . 'doctrine-orm');

  //ClassLoader::createNS('tests',     $ws . 'osso2012/model/testsNS');
    ClassLoader::createNS('S5Games',   $ws . 'osso2012/model/Entities');
  //ClassLoader::createNS('OSSO',     $ws . 'osso2012/model/Entities');

    /* --------------------------------------------------
     * PHPUnit classes use require_once so still need this
     */
    // ini_set('include_path',PATH_SEPARATOR . $ws . 'PHPUnit-3.5');
    //ini_set('include_path',$ws . 'PHPExcel\Classes');

    $this->services = new \Cerad\Services($this->config);
    
    return;
  }
  public function process()
  {
    echo "Base Process\n";
  }
}
?>
