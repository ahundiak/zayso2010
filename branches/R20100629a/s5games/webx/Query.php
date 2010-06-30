<?php
class Query
{
	protected $db;
	
	function __construct($db)
	{
		$this->db = $db;
	}
	function getDb() { return $this->db; }
	
	function queryEventsForIds($eventIds)
	{
		$db = $this->db;
		$eventIds = $db->quote($eventIds);
		
		// Cerad_Debug::dump($eventIds); // die();
		
		$sql = <<<EOT
SELECT 
	event.event_id   AS event_id,
	event.event_date AS event_date,
	event.event_time AS event_time,
	
	field.field_id   AS event_field_id,
	field.descx      AS event_field_desc,
    field.unit_id    AS event_field_region_id,
    
	event_team.event_team_type_id AS team_event_team_type_id,
	event_team.unit_id            AS team_region_id,
	event_team.division_id        AS team_division_id,
	
	event_team_region.keyx        AS team_region_key,
	event_team_region.desc_pick   AS team_region_desc,
	
	event_team_division.desc_pick AS team_division_key,	
	
	sch_team.sch_team_id      AS team_sch_team_id,
	sch_team.desc_short       AS team_sch_team_desc,
	sch_team.schedule_type_id AS team_sch_team_type_id,
	
	phy_team.phy_team_id      AS team_id,
	phy_team.division_seq_num AS team_division_seq_num,
	phy_team.name             AS team_name,
	
	coach_headx.vol_type_id AS coach_head_vol_type_id,

	coach_head.person_id AS coach_head_id,
	coach_head.fname     AS coach_head_fname,
	coach_head.lname     AS coach_head_lname,
	coach_head.nname     AS coach_head_nname,
	coach_head.aysoid    AS coach_head_aysoid
		
FROM event

LEFT JOIN field ON field.field_id = event.field_id

LEFT JOIN event_team ON event_team.event_id = event.event_id

LEFT JOIN unit     AS event_team_region   ON event_team_region.unit_id       = event_team.unit_id
LEFT JOIN division AS event_team_division ON event_team_division.division_id = event_team.division_id

LEFT JOIN sch_team   ON sch_team.sch_team_id = event_team.team_id

LEFT JOIN phy_team   ON phy_team.phy_team_id = sch_team.phy_team_id



LEFT JOIN phy_team_person AS coach_headx 
	ON coach_headx.phy_team_id = phy_team.phy_team_id AND coach_headx.vol_type_id = 16

LEFT JOIN person AS coach_head ON coach_head.person_id = coach_headx.person_id
	
WHERE
    event.event_id IN ($eventIds) 

ORDER BY event.event_date,event.event_time,field.sortx
EOT;
		$sql .= ';';
        // echo $sql; // die();
        try {
        	$rows = $db->fetchRows($sql);
        }
        catch (Exception $e)
        {
        	die($e);
        }
		//die();
		$items = array();
		foreach($rows as $row) {
			
			//Cerad_Debug::dump($row); die();
			
			$id = $row['event_id'];
			if (isset($items[$id])) $item = $items[$id];
			else                    $item = new Zayso_Model_Item_Event($row,'event');
			
			$team = new Zayso_Model_Item_Team($row,'team');
			$item->addTeam($team);
			
			$person = new Zayso_Model_Item_Person($row,'coach_head');
			$team->addHeadCoach($person);
			
			$items[$id] = $item;
			
			//Cerad_Debug::dump($item);//die();
		}
		// Now grab the people
		$sql = <<<EOT
SELECT * FROM event_personx 
WHERE event_id IN ($eventIds) 
ORDER BY event_id,event_person_type_id;
EOT;
		$rows = $db->fetchRows($sql);
		foreach($rows as $row) {
			$person  = new Zayso_Model_Item_EventPerson($row);
			$eventId = $row['event_id'];
			$event = $items[$eventId];
			$event->addPerson($person);
		}
		return $items;
		
		Cerad_Debug::dump($items[6392]);//die();
		die('After loop');
	}
	function queryDistinctEvents($params)
	{
		$dateFrom = $params->date1;
		$dateTo   = $params->date1;
		
		$unitId     = NULL;
		$divisionId = NULL;
		
		$sql = <<<EOT
SELECT DISTINCT event_team.event_id AS id
FROM event_team
LEFT JOIN event ON event.event_id = event_team.event_id
EOT;

		$where = new Cerad_SQL_Where();

        if ($dateFrom) $where->add('event.event_date','>=',$dateFrom);
        if ($dateTo)   $where->add('event.event_date','<=',$dateTo);
        
        //if ($unitId)     $select->where('event_team.unit_id      IN (?)',$unitId);
        //if ($divisionId) $select->where('event_team.division_id  IN (?)',$divisionId);
        
        $sql .= $where->toSQL() . ';';
        
        $db = $this->getDb();
        $rows = $db->fetchRows($sql);
        $ids = array();
        foreach($rows as $row) {
        	$ids[] = (int)$row['id'];
        }
        return $ids;
	}
}
?>