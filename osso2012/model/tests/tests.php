<?php
error_reporting(E_ALL);

use Doctrine\Common\ClassLoader;

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
       PATH_SEPARATOR . $ws . 'osso2012/model/Entities' .
       PATH_SEPARATOR . $ws . 'Cerad/library' .
       PATH_SEPARATOR . $ws . 'PHPUnit-3.4.9'
    );


    require $ws . 'doctrine-orm-b4/Doctrine/Common/ClassLoader.php';

    $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $ws . 'doctrine-orm-b4');
    $classLoader->register();

    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();

    return;
  }
  public function execute()
  {
    require_once 'PHPUnit/Framework.php';
    require_once 'PHPUnit/TextUI/TestRunner.php';

    $suite = new PHPUnit_Framework_TestSuite('OSSO2012 Model Tests');

    $suite->addTestSuite('InitialTests');
    $suite->addTestSuite('DbTests');
    $suite->addTestSuite('OrmTests');
    $suite->addTestSuite('ConnTests');

    PHPUnit_TextUI_TestRunner::run($suite, array());

  }
}
$config = array
(
  'ws' => '/home/ahundiak/zayso2010/',
);
$tests = new Tests($config);
$tests->execute();
?>
