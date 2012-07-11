<?php
namespace Zayso\AreaBundle\Component\FormType\Search;

class PersonSearchEntity implements \ArrayAccess
{
    public $firstName;
    public $lastName;
    public $nickName;
    
    // Array access
    public function offsetGet   ($name) { return $this->$name; }
    public function offsetExists($name) { return isset($this->$name); }
    public function offsetSet   ($name, $value) { $this->$name = $value; }
    public function offsetUnset ($name) { unset($this->$name); }
}
?>
