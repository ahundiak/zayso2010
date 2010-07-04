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
  // Decode args lie /name1/value1/name2/value2 and add to stored data
  public function merge($data = null)
  {
    if (!$data) $data = $_SERVER;

    if (!isset($data['REQUEST_URI'])) return;
    if (!isset($data['SCRIPT_NAME'])) return;

    $uri  = $_SERVER['REQUEST_URI']; //  /test7/ /test7/a/account
    $name = $_SERVER['SCRIPT_NAME']; //  /test7/index.php

    // index.php may or may not be present depending on .htaccess
    if (substr($uri,0,strlen($name)) == $name) $args = substr($uri,strlen($name)+1);
    else                                       $args = substr($uri,strlen(dirname($name)) + 1);

    //echo "URI: $uri <br />NAME: $name<br />ARGS: $args</br />";die();

    $args = explode('/',$args);
    $count = count($args);

    for($i = 0; $i < $count; $i += 2)
    {
      $key = $args[$i];
      if (($i + 1) < $count) $this->data[$key] = $args[$i+1];
      else                   $this->data[$key] = NULL;
    }
  }
}
?>
