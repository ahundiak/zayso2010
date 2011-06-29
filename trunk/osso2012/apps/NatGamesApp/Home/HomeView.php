<?php
namespace NatGamesApp\Home;

class HomeView extends \NatGamesApp\FrontEnd\View
{
  protected $tplTitle =   'NatGames2012 Home';
  protected $tplContent = 'NatGamesApp/Home/HomeTpl.html.php';

  public function process($data)
  {
    $this->data = $data;
    $this->renderPage();
  }
}
?>
