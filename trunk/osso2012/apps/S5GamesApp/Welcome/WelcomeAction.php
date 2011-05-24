<?php
namespace S5GamesApp\Welcome;

class WelcomeAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $view = new WelcomeView($this->services);
    $view->process();
    return;
  }
  public function processPost($args)
  {
    // die('Hello There Agsain');
    return $this->redirect('welcome');
  }
}
?>
