<?php
//namespace Zayso\Osso2007Bundle\FrontEnd;

use Zayso\Osso2007Bundle\Component\Debug;

require 'SessionData.php';

class ProjectLoader
{
  public static function getPath($name)
  {
    return Osso2007_Loader::getPath($name);
  }
}

class Context
{
    protected $data;
    protected $classNames = array
    (
        'db'       => 'Cerad_DatabaseAdapter',

        'request'  => 'Osso2007_Request',
        'response' => 'Osso2007_Response',
        'session'  => 'Osso2007_Session',

        'url'      => 'Osso2007_Url',
        'html'     => 'Cerad_HTML',

        'repos'    => 'Osso2007_Repos',
        'tablesx'  => 'Osso2007_Tables',
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
/* ========================================================
 * Think this was broken out to support command line stuff
 */
class ProjectContext extends Context
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

    $params = $this->config;

    /* Store it */
    ProjectContext::setInstance($this);

    // Assorted variables
    $params = $this->config;

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

    $this->tables = new \Proj_Locator_Table($this);
    $this->models = new \Proj_Locator_Model($this);
  }
}

?>
