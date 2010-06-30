<?php
class Team_TeamItem extends Base_BaseItem
{
  protected $data = array
  (
    'id'         => 0,
	
		'divId'      => 0,
	  'divKey'     => NULL,
		'divSeqNum'  => 0,
	
	  'schTeamId'     => 0,
	  'schTeamTypeId' => 0,
	  'schTeamDesc'   => 0,
  
	  'leagueId'   => 0,
	  'leagueKey'  => NULL,

		'seasonType'   => NULL,
	  'seasonTypeId' => 0,

		'year'       => 0,
	  'yearId'     => 0,

		'points'     => NULL,
		'coach'      => NULL,
		'referees'   => array(),
	);
	public function __get($name)
	{
		switch($name) 
		{
		case 'coach':
		case 'coachHead':
			if (!$this->data['coach']) $this->data['coach'] = new Coach_CoachItem();
			return $this->data['coach'];
			break;
			
		case 'points':
			if (!$this->data['points']) $this->data['points'] = new Points_PointsItem();
			return $this->data['points'];
			break;

		case 'key':
			$divSeqNum = $this->divSeqNum;
			if ($divSeqNum < 10) $divSeqNum = '0' . $divSeqNum;
			return $this->leagueKey . '-' . $this->divKey . '-' . $divSeqNum;
		
		case 'desc':
			return $this->key . ' ' . $this->coach->name1;
			break;
			
		}	
		return parent::__get($name);
	}
}
?>