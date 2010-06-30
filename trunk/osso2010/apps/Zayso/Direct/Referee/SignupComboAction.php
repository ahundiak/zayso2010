<?php
class Direct_Referee_SignupComboAction extends Direct_BaseAction
{
	function read($params)
	{
		$rows = array
		(
		  array('person_id' =>    0, 'person_fname' => '',       'person_lname' => ''          ),
		  array('person_id' =>  419, 'person_fname' => 'Ronnie', 'person_lname' => 'Turrentine'),
      array('person_id' =>  464, 'person_fname' => 'Jim',    'person_lname' => 'Rogers'    ),
      array('person_id' => 1818, 'person_fname' => 'Jeff',   'person_lname' => 'Thompson'  ),
    );
    return $this->wrapResults($rows);
	}
	function readx($params)
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
		$rows = $db->fetchRows($sql,$params);

		$result = array
    (
      'totalCount' => count($rows),
      'records'    => $rows
    );
		return $result;
	}
}