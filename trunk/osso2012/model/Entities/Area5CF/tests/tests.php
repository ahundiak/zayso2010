<?php
error_reporting(E_ALL);

use Cerad\ClassLoader;

class Tests
{
  protected $config;
  function __construct($config)
  {
    $this->config = $config;
    $this->init();
  }
  protected function init()
  {
    $ws = $this->config['ws'];

    require $ws . 'osso2012/libs/Cerad/ClassLoader.php';

    ClassLoader::createNS('Cerad',    $ws . 'osso2012/libs');
    ClassLoader::createNS('Doctrine', $ws . 'doctrine-orm');
    
    ClassLoader::createUS('PHPUnit',  $ws . 'PHPUnit-3.5');
    ClassLoader::createUS('PHPExcel', $ws . 'PHPExcel/Classes');

    ClassLoader::createNS('Area5CF',  $ws . 'osso2012/model/Entities');

    /* --------------------------------------------------
     * PHPUnit classes use require_once so still need this
     *
     */
    ini_set('include_path',
                        $ws . 'PHPUnit-3.5' .
       PATH_SEPARATOR . $ws . 'PHPExcel\Classes'
    );
    return;
  }
  public function execute()
  { 
    $suite = new PHPUnit_Framework_TestSuite('Area5CF Model Tests');

    $suite->addTestSuite('Area5CF\tests\InitialTests');

    PHPUnit_TextUI_TestRunner::run($suite, array());

  }
}
$config = require '/home/ahundiak/zayso2012/osso2012/model/config/config.php';

$tests  = new Tests($config);

$tests->execute();
?>
