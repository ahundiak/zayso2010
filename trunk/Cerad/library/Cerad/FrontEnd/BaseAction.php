<?php
class Cerad_FrontEnd_BaseAction
{
  protected $context;
  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected function redirect($url)
  {
    header("Location: $url");
    return;
  }
  public function executeGet ($args) { return 'GET'; }
  public function executePost($args) { return 'POST'; }

  public function execute($args)
  {
    // Direct based on actions
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') return $this->executePost($args);
    else                   return $this->executeGet ($args);
  }
}
?>
