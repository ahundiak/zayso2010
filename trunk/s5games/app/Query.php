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
			
			case 'homeBracket':   return $this->data['home_bracket'];   break;
			case 'awayBracket':   return $this->data['away_bracket'];   break;
			
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
			case 'aysoid': return $this->data['aysoid'];   break;
			case 'status': return $this->data['status'];   break;
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
		$db = $this->db;
		$gameIds = $db->quote($gameIds);
		
		//Cerad_Debug::dump($gameIds);  die();
		
		$sql = "SELECT * FROM game WHERE game_num IN ($gameIds) ";
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
				$sql .= 'ORDER BY game_num'; 
				break;
		}
        $rows = $db->fetchRows($sql);

		//die();
		$items = array();
		foreach($rows as $row) 
		{
			$game = new GameItem($row);
			$gameId = $game->id;
			$items[$gameId] = $game;
		}
		
		// Now grab the people
		$sql = <<<EOT
SELECT * FROM game_person
WHERE game_num IN ($gameIds) 
ORDER BY game_num,pos_id;
EOT;
		$rows = $db->fetchRows($sql);
		foreach($rows as $row) {
			$person  = new GamePersonItem($row);
			$gameId = $row['game_num'];
			$game = $items[$gameId];
			$game->addPerson($person);
		}
		// Check if filtering is needed
		if (!$searchGame && !$searchRef) return $items;
		$itemsx = $items;
		$items  = array();
		$gameAttrs = array(
			'time',
			'field',
			'div',
			'homeBracket',
			'awayBracket',
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
		foreach($itemsx as $game)
		{
			$keep = FALSE;
			if ($searchGame) {
				foreach($gameAttrs as $attr)
				{
					foreach($searchGames as $search) 
					{
						if (!(stripos($game->$attr,$search) === FALSE)) $keep = TRUE;
					}					
				}
			}
			if ($searchRef) {
				foreach($game->getPersons() as $person)
				{
					$desc = $person->region . ' ' . $person->fname . ' ' . $person->lname;
					foreach($searchRefs as $search) 
					{
						if (!(stripos($desc,$search) === FALSE)) $keep = TRUE;	
					}				
				}
			}
			if ($keep) $items[$game->id] = $game;
		}
		return $items;
		
		Cerad_Debug::dump($items[6392]);//die();
		die('After loop');
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
		if ($tpl->showU10) {
			if ($tpl->showCoed) $divs[] = '10C';
			if ($tpl->showGirl) $divs[] = '10G';
		}
		if ($tpl->showU12) {
			if ($tpl->showCoed) $divs[] = '12C';
			if ($tpl->showGirl) $divs[] = '12G';
		}
		if ($tpl->showU14) {
			if ($tpl->showCoed) $divs[] = '14C';
			if ($tpl->showGirl) $divs[] = '14G';
		}
		if ($tpl->showU16) {
			if ($tpl->showCoed) $divs[] = '16C';
			if ($tpl->showGirl) $divs[] = '16G';
		}
		if ($tpl->showU19) {
			if ($tpl->showCoed) $divs[] = '19C';
			if ($tpl->showGirl) $divs[] = '19G';
		}
		$divs = $db->quote($divs);
		
		$sql = <<<EOT
SELECT DISTINCT game_num FROM game WHERE 
game_date IN($dates) AND
game_div  IN($divs);
EOT;
        $rows = $db->fetchRows($sql);
        $gameIds = array();
        foreach($rows as $row) {
        	$gameIds[] = (int)$row['game_num'];
        }
		return $gameIds;	
	}
}
?>