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

    require $ws . 'CeradNS/library/Cerad/ClassLoader.php';

    ClassLoader::createNS('Cerad',    $ws . 'CeradNS/library');
    ClassLoader::createNS('Doctrine', $ws . 'doctrine-orm');
    
    ClassLoader::createUS('PHPUnit',  $ws . 'PHPUnit-3.5');
    ClassLoader::createUS('PHPExcel', $ws . 'PHPExcel/Classes');

    ClassLoader::createNS('NatGames', $ws . 'osso2012/model/Entities');

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
    $suite = new PHPUnit_Framework_TestSuite('OSSO2012 Model Tests');

    $suite->addTestSuite('NatGames\tests\InitialTests');
//  $suite->addTestSuite('Test\PersonTests');

//    $suite->addTestSuite('Test\DbTests');
//    $suite->addTestSuite('Test\ServicesTests');
    
//    $suite->addTestSuite('Test\OrmTests');
//    $suite->addTestSuite('Test\MailTests');
//    $suite->addTestSuite('Test\SchemaTests');
//    $suite->addTestSuite('Test\AysoTests');
//    $suite->addTestSuite('Test\UserTests');

//    $suite->addTestSuite('Test\SessionTests');
//    $suite->addTestSuite('Test\ArbiterTests');
    
//    $suite->addTestSuite('Test\EventTests');
//  $suite->addTestSuite('Test\ExcelTests');

// $suite->addTestSuite('Test\ProjectTests');

    PHPUnit_TextUI_TestRunner::run($suite, array());

  }
}
$config = require '/home/ahundiak/zayso2012/osso2012/model/config/config.php';

$tests  = new Tests($config);

$tests->execute();
?>
