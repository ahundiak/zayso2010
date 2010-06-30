<?php

require_once 'Items.php';


class TeamRefereesExport extends ExportBase
{
    public function getTeams()
    {
    	$db = $this->context->db;
    	$divisionIds = $this->context->models->DivisionModel->getDivisionIdsForAgeRange(10,19,TRUE,TRUE,TRUE);
    	$divisionIds = $db->quoteInto('?',$divisionIds);
    	
        $sql = <<<EOT
SELECT 
  phy_team.phy_team_id          AS phy_team_id,
  phy_team.division_seq_num     AS phy_team_div_seq_num,
  division.division_id          AS phy_team_div_id,
  division.desc_pick            AS phy_team_div,
  unit.unit_id                  AS phy_team_league_id,
  unit.keyx                     AS phy_team_league_key,
  phy_team_coach_head.person_id AS phy_team_coach_head_person_id,
  phy_team_coach_head.fname     AS phy_team_coach_head_person_fname,
  phy_team_coach_head.lname     AS phy_team_coach_head_person_lname
  
FROM phy_team

LEFT JOIN unit        ON unit.unit_id               = phy_team.unit_id
LEFT JOIN reg_year    ON reg_year.reg_year_id       = phy_team.reg_year_id
LEFT JOIN division    ON division.division_id       = phy_team.division_id
LEFT JOIN season_type ON season_type.season_type_id = phy_team.season_type_id

LEFT JOIN phy_team_person 
  AS  phy_team_person_coach_head 
  ON  phy_team_person_coach_head.phy_team_id = phy_team.phy_team_id
  AND phy_team_person_coach_head.vol_type_id = 16
  
LEFT JOIN person
  AS phy_team_coach_head
  ON phy_team_coach_head.person_id = phy_team_person_coach_head.person_id

WHERE 
  phy_team.unit_id        = 4 AND
  phy_team.season_type_id = 1 AND 
  phy_team.reg_year_id    = 9 AND
  phy_team.division_id IN ($divisionIds)

ORDER BY
  phy_team.unit_id,
  phy_team.division_id,
  phy_team.division_seq_num
;
EOT;


  		$rows = $db->fetchAll($sql);
  		
  		$teams = array();
  		foreach($rows as $row)
  		{
  			$team = array();
  			$team['team_id'] = $row['phy_team_id'];
  			
  			$divSeqNum = $row['phy_team_div_seq_num'];
  			if ($divSeqNum < 10) $divSeqNum = '0' . $divSeqNum;
  			
  			$key = $row['phy_team_league_key'] . '-' . $row['phy_team_div'] . '-' . $divSeqNum;
  			
  			$coach = $row['phy_team_coach_head_person_fname'] . ' ' . $row['phy_team_coach_head_person_lname'];
  			
  			$team['team_desc'] = $key . ' ' . $coach;
  			
  			$team['points_processed'] = 0;
  			$team['number_referees']  = 0;
  			
  			$team['referees'] = array();
  			
  			$teams[$team['team_id']] = $team;
  			
  			// try with objects
  			$team = new Team();
  			$team->id = $row['phy_team_id'];
  			
  			$team->divId     = $row['phy_team_div_id'];
  			$team->divKey    = $row['phy_team_div'];
  			$team->divSeqNum = $row['phy_team_div_seq_num'];
  			
  			$team->leagueId  = $row['phy_team_league_id'];
  			$team->leagueKey = $row['phy_team_league_key'];
  			
  			$coach = new Coach();
  			
  			$coach->id    = $row['phy_team_coach_head_person_id'];
  			$coach->fname = $row['phy_team_coach_head_person_fname'];
  			$coach->lname = $row['phy_team_coach_head_person_lname'];
  			
  			$team->coach = $coach;
  			
  			
  			//Zend_Debug::dump($row); die();
  		}
  		// Zend_Debug::dump($teams);
  		
  		return $teams;
  		
    }
    public function getReferees()
    {
    	$db = $this->context->db;
    	
        $sql = <<<EOT
SELECT 
  vol.vol_id      AS vol_id,    	
  vol.person_id   AS referee_id,
  vol.vol_type_id AS referee_type_id,
  referee.fname   AS referee_fname,
  referee.lname   AS referee_lname,
  referee.aysoid  AS referee_aysoid
  
FROM vol

LEFT JOIN person AS referee 
  ON referee.person_id = vol.person_id
  
WHERE 
  vol.unit_id        = 4 AND
  vol.season_type_id = 1 AND 
  vol.reg_year_id    = 9 AND
  vol.vol_type_id IN (19,20)

ORDER BY
  referee_lname, 
  referee_type_id, 
  referee_fname, 
  referee_aysoid
;
EOT;
  
       	$rows = $db->fetchAll($sql);
       	$referees = array();
       	foreach($rows as $row) 
       	{
       		if ((!$row['referee_lname']) && (!$row['referee_fname'])) {
       			echo "Missing person for vol_id: {$row['vol_id']}\n";
       		}
       		else {
       		$referee = array();
       		$referee['referee_id'] = $row['referee_id'];
       		
       		$referee['name1'] = $row['referee_fname'] .  ' ' . $row['referee_lname'];
       		$referee['name2'] = $row['referee_lname'] . ', ' . $row['referee_fname'];
       		
       		if ($row['referee_type_id'] == 19) $referee['age'] = 'Adult';
       		else                               $referee['age'] = 'Youth';
       		
       		$referee['type_id'] = $row['referee_type_id'];
       		
       		$referee['points_pending']   = 0;
       		$referee['points_processed'] = 0;
       		$referee['points_left']      = 0;
       		
       		$referee['teams'] = array();
       		
       		$referees[$referee['referee_id']] = $referee;
       		
       		// Object
       		$referee = new referee();
       		$referee->id    = $row['referee_id'];
  			$referee->fname = $row['referee_fname'];
  			$referee->lname = $row['referee_lname'];
       		
  			$referee->ageId = $row['referee_type_id'];
  			// Zend_Debug::dump($referee);
  			// echo $referee->ageDesc; die();
       		}
       	}
       	// Zend_Debug::dump($referees);
       	
       	return $referees;
       	
    }
    public function getRefereeTeams()
    {
    	$db = $this->context->db;
    	
    	$sql = <<<EOT
SELECT * FROM phy_team_referee 

WHERE 
  phy_team_referee.unit_id        = 4 AND
  phy_team_referee.season_type_id = 1 AND 
  phy_team_referee.reg_year_id    = 9

ORDER BY referee_id, pri_regular;

EOT;
      	
       	$rows = $db->fetchAll($sql);
       	
       	// Zend_Debug::dump($rows);
       	
       	return $rows;       	
    }
    function getEvents()
    {
    	$db = $this->context->db;
    	
        $sql = <<<EOT
SELECT 
  event_person.person_id            AS referee_id,
  event_person.event_id             AS event_id,
  event_person.event_person_type_id AS referee_position_id,   
  
  event.event_date AS event_date,
  event.point2     AS event_point2,
  
  event_team.division_id        AS division_id,
  event_team.unit_id            AS event_team_unit_id,
  event_team.event_team_type_id AS event_team_type_id
  
FROM event_person

LEFT JOIN event ON event.event_id = event_person.event_id

LEFT JOIN event_team ON event_team.event_id = event.event_id

WHERE
  event_person.event_person_type_id IN (10,11,12,13) AND
  
  event.event_date >= '20090801' AND
  event.event_date <= '20091105' AND
  event.schedule_type_id IN (1)

ORDER BY event_person.event_id, event_person.event_person_type_id
;
EOT;

        $rows = $db->fetchAll($sql);
        $events = array();
        foreach($rows as $row)
        {
        	$eventId = $row['event_id'];
        	
        	if (isset($events[$eventId])) $event = $events[$eventId];
        	else 
        	{
        		$event = array();
        		$event['event_id'] = $eventId;
        		$event['point2']   = $row['event_point2'];
        		$event['division'] = 0;
        		$event['use']      = 0;
        		$event['referees'] = array();
        	}
        	if ($event['division'] < $row['division_id']) $event['division'] = $row['division_id'];
        	
        	// Check for Madison home
            if (($row['event_team_type_id'] == 1) && ($row['event_team_unit_id'] == 4)) $event['use'] = 1;
        	
        	// Pull the referee
        	$refereeId = $row['referee_id'];
        	
        	// Make sure same referee did not signup twice
        	if (!isset($event['referees'][$refereeId]))
        	{
        		$referee = array();
        		$referee['referee_id'] = $refereeId;
        		$referee['position'] = $row['referee_position_id'];
        		
        		$event['referees'][$refereeId] = $referee;
        		
        	}

        	$events[$eventId] = $event;
        	
        	// Object version
        	$event = new Event();
        	$event->id      = $row['event_id'];
        	$event->point2 = $row['event_point2'];
        	
        	if ($event->divId < $row['division_id']) $event->divId = $row['division_id'];
        	
        	$referee = new Referee();
        	$referee->id         = $row['referee_id'];
        	$referee->positionId = $row['referee_position_id'];
        	
        	$event->addReferee($referee);
        	
        }
        // Zend_Debug::dump($events);
        
        return $events;
    }
    public function process($xmlFileName)
    {
    	$teams    = $this->getTeams();
    	$referees = $this->getReferees();

    	$events = $this->getEvents();
    	
    	$refereeTeams = $this->getRefereeTeams();
    	
    	// Calc total points for each referee
		$divPointsCR = array(
			 4 => 1,  5 => 1,  6 => 1, // U08
			 7 => 2,  8 => 2,  9 => 2, // U10
			10 => 2, 11 => 2, 12 => 2, // U12
			13 => 3, 14 => 3, 15 => 3, // U14
			16 => 4, 17 => 4, 18 => 4, // U16
			19 => 4, 20 => 4, 21 => 4, // U19
		);
		$divPointsAR = array(
			 4 => 0,  5 => 0,  6 => 0, // U08
		     7 => 1,  8 => 1,  9 => 1, // U10
			10 => 1, 11 => 1, 12 => 1, // U12
			13 => 1, 14 => 1, 15 => 1, // U14
			16 => 2, 17 => 2, 18 => 2, // U16
			19 => 2, 20 => 2, 21 => 2, // U19
		);
		$divPoints4th = array(
			16 => 1, 17 => 1, 18 => 1, // U16
			19 => 1, 20 => 1, 21 => 1, // U19
		);    	
		foreach($events as $event)
		{
			foreach($event['referees'] as $referee)
			{
				$refereeId = $referee['referee_id'];
				if (isset($referees[$refereeId])) 
				{
					$points = 0;
			
			        // Get the points array
					switch($referee['position'])
					{
					case 10:
						$divPoints = $divPointsCR;
						break;	
					case 11:
					case 12:
						$divPoints = $divPointsAR;
						break;
					case 13:
						$divPoints = $divPoints4th;
						break;
					default:
						$divPoints = array();
					}
			        
			        $division = $event['division'];

			        if (isset($divPoints[$division])) {
						$points = $divPoints[$division];
						if (($points) && 
							($referees[$refereeId]['age'] == 'Youth') && 
							($division >= 7) && 
							($referee['position'] != 13)) $points++;
					}
			        
			        if (!$event['use']) $points = 0;
			        
			        // echo $event['event_id'] . ' ' . $division . ' ' . $referee['position'] . ' ' . $refereeId . ' ' . $points . "\n";
			        
					if ($points) 
					{
			        	if ($event['point2'] == 1)  $referees[$refereeId]['points_pending']   += $points;
			        	if ($event['point2'] == 3)  $referees[$refereeId]['points_processed'] += $points;
			        	
			        	$referees[$refereeId]['points_left'] = $referees[$refereeId]['points_processed'];	
					} 
				}
			}
		}
		// Link teams and referees and apply points to teams
    	foreach($refereeTeams as $link)
    	{
    		$teamId    = $link['phy_team_id'];
    		$refereeId = $link['referee_id'];
    		
    		// Add teams to referee
    		if (isset($referees[$refereeId])) 
    		{    			
    			if (isset($teams[$teamId]))
    			{
    				$team = $teams[$teamId];
    				
    				$teamx = array();
    				$teamx['team_id']      = $teamId;
    				$teamx['team_desc']    = $team['team_desc'];
    				$teamx['priority']     = $link['pri_regular']; // $link['pri_tourn'];
    				$teamx['points_max']   = $link['max_regular']; // $link['max_tourn'];
    				$teamx['points_actual'] = 0;
    				$teamx['points_total']  = $referees[$refereeId]['points_processed'];
    				
    			    $pointsLeft = $referees[$refereeId]['points_left'];
    				
    				if ($pointsLeft) 
    				{
    					if ($pointsLeft <= $teamx['points_max']) {
    						$teamx['points_actual'] = $pointsLeft;
    						$pointsLeft = 0;
    					}
    					else {
    						$teamx['points_actual'] = $teamx['points_max'];
    						$pointsLeft -= $teamx['points_max'];
    					}
    					$referees[$refereeId]['points_left'] = $pointsLeft;
    				}
 //Zend_Debug::dump($teamx);   				
    				$referees[$refereeId]['teams'][] = $teamx;
    			}
    		}
    		// Add referees to teams
    		
    		if (isset($teams[$teamId]))
    		{
    			if (isset($referees[$refereeId])) 
    			{
    				$referee = $referees[$refereeId];
    				
    				$refereex = array();
    				$refereex['name']       = $referee['name1'];
    				$refereex['priority']   = $link['pri_regular']; //$link['pri_tourn'];
    				$refereex['points_max'] = $link['max_regular']; //$link['max_tourn'];
    				$refereex['points_total']  = $referee['points_processed'];
    				$refereex['points_actual'] = 0;
    				
    				foreach($referee['teams'] as $team)
    				{
    					if ($team['team_id'] == $teamId) {
    						$refereex['points_actual']           = $team['points_actual'];
    						$teams[$teamId]['points_processed'] += $team['points_actual'];
    					}
    				}
    				$teams[$teamId]['referees'][] = $refereex;
    				
    				if (count($teams[$teamId]['referees']) > 5) echo 'Referee Count ' . count($teams[$teamId]['referees']) . "\n";
    				
    			}
    		}
    	}
// Zend_Debug::dump($referees);
		// Save data
		$this->teams    = $teams;
		$this->referees = $referees;
		           
        // Render
        ob_start();
        include 'exports/TeamRefereesTpl.xml.php';
        $out = ob_get_clean();
       
        // Save
        if (!$xmlFileName) return $out;
        
        $file = fopen($xmlFileName,'wt');
        fwrite($file,$out);
        fclose($file);        
    }
}
?>
