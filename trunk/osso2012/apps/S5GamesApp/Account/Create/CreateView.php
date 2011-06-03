<?php
namespace S5GamesApp\Account\Create;

use \Cerad\Debug as Debug;

class CreateView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle = 'S5Games Account Create';
  protected $tplContent = 'S5GamesApp/Account/Create/CreateTpl.html.php';

  public function process($data)
  {
    // Debug::dump($data);
    $this->data = $data;
    
    $this->renderPage();
  }
}
?>
