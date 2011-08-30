<?php
class RefereeItem
{
	
}
error_reporting(E_ALL);

class RefPointsBaseView extends Proj_View
{
  protected $refereeId = 0;
  
  function getReferees()
  {
    $user      = $this->context->user;
    $accountId = $user->account->id;

    $sql = <<<EOT
SELECT
  person.fname     AS fname,
  person.lname     AS lname,
  person.nname     AS nname,
  person.person_id AS person_id,
  person.aysoid    AS aysoid,

  member.member_id AS member_id,
  
  eayso.reg_cert.catx  AS cert_cat,
  eayso.reg_cert.typex AS cert_type

FROM member
LEFT JOIN person ON person.person_id = member.person_id
LEFT JOIN eayso.reg_cert ON eayso.reg_cert.reg_num = person.aysoid
WHERE account_id = :account_id
ORDER BY lname,fname;
EOT;
    $rows = $this->context->db->fetchRows($sql,array('account_id' => $accountId));

    $persons = array();
    foreach($rows as $row)
    {
      $personId = $row['person_id'];
      if (!isset($persons[$personId]))
      {
        $persons[$personId] = $row;
        $persons[$personId]['isReferee'] = false;
      }
      if ($row['cert_cat'] == 200) $persons[$personId]['isReferee'] = true;
    }
    $items = array();
    foreach($persons as $person)
    {
      if ($person['isReferee'])
      {
        $item = new RefereeItem();
        $item->id    = $person['person_id'];
        $item->fname = $person['fname'];
        $item->lname = $person['lname'];
        $item->nname = $person['lname'];

        $item->memberId  = $person['member_id'];
        $item->volTypeId = VolTypeModel::TYPE_ADULT_REF; // Pull dob later

        $items[] = $item;
      }
    }
    return $items;
  }
  function getRefereeTeams($refereeIds,$data)
  {
    // Cerad_Debug::dump($data); die();
    if (count($refereeIds) < 1) return array();
        
    $db = $this->context->db;
    	
    $select = new Proj_Db_Select($db);
        
    $select->from ('phy_team_referee',array
    (
      'phy_team_referee.phy_team_referee_id  AS phy_team_referee_id',
      'phy_team_referee.referee_id           AS referee_id',
      'phy_team_referee.phy_team_id          AS team_id',
      'phy_team_referee.pri_regular          AS pri_regular',
      'phy_team_referee.pri_tourn            AS pri_tourn',
      'phy_team_referee.max_regular          AS max_regular',
      'phy_team_referee.max_tourn            AS max_tourn',
    ));
    $select->joinleft(
      'person as referee',
      'referee.person_id = phy_team_referee.referee_id',
      array(
        'referee.fname AS referee_fname',
        'referee.lname AS referee_lname',
      ));
      $select->joinleft(
        'phy_team as team',
        'team.phy_team_id = phy_team_referee.phy_team_id',
        array(
          'team.unit_id          AS team_unit_id',
          'team.division_id      AS team_div_id',
          'team.division_seq_num AS team_seq_num',
      ));
        $select->joinleft(
            'unit as unit',
            'unit.unit_id = team.unit_id',
            array(
                'unit.keyx AS team_unit_desc',
        ));
        $select->joinleft(
            'division as division',
            'division.division_id = team.division_id',
            array(
                'division.desc_pick AS team_div_desc',
        ));
        $select->joinleft(
            'phy_team_person as head_coachx',
            'head_coachx.phy_team_id = phy_team_referee.phy_team_id AND head_coachx.vol_type_id = 16',
            array(
                'head_coachx.person_id   AS head_coach_id',
                'head_coachx.vol_type_id AS head_coach_type',
            
        ));
        $select->joinleft(
            'person as head_coach',
            'head_coach.person_id = head_coachx.person_id',
            array(
                'head_coach.fname AS head_coach_fname',
                'head_coach.lname AS head_coach_lname',
        ));
        
        $select->where("phy_team_referee.referee_id     IN (?)",$refereeIds);
        $select->where("phy_team_referee.reg_year_id    IN (?)",$data->yearId);
        $select->where("phy_team_referee.season_type_id IN (?)",$data->seasonTypeId);
		
        $select->order('phy_team_referee.referee_id,phy_team_referee.pri_regular');

        $rows = $db->fetchAll($select);
// Zend_Debug::dump($rows[0]);		
        $teams = array();
        foreach($rows as $row) 
        {
        	$team['phy_team_referee_id'] = $row['phy_team_referee_id'];
        	
        	$team['referee_id'] = $row['referee_id'];
        	$team['referee_name'] = $row['referee_fname'] . ' ' . $row['referee_lname'];
        	
        	$team['team_id']     = $row['team_id'];
        	$team['pri_regular'] = $row['pri_regular'];
        	$team['pri_tourn']   = $row['pri_tourn'];
        	$team['max_regular'] = $row['max_regular'];
        	$team['max_tourn']   = $row['max_tourn'];
        	
        	$teamSeqNum = $row['team_seq_num'];
        	if ($teamSeqNum < 10) $teamSeqNum = '0' . $teamSeqNum;
        	
        	$team['team_desc'] = 
        		$row['team_unit_desc']   . '-' . $row['team_div_desc'] . '-' . $teamSeqNum . ' ' . 
        		$row['head_coach_fname'] . ' ' . $row['head_coach_lname'];
        		
        	$teams[] = $team;
        }
        return $teams;
        
    }
    function buildPickLists($referees,$data)
    {
        // Team Information
        $refereeIds = array();
        foreach($referees as $referee) {
        	$refereeIds[] = $referee->id;
        }
        $this->refereeTeams = $this->getRefereeTeams($refereeIds,$data);
        
        $this->refereeTeamPriorityPickList = array(1 => 1, 2 => 2, 3 => 3);
        
        $refereeTeamMaxRegularPicklist = array();
        for($i = 1; $i < 21; $i++) $refereeTeamMaxRegularPicklist[$i] = $i;
        
        $this->refereeTeamMaxRegularPicklist = array(
        	 1 =>  1,  2 =>  2,  3 =>  3,  4 =>  4,  5 =>  5,  6 =>  6,  7 =>  7,  8 =>  8,  9 =>  9, 10 => 10,
        	11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20,
        );
        $this->refereeTeamMaxTournPicklist = array(
        	 1 =>  1,  2 =>  2,  3 =>  3,  4 =>  4,  5 =>  5,  6 =>  6,
        );
        
        // Setup physical team pick list
        $models = $this->context->models;
        
        $this->tplData = $data;
                     
        $this->unitPickList       = $models->UnitModel      ->getPickList();
        $this->yearPickList       = $models->YearModel      ->getPickList();
        $this->divisionPickList   = $models->DivisionModel  ->getDivisionPickList();
        $this->seasonTypePickList = $models->SeasonTypeModel->getPickList();
        
        $this->phyTeamPickList = array();

        // Pull up all the teams
        if ($data->unitId == 1) $age1 =  8; // For Monrovia
        else                    $age1 = 10;
        
        $divIds = $models->DivisionModel->getDivisionIdsForAgeRange($age1,19,TRUE,TRUE,TRUE);
        
        $search = new SearchData();
        $search->unitId       = $data->unitId;
        $search->yearId       = $data->yearId;
        $search->seasonTypeId = $data->seasonTypeId;
        $search->divisionId   = $divIds;
        $search->wantx         = TRUE;
        $search->wantCoachHead = TRUE;
        $teams = $models->PhyTeamModel->search($search);
        
        foreach($teams as $team) {
        //Zend_Debug::dump($team); die();
        	$key   = $team->key;
        	$coach = $team->coachHead->fullName;
        	
        	$this->phyTeamPickList[$team->id] = $key . ' ' . $coach;
        }
        $this->refereePickList = array();
        foreach($referees as $referee)
        {
        	$this->refereePickList[$referee->id] = $referee->fname . ' ' . $referee->lname;
        	
        	if (!$this->refereeId) {
        		$this->refereeId = $referee->id;
        	}
        }
        
        //Zend_Debug::dump($this->phyTeamPickList); die();
        
        /* And render it  */      
        return $this->renderx();
    }
    // Returns the events a referee was involved in
    function getEventsForReferee($personId,$unitId)
    {
    	$db = $this->context->db;
    	
        $select = new Proj_Db_Select($db);
        
        $select->from ('event_person',array(
        	'event_person.person_id            AS person_id',
        	'event_person.event_id             AS event_id',
            'event_person.event_person_type_id AS event_person_type_id',
        ));
        $select->joinleft(
            'event',
            'event.event_id = event_person.event_id',
            array(
                'event.event_date       AS event_date',
                'event.point2           AS event_point2',
            	'event.schedule_type_id AS event_schedule_type_id',
        ));
        $select->joinleft(
            'event_team',
            'event_team.event_id = event.event_id',
            array(
                'event_team.division_id        AS division_id',
            	'event_team.unit_id            AS event_team_unit_id',
            	'event_team.event_team_type_id AS event_team_type_id'
        ));
        // Join youth referee records

        $select->joinLeft(
        	'vol',
        	'vol.person_id = event_person.person_id AND ' . 
            'vol.reg_year_id    = 9 AND ' .
        	'vol.season_type_id = 1 AND ' . 
            "vol.unit_id        = $unitId AND " .
            'vol.vol_type_id    = 20',
        	array(
        		'vol.vol_type_id AS vol_type_id'
        ));
		$select->where("event_person.person_id = ?",$personId);
		$select->where("event_person.event_person_type_id IN (?)",array(
			EventPersonTypeModel::TYPE_CR,
			EventPersonTypeModel::TYPE_AR1,
			EventPersonTypeModel::TYPE_AR2,
			EventPersonTypeModel::TYPE_4TH,
		));
			
		$select->where("event.event_date >= ?",'20110801');
		$select->where("event.event_date <= ?",'20111031');
		
        $rows = $db->fetchAll($select);
	//  Zend_Debug::dump($rows); die();
        
        // Once around to reduce one person per event with highest division
        $events = array();
		foreach($rows as $row) {
			$eventId = $row['event_id'];
			if (isset($events[$eventId])) $event = $events[$eventId];
			else {
				$event = array(
				    'type'     => 0,
				    'point2'   => 0,
				    'division' => 0,
				    'use'      => 0,
					'sch_type' => 0,
				);
			}
			if ($event['type']     == 0) $event['type']     = $row['event_person_type_id']; // CR AR1 AR2
			if ($event['point2']   == 0) $event['point2']   = $row['event_point2'];         // Pending or processed
			if ($event['sch_type'] == 0) $event['sch_type'] = $row['event_schedule_type_id']; // reg or tourn
			
			if ($event['division'] < $row['division_id']) $event['division'] = $row['division_id'];

                        // Need age from somewhere
			if ($row['vol_type_id']) $event['youth'] = TRUE;
			else                     $event['youth'] = FALSE;
			
			// Restrict to Madison games
			if ($unitId == 4) {
			    if (($row['event_team_type_id'] == 1) && ($row['event_team_unit_id'] == 4)) $event['use'] = 1;
			}
			else $event['use'] = 1;
			
			$events[$eventId] = $event;
		}
    	return $events;
	}
}
?>
