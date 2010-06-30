<?php
class Export
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
    echo "Executing Export\n";
    $datax = $this->config['datax'] . 'osso/';
/*
    $export = new Person_Export_Person($this->context);
    $export->process($datax . 'Person.csv');

    $export = new Org_Export_Unit($this->context);
    $export->process($datax . 'Unit.csv');

    $export = new Org_Export_Org($this->context);
    $export->process($datax . 'Org.csv');
*/
    $export = new Osso2007_Account_AccountExport($this->context);
    $export->process($datax . 'Account2007.csv');
    echo $export->getResultMessage() . "\n";

    $export = new Osso2007_Account_Member_MemberExport($this->context);
    $export->process($datax . 'Member2007.csv');
    echo $export->getResultMessage() . "\n";
  }
}
$config = require '../config/config.php';
$export = new Export($config);
$export->execute();
?>
