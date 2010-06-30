<?php
class Action_Index_IndexCont extends Action_Base_BaseCont
{
  protected $context;
  protected $tplTitle = 'OSSO Index';
  protected $tplName  = 'Action/Index/Index.html.php';

  function executeGet()
  {
    // User has signed in to get here to go to home page
    $home = new Action_Home_HomeCont($this->context);
    return $home->execute();
  }
}

?>
