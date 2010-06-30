<?php
class Cerad_Routes
{
  protected $context;
  protected $routes;

  function __construct($context = NULL)
  {
    $this->context = $context;
    $this->init();
  }
  function init()
  {
    $this->routes = array(
      'index' => array('className' => 'FE_IndexController')
    );
  }
  function getRouteClassName($name)
  {
    if (isset($this->routes[$name])) return $this->routes[$name]['className'];
  }
}
?>
