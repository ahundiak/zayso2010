<?php
/* ==========================================================
 * See if physical team exists
 */
class Team_PhyTeamQueryOne extends Base_BaseQuery
{
	public function execute($search)
	{
	    $db = $this->context->db;
	    
        $sql = <<<EOT
        
SELECT  
    phy_team.phy_team_id      AS phy_team_id,
    phy_team.unit_id          AS phy_team_league_id,
    phy_team.division_id      AS phy_team_division_id,
    phy_team.division_seq_num AS phy_team_division_seq_num
          
FROM phy_team
 
WHERE 
	phy_team.unit_id          = :league_id        AND
	phy_team.reg_year_id      = :year_id          AND
	phy_team.season_type_id   = :season_type_id   AND 
	phy_team.division_id      = :division_id      AND
	phy_team.division_seq_num = :division_seq_num
;
EOT;

        $rows = $db->fetchRows($sql,$search);
        
        if (count($rows) != 1) return NULL;
        $row = $rows[0];
        
        $item = new Team_TeamItem();
        $item->id        = $row['phy_team_id'];
        $item->leagueId  = $row['phy_team_league_id'];
        $item->divId     = $row['phy_team_division_id'];
        $item->divSeqNum = $row['phy_team_division_seq_num'];
        
        return $item;
    }
}
?>