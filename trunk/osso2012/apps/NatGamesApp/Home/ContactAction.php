<?php
namespace NatGamesApp\Home;

class ContactAction extends \NatGamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $user = $this->services->user;
    
    $data = new \NatGames\DataItem();

    $view = new ContactView($this->services);
    $view->process($data);
    return;
  }
  public function processPost($args)
  {
    return $this->redirect('welcome');
  }
}
?>
