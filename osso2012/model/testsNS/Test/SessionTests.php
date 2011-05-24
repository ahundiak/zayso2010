<?php

namespace Test;

use \Cerad\Debug;


class SessionTests extends BaseTests
{
  protected $sessionId = 'test';

  function testDelete()
  {
    $em = $this->em;
    $em->clear();

    $query = $em->createQuery('DELETE Session\SessionDataItem item WHERE item.key = :session_id');
    $query->setParameter('session_id', $this->sessionId);
    $query->getResult();

  }
  function testInsert()
  {
    $em = $this->em;

    $sessionRepo = $em->getRepository('Session\SessionDataItem');
    $sessionRepo->setSessionId($this->sessionId);

    $sessionData = $sessionRepo->load('test');
    
    $this->assertTrue($sessionData->isEmpty());

    $sessionData->par1 = 'Par1';
    $sessionData->par2 = 'Par2';

    $sessionRepo->save($sessionData);

    //$sessionData->setItem();

    //$em->persist($sessionData);
    //$em->flush();
    
  }
  function testLookup()
  {
    $em = $this->em;
    
    $sessionRepo = $em->getRepository('Session\SessionDataItem');
    $sessionRepo->setSessionId($this->sessionId);

    $sessionData = $sessionRepo->load('test');

    $this->assertFalse($sessionData->isEmpty());

    $this->assertEquals('Par1',$sessionData->par1);

    return;

  }
  function testUpdate()
  {
    $em = $this->em;

    $sessionRepo = $em->getRepository('Session\SessionDataItem');
    $sessionRepo->setSessionId($this->sessionId);

    $sessionData = $sessionRepo->load('test');

    $sessionData->par3 = 'Par3';
    $sessionRepo->save($sessionData);
    
    return;

  }
}
?>
