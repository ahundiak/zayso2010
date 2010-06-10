<?php
class Team_EventTeamItem extends Base_BaseItem
{
  protected $data = array
  (
	  'id'         => 0,
    'eventId'    => 0,
		'typeId'     => 0,
		'teamId'     => 0,
		'eventId'    => 0,
	
	  'divId'      => 0,
	  'yearId'     => 0,
	  'leagueId'   => 0,
	
		'score'      => 0,
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