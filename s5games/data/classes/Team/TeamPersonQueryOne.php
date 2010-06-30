<?php
/* ==========================================================
 * See if team person exists
 */
class Team_TeamPersonQueryOne extends Base_BaseQuery
{
	public function execute($search)
	{
	    $db = $this->context->db;
	    
	    if (is_object($search))
	    {
	    	$item = $search;
	    	$search = array
	    	(
	    	    'phy_team_id' => $item->teamId,
	    	    'person_id'   => $item->personId,
	    	    'vol_type_id' => $item->typeId,
	    	);
	    }
        $sql = <<<EOT
        
SELECT  
    phy_team_person.phy_team_person_id AS phy_team_person_id,
    phy_team_person.phy_team_id        AS team_id,
    phy_team_person.person_id          AS person_id,
    phy_team_person.vol_type_id        AS type_id
          
FROM phy_team_person
 
WHERE 
	phy_team_person.phy_team_id = :phy_team_id  AND
	phy_team_person.person_id   = :person_id    AND
	phy_team_person.vol_type_id = :vol_type_id
;
EOT;

        $rows = $db->fetchRows($sql,$search);
        
        if (count($rows) != 1) return NULL;
        $row = $rows[0];
        
        $item = new Team_TeamPersonItem();
        $item->id       = $row['phy_team_person_id'];
        $item->teamId   = $row['team_id'];
        $item->personId = $row['person_id'];
        $item->typeId   = $row['type_id'];
        
        return $item;
    }
}
?>