<?php
class Osso2007_Session extends Cerad_Session_Database
{
  protected $sessionCookieName =  'osso2007';

  protected function init()
  {
    parent::init();
    $this->sessionCookiePath = $this->context->request->webPath;
  }
}
?>
