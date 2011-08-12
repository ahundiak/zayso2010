<?php
namespace Area5CFApp\Welcome;

class WelcomeView extends \Area5CFApp\base\View
{
  protected $tplTitle   = 'Area5CF Welcome';
  protected $tplContent = 'Area5CFApp/Welcome/WelcomeTpl.html.php';

  public function process($data)
  {
    $this->tplData = $data;
    $this->renderPage();
  }
}
?>
