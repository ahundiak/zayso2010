<?php
class Zayso_Model_Item_EventPerson extends Zayso_Model_Item_Base
{
	function getFirstName() { return $this->getData('fname'); }
	function getLastName()  { return $this->getData('lname'); }
	function getNickName()  { return $this->getData('nname'); }
	function getAysoid()    { return $this->getData('aysoid'); }
	
	function getEventPersonTypeId() { return $this->getData('event_person_type_id'); }
	function getRegionNumber() { return $this->getData('region'); }
	function getPosId() { return $this->getData('event_person_type_id'); }
	function getStatus() { return $this->getData('status'); }
	
	function getFullName()
	{
		$fname = $this->getFirstName();
		$lname = $this->getLastName();
		
		$name = $fname;
		if ($name) {
			if ($lname) {
				$name .= ' ' . $lname;
			}
		}
		else $name = $lname;
		
		if (!$name) $name = 'Unknown';
		
		return $name;
	}
}
?>