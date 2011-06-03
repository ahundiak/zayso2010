<?php
namespace S5GamesApp\Account\Signin;

use \Cerad\Debug as Debug;

class SigninView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle = 'S5Games Signin';
  protected $tplContent = 'S5GamesApp/Account/Signin/SigninTpl.html.php';

  public function process($data)
  {
    // Debug::dump($data);
    $this->data = $data;
    
    $this->renderPage();
  }
}
?>
