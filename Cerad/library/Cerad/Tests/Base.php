<?php
class Cerad_Tests_Base extends PHPUnit_Framework_TestCase
{
  protected $context;

  function __construct()
  {
    parent::__construct();
    $this->init();
  }
  protected function init()
  {
    if (isset($GLOBALS['tests_context'])) $this->context = $GLOBALS['tests_context'];
  }
}
?>
