<?php

namespace ZaysoApp\Team\Schedule\Show;

class SchTeamShowView extends \ZaysoApp\FrontEnd\View
{
  protected $tplTitle = 'Zayso Show Schedule Teams';
  protected $tplContent = 'ZaysoApp/Team/Schedule/Show/SchTeamShowTpl.html.php';

  public function process()
  {
    $this->renderPage();
  }
}
?>
