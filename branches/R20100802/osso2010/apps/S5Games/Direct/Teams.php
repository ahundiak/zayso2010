<?php
class Direct_Teams extends ExtJS_Direct_Base
{
	protected function getReadSql()
	{
	   $sql = <<<EOT
SELECT
  event_person.event_person_id      AS event_person_id,
  event_person.event_id             AS event_id,
  
  person.person_id  AS person_id,
  person.fname      AS person_fname,
  person.lname      AS person_lname,
  
  event_person_type.event_person_type_id AS position_id,
  event_person_type.keyx                 AS position_key,
  event_person_type.descx                AS position_desc
  
FROM event_person

LEFT JOIN person ON person.person_id = event_person.person_id
LEFT JOIN event_person_type ON event_person_type.event_person_type_id = event_person.event_person_type_id

EOT;
    return $sql;
	}
	function create($params, $allowEmptyPosition = false)
	{
    $eventId = $params['event_id'];
    if (!$eventId)
    {
    	echo "No eventId in Event_EventPersonAction.create\n";
    	return;
    }
    $idName  = 'event_person_id';
    $results = array();
    
    $db = $this->context->db;
    
    $records = $params['records'];
    if (!isset($records[0])) $records = array($records);
    
    $success = true;
    
    foreach($records as $record)
    {
    	$personId   = $record['person_id'];
    	$positionId = $record['position_id']; // Server side needs to detect this or 
    	if ($personId && $positionId)
    	{
        $row = array
        (
          'event_id'              => $eventId,
          'person_id'             => $personId,
          'event_person_type_id'  => $positionId,
        );
        try
        {
          $db->insert('event_person','event_person_id',$row);
          $results[] = array($idName => $db->lastInsertId());
        }
        catch(Exception $e)
        {
        	$success = false;
       // $results[] = array($idName => $record['event_person_id']);
          $results[] = array($idName => 0);
        }
    	}
      else 
      {
      	$success = false;  
      //$results[] = array($idName => $record['event_person_id']);
        $results[] = array($idName => 0);
      }	
    }
    // Need tosend back what was inserted to get ids to match up
    return $this->wrapResults($results,$success);

    $ids  = implode(',',$ids);
    $sql  = $this->getReadSql();
    $sql .= "WHERE event_person.event_person_id IN ({$ids}) ;";
    
    $rows = $db->fetchRows($sql);

    return $this->wrapResults($rows,$success);
	}
	function update($params)
	{
		$sql = <<<EOT
UPDATE event_person
SET person_id = :person_id
WHERE event_person_id = :event_person_id;
EOT;
    $eventId = $params['event_id'];
    $db = $this->context->db;
    
    $records = $params['records'];
    $count   = 0;
    if (!isset($records[0])) $records = array($records);
    
    foreach($records as $record)
    {
      $paramsx = array
      (
        'person_id'       => $record['person_id'],
        'event_person_id' => $record['event_person_id'],
      );
      $count += $db->execute($sql,$paramsx);
    }
    return $this->read(array('event_id' => $eventId));
	}

  function read($params)
  {
    $sql = <<<EOT
SELECT
  event_person.event_person_id      AS event_person_id,
  event_person.event_id             AS event_id,
  
  person.person_id  AS person_id,
  person.fname      AS person_fname,
  person.lname      AS person_lname,
  
  event_person_type.event_person_type_id AS position_id,
  event_person_type.keyx                 AS position_key,
  event_person_type.descx                AS position_desc
  
FROM event_person

LEFT JOIN person ON person.person_id = event_person.person_id
LEFT JOIN event_person_type ON event_person_type.event_person_type_id = event_person.event_person_type_id

WHERE    event_person.event_id = :event_id
ORDER BY event_person.event_person_type_id

EOT;

    $db   = $this->context->db;
    $rows = $db->fetchRows($sql);

    return $this->wrapResults($rows);
  }
}