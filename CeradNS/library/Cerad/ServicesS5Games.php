<?php
namespace Cerad;

use
  Cerad\Debug,
  Doctrine\ORM\EntityManager,
  Doctrine\Common\EventManager,
  Doctrine\ORM\Configuration;

/* ===================================================
 * Perserves existing s5games application
 */
class ServicesS5Games
{
  protected $data = array();

  protected $classNames = array
  (

    'request'        => 'Cerad\Request',
    'response'       => 'Cerad\Response',
      
    // 'session'        => 'Cerad_Session_Basic',
  );
  
  public function __construct($config = array())
  {
    $this->data['config'] = $config;
    $this->init();
  }
  protected function init() {}

  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];

    // Special case fo db adapters
    if (substr($name,0,2) == 'db')   return $this->newDb($name);
    if (substr($name,0,2) == 'em')   return $this->newEm($name);
    if (substr($name,0,4) == 'repo') return $this->newRepo($name);

    // Create a class
    if (isset($this->classNames[$name]))
    {
      $className = $this->classNames[$name];
      $this->data[$name] = $item = new $className($this);
      return $item;
    }
    // Special handling
    $methodName = 'new' . ucfirst($name);

    return $this->$methodName($name);
  }
  /* ===============================================================
   * Fun code for Doctrine entity manager
   */
  protected function newEm($name = null)
  {
    $configx = $this->config;

    $ws        = $configx['ws'];
    $appMode   = $configx['app_mode'];
    $sqlLog    = $configx['sql_log'];
    $dbParams  = $configx['db_params'];
    $modelPath = $configx['model_path'];

    if ($appMode == "dev") $cache = new \Doctrine\Common\Cache\ArrayCache;
    else                   $cache = new \Doctrine\Common\Cache\ApcCache;

    $config = new Configuration;
    $config->setMetadataCacheImpl($cache);
    $config->setQueryCacheImpl   ($cache);

    // Need path for console operations
    $driverImpl = $config->newDefaultAnnotationDriver($ws . $modelPath . 'Entities');
    $config->setMetadataDriverImpl($driverImpl);

    $config->setProxyDir($ws . $modelPath . 'Proxies');
    $config->setProxyNamespace('Proxies');

    $logger = new \Cerad\EchoSQLLogger($sqlLog);
    $config->setSQLLogger($logger);
    
    if ($appMode == "dev") $config->setAutoGenerateProxyClasses(true);
    else                   $config->setAutoGenerateProxyClasses(false);

    $eventManager = new EventManager();

    $em = EntityManager::create($dbParams, $config, $eventManager);

    if ($name) $this->data[$name] = $em;

    return $em;
  }
  /* ===============================================================
   * Repos require am en
   */
  protected $repoMap = array
  (
    'repoAccount' => array('em' => 'em', 'item' => 'S5Games\Account\AccountItem'),
  );
  protected function newRepo($name)
  {
    if (!isset($this->repoMap[$name])) return null;

    $emName   = $this->repoMap[$name]['em'];
    $itemName = $this->repoMap[$name]['item'];

    $em = $this->$emName;
    $repo = $em->getRepository($itemName);

    $this->data[$name] = $repo;
    return $repo;
    
  }
  /* ===============================================================
   * Keep this for now, for accessing older style database adapter
   */
  protected function newDb($name)
  {
    $dbParams = $this->config['dbParams'];
    
    if (isset($dbParams['default'])) $default = $dbParams['default'];
    else                             $default = array();

    $params = array_merge($default,$dbParams[$name]);

    $className = $this->classNames['db'];
    return $this->data[$name] = new $className($params);
  }
  /* ==========================================================
   * Sort of goes here
   */
  public function getTimeStamp()
  {
    return date('YmdHis');
  }
}
?>
