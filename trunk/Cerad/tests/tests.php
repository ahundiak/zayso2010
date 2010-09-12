<?php
error_reporting(E_ALL);

// Needs to be at the top
$config = require './config/config.php';
require $config['ws'] . 'Cerad/library/Cerad/Tests/Driver.php';

// The driver itself
class Tests extends Cerad_Tests_Driver
{
  protected $testsClassNames = array
  (
    'BasicTest',
    'Cerad_Direct_BaseTests',
    'Cerad_ArrayObjectTests',
    'XMLTests',
  );
  function init()
  {
    parent::init();
    $this->testsClassNames = array('Cerad_Repo_RepoTests');
  }
}
// Needs to be at the bottom
$tests = new Tests($config);
unset($config);
$tests->execute();

?>
