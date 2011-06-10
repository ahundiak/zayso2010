<?php
namespace S5GamesApp\Schedule\Assign;

class GameView
{
  protected $view;
  protected $game;

  public function __construct($view)
  {
    $this->view = $view;
  }
  public function set($game)
  {
    $this->game = $game;
  }
  public function __get($name)
  {
    $game = $this->game;

    switch($name)
    {
      case 'id':        return $game->getId();       break;
      case 'date':      return $game->getDate();     break;
      case 'time':      return $game->getTime();     break;
      case 'field':     return $game->getField();    break;
      case 'div':       return $game->getDiv();      break;
      case 'bracket':   return $game->getBracket();  break;
      case 'homeTeam':  return $game->getHomeTeam(); break;
      case 'awayTeam':  return $game->getAwayTeam(); break;
      case 'teams':     return $this->getTeams();    break;
      case 'persons':   return $this->getPersons();  break;
      case 'personsx':  return $this->getPersonsx(); break;
    }
    return null;
  }
  protected function getTeams()
  {
    $home = $this->game->getHomeTeam();
    $away = $this->game->getAwayTeam();
    return $home . '<br />' . $away;
  }
  protected function getPersonsx()
  {
    $game = $this->game;

    $persons = array
    (
      1 => array('pos' => 'CR',  'name' => '.', 'status' => 0),
      2 => array('pos' => 'AR1', 'name' => '.', 'status' => 0),
      3 => array('pos' => 'AR2', 'name' => '.', 'status' => 0),
      4 => array('pos' => 'AS',  'name' => '',  'status' => 0),
      5 => array('pos' => 'AS2', 'name' => '',  'status' => 0),
    );
    $personsx = $game->getPersons();
    foreach($personsx as $personx)
    {
      $posId = $personx->getPosId();
      $persons[$posId]['name']   = $personx->getName() . ' ' . $personx->getRegion();
      $persons[$posId]['status'] = $personx->getStatus();
    }
    return $persons;
  }
}
class AssignView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle = 'S5Games Assign';
  protected $tplContent = 'S5GamesApp/Schedule/Assign/AssignTpl.html.php';

  /* -------------------------------------------------------
   * Always come in with
   * gameId
   * posId
   *
   * Might have aysoid, if not pull from gamePerson or user
   */
  public function process($data)
  {
    $user     = $this->services->user;
    $gameRepo = $this->services->repoGame;

    // Pull the game out, wrap in display wrapper
    $gamex = $gameRepo->find($data->gameId);
    $game  = new GameView($this);
    $game->set($gamex);
    $data->game = $game;

    // See if have a gamePerson
    $gamePerson = $gamex->getPerson($data->posId);
    if ($gamePerson)
    {
      $data->status = $gamePerson->getStatus();
      $data->assId  = $gamePerson->getAssId();
      if (!$data->aysoid) $data->aysoid = $gamePerson->getAysoid();
    }
    if (!$data->status) $data->status = 1;
    if (!$data->assId)  $data->assId  = 0;

    // Load in volunteer information
    if (!$data->aysoid) $data->aysoid = $user->getAysoid();

    $em  = $this->services->emVols;
    $vol = $em->find('S5Games\Vol\VolItem',$data->aysoid);
    if (!$vol)
    {
      $vol = new \S5Games\Vol\VolItem();
      $vol->aysoid = $data->aysoid;
    }
    $data->vol = $vol;

    // Referee positions
    $data->posPickList = array
    (
      1 => 'Center Referee',
      2 => 'Assistant Referee 1',
      3 => 'Asssitant Referee 2',
      4 => 'Assessor',
      5 => 'Assessor 2',
    );
    // Requested Assessments
    $data->assPickList = array
    (
      1 => 'National Upgrade',
      2 => 'Advanced Upgrade',
      3 => 'Intermediate Observation',
      4 => 'Informal Observation',
    );
    // Status options
    $statusPickList = array
    (
      1 => 'Request Game',
      2 => 'Ref only if needed',
      6 => 'Request Removal',
    );
    if ($user->isAdmin())
    {
      $statusPickList[3] = 'Assigned by Admin';
      $statusPickList[4] = 'Approved';
      $statusPickList[5] = 'Remove';
    }
    $data->statusPickList = $statusPickList;

    $this->data = $data;

    $this->renderPage();
  }
}
?>
