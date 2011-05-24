<?php
namespace Cerad\FrontEnd;

class Action
{
  protected $services;

  public function __construct($services)
  {
    $this->services = $services;
    $this->init();
  }
  protected function init() {}

  public function processGet ($args) { return 'GET'; }
  public function processPost($args) { return 'POST'; }

  public function process($args)
  {
    // Direct based on actions
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') return $this->processPost($args);
    else                   return $this->processGet ($args);
  }
  protected function redirect($url)
  {
    $this->services->response->setRedirect($url);
  }
  /*
  function isAuthorized()
  {
    if ($this->adminOnly) {
            if (!$this->context->user->isAdmin) return FALSE;
//            $response = $this->getResponse();
//            $response->setRedirect($this->link('home_auth'));
//            return FALSE;
    }
    return TRUE;
  }*/
}
?>
