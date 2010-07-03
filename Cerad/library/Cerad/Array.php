<?php
/* -------------------------------------------
 * Look at the tests to see how some of this works
 * __set and __get are replaced with offsetSet and offsetGet
 * Use offsetExists to check for existence
 */
class Cerad_Array extends ArrayObject
{
  protected $map;

  function __construct($data = array(), $props = NULL, $map = array())
  {
    if (!$props) $props = ArrayObject::ARRAY_AS_PROPS;

    parent::__construct($data,$props);

    $this->map = $map;
    
    $this->init();
  }
  protected function init() {}
  
  function get($name,$default = NULL)
  {
    if (isset($this->map[$name])) $name = $this->map[$name];

    if ($this->offsetExists($name)) return parent::offsetGet($name);
    return $default;
  }
  function offsetGet($name)
  {
    if (isset($this->map[$name])) $name = $this->map[$name];
    
    if ($this->offsetExists($name)) return parent::offsetGet($name);

    return NULL;
  }
  function offsetSet($name,$value)
  {
    if (isset($this->map[$name])) $name = $this->map[$name];

    return parent::offsetSet($name,$value);
  }
  // Not going to implement the mapping for offsetExists,

  // Easier to remember
  function getData() { return $this->getArrayCopy(); }
  
  function setMap()  { $this->map = $map; }
}
?>
