<?php
class ScheduleController extends Controller
{
  function executeGet()
  {
    // Template data
    $tpl = new Cerad_Data();
    $tpl->userName = $userName = $this->getUserName();
		
    $tpl->showFri  = $this->getSess('sched_show_fri', 1);
    $tpl->showSat  = $this->getSess('sched_show_sat', 1);
    $tpl->showSun  = $this->getSess('sched_show_sun', 1);
    $tpl->showU10  = $this->getSess('sched_show_u10', 1);
    $tpl->showU12  = $this->getSess('sched_show_u12', 1);
    $tpl->showU14  = $this->getSess('sched_show_u14', 1);
    $tpl->showU16  = $this->getSess('sched_show_u16', 1);
    $tpl->showU19  = $this->getSess('sched_show_u19', 1);
    $tpl->showCoed = $this->getSess('sched_show_coed',1);
    $tpl->showGirl = $this->getSess('sched_show_girl',1);
    $tpl->showMM   = $this->getSess('sched_show_mm',  1);
    $tpl->showJH   = $this->getSess('sched_show_jh',  1);

    $tpl->sort       = $this->getSess('sched_sort',1);
    $tpl->searchGame = $this->getSess('sched_search_game');
    $tpl->searchRef  = $this->getSess('sched_search_ref');
		
    $tpl->sortPickList = array
    (
      '1' => 'Date,Time,Div,Field',
      '2' => 'Date,Field,Time',
      '3' => 'Div,Date,Time,Field',
      '4' => 'Date,Div,Time,Field',
      '5' => 'Game Number',
    );
		
    // Setup for query
    $query = new Query($this->getDb());
    $gameIds = $query->queryDistinctGames($tpl);
		
    $tpl->games = $query->queryGamesForIds($gameIds,$tpl->sort,$tpl->searchGame,$tpl->searchRef);
    $tpl->gameCnt = count($tpl->games);
		
    // And process it
    $out = $this->getGet('out','web');
    switch($out)
    {
      case 'web':
        $this->processTemplate('schedule.phtml',$tpl);
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
    $_SESSION['sched_sort']        = $this->getPost('sched_sort');
    $_SESSION['sched_search_game'] = $this->getPost('sched_search_game');
    $_SESSION['sched_search_ref' ] = $this->getPost('sched_search_ref');
		
    $_SESSION['sched_show_fri']  = $this->getPost('sched_show_fri');
    $_SESSION['sched_show_sat']  = $this->getPost('sched_show_sat');
    $_SESSION['sched_show_sun']  = $this->getPost('sched_show_sun');
		
    $_SESSION['sched_show_u10']  = $this->getPost('sched_show_u10');
    $_SESSION['sched_show_u12']  = $this->getPost('sched_show_u12');
    $_SESSION['sched_show_u14']  = $this->getPost('sched_show_u14');
    $_SESSION['sched_show_u16']  = $this->getPost('sched_show_u16');
    $_SESSION['sched_show_u19']  = $this->getPost('sched_show_u19');
		
    $_SESSION['sched_show_coed'] = $this->getPost('sched_show_coed');
    $_SESSION['sched_show_girl'] = $this->getPost('sched_show_girl');

    $_SESSION['sched_show_mm']   = $this->getPost('sched_show_mm');
    $_SESSION['sched_show_jh']   = $this->getPost('sched_show_jh');
		
    header("location: index.php?page=schedule");
  }
  function displayBracket($game)
  {
    $home = $game->homeBracket;
    $away = $game->awayBracket;
    if ($home == $away)
    {
      if (!$home) $home = 'FRIEND';
      return $home;
    }
    return $home . '<br />' . $away;
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
		
    return $person->region . ' ' . $person->fname . ' ' . $person->lname;
  }
  function displayPersons($game)
  {
    $persons = array
    (
      1 => array('pos' => 'CR',  'name' => '.', 'status' => 0),
      2 => array('pos' => 'AR1', 'name' => '.', 'status' => 0),
      3 => array('pos' => 'AR2', 'name' => '.', 'status' => 0),
    );
    $personsx = $game->getPersons();
    foreach($personsx as $personx)
    {
      $name   = $personx->fname . ' ' . $personx->lname;
      $status = $personx->status;
      $region = $personx->region;
    // if ($region < 1000) $region = 'R0' . $region;
    // else                $region = 'R'  . $region;
			
      $name  = $region . ' ' . $name;
      $posId = $personx->posId;
      $persons[$posId]['name']   = $name;
      $persons[$posId]['status'] = $status;
    }
    $html = "<table>\n";
    foreach($persons as $posId => $person)
    {
      $pos  = $person['pos'];
      if ($this->isReferee())
      {
        $gameId = $game->id;
	$url = "index.php?page=signup&game={$gameId}&pos={$posId}";
	$pos = "<a href=\"$url\">$pos</a>";
      }
      switch($person['status'])
      {
        case 1:
          $span = "<span style=\"color: green;\">";
          if (!$this->isAdmin()) $pos = $person['pos'];
          break;
					
        case 3:
          $span = "<span style=\"color: brown;\">";
          if (!$this->isAdmin()) $pos = $person['pos'];
          break;
					
	case 2:
          $span = "<span style=\"color: red;\">";
          break;
					
	case 4:
          $span = "<span>";
          if (!$this->isAdmin()) $pos = $person['pos'];
          break;
					
	default:
          $span = "<span>";
      }
      $name = $span . $this->escape($person['name']) . "</span>";
			
      $html .= "<tr><td>{$pos}</td><td>{$name}</td></tr>\n";
    }
    $html .= "</table>\n";
		
    return $html;
  }
}
?>