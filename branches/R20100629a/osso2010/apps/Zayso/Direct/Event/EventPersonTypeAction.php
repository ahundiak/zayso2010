<?php
class Direct_Event_EventPersonTypeAction extends Direct_BaseAction
{
	function read($params = null)
	{
		$sql = <<<EOT
SELECT
  event_person_type_id AS type_id,
  keyx                 AS type_key,
  descx                AS type_desc
  
FROM event_person_type  

ORDER BY type_id;

EOT;

		$db   = $this->context->db;
		$rows = $db->fetchRows($sql);

		return $this->wrapResults($rows);
	}
}