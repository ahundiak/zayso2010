<?php
namespace ArbiterApp\Schedule;

class ScheduleAction extends \ArbiterApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $view = new ScheduleView($this->services);
    $view->process();
    return;
  }
}
?>
