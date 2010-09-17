<?php
class Osso2007_Context extends Cerad_Context
{
  protected function init()
  {
    $this->classNames['repos']  = 'Osso2007_Repos';
    $this->classNames['tables'] = 'Osso2007_Tables';
  }
}

?>
