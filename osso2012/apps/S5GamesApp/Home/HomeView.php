<?php
namespace S5GamesApp\Home;

class HomeView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle = 'S5Games User Home';
  protected $tplContent = 'S5GamesApp/Home/HomeTpl.html.php';

  public function process($data)
  {
    $this->data = $data;

    // Show account information
    $accountx = new \Cerad\DataItem();
    $accountId = $this->services->user->getAccountId();
    
    $this->renderPage();
  }
}
?>
