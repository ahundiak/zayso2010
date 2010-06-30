<?php
class Cerad_Context
{
  protected $data = array();

  protected $dbs = array();
  protected $dbAlias = 'dbMain';

  protected $request  = NULL;
  protected $response = NULL;
	
  protected $params;
	
  public function __construct($params)
  {
    $this->params = $params;
    $this->init();
  }
  protected function init() {}

  protected function get($name,$default = NULL)
  {
    if(isset($this->data[$name])) return $this->data[$name];
    return $default;
  }
  protected function set($name,$item)
  {
    $this->data[$name] = $item;
  }
  protected function has($name)
  {
    if(isset($this->data[$name])) return true;
    return false;
  }
  
  function __get($name)
  {
    if (substr($name,0,2) == 'db') return $this->getDbForName($name);
    switch($name)
    {
      case 'request':  return $this->getRequest();
      case 'response': return $this->getResponse();
    }
  }
  public function getDbForName($name)
  {
    if ($name == 'db') $name = $this->dbAlias;
    if (!isset($this->dbs[$name]))
    {
      $this->dbs[$name] = new Cerad_DatabaseAdapter($this->params['dbs'][$name]);
    }
    return $this->dbs[$name];
  }
  function getRequest()
  {
    if (!$this->request)
    {
      $this->request = new Cerad_Request($this);
    }
    return $this->request;
  }
  function setRequest($request) { $this->request = $request; }
	
  function getResponse()
  {
    if (!$this->response)
    {
      $this->response = new Cerad_Response($this);
    }
    return $this->response;
  }
}
?>