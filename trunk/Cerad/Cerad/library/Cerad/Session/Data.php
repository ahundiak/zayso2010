<?php
class Cerad_Session_Data
{
  protected $data;

  function __construct($data = NULL)
  {
    $this->data = $data;
    $this->init();
  }
  protected function init() {}
  
  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];
    return null;
  }
  public function get($name = null,$default = null)
  {
    if (!$name) return $this->data;

    if (isset($this->data[$name])) return $this->data[$name];
    
    return $default;
  }
  public function __set($name,$value)
  {
    $this->data[$name] = $value;
  }
  public function set($name,$value)
  {
    $this->data[$name] = $value;
  }
}
?>
