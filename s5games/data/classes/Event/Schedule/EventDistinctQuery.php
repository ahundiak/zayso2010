<?php
class Event_Schedule_EventDistinctQuery extends Base_BaseQuery
{
	public function execute($params)
  {
    $db = $this->context->db;
	
    $sql = <<<EOT
SELECT DISTINCT event.event_id FROM event

LEFT JOIN event_team ON event_team.event_id = event.event_id

EOT;

    $wheres = array();
    if (isset($params['date1'])) $wheres[] = 'event.event_date >= :date1';
    if (isset($params['date2'])) $wheres[] = 'event.event_date <= :date2';
    
    if (count($wheres)) $sql .= "WHERE\n" . implode(" AND\n",$wheres);
    
    $rows = $db->fetchRows($sql,$params);
    
    $ids = array();
    foreach($rows as $row)
    {
    	$ids[] = $row['event_id'];
    }
    return $ids;
  }
}