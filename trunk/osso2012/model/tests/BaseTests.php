<?php
class BaseTests extends PHPUnit_Framework_TestCase
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
    if (isset($GLOBALS['tests_db']))      $this->db      = $GLOBALS['tests_db'];
  }
}
?>
