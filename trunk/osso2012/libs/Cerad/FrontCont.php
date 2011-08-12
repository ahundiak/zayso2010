<?php

namespace Cerad;

use
  Cerad\Debug,
  Cerad\ClassLoader;

class FrontCont
{
  protected $config;
  protected $services;

  public function __construct($config)
  {
    $this->config = $config;
    $this->init();
  }
  protected function init()
  {
    $ws = $this->config['ws'];

    require $ws . 'CeradNS/library/Cerad/ClassLoader.php';

    ClassLoader::createNS('Cerad',    $ws . 'osso2012/libs');
    ClassLoader::createNS('Doctrine', $ws . 'doctrine-orm');

    return;
  }
  /* ----------------------------------------------------
   * Originally this allowed passing some args for testing
   * Don't really need it
   * Only support /xxx/ccc notation for now
   */
  protected function getArgs()
  {
    $uri  = $_SERVER['REQUEST_URI'];
    $name = $_SERVER['SCRIPT_NAME'];

    if (substr($uri,0,strlen($name)) == $name) $args = substr($uri,strlen($name)+1);
    else                                       $args = substr($uri,strlen(dirname($name)) + 1);

    // Need to pull out any thing after ?
    // $tmp  = explode('?',$args);
    // $args = $tmp[0];

    // Convert to array
    $args = explode('/',$args);

    return $args;
  }
  protected $actions = array();
  
  public function process()
  {
    $args = $this->getArgs();
    if ($args[0]) $actionName = $args[0];
    else          $actionName = 'welcome';
    array_shift($args);

    $actionClassName = $this->actions[$actionName];

    $action = new $actionClassName($this->services);

    $action->process($args);
    
    $this->services->response->sendResponse();
  }
}
?>
