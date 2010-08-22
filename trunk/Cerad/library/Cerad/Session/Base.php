<?php
class Cerad_Session_Base
{
  protected $data = NULL;
  protected $context = NULL;
  protected $sessionCookieName = 'cerad';
  protected $sessionCookiePath = '/';
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

  public function   __get($name) { return $this->get($name); }
  public function __isset($name) { return $this->has($name); }
  public function   __set($name,$value) { return $this->set($name,$value); }

  public function save()    {}
  public function destroy() {}
  public function newSessionData() { return $this->context->getSessionData(); }
}
?>
