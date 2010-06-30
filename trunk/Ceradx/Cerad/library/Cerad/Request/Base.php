<?php
class Cerad_Request_Base
{
  protected $context;
  protected $data;

  function __construct($context = NULL,$data = NULL)
  {
    $this->context = $context;
    $this->data    = $data;
    $this->init();
  }
  protected function init() {}

  protected function clean($input)
  {
    if (!is_array($input)) return trim(strip_tags($input));

    $data = array();
    foreach($input as $key => $value)
    {
      $data[$key] = $this->clean($value);
    }
    return $data;
  }
  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->clean($this->data[$name]);
    return NULL;
  }
  public function get($name = NULL, $default = NULL)
  {
    if (!$name)
    {
      if ($this->data) return $this->data;
      return array();
    }
    if (isset($this->data[$name])) return $this->clean($this->data[$name]);
    
    return $default;
  }
}
?>
