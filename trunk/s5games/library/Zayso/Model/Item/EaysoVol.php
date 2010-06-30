<?php
class Zayso_Model_Item_EaysoVol extends Zayso_Model_Item_Person
{
	//function getId() { return $this->getData('eayso_vol_id'); }
	
	// Just copies of the person routine for now
	function getFirstName() { return $this->getData('fname'); }
	function getLastName()  { return $this->getData('lname'); }
	function getNickName()  { return $this->getData('nname'); }
	
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
	function getEmail() { return $this->getData('email'); }
	
}
?>