<?php
namespace ArbiterApp\Welcome;

class WelcomeView extends \ArbiterApp\FrontEnd\View
{
  protected $tplTitle = 'Welcome to Arbiter Apps';
  protected $tplContent = 'ArbiterApp/Welcome/WelcomeTpl.html.php';

  public function process()
  {
    $this->renderPage();
  }
}
?>
