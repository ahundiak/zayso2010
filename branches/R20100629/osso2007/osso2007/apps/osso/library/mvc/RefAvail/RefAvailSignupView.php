<?php

class RefAvailSignupView extends Proj_View
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'Referee Tournament Availability';
        $this->tplContent = 'RefAvailSignupTpl';
    }
    function getTeams($regionId)
    {
        $sql = <<<EOT
SELECT 
  phy_team.phy_team_id          AS phy_team_id,
  phy_team.division_seq_num     AS phy_team_div_seq_num,
  division.desc_pick            AS phy_team_div,
  
  phy_team_coach_head.fname     AS phy_team_coach_head_person_fname,
  phy_team_coach_head.lname     AS phy_team_coach_head_person_lname
  
FROM phy_team

LEFT JOIN division    ON division.division_id       = phy_team.division_id

LEFT JOIN phy_team_person 
  AS  phy_team_person_coach_head 
  ON  phy_team_person_coach_head.phy_team_id = phy_team.phy_team_id
  AND phy_team_person_coach_head.vol_type_id = 16
  
LEFT JOIN person
  AS phy_team_coach_head
  ON phy_team_coach_head.person_id = phy_team_person_coach_head.person_id
  
WHERE 

  phy_team.season_type_id =  1  AND 
  phy_team.reg_year_id    =  9  AND
  phy_team.division_id   IN (7,8,9,10,11,12,13,14,15,16,17,18,19,20,21) AND
  phy_team.unit_id        = :region_id

ORDER BY
  phy_team.unit_id,
  phy_team.division_id,
  phy_team.division_seq_num
;
EOT;
		$params = array('region_id' => $regionId);
		
        $rows = $this->context->db->fetchAll($sql,$params);
        
        $teams = array(0 => 'Select');
        
        foreach($rows as $row)
        {
        	$num = $row['phy_team_div_seq_num'];
        	if ($num < 10) $num = '0' . $num;
        	
        	$team = 
        		$row['phy_team_div'] . $num . ' ' . 
        		$row['phy_team_coach_head_person_lname'] . ', ' .
        		$row['phy_team_coach_head_person_fname'];
        		
        	$team = 
        		$row['phy_team_div'] . $num . ' ' . 
        		$row['phy_team_coach_head_person_lname'];
        		
        	$team = substr($team,0,18);
        	
        	$teams[$row['phy_team_id']] = $team;
        }
        // Zend_Debug::dump($teams); die();
    	return $teams;
    }
    function process($data)
    {
        $models = $this->context->models;
        $user   = $this->context->user;

        $this->availPickList = array(
        	0 => 'Do not know yet',
        	1 => 'Not available',
        	2 => 'Available all day',
        	3 => 'Morning only',
        	4 => 'Afternoon only',
        	5 => 'If needed',
        	6 => 'See remarks',
        );
        $this->divPickList = array(
             0 => 'None',
        	 7 => 'U10 Boys',
        	 8 => 'U10 Girls',
        	10 => 'U12 Boys',
        	11 => 'U12 Girls',
        	13 => 'U14 Boys',
        	14 => 'U14 Girls',
        	16 => 'U16 Boys',
        	17 => 'U16 Girls',
        	19 => 'U19 Boys',
        	20 => 'U19 Girls',
         	
        );
        $refList = $user->refereePickList;
        
        $refAvailRepo = new Ref_RefAvailRepo($this->context);
        $groupId = 1;
        $refs = array();
        foreach($refList as $id => $name)
        {
        	$ref = $refAvailRepo->load($groupId,$id);
        	
        	$ref->teams = $this->getTeams($ref->regionId);
        	
        	$refs[$id] = $ref;
        }
        // Zend_Debug::dump($refs); die();
        
        $this->refs = $refs;

        $this->teamPickList = array
        (
        	0 => 'NONE',
        	1 => 'U10C01',
        	2 => 'U10C02',
        	3 => 'U10C03',        	
        );
        return $this->renderx();
        
    }
}
?>