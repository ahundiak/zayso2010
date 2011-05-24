<?php

use Doctrine\Common\Util\Debug;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

class ConnTests extends BaseTests
{
  protected function init()
  {
    $ws      = '/home/ahundiak/zayso2010/';
    $appMode = 'development';

    $dbParams = array
    (
      'dbname'   => 'osso2007',
      'user'     => 'impd',
      'password' => 'impd894',
      'host'     => 'localhost',
      'driver'   => 'pdo_mysql', // pdo_mysql
      'wrapperClass' => 'MyConn',
    );
    if ($appMode == "development")
    {
      $cache = new \Doctrine\Common\Cache\ArrayCache;
    } else
    {
      $cache = new \Doctrine\Common\Cache\ApcCache;
    }
    $config = new Configuration;
    $config->setMetadataCacheImpl($cache);
    $driverImpl = $config->newDefaultAnnotationDriver($ws . 'osso2012/model/Entities');
    $config->setMetadataDriverImpl($driverImpl);
    $config->setQueryCacheImpl($cache);
    $config->setProxyDir($ws . 'osso2012/model/Proxies');
    $config->setProxyNamespace('osso2012\model\Proxies');

    $logger = new \Doctrine\DBAL\Logging\EchoSqlLogger();
  //$config->setSQLLogger($logger);
    
    if ($appMode == "development")
    {
      $config->setAutoGenerateProxyClasses(true);
    } else
    {
      $config->setAutoGenerateProxyClasses(false);
    }

    $this->em = EntityManager::create($dbParams, $config);
  }
  function test1()
  {
    $this->assertTrue(true);
  }
  function testWrapper()
  {
    $em = $this->em;
    $conn = $em->getConnection();
    $this->assertEquals('MyConn',get_class($conn));
  }
  function sestDQL2()
  {
  //echo "\n>>> testDLQ2\n";
    $em = $this->em;
    $logger = new \Doctrine\DBAL\Logging\EchoSqlLogger();
    $config = $em->getConfiguration();
  //$config->setSQLLogger($logger);

    $dql = <<<EOT
SELECT event, partial field.{id,desc}, teams, schTeam, phyTeam
FROM \OSSO\EventItem event
LEFT JOIN  event.field     field
LEFT JOIN  event.teams     teams
LEFT JOIN  teams.schTeam   schTeam
LEFT JOIN  schTeam.phyTeam phyTeam

WHERE event.id IN (:event_ids)
EOT;
    $query = $em->createQuery($dql);
  //$query->setParameter('event_ids','9909,9922');
    $query->setParameter('event_ids',array(9909,9922));
    $events = $query->getResult();
    $this->assertEquals(1,count($events));

    $event = $events[0];
    $team  = $event->getHomeTeam();
    $this->assertEquals(1,$team->getTypeId());
    $this->assertEquals('U12G-01',$team->getDesc());

    // Debug::dump($event);
    //$config->setSQLLogger();
  //echo "\n<<< testDLQ2\n";
  }
}
?>
