<?php
namespace S5GamesApp\Schedule\Show;

class GameDisplay
{
  protected $services;
  protected $view;
  protected $game;

  public function __construct($services,$view,$gameItem)
  {
    $this->services = $services;
    $this->view     = $view;
    $this->game     = $gameItem;
  }
  public function setGame($gameItem)
  {
    $this->game = $gameItem;
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
      1 => array('pos' => 'CR',  'name' => '.', 'status' => 0, 'aysoid' => ''),
      2 => array('pos' => 'AR1', 'name' => '.', 'status' => 0, 'aysoid' => ''),
      3 => array('pos' => 'AR2', 'name' => '.', 'status' => 0, 'aysoid' => ''),
      4 => array('pos' => 'AS',  'name' => '',  'status' => 0, 'aysoid' => ''),
      5 => array('pos' => 'AS2', 'name' => '',  'status' => 0, 'aysoid' => ''),
    );
    $personsx = $game->getPersons();
    // echo count($personsx); die();
    foreach($personsx as $personx)
    {
      $posId = $personx->getPosId();
      $persons[$posId]['name']   = $personx->getName() . ' ' . $personx->getRegion();
      $persons[$posId]['status'] = $personx->getStatus();
      $persons[$posId]['aysoid'] = $personx->getAysoid();
    }
    return $persons;
  }
  protected function getPersons()
  {
    $game = $this->game;
    $user = $this->services->user;

    $persons = $this->getPersonsx();
    
    $userIsReferee = $user->isReferee();
    $userIsAdmin   = $user->isAdmin();
    $userAysoid    = $user->getAysoid();

    $html = "<table>\n";
    foreach($persons as $posId => $person)
    {
      if ($userAysoid == $person['aysoid']) $userIsOwner = true;
      else                                  $userIsOwner = false;

      $pos  = $person['pos'];
      if ($userIsReferee)
      {
        $gameId = $game->getId();
	$url = "schedule-assign/{$gameId}/{$posId}";
	$pos = "<a href=\"$url\">$pos</a>";
      }
      switch($person['status'])
      {
        case 1:
          $span = "<span style=\"color: green;\">";
          if (!$userIsAdmin && !$userIsOwner) $pos = $person['pos'];
          break;

        case 3:
          $span = "<span style=\"color: brown;\">";
          if (!$userIsAdmin) $pos = $person['pos'];
          break;

	case 2:
          $span = "<span style=\"color: magenta;\">";
          break;
        
        case 6:
          $span = "<span style=\"color: red;\">";
          break;

	case 4:
          $span = "<span>";
          if (!$userIsAdmin) $pos = $person['pos'];
          break;

	default:
          $span = "<span>";
      }
      $name = $span . $this->view->escape($person['name']) . "</span>";
      if ($person['name'])
      {
        $html .= "<tr><td>{$pos}</td><td>{$name}</td></tr>\n";
      }
    }
    $html .= "</table>\n";
    return $html;
  }
}
class ShowView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle = 'S5Games Show Schedule';
  protected $tplContent = 'S5GamesApp/Schedule/Show/ShowTpl.html.php';

  public function getGameDisplay($gameItem)
  {
    $game = new GameDisplay($this->services,$this,$gameItem);
    return $game;
  }
  public function process($data)
  {
    $gameRepo = $this->services->repoGame;
    $games = $gameRepo->search($data);
    $data->games = $games;
    
    $data->sortPickList = array
    (
      '1' => 'Date,Time,Div,Field',
      '2' => 'Date,Field,Time',
      '3' => 'Div,Date,Time,Field',
      '4' => 'Date,Div,Time,Field',
      '5' => 'Game Number',
    );

    $this->data = $data;

    switch($data->out)
    {
      case 'csv':
        $response = $this->services->response;
        $response->setBody($this->render('S5GamesApp/Schedule/Show/ListTpl.csv.php'));
        $response->setFileHeaders('Schedule.csv');
        return;

      case 'excel':
        $response = $this->services->response;
        $response->setBody($this->render('S5GamesApp/Schedule/Show/ListTpl.xml.php'));
        $response->setFileHeaders('Schedule.xml');
        return;

    }
    $this->renderPage();
  }
}
?>
