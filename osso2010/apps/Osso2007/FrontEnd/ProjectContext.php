<?php
/* ========================================================
 * This is designed to create the context for a given project
 * 
 * Basically it starts things off before the controller takes over
 */

class SessionData
{
  function __get($name) { return NULL; }
};

class ProjectLoader
{
  public static function getPath($name)
  {
    return Osso2007_Loader::getPath($name);
  }
}

class ProjectContext extends Osso2007_FrontEnd_Context
{	
  static $instances = array();
    
  /* -------------------------------------------------------
   * The idea here is that object which do not have the context injected into them
   * can get the context with the possibility that a different might have been created
   * just for them.
   */
  static function getInstance($name = 'default')
  {
    if (isset(self::$instances[$name]))     return self::$instances[$name];
    if (isset(self::$instances['default'])) return self::$instances['default'];
        
    throw new Exception("Tried to get project context before it was created");
  }
  static function setInstance($instance,$name = 'default')
  {
    self::$instances[$name] = $instance;
  }
  protected function init()
  {
    parent::init();

    // $this->classNames['session']  = 'Osso2007_Session';
    // $this->classNames['response'] = 'Cerad_Response';
    
    $params = $this->config;
    
    /* Store it */
    ProjectContext::setInstance($this);
        
    /* A project can have multiple applications */
    $this->appProjDir = $projDir = $params['proj_dir'];
		
    /* Application specific directory */
    $this->appAppDir = $appAppDir = $params['app_dir'];
		
    /* Web Directory for url generation */
    $this->appWebDir = dirname($_SERVER['SCRIPT_NAME']);
		
    /* Server name is often handy */
    $this->appServerName = $_SERVER['SERVER_NAME'];
		
    /* Absolute url */
    $this->appUrlAbs = "http://{$this->appServerName}{$this->appWebDir}";

    /* Setup database and locators */
    $this->db = $this->dbOsso2007;
        
    $this->tables = new Proj_Locator_Table($this);       
    $this->models = new Proj_Locator_Model($this);
  }
}
?>