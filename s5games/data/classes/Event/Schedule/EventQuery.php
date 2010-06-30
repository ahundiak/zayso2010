<?php
class Event_Schedule_EventQuery extends Base_BaseQuery
{
	// Given a list of ids return a list of event details
	function process($ids,$wantOfficials = TRUE)
	{
		$db = $this->context->db;
		
		// Do this instead of running throught the prepared system process
		if (count($ids) < 1) return array();
		
		$idx = implode(',',$ids);
		
		$personTypeHeadCoach = Person_PersonTypeRepo::TYPE_HEAD_COACH;
		
		$sql = <<<EOT
		
SELECT
  event.event_id   AS event_id,
  event.event_date AS event_date,
  event.event_time AS event_time,

  field.field_id   AS field_id,
  field.descx      AS field_desc,

  event_team.event_team_id      AS event_team_id,
  event_team.event_team_type_id AS event_team_type_id,
  event_team.division_id        AS event_team_div_id,
  event_team.score              AS event_team_score,
  
  event_team_div.desc_pick      AS event_team_div_desc,
  
  event_team_league.unit_id     AS event_team_league_id,
  event_team_league.keyx        AS event_team_league_key,
  
  sch_team.sch_team_id      AS sch_team_id,
  sch_team.schedule_type_id AS sch_team_sched_type_id,
  sch_team.desc_short       AS sch_team_desc,

  phy_team.phy_team_id      AS phy_team_id,
  phy_team.division_seq_num AS phy_team_div_seq_num,
  phy_team.name             AS phy_team_name,
  phy_team.colors           AS phy_team_colors,

  phy_team_person.vol_type_id AS person_vol_type_id,
  person.person_id            AS person_id,
  person.fname                AS person_fname,
  person.lname                AS person_lname,
  person.nname                AS person_nname,
  person.aysoid               AS person_aysoid
  
FROM event

LEFT JOIN field ON field.field_id = event.field_id

LEFT JOIN event_team ON event_team.event_id = event.event_id

LEFT JOIN division AS event_team_div ON event_team_div.division_id = event_team.division_id

LEFT JOIN unit AS event_team_league ON event_team_league.unit_id = event_team.unit_id

LEFT JOIN sch_team ON sch_team.sch_team_id = event_team.team_id

LEFT JOIN phy_team ON phy_team.phy_team_id = sch_team.phy_team_id

LEFT JOIN phy_team_person ON phy_team_person.phy_team_id = phy_team.phy_team_id AND 
  phy_team_person.vol_type_id = $personTypeHeadCoach

LEFT JOIN person ON person.person_id = phy_team_person.person_id

WHERE event.event_id IN ($idx);

EOT;

		// If i had a bunch of queries like this then the maps could go into a base class of some sort
		$eventMap = array
		(
			'event_id'   => 'id',
			'event_date' => 'date',
			'event_time' => 'time',
		  'field_id'   => 'fieldId',
		  'field_desc' => 'fieldDesc',
		);
		$teamMap = array
		(
      'event_team_id'         => 'id',
      'event_team_type_id'    => 'typeId',
      'event_team_div_id'     => 'divId',
      'event_team_div_desc'   => 'divKey',
		  'event_team_league_id'  => 'leagueId',
		  'event_team_league_key' => 'leagueKey',
		  'event_team.score'      => 'score',
		
		  'event_id'              => 'eventId', // Probably do not need
		
		  'sch_team_id'            => 'schTeamId',
		  'sch_team_sched_type_id' => 'schTeamScheduleTypeId',
		  'sch_team_desc'          => 'schTeamDesc',
		
		  'phy_team_id'          => 'phyTeamId',
      'phy_team_div_seq_num' => 'divSeqNum',
      'phy_team_name'        => 'phyTeamName',
      'phy_team_colors'      => 'phyTeamColors',
		);
		$personMap = array
		(
		  'person_id'          => 'id',
      'person_vol_type_id' => 'typeId',
      'person_fname'       => 'fname',
      'person_lname'       => 'lname',
      'person_nname'       => 'nname',
      'person_aysoid'      => 'aysoid',
		);
    $events  = array();
  //$teams   = array(); // Eventually allow for multiple team records
  //$persons = array(); // Eventually allow for multiple coach records if needed
    
		$rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
    	// Process the event
    	$eventId = $row['event_id'];
    	if (isset($events[$eventId])) $event = $events[$eventId];
    	else
    	{
    		$event = new Event_Schedule_EventItem($row,$eventMap);
    		$events[$eventId] = $event;
    	}
      // if ($eventId == 7758) Cerad_Debug::dump($row);
      
    	// Process the team
    	$teamId = $row['event_team_id'];
    	$team = new Event_Schedule_EventTeamItem($row,$teamMap);
    	$event->addTeam($team);
    	
    	$person = new Person_PersonItem($row,$personMap);
    	$team->coach = $person;
    	
      //if ($eventId == 7758) Cerad_Debug::dump($event);
    	//if ($eventId == 7758) Cerad_Debug::dump($row);
    }
    // Want to daa referee information?
    if (!$wantOfficials) return $events;
    
    $sql = <<<EOT
SELECT 
  event_person.event_id        AS event_id,
  event_person.event_person_id AS event_person_id,
  
  event_person.event_person_type_id AS person_vol_type_id,
  
  person.person_id            AS person_id,
  person.fname                AS person_fname,
  person.lname                AS person_lname,
  person.nname                AS person_nname,
  person.aysoid               AS person_aysoid
  
FROM event_person

LEFT JOIN person ON person.person_id = event_person.person_id

WHERE event_person.event_id IN ($idx);

EOT;
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      // Process the event
      $eventId = $row['event_id'];
      if (isset($events[$eventId])) 
      {
      	$event = $events[$eventId];
        $person = new Person_PersonItem($row,$personMap);
        $event->addReferee($person);
        // Cerad_Debug::dump($row);
      }
      else
      {
      	// Something really wrong
      }
    }
    return $events;
	}
}