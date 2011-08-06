<?php

namespace Cerad;

use
  Cerad\Debug,
  Doctrine\ORM\EntityManager,
  Doctrine\Common\EventManager,
  Doctrine\ORM\Configuration;

class Services
{
  protected $data = array();
  
  protected $classNames = array();

  protected $repoMap = array ();
  protected $itemMap = array ();
  
  public function __construct($config = array())
  {
    $this->data['config'] = $config;
    $this->init();
  }
  protected function init() {}

  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];

    // Some predefined
    switch($name)
    {
      case 'ts':       return $this->getTimeStamp(); break;
      case 'dataItem': return $this->newDataItem();  break;
    }
    // Special case fo db adapters
    if (substr($name,-2) == 'Db')   return $this->newDb  ($name);
    if (substr($name,-2) == 'Em')   return $this->newEm  ($name);
    if (substr($name,-4) == 'Item') return $this->newItem($name);
    if (substr($name,-4) == 'Repo') return $this->newRepo($name);
    
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
  /* ===========================================================
   * Shared repos
   * Need to be careful about unit of work
   */
  protected function newRepo($name)
  {
    if (!isset($this->repoMap[$name])) return null;

    $emName   = $this->repoMap[$name]['em'];
    $itemName = $this->repoMap[$name]['item'];

    $em = $this->$emName;
    $repo = $em->getRepository($itemName);
    $repo->services = $this;
    
    $this->data[$name] = $repo;
    return $repo;
  }
  /* ==============================================================
   * Just a way to abstract item classes
   */
  protected function newItem($name)
  {
    if (!isset($this->itemMap[$name])) return null;

    $itemName = $this->itemMap[$name]['item'];

    return new $itemName();
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
   * Sort of goes here, really should return same
   */
  protected $ts = null;
  
  public function getTimeStamp()
  {
    if (!$this->ts) $this->ts = date('YmdHis');
    
    return $this->ts;

  }
  /* ==========================================================
   * DataItem is an awkward name as it conflicts with Doctrine Entity Items
   * But it does the job so live with it
   */
  public function newDataItem() { return new \Cerad\DataItem(); }
}
?>
