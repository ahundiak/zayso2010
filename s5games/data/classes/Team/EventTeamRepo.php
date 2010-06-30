<?php
class Team_EventTeamRepo extends Base_BaseRepo
{
    protected $context = NULL;
    
    const TYPE_HOME  = 1;
    const TYPE_AWAY  = 2;
    const TYPE_AWAY3 = 3;
    const TYPE_AWAY4 = 4;
    
    protected $pickList = array(
        self::TYPE_HOME  => 'Home',
        self::TYPE_AWAY  => 'Away',
        self::TYPE_AWAY3 => 'Away #3',
        self::TYPE_AWAY4 => 'Away #4',
    );
            
   	public function getTypePickList() { return $this->typePickList; }
    
    function getTypeDesc($typeId)
    {
        if (isset($this->typePickList[$typeId])) return $this->typePickList[$typeId];
        return NULL;
    }
    public function __get($name)
    {
        $constName = "self::$name";
        
        if (defined($constName)) return constant($constName);
        
        return parent::__get($name);
    }    
    public function save($item)
    {
    	$data = array(
    		'event_team_id'      => $item->id,
    		'event_team_type_id' => $item->typeId,
    		'event_id'           => $item->eventId,
    		'team_id'            => $item->teamId,
    	    'reg_year_id'        => $item->yearId,
    		'unit_id'            => $item->leagueId,
    		'division_id'        => $item->divId,
    		'score'              => $item->score,
    	);
    	return $this->saveRow('event_team','event_team_id',$data);    	
   }
}
?>