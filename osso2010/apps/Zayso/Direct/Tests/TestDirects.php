<?php
define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2010/');

ini_set('include_path','.' .
  PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'osso2010x/apps/zayso' .
  PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'Cerad/library' .
  PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'PHPUnit-3.4.3'
);
require_once 'Cerad/Loader.php';
Cerad_Loader::registerAutoload();

if (!defined('PHPUnit_MAIN_METHOD')) {
     define ('PHPUnit_MAIN_METHOD', 'TestDirects::main');
}

class TestDirects
{
  public static function main()
  {
    $parameters = array();

    PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
  }
  public static function suite()
  {
    $suite = new PHPUnit_Framework_TestSuite('osso2010 tests');

    // $suite->addTestSuite('StackTest');

    // $suite->addTestSuite('PrepareTest');
    // $suite->addTestSuite('EaysoTest');
    $suite->addTestSuite('EventPersonTest');
    $suite->addTestSuite('UserTest');
    
    return $suite;
  }
}

if (PHPUnit_MAIN_METHOD == 'TestDirects::main') { TestDirects::main(); }

?>
