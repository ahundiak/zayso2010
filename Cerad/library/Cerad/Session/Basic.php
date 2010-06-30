<?php
class Cerad_Session_Basic extends Cerad_Session_Base
{
  protected function init()
  {
    session_name             ($this->sessionCookieName);
    session_set_cookie_params($this->sessionCookieLifetime);
    session_start();
    return;
  }
  public function get($name)
  {
    if(isset($_SESSION[$name])) return $_SESSION[$name];
    return $this->context->getSessionData();
  }
  public function has($name)
  {
    if(isset($_SESSION[$name])) return true;
    return false;
  }
  public function set($name,$value = NULL)
  {
    if ($value) return $_SESSION[$name] = $value;
    
    unset($_SESSION[$name]);
  }
  public function save() {}
}
?>
