<?php
class Osso_Context extends Cerad_Context
{
  protected function init()
  {
    parent::init();

    $this->classNames['session'] = 'Osso_Session';
    $this->classNames['user']    = 'Osso_User';
    
  }
}

?>
