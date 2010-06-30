<?php
class Referee_RefSchedQuery extends Base_BaseQuery
{
	public function execute($params)
	{
	    $db = $this->context->db;
    	
        $sql = <<<EOT
SELECT 
  event_person.person_id            AS referee_id,
  event_person.event_id             AS event_id,
  event_person.event_person_type_id AS referee_position_id,   
  
  event.event_date AS event_date,
  event.point2     AS event_point2,
  
  event_team.division_id AS division_id
  
FROM event_person

LEFT JOIN event ON event.event_id = event_person.event_id

LEFT JOIN event_team ON event_team.event_id = event.event_id

WHERE
  event_person.event_person_type_id IN (10,11,12) AND
  
  event.unit_id IN (:league_id) AND
  
  event.event_date >= :date1 AND
  event.event_date <= :date2

ORDER BY event_person.event_id, event_person.event_person_type_id
;
EOT;

        $rows = $db->fetchRows($sql,$params);
        $events = array();
        foreach($rows as $row)
        {
        	$eventId = $row['event_id'];
        	
        	if (isset($events[$eventId])) $event = $events[$eventId];
        	else                          $event = new Event_EventItem();
        	
        	$event->id     = $row['event_id'];
        	$event->point2 = $row['event_point2'];
        	
        	if ($event->divId < $row['division_id']) $event->divId = $row['division_id'];
        	
        	$referee = new Referee_RefItem();
        	$referee->id         = $row['referee_id'];
        	$referee->positionId = $row['referee_position_id'];
        	
        	$event->addReferee($referee);
        	
        	$events[$event->id] = $event;
        }
        
        return $events;
    }
}
?>