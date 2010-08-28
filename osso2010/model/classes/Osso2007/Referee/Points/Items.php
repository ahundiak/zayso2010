<?php
class Item
{
	protected $data = array();
	
	public function __set($name,$value)
	{
		if (array_key_exists($name,$this->data)) {
			$this->data[$name] = $value;
			return;
		}
		echo "Bad attribute name {$name}\n";
	}
	public function __get($name)
	{
		if (array_key_exists($name,$this->data)) return $this->data[$name];
		
		return NULL;
	}
}
class Person extends Item
{
	protected $data = array(
		'id'    => 0,
		'fname' => NULL,
		'lname' => NULL,
	);
	public function __get($name)
	{
		switch($name)
		{
		case 'name1':
			return $this->data['fname'] .  ' ' . $this->data['lname'];
		case 'name2';
			return $this->data['lname'] . ', ' . $this->data['fname'];
		}
		return parent::__get($name);
	}
}
class Coach extends Person
{

}
class Points extends Item
{
	protected $data = array(
		'pending'   => 0,
		'processed' => 0,
		'team'      => 0,
		'left'      => 0
	);
}
class Referee extends Person
{
	public function __construct()
	{
		$this->data['ageId']      = 0;
	    $this->data['positionId'] = 0;
		$this->data['gameCnt']    = 0;
		
		$this->data['points'] = NULL;
		$this->data['teams']    = array();
		$this->data['teamsPri'] = array();
	}

	public function __get($name)
	{		
		switch($name) 
		{
		case 'points':
			if (!$this->data['points']) $this->data['points'] = new Points();
			break;
			
		case 'ageDesc':
			switch ($this->ageId)
			{
			case 19: return 'Adult';
			case 20: return 'Youth';
			default: return 'Unknown';
			}
			break;
		}
		return parent::__get($name);
	}
	public function addTeam($team,$pri)
	{
		$this->data['teams'][] = $team;
		$this->data['teamsPri'][$team->id] = $pri;
	}
	public function getTeamPri($team)
	{
		return $this->data['teamsPri'][$team->id];
	}
	public function incGameCnt()
	{
		$this->data['gameCnt']++;
	}
}
class Team extends Item
{
	protected $data = array(
		'id'         => 0,
	
	    'divId'      => 0,
	    'divKey'     => '',
		'divSeqNum'  => 0,
	
	    'leagueId'   => 0,
	    'leagueKey'  => '',
	
		'points'     => NULL,
		'coach'      => NULL,
		'referees'   => array(),
	);
	public function __get($name)
	{
		switch($name) 
		{
		case 'coach':
			if (!$this->data['coach']) $this->data['coach'] = new Coach();
			break;
			
		case 'points':
			if (!$this->data['points']) $this->data['points'] = new Points();
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
	public function addReferee($referee)
	{
		$this->data['referees'][] = $referee;
	}
}
class Event extends Item
{
	protected $data = array(
		'id'       => 0,
		'divId'    => 0,
		'point2'   => 0,
		'typeId'   => 0,
		'fieldId'  => 0,
		'referees' => array(),
	);
	public function addReferee($referee)
	{
		if (isset($this->data['referees'][$referee->id])) return NULL;
		
		$this->data['referees'][$referee->id] = $referee;
		
		return $referee;
	}
}
?>