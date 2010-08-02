<?php
class Osso_Base_BaseTests extends PHPUnit_Framework_TestCase
{
  protected $context;

  protected $db;
  protected $dbName = 'dbOsso';

  protected $direct;
  protected $directClassName = null;

  protected function setUp()
  {
    $this->context = $GLOBALS['context_tests'];

    if ($this->dbName)
    {
      $dbName = $this->dbName;
      $this->db = $this->context->$dbName;
    }
    if ($this->directClassName)
    {
      $this->direct = new $this->directClassName($this->context);
    }
  }
}
?>
