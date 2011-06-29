<?php
namespace NatGamesApp\Home;

class ContactView extends \NatGamesApp\FrontEnd\View
{
  protected $tplTitle   = 'NatGames2012 Contact';
  protected $tplContent = 'NatGamesApp/Home/ContactTpl.html.php';

  public function process($data)
  {
    $this->data = $data;
    $this->renderPage();
  }
}
?>
