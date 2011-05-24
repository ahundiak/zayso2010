<?php

namespace Test;

class OrmTests extends BaseTests
{
  function test1()
  {
    $this->assertTrue(true);
  }
  function testFindId()
  {
    $em = $this->em;
    $id = 3;
    $openid = $em->find('OSSO\Openid',$id);
    $this->assertEquals('Arthur Hundiak',$openid->getDisplayName());
    $this->assertEquals('ArthurHundiak', $openid->getUserName());
  }
  function testQuery1()
  {
    $em = $this->em;
    $search = array('regType' => 102, 'regNum' => '99437977');

    $entity = $em->getRepository('AYSO\RegMain')->findOneBy($search);
    $this->assertEquals('Arthur', $entity->getFirstName());
    $this->assertEquals('Hundiak',$entity->getLastName());
  }
  function testRel1()
  {
    $em = $this->em;
    $event = $em->getRepository('OSSO\EventItem')->find(9500);
    $this->assertEquals(409, $event->getFieldId());

    //$entity = $em->getRepository('OSSO\FieldItem')->find(409);
    $field = $event->getField();
    $this->assertEquals('Proxies\OSSOFieldItemProxy',get_class($field));
    
    $this->assertEquals('John Hunt 13A', $event->getFieldDesc());
    $this->assertEquals('John Hunt',     $event->getSiteDesc());
  }
  function testRel2()
  {
    $em = $this->em;
    $event = $em->find('OSSO\EventItem',9016);
    $teams = $event->getTeams();

    $this->assertEquals(2,count($teams));

    $team = $teams[0];
    $this->assertEquals('U12B',$team->getDivDesc());

    $team = $event->getAwayTeam();
    $this->assertEquals(18646,$team->getId());
    $this->assertEquals('U12B',$team->getDivDesc());

    $eventPersons = $event->getPersons();
    $this->assertEquals(3,count($eventPersons));

    $person = $eventPersons[0]->getPerson();
    $this->assertEquals('Strand',$person->getLastName());
    $this->assertEquals('Strand',$eventPersons[0]->getLastName());

    $this->assertEquals('Stephen Strand',$person->getName());
    $this->assertEquals(514,$person->id);
  }
  function testTeams1()
  {
    $em = $this->em;
    $event = $em->find('OSSO\EventItem',9909);

    $eventTeam = $event->getHomeTeam();
    $schTeam = $eventTeam->getSchTeam();

    $this->assertEquals('U12G-01',$schTeam->getDesc());
    
    $org = $schTeam->getOrg();
    $this->assertEquals('R0498',$org->getKey());

    $phyTeam = $schTeam->getPhyTeam();
    $this->assertEquals(1,$phyTeam->getDivSeqNum());

    $persons = $phyTeam->getPersons();
    $this->assertEquals(2,count($persons));
    foreach($persons as $person)
    {
      // echo $person->getName() . "\n";
    }
  }
  function testTeams2()
  {
    $em = $this->em;
    $event = $em->find('OSSO\EventItem',9922);

    $eventTeam = $event->getHomeTeam();
    $schTeam = $eventTeam->getSchTeam();

    $this->assertEquals('U12G-1st Points B',$schTeam->getDesc());

    // An id of 0 means no team attached
    $phyTeamId = $schTeam->getPhyTeamId();
    $this->assertEquals(0,$phyTeamId);

  }
  function testDQL1()
  {
    $em = $this->em;
    $dql = 'SELECT event FROM \OSSO\EventItem event WHERE event.id IN (9909,9922)';
    $events = $em->createQuery($dql)->getResult();
    $this->assertEquals(2,count($events));

    $event = $events[0];
  //Debug::dump($event);
  }
  function testDQL2()
  {
    // echo "\n>>> testDLQ2\n";
    $em = $this->em;

    $dql = <<<EOT
SELECT event, partial field.{id,desc}, teams, schTeam, phyTeam
FROM \OSSO\EventItem event
LEFT JOIN  event.field     field
LEFT JOIN  event.teams     teams
LEFT JOIN  teams.schTeam   schTeam
LEFT JOIN  schTeam.phyTeam phyTeam

WHERE event.id IN (9909,9922)
EOT;
    $query = $em->createQuery($dql);
  //$query->setParameter('event_ids','9909,9922','string');
  //$query->setParameter('event_ids',array(9909,9922));
    $events = $query->getResult();
    $this->assertEquals(2,count($events));

    $event = $events[0];
  //Debug::dump($event);

    $event = $events[1];
    $team  = $event->getHomeTeam();
    $this->assertEquals(1,$team->getTypeId());
    $this->assertEquals('U12G-1st Points B',$team->getDesc());

    // Debug::dump($event);
    //$config->setSQLLogger();
    // echo "\n<<< testDLQ2\n";
  }
}
?>
