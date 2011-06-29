<?php
namespace NatGamesApp\Home;

class HomeAction extends \NatGamesApp\FrontEnd\Action
{
  protected $mustbeSignedIn = true;
  
  public function processGet($args)
  {
    $user = $this->services->user;
    if (!$user->isSignedIn()) return $this->redirect('welcome');

    $plans = $user->getProjectPerson()->plans;
    if (!$plans)
    {
      return $this->redirect('projinfo-plans');
    }
    $levels = $user->getProjectPerson()->refLevels;
    if (!$levels)
    {
      return $this->redirect('projinfo-reflevel');
    }
    
    $data = new \NatGames\DataItem();

    $view = new HomeView($this->services);
    $view->process($data);
    
    return;
  }
  public function processPost($args)
  {
    return $this->redirect('home');
  }
}
?>
