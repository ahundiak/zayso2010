<?php
class Event_EventRepo extends Base_BaseRepo
{
    protected $context = NULL;
    
    const TYPE_GAME      = 1;
    const TYPE_PRACTICE  = 2;
    const TYPE_SCRIMMAGE = 3;
    const TYPE_JAMBOREE  = 4;
    const TYPE_UPS       = 5;
    const TYPE_TRYOUTS   = 6;
    const TYPE_OTHER     = 9;
    
	protected $typePickList = array(
        self::TYPE_GAME      => 'Game',
        self::TYPE_PRACTICE  => 'Practice',
        self::TYPE_SCRIMMAGE => 'Scrimmage',
        self::TYPE_JAMBOREE  => 'Jamboree',
        self::TYPE_UPS       => 'Unifieds',
        self::TYPE_TRYOUTS   => 'Tryouts',
        self::TYPE_OTHER     => 'Other',
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
    public function save($event)
    {
    	$data = array(
    		'event_id'         => $event->id,
    	    'event_num'        => $event->num,
    		'reg_year_id'      => $event->yearId,
    		'season_type_id'   => $event->seasonTypeId,
    		'schedule_type_id' => $event->schTypeId,
    		'event_type_id'    => $event->typeId,
    		'unit_id'          => $event->leagueId,
    		'status'           => $event->status,
    		'field_id'         => $event->fieldId,
    		'event_date'       => $event->date,
    		'event_time'       => $event->time,
    		'event_duration'   => $event->duration,
    		'point1'           => $event->point1,
    		'point2'           => $event->point2,
    	);
    	return $this->saveRow('event','event_id',$data);    	
   }
}
?>