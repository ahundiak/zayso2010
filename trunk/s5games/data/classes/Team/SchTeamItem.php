<?php
class Team_SchTeamItem extends Base_BaseItem
{
	protected $data = array(
		'id'         => 0,
		'typeId'     => 0,  // Schedule type id
		'phyTeamId'  => 0,
	
	    'divId'      => 0,
	    'divKey'     => NULL,
		'divSeqNum'  => 0,
	
	    'leagueId'   => 0,
	    'leagueKey'  => NULL,

		'seasonType'   => NULL,
	    'seasonTypeId' => 0,

		'year'       => 0,
	    'yearId'     => 0,
	
		'desc'       => NULL,
	
	);
	public function __get($name)
	{
		switch($name) 
		{
		}	
		return parent::__get($name);
	}
}
?>