<?php
class Cerad_Session_Base
{
  protected $context = NULL;
  protected $sessionCookieName = 'cerad';
  protected $sessionCookieLifetime = 0;
  
  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function get($name) {}

  public function has($name) {}

  public function set($name,$item) {}

  public function save() {}

  public function newSessionData() { return $this->context->getSessionData(); }
}
?>
