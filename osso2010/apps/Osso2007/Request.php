<?php
class Osso2007_Request
{
  protected $context = null;

  public $webPath; // '/osso2007/'
  public $webBase; // http://local.osso2010.org/osso2007/

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
}
?>
