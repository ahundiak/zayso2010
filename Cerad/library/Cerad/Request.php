<?php
class Cerad_Request
{
  protected $context = null;

  public $webPath; // EX: /osso2007/
  public $webBase; // EX: http://local.osso2010.org/osso2007/

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->webPath = dirname($_SERVER['SCRIPT_NAME']) . '/';
    $this->webBase = 'http://' . $_SERVER['SERVER_NAME'] . $this->webPath;
  }
  public function getParam($name,$default = null)
  {
    if (isset($_GET [$name])) return $_GET [$name];
    if (isset($_POST[$name])) return $_POST[$name];
    return $default;
  }
  public function getPost($name,$default = null)
  {
    if (isset($_POST[$name])) return $_POST[$name];
    return $default;
  }
  public function getPostInt($name,$default=0)
  {
      if (!isset($_POST[$name])) return $default;

      $value = $_POST[$name];
      if (!is_array($value)) return (int)$value;

      $valuex = array();
      foreach($value as $key => $val)
      {
        $valuex[$key] = (int)$val;
      }
      return $valuex;
  }
  public function getPostStr($name,$default='')
  {
      if (!isset($_POST[$name])) return $default;
      $value = strip_tags(trim($_POST[$name]));
      return (string)$value;
  }
}
?>
