<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_ALL);

class Tests
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
       PATH_SEPARATOR . $ws . 'Cerad/library' .
       PATH_SEPARATOR . $ws . 'PHPUnit-3.4.9'
    );
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();

    $this->context = new Cerad_Context($this->config);

    $GLOBALS['context_tests'] = $this->context;
    
  }
  public function execute()
  {
    require_once 'PHPUnit/Framework.php';
    require_once 'PHPUnit/TextUI/TestRunner.php';

    $suite = new PHPUnit_Framework_TestSuite('OSSO Model Tests');

  //$suite->addTestSuite('Osso_Org_OrgTests');
  //$suite->addTestSuite('Osso_Person_Reg_PersonRegTests');

    $suite->addTestSuite('Osso2007_Account_AccountTests');

    PHPUnit_TextUI_TestRunner::run($suite, array());

  }
}
$config = require '../config/config.php';
$import = new Tests($config);
$import->execute();
?>
