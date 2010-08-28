<?php

require_once 'Items.php';

class Osso2007_Referee_Points_RefPointsBase
{
  protected $context = NULL;
  protected $db;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->context->db = $this->context->dbOsso2007;
  }
  public function getResultMessage()
  {
    return 'Osso2007_Referee_Points_RefPointsBase';
  }
  public function process($xmlFileName = NULL)
  {
  }
}
?>