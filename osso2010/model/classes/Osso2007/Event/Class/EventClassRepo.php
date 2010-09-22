<?php

class Osso2007_Event_Class_EventClassRepo
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected $classPickList = array
  (
    1 => 'RG - Regular Game',
    2 => 'PP - Pool Play',
    3 => 'QF - Quarter Final',
    4 => 'SF - Semi Final',
    5 => 'F  - Final',
    6 => 'CM - Consolation Match',
  );
  protected $classKeys = array
  (
    1 => 'RG',
    2 => 'PP',
    3 => 'QF',
    4 => 'SF',
    5 => 'F',
    6 => 'CM',
  );
  protected $classIds = array
  (
    'RG' => 1,
    'PP' => 2,
    'QF' => 3,
    'SF' => 4,
    'F'  => 5,
    'CM' => 6,
  );
  function getPickList() { return $this->classPickList; }
    
  function getDescForId($id)
  {
    if (isset($this->classPickList[$id])) return $this->classPickList[$id];
    return NULL;
  }
  function getKeyForId($id)
  {
    if (isset($this->classKeys[$id])) return $this->classKeys[$id];
    return NULL;
  }
  function getIdForKey($key)
  {
    if (isset($this->classIds[$key])) return $this->classIds[$key];
    return 0;
  }
}
?>
