<?php
class GameItem
{
  protected $data;
  protected $persons = array();
	
  function __construct($data)
  {
    $this->data = $data;
  }
  function __get($name)
  {
    switch($name)
    {
      case 'id':    return $this->data['game_num'];   break;
      case 'date':  return $this->data['game_date'];  break;
      case 'time':  return $this->data['game_time'];  break;
      case 'type':  return $this->data['game_type'];  break;
      case 'field': return $this->data['game_field']; break;
      case 'div':   return $this->data['game_div'];   break;
			
      case 'bracket':   return $this->data['game_bracket']; break;
			
      case 'homeName':   return $this->data['home_name'];   break;
      case 'awayName':   return $this->data['away_name'];   break;
    }
    return NULL;
  }
  function addPerson($person)
  {
    $this->persons[$person->posId] = $person;
  }
  function getPersons() { return $this->persons; }
}
class GamePersonItem
{
  protected $data;
	
  function __construct($data)
  {
    $this->data = $data;
  }
  function __get($name)
  {
    switch($name)
    {
      case 'id':     return $this->data['game_person_id'];   break;
      case 'fname':  return $this->data['fname'];    break;
      case 'lname':  return $this->data['lname'];    break;
      case 'region': return $this->data['region'];   break;
      case 'gameId': return $this->data['game_num']; break;
      case 'posId':  return $this->data['pos_id'];   break;
      case 'assId':  return $this->data['ass_id'];   break;
      case 'aysoid': return $this->data['aysoid'];   break;
      case 'status': return $this->data['status'];   break;

      case 'desc':
        $name = $this->fname . ' ' . $this->lname;
        switch($this->assId)
        {
          case 1: $name .= '(AN)'; break;
          case 2: $name .= '(AA)'; break;
          case 3: $name .= '(AI)'; break;
          case 4: $name .= '(AO)'; break;
        }
        $desc  = $this->region . ' ' . $name;
        return $desc;
        break;
    }
    return NULL;
  }
}
class Query
{
  protected $db;

  function __construct($db)
  {
    $this->db = $db;
  }
  function getDb() { return $this->db; }
	
  function queryGamesForIds($gameIds, $sort = NULL, $searchGame = NULL, $searchRef = NULL)
  {
    if (count($gameIds) < 1) return array();
    
    $db = $this->db;
    $gameIds = $db->quote($gameIds);
	
    //Cerad_Debug::dump($gameIds);  die();
		
    $sql = "SELECT * FROM games WHERE game_num IN ($gameIds) ";
    switch($sort)
    {
      case 1:
        $sql .= 'ORDER BY game_date,game_time,game_div,game_field';
	break;
				
      case 2:
        $sql .= 'ORDER BY game_date,game_field,game_time';
	break;
				
      case 3:
	$sql .= 'ORDER BY game_div,game_date,game_time,game_field'; 
	break;
				
      case 4:
	$sql .= 'ORDER BY game_date,game_div,game_time,game_field'; 
	break;
				
      case 5:
      default:
	$sql .= 'ORDER BY game_num'; 
	break;
    }
    $rows = $db->fetchRows($sql);

    //die();
    $games = array();
    foreach($rows as $row)
    {
      $game = new GameItem($row);
      $gameId = $game->id;
      $games[$gameId] = $game;
    }
		
    // Now grab the people
    $sql = <<<EOT
SELECT * FROM game_person
WHERE game_num IN ($gameIds) 
ORDER BY game_num,pos_id;
EOT;
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $person  = new GamePersonItem($row);
      $gameId = $row['game_num'];
      $game = $games[$gameId];
      $game->addPerson($person);
    }
    // Check if filtering is needed
    $searchGame = trim($searchGame);
    $searchRef  = trim($searchRef);
    if (!$searchGame && !$searchRef) return $games;

    $gamesx = $games;
    $games  = array();
    $gameAttrs = array
    (
      'time',
      'field',
      'div',
      'bracket',
      'homeName',
      'awayName'
    );
    // Comma deliimited searches
    $searches = explode(',',$searchGame);
    $searchGames = array();
    foreach($searches as $search)
    {
      $search = trim($search);
      if($search) $searchGames[] = $search;
    }
    $searches = explode(',',$searchRef);
    $searchRefs = array();
    foreach($searches as $search)
    {
      $search = trim($search);
      if($search) $searchRefs[] = $search;
    }
    foreach($gamesx as $game)
    {
      $keep = FALSE;
      if ($searchGame)
      {
        foreach($gameAttrs as $attr)
        {
          foreach($searchGames as $search)
          {
            if (!(stripos($game->$attr,$search) === FALSE)) $keep = TRUE;
          }
        }
      }
      if ($searchRef)
      {
        foreach($game->getPersons() as $person)
				{
        $desc = $person->region . ' ' . $person->fname . ' ' . $person->lname;
          foreach($searchRefs as $search)
          {
            if (!(stripos($desc,$search) === FALSE)) $keep = TRUE;
          }
	}
      }
      if ($keep) $games[$game->id] = $game;
    }
    return $games;
  }
  function queryDistinctGames($tpl)
  {
    $db = $this->getDb();
		
    $dates = array('XXX');
    if ($tpl->showFri) $dates[] = 'FRI';
    if ($tpl->showSat) $dates[] = 'SAT';
    if ($tpl->showSun) $dates[] = 'SUN';
    $dates = $db->quote($dates);
		
    $divs = array('XXX');
    $shows = array
    (
      array('show' => $tpl->showU10, 'div' => 'U10'),
      array('show' => $tpl->showU12, 'div' => 'U12'),
      array('show' => $tpl->showU14, 'div' => 'U14'),
      array('show' => $tpl->showU16, 'div' => 'U16'),
      array('show' => $tpl->showU19, 'div' => 'U19'),
    );
    foreach($shows as $show)
    {
      if ($show['show'])
      {
        if ($tpl->showCoed) $divs[] = $show['div'] . 'B';
        if ($tpl->showGirl) $divs[] = $show['div'] . 'G';
      }
    }
    $divs = $db->quote($divs);

    $fields = NULL;
    if (!$tpl->showJH && $tpl->showMM) $fields = " AND game_field like 'MM%' ";
    if (!$tpl->showMM && $tpl->showJH) $fields = " AND game_field like 'JH%' ";

    $sql = <<<EOT
SELECT DISTINCT game_num FROM games WHERE
game_date IN($dates) AND
game_div  IN($divs) $fields;
EOT;
    // die($sql);
    $rows = $db->fetchRows($sql);
    $gameIds = array();
    foreach($rows as $row)
    {
      $gameIds[] = (int)$row['game_num'];
    }
    return $gameIds;
  }
  function getCoverageStats()
  {
    $db = $this->getDb();

    $gameCount = 190;

    $sql = 'SELECT count(*) AS slots_covered FROM game_person WHERE pos_id IN (1,2,3);';
    $row = $db->fetchRow($sql);

    $slotsCovered = $row['slots_covered'];
    $slotsTotal   = $gameCount * 3;
    $slotsOpen    = $slotsTotal - $slotsCovered;

    $stats = array();
    $stats['game_count']    = $gameCount;
    $stats['slots_open']    = $slotsOpen;
    $stats['slots_total']   = $slotsTotal;
    $stats['slots_covered'] = $slotsCovered;

    $covered = ($slotsCovered / $slotsTotal) * 100.0;

    $stats['covered'] = (int)$covered;

    return $stats;
  }
}
?>