<?php
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

    $this->context = new Osso2007_Context($this->config);

    $GLOBALS['tests_context'] = $this->context;
    
  }
  public function execute()
  {
    require_once 'PHPUnit/Framework.php';
    require_once 'PHPUnit/TextUI/TestRunner.php';

    $suite = new PHPUnit_Framework_TestSuite('OSSO Model Tests');

  //$suite->addTestSuite('Osso_Org_OrgTests');
  //$suite->addTestSuite('Osso_Person_Reg_PersonRegTests');

  //$suite->addTestSuite('Osso2007_Account_AccountTests');
  //$suite->addTestSuite('Osso2007_Person_PersonTests');
  //$suite->addTestSuite('Osso2007_Referee_RefereeTests');

    $suite->addTestSuite('Osso2007_Event_EventTests');

    $suite->addTestSuite('Osso2007_Div_DivTests');
    $suite->addTestSuite('Osso2007_Org_OrgTests');

    $suite->addTestSuite('Osso2007_Team_Phy_PhyTeamTests');
    $suite->addTestSuite('Osso2007_Team_Sch_SchTeamTests');

    PHPUnit_TextUI_TestRunner::run($suite, array());

  }
}
$config = require '../config/config.php';
$tests = new Tests($config);
$tests->execute();
?>
