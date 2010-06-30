<?php
class Osso_Base_BaseDirect
{
  protected $context;
  protected $db;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->db = $this->context->dbOsso;
  }
}

?>
