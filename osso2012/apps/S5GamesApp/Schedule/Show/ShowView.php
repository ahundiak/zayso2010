<?php
namespace S5GamesApp\Schedule\Show;

class ShowView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle = 'S5Games Show Schedule';
  protected $tplContent = 'S5GamesApp/Schedule/Show/ShowTpl.html.php';

  public function process($data)
  {
    $this->data = $data;
    $this->renderPage();
  }
}
?>
