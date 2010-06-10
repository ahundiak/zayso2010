<?php
class League_LeagueCombo
{
	function __construct($context)
	{
		$this->context = $context;
	}
    function execute()
    {
        $sql = <<<EOT
SELECT
  unit_id   AS  id,
  desc_pick AS  value,
  keyx      AS `key`,
  desc_long AS `desc`
FROM unit
ORDER BY keyx;
EOT;
        $rows = $this->context->db->fetchRows($sql);
        $results = array(
            'success' => TRUE,
            'rows'    => $rows
        );
        return $results;
    }
}
?>
