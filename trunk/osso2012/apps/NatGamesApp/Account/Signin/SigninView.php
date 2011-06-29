<?php
namespace NatGamesApp\Account\Signin;

use \Cerad\Debug as Debug;

class SigninView extends \NatGamesApp\FrontEnd\View
{
  protected $tplTitle = 'NatGames Signin';
  protected $tplContent = 'NatGamesApp/Account/Signin/SigninTpl.html.php';

  public function process($data)
  {
    // Debug::dump($data);
    $this->data = $data;
    
    $this->renderPage();
  }
}
?>
