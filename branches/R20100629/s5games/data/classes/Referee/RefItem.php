<?php
class Referee_RefItem extends Person_PersonItem
{
	public function __construct()
	{
		$this->data['ageId']      = 0;
	  $this->data['positionId'] = 0;
		
		$this->data['points'] = NULL;
		$this->data['teams']  = array();
	}

	public function __get($name)
	{		
		switch($name) 
		{
		case 'points':
			if (!$this->data['points']) $this->data['points'] = new Points_PointsItem();
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
	
}
?>