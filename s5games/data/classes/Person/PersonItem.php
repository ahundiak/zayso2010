<?php
class Person_PersonItem extends Base_BaseItem
{
	protected $data = array(
		'id'       => 0,
		'fname'    => NULL,
	  'lname'    => NULL,
		'nname'    => NULL,
		'mname'    => NULL,
		'aysoid'   => NULL,
	  'leagueId' => 0,
		'status'   => 1,
	  'typeId'   => 0,
	
		'phones' => array(),
		'emails' => array()
	);
	public function __get($name)
	{
		switch($name)
		{
		  case 'name':
		  case 'name1':
			  return $this->data['fname'] .  ' ' . $this->data['lname'];
			
		  case 'name2';
			  return $this->data['lname'] . ', ' . $this->data['fname'];
			
		  case 'phoneHome': return $this->getPhone(1);
		  case 'phoneWork': return $this->getPhone(2);
		  case 'phoneCell': return $this->getPhone(3);
		  case 'emailHome': return $this->getEmail(1);
		  case 'emailWork': return $this->getEmail(2);
		}
		return parent::__get($name);
	}
	// Phones
	public function addPhone($item)
	{
		if (isset($this->data['phones'][$item->typeId])) return FALSE;
		
		$this->data['phones'][$item->typeId] = $item;
		
		return TRUE;
	}
	public function hasPhone($typeId)
	{
		if (is_object($typeId)) $typeId = $typeId->typeId;
		
		if (isset($this->data['phones'][$typeId])) return TRUE;
		
		return FALSE;
	}
	public function getPhone($typeId)
	{
		if (isset($this->data['phones'][$typeId])) return $this->data['phones'][$typeId];
		
		// Maybe should return an empty object
		$item = new Phone_PhoneItem();
		$item->typeId = $typeId;
		$this->data['phones'][$typeId] = $item;
		
		return $item;
	}
	// Emails
	public function addEmail($item)
	{
		if (isset($this->data['emails'][$item->typeId])) return FALSE;
		
		$this->data['emails'][$item->typeId] = $item;
		
		return TRUE;
	}
	public function hasEmail($typeId)
	{
		if (is_object($typeId)) $typeId = $typeId->typeId;
		
		if (isset($this->data['emails'][$typeId])) return TRUE;
		
		return FALSE;
	}
	public function getEmail($typeId)
	{
		if (isset($this->data['emails'][$typeId])) return $this->data['emails'][$typeId];
		
		// Maybe should return an empty object
		$item = new Email_EmailItem();
		$item->typeId = $typeId;
		$this->data['phones'][$typeId] = $item;
		
		return $item;
	}
}
?>