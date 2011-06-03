<?php
namespace S5GamesApp\Welcome;

class WelcomeAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $data = $this->services->session->load('account-signin');
    $view = new WelcomeView($this->services);
    $view->process(clone $data);
    return;
  }
  public function processPost($args)
  {
    // die('Hello There Agsain');
    return $this->redirect('welcome');
  }
}
?>
