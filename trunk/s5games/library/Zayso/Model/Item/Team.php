<?php
class Zayso_Model_Item_Team extends Zayso_Model_Item_Base
{
	protected $headCoach = NULL;
	
	function addHeadCoach($person)
	{
		$this->headCoach = $person;
	}
	function getHeadCoach()
	{
		if (!$this->headCoach) {
			$this->headCoach = new Zayso_Model_Item_Person();
		}
		return $this->headCoach;
	}
	function getEventTeamTypeId()
	{
		return $this->getData('event_team_type_id',0);
	}
	function getDesc()
	{
		$region = $this->getData('region_key',  'RXXXX');
		$div    = $this->getData('division_key','UXXX');
		$seq    = $this->getData('division_seq_num','0');
		if (strlen($seq) < 2) $seq = '0' . $seq;
		if ($seq == '00') $seq = 'XX';
		
		$coach = $this->getHeadCoach();
		$coachName = $coach->getFullName();
				
		return $region . '-' . $div . '-' . $seq . ' ' . $coachName;
		
	}	
}
?>