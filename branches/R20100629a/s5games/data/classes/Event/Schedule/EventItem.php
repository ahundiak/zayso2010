<?php
/* For holding event scheduling related information
 * 
 */
class Event_Schedule_EventItem extends Base_BaseItem
{
  protected $data = array
  (
    'id'       => 0,
  
    'typeId'   => 0,

    'fieldId'   => 0,
    'fieldDesc' => NULL,
  
    'leagueId' => 0,
  
    'date'     => NULL,
    'time'     => NULL,
    'duration' => 75,
    'status'   => 1,
  
    'yearId'       => 0,
    'seasonTypeId' => 0,
    'schTypeId'    => 0,
  
    'point1'   => 1,
    'point2'   => 1,
  
    'num'      => 0,
  
    'divId'    => 0,  // Calculated
    'referees' => array(),
    'teams'    => array(),
  );
  public function __get($name)
  {
    switch($name) 
    {
      case 'teamHome':
      case 'homeTeam':
        return $this->getTeam(Event_EventTeamTypeRepo::TYPE_HOME);
        
      case 'teamAway':
      case 'awayTeam':
        return $this->getTeam(Event_EventTeamTypeRepo::TYPE_AWAY);
        
    } 
    return parent::__get($name);
  }
  public function addReferee($referee)
  {
    if (isset($this->data['referees'][$referee->id])) return NULL;
    
    $this->data['referees'][$referee->id] = $referee;
    
    return $referee;
  }
  public function getRefereeByType($typeId)
  {
  	foreach($this->data['referees'] as $item)
  	{
  		if ($item->typeId == $typeId) return $item;
  	}
  	return NULL;
  }
  public function addTeam($team)
  {
    $this->data['teams'][$team->typeId] = $team;
  }
  public function getTeams() { return $this->data['teams']; }
  
  public function getTeam($typeId)
  {
    if (!isset($this->data['teams'][$typeId])) 
    {
      $team = new Event_Schedule_EventTeamItem();
      $team->eventId = $this->eventId;
      $team->typeId  = $typeId;
      $this->data['teams'][$typeId] = $team;  
    }
    return $this->data['teams'][$typeId];
  }
}