<?php
namespace NatGamesApp\Home;

class WelcomeAction extends \NatGamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $user = $this->services->user;
    
    $data = new \NatGames\DataItem();

    $view = new WelcomeView($this->services);
    $view->process(clone $data);
    return;
  }
  public function processPost($args)
  {
    return $this->redirect('welcome');
  }
}
?>
