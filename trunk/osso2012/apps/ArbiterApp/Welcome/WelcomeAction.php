<?php
namespace ArbiterApp\Welcome;

class WelcomeAction extends \ArbiterApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $view = new WelcomeView($this->services);
    $view->process();
    return;
  }
}
?>
