<?php
class Person_PersonTypeRepo extends Base_Baserepo
{   
    const TYPE_DIV_COORD   = 31;
    const TYPE_HEAD_COACH  = 16;
    const TYPE_ASST_COACH  = 17;
    const TYPE_TEAM_PARENT = 18;
    const TYPE_ADULT_REF   = 19;
    const TYPE_YOUTH_REF   = 20;
    const TYPE_GAME_SCHEDULER    = 34;
    const TYPE_REFEREE_SCHEDULER = 35;
    const TYPE_ZADM        = 27;
       
    protected $pickList = array(
        self::TYPE_HEAD_COACH  => 'Head Coach',
        self::TYPE_ASST_COACH  => 'Asst Coach',
        self::TYPE_TEAM_PARENT => 'Team Parent',
        self::TYPE_DIV_COORD   => 'Div Coordinator',
        self::TYPE_ADULT_REF   => 'Adult Referee',
        self::TYPE_YOUTH_REF   => 'Youth Referee',
        self::TYPE_GAME_SCHEDULER    => 'Game Scheduler',
        self::TYPE_REFEREE_SCHEDULER => 'Referee Scheduler',
        self::TYPE_ZADM              => 'Zayso Administrator',
    );
    public function getPickList() { return $this->pickList; }
    
    function getDesc($typeId)
    {
        if (isset($this->pickList[$typeId])) return $this->pickList[$typeId];
        return NULL;
    }
    public function __get($name)
    {
        $constName = "self::$name";
        
        if (defined($constName)) return constant($constName);
        
        return parent::__get($name);
    }
}    