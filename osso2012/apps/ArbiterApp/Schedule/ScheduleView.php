<?php
namespace ArbiterApp\Schedule;

class ScheduleView extends \ArbiterApp\FrontEnd\View
{
  protected $tplTitle = 'Arbiter Schedule';
  protected $tplContent = 'ArbiterApp/Schedule/ScheduleTpl.html.php';

  public function process()
  {
    $em = $this->services->em;

    $gameRepo = $em->getRepository('Arbiter\GameItem');

    $params = array();
    $games = $gameRepo->getGames($params);

    $this->games = $games;
    
    $this->renderPage();
  }
}
?>
