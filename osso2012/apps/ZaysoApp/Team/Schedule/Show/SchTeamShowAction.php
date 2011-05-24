<?php
namespace ZaysoApp\Team\Schedule\Show;

class SchTeamShowAction extends \ZaysoApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $view = new SchTeamShowView($this->services);
    $view->process();
    return;
  }
  public function processPost($args)
  {
    return $this->processGet(array());
  }
}
?>
