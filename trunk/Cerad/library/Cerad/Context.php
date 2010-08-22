<?php
class Cerad_Context
{
  protected $data;
  protected $classNames = array
  (
    'db'             => 'Cerad_DatabaseAdapter',

    'request'        => 'Cerad_Request_Request',
    'requestGet'     => 'Cerad_Request_Get',
    'requestPost'    => 'Cerad_Request_Post',
    'requestFiles'   => 'Cerad_Request_Files',
    'requestServer'  => 'Cerad_Request_Server',
    'requestRequest' => 'Cerad_Request_Request',
    'requestCookies' => 'Cerad_Request_Cookies',

    'session'        => 'Cerad_Session_Basic',
    'sessionDB'      => 'Cerad_Session_Database',
    'sessionData'    => 'Cerad_Session_Data',
    'sessionFile'    => 'Cerad_Session_Basic',

    'routes'         => 'Cerad_Routes',
    'html'           => 'Cerad_HTML',
  );
  
  public function __construct($config = null)
  {
    $this->data['config'] = $config;
    $this->init();
  }
  protected function init() {}

  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];

    // Special case fo db adapters
    if (substr($name,0,2) == 'db') return $this->newDb($name);

    // Create a class
    if (isset($this->classNames[$name]))
    {
      $className = $this->classNames[$name];
      $this->data[$name] = $item = new $className($this);
      return $item;
    }
    // Special handling
    $methodName = 'new' . ucfirst($name);

    return $this->$methodName();
  }
  // Short term hack for s5games
  protected function get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];
    return NULL;
  }
  protected function set($name,$data)
  {
    $this->data[$name] = $data;
    return $data;
  }
  public function __set($name,$value)
  {
    $this->data[$name] = $value;
  }
  protected function newConfig()
  {
    return $this->data['config'] = array();

  }
  protected function newDb($name)
  {
    $dbParams = $this->config['dbParams'];
    
    if (isset($dbParams['default'])) $default = $dbParams['default'];
    else                             $default = array();

    $params = array_merge($default,$dbParams[$name]);

    $className = $this->classNames['db'];
    return $this->data[$name] = new $className($params);
  }
  public function getTimeStamp()
  {
    return date('YmdHis');
  }
  public function newSessionData()
  {
    $className = $this->classNames['sessionData'];
    return new $className();
  }
}
?>
