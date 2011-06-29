<?php

namespace NatGamesApp\FrontEnd;

use
  Cerad\Debug,
  Doctrine\ORM\EntityManager,
  Doctrine\Common\EventManager,
  Doctrine\ORM\Configuration;

class CeradServices
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
    $repo->services = $this;
    
    $this->data[$name] = $repo;
    return $repo;
  }
  protected $itemMap = array();
  
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
   * Sort of goes here
   */
  public function getTimeStamp()
  {
    return date('YmdHis');
  }
}

/* Kind of cute but might be better to just have the cookie stuff in there */
class SessionListener
{
  public function getCookieName() { return 'NatGames2012'; }

  public function genSessionId()
  {
    $name = $this->getCookieName();

    if (isset($_COOKIE[$name])) return $_COOKIE[$name];

    $sessionId =  md5(uniqid());

    setcookie($name,$sessionId,mktime(0, 0, 0, 12, 31, 2015));
    
    return $sessionId;
  }
}
class Services extends CeradServices
{
  protected $repoMap = array
  (
    'userRepo'    => array('em' => 'userEm',    'item' => 'NatGames\User\UserItem'),
    'gameRepo'    => array('em' => 'gameEm',    'item' => 'NatGames\Game\GameItem'),
    'personRepo'  => array('em' => 'workEm',    'item' => 'NatGames\Person\PersonItem'),
    'projectRepo' => array('em' => 'workEm',    'item' => 'NatGames\Project\ProjectItem'),
    'accountRepo' => array('em' => 'workEm',    'item' => 'NatGames\Account\AccountItem'),
    'sessionRepo' => array('em' => 'sessionEm', 'item' => 'NatGames\Session\SessionDataItem'),
  );
  protected $itemMap = array
  (
    'userItem'    => array('em' => 'userEm',    'item' => 'NatGames\User\UserItem'),
    'gameItem'    => array('em' => 'gameEm',    'item' => 'NatGames\Game\GameItem'),
    'personItem'  => array('em' => 'workEm',    'item' => 'NatGames\Person\PersonItem'),
    'projectItem' => array('em' => 'workEm',    'item' => 'NatGames\Project\ProjectItem'),
    'accountItem' => array('em' => 'workEm',    'item' => 'NatGames\Account\AccountItem'),
    'sessionItem' => array('em' => 'sessionEm', 'item' => 'NatGames\Session\SessionDataItem'),
  );

  public function newSession()
  {
    // Actually a repository
    $session = $this->sessionRepo;
    // $listener = new SessionListener();
    // $session->setListener($listener);
    
    // Done
    return $this->data['session'] = $session;
  }
  public function newUser()
  {
    $this->data['user'] = $user = new \NatGames\UserItem($this);
    
    $userData = $this->session->load('user');

    $accountId = $userData->accountId;
    $memberId  = $userData->memberId;
    $projectId = $userData->projectId;

    if (!$accountId) return $user;

    $user->load($accountId,$memberId,$projectId);
    
    return $user;
    
    if ($accountId)
    {
      $search = array('account_id' => $accountId);

      $user = $this->userRepo->findOneBy($search);
    }
    if (!$user) $user = $this->userItem;
    
    return $this->data['user'] = $user;
  }

}
?>
