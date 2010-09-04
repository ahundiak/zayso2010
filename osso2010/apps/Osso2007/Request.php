<?php
class Osso2007_Request
{
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
