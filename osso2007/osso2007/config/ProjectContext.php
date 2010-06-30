<?php
/* ========================================================
 * This is designed to create the context for a given project
 * 
 * Basically it starts things off before the controller takes over
 */
require_once 'Zend/Loader.php';

/* --------------------------------------
 * Registered by application
 */
class ProjectLoader
{
    static function getPath($class)
    {
        // Scan backwards, find first upper case letter
        $category = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $class);
        if ($class == $category) return NULL;
    
        $left = substr($class,0,strlen($class) - strlen($category));
        switch($category) {
            
            /* Models */
            case 'Map':
            case 'Item':
            case 'Model':
            case 'Table':
                return "models/{$left}Model.php";
                
            /* Imports */
            case 'Import':
                return "imports/{$left}Import.php";
                
            /* Exports */
            case 'Export':
                return "exports/{$left}Export.php";
            
            /* Basic MVC Elements */
            case 'Tpl':
            case 'Cont':
            case 'View':
                $action = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $left);
                if ($action == $left) return NULL;
              
                switch($action) {
                    case 'Base':
                    case 'Web':
                    case 'Txt':
                    case 'Pdf':
                    case 'Excel':
                    case 'Print':
                        $left = substr($left,0,strlen($left) - strlen($action));
                        $action = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $left);
                        break;
                }
                $leftx = substr($left,0,strlen($left) - strlen($action));
                return "mvc/{$leftx}/{$class}.php";
                break;
        }
        return NULL;
    }
    static function loadClass($class)
    {
        $path = self::getPath($class);
        if (!$path) return;
        
        require $path;
    }
}
// Done by application to allow overriding
// spl_autoload_register(array('ProjectLoader','loadClass'));
// spl_autoload_register(array('Zend',         'loadClass'));

class SessionData
{
    function __get($name) { return NULL; }
};

class ProjectContext
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
	function __construct($params = array())
	{
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
	
		/* Load in config file */
		if (isset($params['config_file_name'])) $configFileName = $params['config_file_name'];
		else                                    $configFileName = 'config.ini';
		
        $appConfigFilePath = $appAppDir . '/config/' . $configFileName;
		$this->config = $config = new Zend_Config_Ini($appConfigFilePath, 'all');
        
        /* Setup database and locators */
        $this->db     = new Proj_Db_Adapter($config->db->toArray());
        
        $this->tables = new Proj_Locator_Table($this);
        
        $this->models = new Proj_Locator_Model($this);
		
	}
	/* Create on demand */
	public function __get($name)
	{
		switch($name) {
			case 'db':      return $this->createDb();
			case 'dbEayso': return $this->createDbEayso();
			case 'session': return $this->createSession();
		}
		throw new Exception("ApplicationContext.__get $name");
	}
	protected function createSession()
	{
		$params = $this->config->session->toArray();

		// Zend_Session_Core::setOptions($params);
        Zend_Session::setOptions($params);
		Zend_Session::rememberMe();
		$this->session = $session = new Zend_Session_Namespace('Project');
		
		return $session;
	}
	protected function createDb()
	{
		// set up an adapter
		$params = $this->config->db->toArray();
        $this->db = $db = new Proj_Db_Adapter($params);
        
		return $db;
	}
	protected function createDbEayso()
	{
		// set up an adapter
		$params = $this->config->dbEayso->toArray();
        $this->dbEayso = $dbEayso = new Proj_Db_Adapter($params);
        
		return $dbEayso;
	}
}
?>