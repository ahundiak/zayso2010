<?php
/* --------------------------------------------------
 * Usually need to include by spelling out the full path
 */
class Cerad_Tests_Driver
{
  protected $config;
  protected $context;
  protected $contextClassName = 'Cerad_Context';
  
  protected $testsClassNames = array();

  function __construct($config = array())
  {
    $this->config = $config;
    $this->init();
  }
  protected function init()
  {
    $this->initIncludePaths();
    $this->initClassLoader();
    $this->initContext();
    $this->initFramework();
  }
  protected function initContext()
  {
    $this->context = new $this->contextClassName($this->config);
    $GLOBALS['tests_context'] = $this->context;
  }
  protected function initIncludePaths()
  {
   $ws = $this->config['ws'];
   ini_set('include_path','.' .
       PATH_SEPARATOR . $ws . 'Cerad/library' .
       PATH_SEPARATOR . $ws . 'PHPUnit-3.4.9'
    );
  }
  protected function initClassLoader()
  {
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
  }
  protected function initFramework()
  {
    require_once 'PHPUnit/Framework.php';
    require_once 'PHPUnit/TextUI/TestRunner.php';
  }
  protected function addTests($suite)
  {
    foreach($this->testsClassNames as $name)
    {
      $suite->addTestSuite($name);
    }
  }
  public function execute()
  {

    $suite = new PHPUnit_Framework_TestSuite('Cerad Tests');

    $this->addTests($suite);

    // $suite->addTestSuite('Cerad_ArrayObjectTests');

    PHPUnit_TextUI_TestRunner::run($suite, array());
  }
}
?>