<?php
class Event_Schedule_EventTeamItem extends Base_BaseItem
{
  protected $data = array
  (
    'id'         => 0,
    'typeId'     => 0,
    'score'      => 0,
   
    'divId'      => 0,
    'divKey'     => NULL,
    'divSeqNum'  => 0,
  
    'leagueId'   => 0,
    'leagueKey'  => NULL,
  
    // These really should be flattened
    'schTeamId'   => 0,
    'schTeamDesc' => 0,
    'schTeamScheduleTypeId' => 0,
    'schTeamSeasonTypeId'   => 0,
  
    // These really should be flattened like divSeqNum is
    'phyTeamId'     => 0,
    'phyTeamName'   => NULL,
    'phyTeamColors' => NULL,

    'coach'      => NULL,
  );

}