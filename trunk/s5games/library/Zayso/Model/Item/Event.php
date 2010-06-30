<?php
class Zayso_Model_Item_Event extends Zayso_Model_Item_Base
{
	protected $teams   = array();
	protected $persons = array();
	
	function getDate()      { return $this->getData('date'); }
	function getTime()      { return $this->getData('time'); }
	
	function getFieldId()   { return $this->getData('field_id'); }
	function getFieldDesc() { return $this->getData('field_desc'); }
	
	function addTeam($team)
	{
		$typeId = $team->getEventTeamTypeId();
		$this->teams[$typeId] = $team;
	}
	function getTeam($typeId)
	{
		if (!isset($this->teams[$typeId])) 
		{
			$this->teams[$typeId] = new Zayso_Model_Item_Person();
		}
		return $this->teams[$typeId];
	}
	function getHomeTeam() { return $this->getTeam(1); }
	function getAwayTeam() { return $this->getTeam(2); }
	
	function addPerson($person)
	{
		$typeId = $person->getEventPersonTypeId();
		$this->persons[$typeId] = $person;
	}
	function getPersons() { return $this->persons; }
}
?>