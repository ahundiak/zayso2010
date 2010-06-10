<?php
/* ---------------------------------------------------
 * Want all teams with coach names and maybe eventually phone numbers?
 */
class Team_TeamQueryAllWithCoaches extends Base_BaseQuery
{
	protected $context = NULL;
	
	public function execute($params = array())
	{
    	$db = $this->context->db;
    	
   	
        $sql = <<<EOT
SELECT 
  phy_team.phy_team_id          AS phy_team_id,
  phy_team.division_seq_num     AS phy_team_div_seq_num,
  division.division_id          AS phy_team_div_id,
  division.desc_pick            AS phy_team_div,
  unit.unit_id                  AS phy_team_league_id,
  unit.keyx                     AS phy_team_league_key,
  phy_team_coach_head.person_id AS phy_team_coach_head_person_id,
  phy_team_coach_head.fname     AS phy_team_coach_head_person_fname,
  phy_team_coach_head.lname     AS phy_team_coach_head_person_lname
  
FROM phy_team

LEFT JOIN unit        ON unit.unit_id               = phy_team.unit_id
LEFT JOIN reg_year    ON reg_year.reg_year_id       = phy_team.reg_year_id
LEFT JOIN division    ON division.division_id       = phy_team.division_id
LEFT JOIN season_type ON season_type.season_type_id = phy_team.season_type_id

LEFT JOIN phy_team_person 
  AS  phy_team_person_coach_head 
  ON  phy_team_person_coach_head.phy_team_id = phy_team.phy_team_id
  AND phy_team_person_coach_head.vol_type_id = 16
  
LEFT JOIN person
  AS phy_team_coach_head
  ON phy_team_coach_head.person_id = phy_team_person_coach_head.person_id

WHERE 
  phy_team.unit_id        = :league_id      AND
  phy_team.season_type_id = :season_type_id AND 
  phy_team.reg_year_id    = :reg_year_id    AND
  phy_team.division_id IN (:division_ids)

ORDER BY
  phy_team.unit_id,
  phy_team.division_id,
  phy_team.division_seq_num
;
EOT;

  		$divRepo = new Division_DivisionRepo($this->context);
    	
    	$divisionIds = $divRepo->getDivisionIdsForAgeRange(10,19,TRUE,TRUE,TRUE);
    	
    	// $divisionIds = $db->quoteInto('?',$divisionIds);
 
		$params = array(
			'league_id'      => 4,
			'season_type_id' => 1,
		    'reg_year_id'    => 8,
			'division_ids'   => $divisionIds,
	    );
	    
  		$rows = $db->fetchRows($sql,$params);
  		
  		$teams = array();
  		
  		foreach($rows as $row) 
  		{
  			// try with objects
  			$team = new Team_TeamItem();
  			
  			$team->id = $row['phy_team_id'];
  			
  			$team->divId     = $row['phy_team_div_id'];
  			$team->divKey    = $row['phy_team_div'];
  			$team->divSeqNum = $row['phy_team_div_seq_num'];
  			
  			$team->leagueId  = $row['phy_team_league_id'];
  			$team->leagueKey = $row['phy_team_league_key'];
  			
  			$coach = new Coach_CoachItem();
  			
  			$coach->id    = $row['phy_team_coach_head_person_id'];
  			$coach->fname = $row['phy_team_coach_head_person_fname'];
  			$coach->lname = $row['phy_team_coach_head_person_lname'];
  			
  			$team->coach = $coach;
  			
  			$teams[$team->id] = $team;
  		}
  		// Cerad_Debug::dump($teams);
  		return $teams;
	}
}
?>