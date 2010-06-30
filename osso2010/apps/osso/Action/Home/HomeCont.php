<?php
class Action_Home_HomeCont extends Action_Base_BaseCont
{
  protected $context;
  protected $tplTitle = 'OSSO Home';
  protected $tplName  = 'Action/Home/Home.html.php';

  function executeGet()
  { 
    return $this->renderPage();
  }
}

?>
