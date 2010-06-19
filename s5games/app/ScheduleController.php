<?php
class ScheduleController extends Controller
{
  protected $userIsReferee = false;
  protected $userIsAdmin   = false;

  function executeGet()
  {
    $context = $this->context;
    $session = $context->session;
    $get     = $context->get;
    $user    = $context->user;

    $this->userIsReferee = $user->isReferee;
    $this->userIsAdmin   = $user->isAdmin;

    // Template data
    $tpl = new Cerad_Data();
    $tpl->user = $user;

    $tpl->showFri  = $session->get('sched_show_fri', 0);
    $tpl->showSat  = $session->get('sched_show_sat', 1);
    $tpl->showSun  = $session->get('sched_show_sun', 1);
    $tpl->showU10  = $session->get('sched_show_u10', 1);
    $tpl->showU12  = $session->get('sched_show_u12', 1);
    $tpl->showU14  = $session->get('sched_show_u14', 1);
    $tpl->showU16  = $session->get('sched_show_u16', 1);
    $tpl->showU19  = $session->get('sched_show_u19', 1);
    $tpl->showCoed = $session->get('sched_show_coed',1);
    $tpl->showGirl = $session->get('sched_show_girl',1);
    $tpl->showMM   = $session->get('sched_show_mm',  1);
    $tpl->showJH   = $session->get('sched_show_jh',  1);

    $tpl->sort       = $session->get('sched_sort',1);
    $tpl->searchGame = $session->get('sched_search_game');
    $tpl->searchRef  = $session->get('sched_search_ref');
		
    $tpl->sortPickList = array
    (
      '1' => 'Date,Time,Div,Field',
      '2' => 'Date,Field,Time',
      '3' => 'Div,Date,Time,Field',
      '4' => 'Date,Div,Time,Field',
      '5' => 'Game Number',
    );
		
    // Setup for query
    $query = new Query($context->db);
    $gameIds = $query->queryDistinctGames($tpl);
		
    $tpl->games = $query->queryGamesForIds($gameIds,$tpl->sort,$tpl->searchGame,$tpl->searchRef);
    $tpl->gameCnt = count($tpl->games);

    // Stats
    $stats = $query->getCoverageStats();
    $tpl->statCovered   = $stats['covered'];
    $tpl->statOpenSlots = $stats['slots_open'];
    
    // And process it
    $out = $get->get('out','web');
    switch($out)
    {
      case 'web':
        $this->processTemplate('schedule.phtml',$tpl);
	break;
				
      case 'csv':
        ob_start();
        include 'schedule.csv.php';
	$content = ob_get_clean();
	echo $content;
	break;

      case 'excel':
        ob_start();
        include 'ExcelTpl.xml.php';
        include 'ScheduleTpl.xml.php';
	$content = ob_get_clean();
	echo $content;
	break;
    }
  }
  function executePost()
  {
    $session = $this->context->session;
    $post    = $this->context->post;

    $names = array
    (
      'sched_sort','sched_search_game','sched_search_ref',
      'sched_show_fri','sched_show_sat','sched_show_sun',
      'sched_show_u10','sched_show_u12','sched_show_u14','sched_show_u16','sched_show_u19',
      'sched_show_coed','sched_show_girl',
      'sched_show_jh','sched_show_mm',
    );
    foreach($names as $name)
    {
      $session->set($name,$post->get($name));
    }
    header("location: index.php?page=schedule");
  }
  function displayBracket($game)
  {
    $bracket = $game->bracket;
    switch($bracket)
    {
      case 'NO BRACKETx' : return 'NA'; break;
      case 'FINALx'      : return 'FINALS'; break;
    }
    return $bracket;
  }
  function displayTeams($game)
  {
    $home = $game->homeName;
    $away = $game->awayName;
    return $home . '<br />' . $away;
  }
  function displayPerson($game,$posId)
  {
    $persons = $game->getPersons();
    if (!isset($persons[$posId])) return NULL;
    $person = $persons[$posId];

    return $person->desc;

    //return $person->region . ' ' . $person->fname . ' ' . $person->lname;
  }
  function getGamePersons($game)
  {
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
      $posId = $personx->posId;
      $persons[$posId]['name']   = $personx->desc;
      $persons[$posId]['status'] = $personx->status;
    }
    return $persons;
  }
  function displayPersons($game)
  {
    // $user = $this->context->user;
    $userIsReferee = $this->userIsReferee;
    $userIsAdmin   = $this->userIsAdmin;

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
      $posId = $personx->posId;
      $persons[$posId]['name']   = $personx->desc;
      $persons[$posId]['status'] = $personx->status;
    }
    $html = "<table>\n";
    foreach($persons as $posId => $person)
    {
      $pos  = $person['pos'];
      if ($userIsReferee)
      {
        $gameId = $game->id;
	$url = "index.php?page=signup&game={$gameId}&pos={$posId}";
	$pos = "<a href=\"$url\">$pos</a>";
      }
      switch($person['status'])
      {
        case 1:
          $span = "<span style=\"color: green;\">";
          if (!$userIsAdmin) $pos = $person['pos'];
          break;
					
        case 3:
          $span = "<span style=\"color: brown;\">";
          if (!$userIsAdmin) $pos = $person['pos'];
          break;
					
	case 2:
          $span = "<span style=\"color: red;\">";
          break;
					
	case 4:
          $span = "<span>";
          if (!$userIsAdmin) $pos = $person['pos'];
          break;
					
	default:
          $span = "<span>";
      }
      $name = $span . $this->escape($person['name']) . "</span>";
      if ($person['name'])
      {
        $html .= "<tr><td>{$pos}</td><td>{$name}</td></tr>\n";
      }
    }
    $html .= "</table>\n";
		
    return $html;
  }
}
?>