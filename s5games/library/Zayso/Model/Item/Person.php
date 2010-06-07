<?php
class Zayso_Model_Item_PersonMap
{
	protected $map = array(
		'firstName' => 'fname',
		'lastName'  => 'lname',
		'nickName'  => 'nname',
		'aysoid'    => 'aysoid'
	);
}
class Zayso_Model_Item_Person extends Zayso_Model_Item_Base
{
	function getFirstName() { return $this->getData('fname'); }
	function getLastName()  { return $this->getData('lname'); }
	function getNickName()  { return $this->getData('nname'); }
	function getAysoid()    { return $this->getData('aysoid'); }
	
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