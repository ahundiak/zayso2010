<?php
namespace S5GamesApp\Home;

class HomeAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $user = $this->services->user;
    if ($user->isGuest()) return $this->redirect('welcome');
    
    $view = new HomeView($this->services);

    $view->process(array());

    return;
  }
  public function processPost($args)
  {
    die('Hello There Agsain');
    return $this->redirect('welcome');
  }
}
?>
