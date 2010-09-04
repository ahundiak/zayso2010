<?php
class Osso2007_Action
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function executeGet ($args) { return 'GET'; }
  public function executePost($args) { return 'POST'; }

  public function execute($args)
  {
    // Direct based on actions
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') return $this->executePost($args);
    else                   return $this->executeGet ($args);
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
