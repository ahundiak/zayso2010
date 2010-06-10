<?php
/* ==========================================================
 * Very simple query just to see if person with position exists
 */
class Team_SchTeamQueryOne extends Base_BaseQuery
{
	public function execute($search)
	{
	    $db = $this->context->db;
	    
        $sql = <<<EOT
        
SELECT  
     phy_team.phy_team_id AS phy_team_id,
     sch_team.sch_team_id AS sch_team_id,
     sch_team.unit_id     AS sch_team_league_id,
     sch_team.division_id AS sch_team_division_id
     
FROM phy_team
LEFT JOIN sch_team ON sch_team.phy_team_id = phy_team.phy_team_id
 
WHERE 
	phy_team.unit_id          = :league_id        AND
	phy_team.reg_year_id      = :year_id          AND
	phy_team.season_type_id   = :season_type_id   AND 
	phy_team.division_id      = :division_id      AND
	phy_team.division_seq_num = :division_seq_num AND
	
	sch_team.schedule_type_id = 1
;
EOT;

        $rows = $db->fetchRows($sql,$search);
        
        if (count($rows) != 1) return NULL;
        $row = $rows[0];
        
        $item = new Team_SchTeamItem();
        $item->id         = $row['sch_team_id'];
        $item->phyTeamId  = $row['phy_team_id'];
        $item->leagueId   = $row['sch_team_league_id'];
        $item->divId      = $row['sch_team_division_id'];
                
        return $item;
    }
}
?>