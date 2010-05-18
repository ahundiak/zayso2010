<?php
class Cerad_Context
{
  protected $db       = NULL;
  protected $dbEayso  = NULL;
  protected $request  = NULL;
  protected $response = NULL;
	
  protected $params;
	
  public function __construct($params)
  {
    $this->params = $params;
  }
  function __get($name)
  {
    switch($name)
    {
      case 'db':       return $this->getDbMain();
      case 'dbMain':   return $this->getDbMain();
      case 'dbEayso':  return $this->getDbEayso();

      case 'request':  return $this->getRequest();
      case 'response': return $this->getResponse();
    }
  }
  public function getDbMain()
  {
    if (!$this->db)
    {
      $this->db = new Cerad_DatabaseAdapter($this->params['dbs']['dbMain']);
    }
    return $this->db;
  }
  public function getDbEayso()
  {
    if (!$this->dbEayso)
    {
      $this->dbEayso = new Cerad_DatabaseAdapter($this->params['dbs']['dbEayso']);
    }
    return $this->dbEayso;
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