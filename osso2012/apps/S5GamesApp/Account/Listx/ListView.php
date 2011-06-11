<?php
namespace S5GamesApp\Account\Listx;

class ListView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle   = 'S5Games List Accounts';
  protected $tplContent = 'S5GamesApp/Account/Listx/ListTpl.html.php';

  public function process($data)
  {
    $accountRepo = $this->services->repoAccount;
    $items = $accountRepo->search($data);

    $data->items = $items;

    $this->data = $data;
    $this->renderPage();
  }
}
?>
