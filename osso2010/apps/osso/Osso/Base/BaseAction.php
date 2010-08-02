<?php
class Osso_Base_BaseAction
{
  protected $context;
  protected $result;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() 
  {
    $this->result = new Cerad_Direct_Result();
  }

  function executeGet($args)  { return $this->result; }
  function executePost($args) { return $this->result; }

  function execute($args)
  { 
    // Direct based on actions
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') return $this->executePost($args);
    else                   return $this->executeGet ($args);
  }
}
?>
