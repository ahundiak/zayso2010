<?php
/* ---------------------------------------------------
 * teams, head coach names, phones and emails
 */
class Team_TeamQueryExport extends Base_BaseQuery
{
	protected $context = NULL;
	
	public function execute($params = array())
	{
    	$db = $this->context->db;
    	
        $sql = <<<EOT
SELECT 
  phy_team.phy_team_id          AS phy_team_id,
  phy_team.division_seq_num     AS phy_team_div_seq_num,
  unit.unit_id                  AS phy_team_league_id,
  unit.keyx                     AS phy_team_league_key,
  reg_year.reg_year_id          AS phy_team_year_id,
  reg_year.descx                AS phy_team_year,
  division.division_id          AS phy_team_div_id,
  division.desc_pick            AS phy_team_div,
  season_type.season_type_id    AS phy_team_season_type_id,
  season_type.descx             AS phy_team_season_type,
  
  phy_team_coach_head.person_id AS phy_team_coach_head_person_id,
  phy_team_coach_head.fname     AS phy_team_coach_head_person_fname,
  phy_team_coach_head.lname     AS phy_team_coach_head_person_lname,
  phy_team_coach_head.aysoid    AS phy_team_coach_head_person_aysoid,
  
  phy_team_coach_head_phone.phone_id      AS phy_team_coach_head_phone_id,
  phy_team_coach_head_phone.phone_type_id AS phy_team_coach_head_phone_type_id,
  phy_team_coach_head_phone.person_id     AS phy_team_coach_head_phone_person_id,
  phy_team_coach_head_phone.area_code     AS phy_team_coach_head_phone_area_code,
  phy_team_coach_head_phone.num           AS phy_team_coach_head_phone_num,
  phy_team_coach_head_phone.ext           AS phy_team_coach_head_phone_ext,

  phy_team_coach_head_email.email_id      AS phy_team_coach_head_email_id,
  phy_team_coach_head_email.email_type_id AS phy_team_coach_head_email_type_id,
  phy_team_coach_head_email.person_id     AS phy_team_coach_head_email_person_id,
  phy_team_coach_head_email.address       AS phy_team_coach_head_email_address
  
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

LEFT JOIN phone 
  AS phy_team_coach_head_phone
  ON phy_team_coach_head_phone.person_id = phy_team_coach_head.person_id
  
LEFT JOIN email 
  AS phy_team_coach_head_email
  ON phy_team_coach_head_email.person_id = phy_team_coach_head.person_id
  
WHERE 

  phy_team.season_type_id =  :season_type_id AND 
  phy_team.reg_year_id    =  :reg_year_id    AND
  phy_team.division_id   IN (:division_ids)

ORDER BY
  phy_team.unit_id,
  phy_team.division_id,
  phy_team.division_seq_num
;
EOT;

        //   phy_team.unit_id       IN (:league_ids)    AND

  		$divRepo = new Division_DivisionRepo($this->context);
    	
    	$divisionIds = $divRepo->getDivisionIdsForAgeRange(10,19,TRUE,TRUE,TRUE);
    	
    	if (isset($params['leagueIds'])) $leagueIds = $params['leagueIds'];
    	else                             $leagueIds = array(4);
    	
    	// $divisionIds = $db->quoteInto('?',$divisionIds);
 
		$params = array(
//			'league_ids'     => $leagueIds,
			'season_type_id' => 1,
		    'reg_year_id'    => 9,
			'division_ids'   => $divisionIds,
	    );
	    
  		$rows = $db->fetchRows($sql,$params);
  		
  		$teams = array();
  		
  		foreach($rows as $row) 
  		{
  			// Multiple record because of phone/email joins
  			$teamId = $row['phy_team_id'];
  			
  			// One team
  			if (isset($teams[$teamId])) $team = $teams[$teamId];
  			else 
  			{
  				$team = new Team_TeamItem();
  			
  				$team->id = $teamId;
  			
  				$team->divId     = $row['phy_team_div_id'];
  				$team->divKey    = $row['phy_team_div'];
  				$team->divSeqNum = $row['phy_team_div_seq_num'];
  			
  				$team->leagueId  = $row['phy_team_league_id'];
  				$team->leagueKey = $row['phy_team_league_key'];
  				
  				$team->yearId    = $row['phy_team_year_id'];
  				$team->year      = $row['phy_team_year'];
  				
  				$team->seasonTypeId = $row['phy_team_season_type_id'];
  				$team->seasonType   = $row['phy_team_season_type'];
  				
  				$coach = new Coach_CoachItem();
  			
  				$coach->id     = $row['phy_team_coach_head_person_id'];
  				$coach->fname  = $row['phy_team_coach_head_person_fname'];
  				$coach->lname  = $row['phy_team_coach_head_person_lname'];
  				$coach->aysoid = $row['phy_team_coach_head_person_aysoid'];
  				
  				$team->coach = $coach;
  			}
  			// Phone
  			if ($row['phy_team_coach_head_phone_id'] && !$team->coach->hasPhone($row['phy_team_coach_head_phone_type_id'])) 
  			{
  				$phone = new Phone_PhoneItem();
  				
  				$phone->id       = $row['phy_team_coach_head_phone_id'];
  				$phone->personId = $row['phy_team_coach_head_phone_person_id'];
  				$phone->typeId   = $row['phy_team_coach_head_phone_type_id'];
  				$phone->areaCode = $row['phy_team_coach_head_phone_area_code'];
  				$phone->num      = $row['phy_team_coach_head_phone_num'];
  				$phone->ext      = $row['phy_team_coach_head_phone_ext'];
  			
  				$team->coach->addPhone($phone);
  			}
  			// Email
  			if ($row['phy_team_coach_head_email_id'] && !$team->coach->hasEmail($row['phy_team_coach_head_email_type_id'])) 
  			{
  				$email = new Email_EmailItem();
  				
  				$email->id       = $row['phy_team_coach_head_email_id'];
  				$email->personId = $row['phy_team_coach_head_email_person_id'];
  				$email->typeId   = $row['phy_team_coach_head_email_type_id'];
  				$email->address  = $row['phy_team_coach_head_email_address'];
  			
  				$team->coach->addEmail($email);
  			}
   			
  			// And done
  			$teams[$team->id] = $team;
  		}
  		// Cerad_Debug::dump($teams);
  		return $teams;
	}
}
?>