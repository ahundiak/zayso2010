<?php
// Not yet used under Symfony
class Osso2007_FrontEnd_Action
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
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
  protected function redirect($routeName,$par1 = NULL,$par2 = NULL)
  {
    $url = $this->context->url->link($routeName,$par1,$par2);
    
    $this->context->response->setRedirect($url);
  }
  public function link($routeName = NULL,$par1 = NULL,$par2 = NULL)
  {
    return $this->context->url->link($routeName,$par1,$par2);
  }
  function isAuthorized()
  {
    if ($this->adminOnly) {
            if (!$this->context->user->isAdmin) return FALSE;
//            $response = $this->getResponse();
//            $response->setRedirect($this->link('home_auth'));
//            return FALSE;
    }
    return TRUE;
  }
}
?>
