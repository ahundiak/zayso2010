<?php
namespace NatGamesApp\Home;

class WelcomeView extends \NatGamesApp\FrontEnd\View
{
  protected $tplTitle = 'Welcome to Nat Games 2012 App';
  protected $tplContent = 'NatGamesApp/Home/WelcomeTpl.html.php';

  public function process($data)
  {
    $this->data = $data;
    $this->renderPage();
  }
}
?>
