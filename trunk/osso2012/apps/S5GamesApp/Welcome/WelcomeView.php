<?php
namespace S5GamesApp\Welcome;

class WelcomeView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle = 'Welcome to S5Games App';
  protected $tplContent = 'S5GamesApp/Welcome/WelcomeTpl.html.php';

  public function process()
  {
    $this->renderPage();
  }
}
?>
