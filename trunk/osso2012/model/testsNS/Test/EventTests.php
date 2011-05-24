<?php

namespace Test;

use \Cerad\Debug;
use \OSSO2012\Event\EventItem;

class EventTests extends BaseTests
{
  function getRepo()   { return $this->em->getRepository('OSSO2012\Event\EventItem'); }
  function getLogger() { return $this->em->getConfiguration()->getSQLLogger();        }
  
  function sestDelete()
  {
    $em = $this->em;
    $em->clear();

    $query = $em->createQuery('DELETE OSSO2012\Event\EventItem item');
    $query->getResult();
  }
  function testGetEventClass()
  {
    $em = $this->em;

    $key = 'RG';
    $search = array('key1' => $key);

    $entity = $em->getRepository('OSSO2012\Event\EventClassItem')->findOneBy($search);
    $this->assertEquals('RG - Regular Game', $entity->getDesc1());
  }
  function testRepo()
  {
    // $em = $this->em;
    $this->getLogger()->enable(false);

    $repo = $this->getRepo(); // $em->getRepository('OSSO2012\Event\EventItem');
    $this->assertNotNull($repo);

    $entity = $repo->getClassForKey('RG');
    $this->assertEquals('RG - Regular Game', $entity->getDesc1());

    $entity = $repo->getClassForKey('RG');
    $this->assertEquals('RG - Regular Game', $entity->getDesc1());

    $entity = $repo->getClassForKey('RGx');
    $this->assertNull($entity);

    $this->getLogger()->enable(false);
  }
  function testNewEvent()
  {
    $repo = $this->getRepo();
    $event = $repo->newEvent();

    $this->assertNotNull($event);
    $this->assertEquals(1,$event->getType  ()->getId());
    $this->assertEquals(1,$event->getClass ()->getId());
    $this->assertEquals(1,$event->getStatus()->getId());

    $field = $repo->getFieldForKey('John Hunt 1');
    $event->setField($field);

    $dtBeg = new \DateTime('2011-03-31');
    $dtBeg->setTime(13,30);
    $event->setDtBeg($dtBeg);

    $dtEnd = clone($dtBeg);
    $di = new \DateInterval('PT45M');
    //$di->i = 45;
    $dtEnd->add($di);
    $event->setDtEnd($dtEnd);

    $this->em->persist($event);
    $this->em->flush();

    // Works as expected
    // echo "Event id " . $event->getId();
  }
  /* =======================================================
   * So Mysql Date is not built in
   * WHERE date(event.dtBeg) = '20110330'
   * Using BETWEEN eliminates the DATE function and possible index issues
   */
  function testQuery1()
  {
    $em = $this->em;
    
    $params = array('dt1' => '20110330000000', 'dt2' => '20110330235959');

    $dql = <<<EOT
SELECT DISTINCT partial event.{id}
FROM \OSSO2012\Event\EventItem event
WHERE (event.dtBeg BETWEEN :dt1 AND :dt2) AND ((1 = 2) OR (3 = 3))
EOT;
    $query  = $em->createQuery($dql);
    $query->setParameters($params);
    $events = $query->getArrayResult();

    $this->assertEquals(3,count($events));

    // Debug::dump($events);
  }
  /* =======================================================================
   * If I don't explicitly join things like event_type then I get no event type info
   * at all for array results, not even the id
   */
  function testQuery2()
  {
    // $this->getLogger()->enable(true);
    
    $em = $this->em;

    $ids = implode(',',array(2,3,5));

    $dql = <<<EOT
SELECT event, field
FROM      \OSSO2012\Event\EventItem event
LEFT JOIN event.field field
WHERE event.id IN ($ids)
EOT;

    $query  = $em->createQuery($dql);

    $events = $query->getResult();

    $this->assertEquals(3,count($events));

    $this->assertEquals('Game',$events[0]->getType()->getKey1());
    $this->assertEquals('Game',$events[1]->getType()->getKey1());

    // Debug::dump($events);

  }
}
?>
