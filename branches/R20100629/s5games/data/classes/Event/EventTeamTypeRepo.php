<?php
class Event_EventTeamTypeRepo extends Base_BaseRepo
{    
  const TYPE_HOME  = 1;
  const TYPE_AWAY  = 2;
  const TYPE_OTHER = 3;

  protected $typePickList = array
  (
    self::TYPE_HOME  => 'Home',
    self::TYPE_AWAY  => 'Away',
    self::TYPE_OTHER => 'Other',
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
}    