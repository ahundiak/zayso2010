<?php
namespace Test;

use Doctrine\Common\Util\Debug;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

/* =========================================================
 * Need to refactor to use services
 */
class BaseTests extends \PHPUnit_Framework_TestCase
{
  static    $emx;
  protected $em;

  static    $servicesx;
  protected $services;

  public static function setUpBeforeClass()
  {
    $config = $GLOBALS['config'];

    self::$servicesx = new \Cerad\Services($config);

    return;

    $ws       = $config['ws'];
    $appMode  = $config['app_mode'];
    $dbParams = $config['db_params'];

    // $appMode = 'prod';
    
    if ($appMode == "dev") $cache = new \Doctrine\Common\Cache\ArrayCache;
    else                   $cache = new \Doctrine\Common\Cache\ApcCache;

    $config = new Configuration;
    $config->setMetadataCacheImpl($cache);
    $driverImpl = $config->newDefaultAnnotationDriver($ws . 'osso2012/model/Entities');
    $config->setMetadataDriverImpl($driverImpl);
    $config->setQueryCacheImpl($cache);
    $config->setProxyDir($ws . 'osso2012/model/Proxies');
    $config->setProxyNamespace('Proxies');

    $logger = new \Cerad\EchoSqlLogger(false);
    $config->setSQLLogger($logger);

    if ($appMode == "dev") $config->setAutoGenerateProxyClasses(true);
    else                   $config->setAutoGenerateProxyClasses(false);

    self::$emx = EntityManager::create($dbParams, $config);
  }
  public function setUp()
  {
    $this->services = self::$servicesx;

    $this->em = $this->services->em;
    
  }
}
?>
