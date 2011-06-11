<?php
namespace S5GamesApp\Admin\Session;

class ShowView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle   = 'S5Games Show Sessions';
  protected $tplContent = 'S5GamesApp/Admin/Session/ShowTpl.html.php';

  public function process($data)
  {
    $sessionRepo = $this->services->repoSession;
    $items = $sessionRepo->search($data);

    $data->items = $items;

    $this->data = $data;
    $this->renderPage();
  }
}
?>
