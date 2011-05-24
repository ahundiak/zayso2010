<?php

namespace Test;

use \Cerad\Debug;


class ArbiterTests extends BaseTests
{
  protected $sessionId = 'test';

  function sestDelete()
  {
    $em = $this->em;
    $em->clear();

    $query = $em->createQuery('DELETE Session\SessionDataItem item WHERE item.keyx = :session_id');
    $query->setParameter('session_id', $this->sessionId);
    $query->getResult();

  }
  function sestInsert()
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
  function testGetReferees()
  {
    $em = $this->em;
    
    $gameRepo = $em->getRepository('Arbiter\GameItem');

    $referees = $gameRepo->getReferees();

    $this->assertTrue(is_array($referees));
    $this->assertTrue(count($referees) > 10);

    $referee = array_pop($referees); // $referees[2];
    $this->assertEquals('Williamson, Michael',$referee);
    
    // Debug::dump($referees);
    
    return;

  }
  function testGetTeams()
  {
    $em = $this->em;

    $gameRepo = $em->getRepository('Arbiter\GameItem');

    $teams = $gameRepo->getTeams();

    $this->assertTrue(is_array($teams));
    $this->assertTrue(count($teams) > 10);

    $team = array_pop($teams); // $referees[2];
    $this->assertEquals('MS-G,Whitesburg Girls',$team);

    // Debug::dump($teams);

    return;

  }
  function testGetGames()
  {
    $em = $this->em;

    $gameRepo = $em->getRepository('Arbiter\GameItem');

    $params = array();
    $games = $gameRepo->getGames($params);

    $this->assertTrue(is_array($games));

    $game = $games[0];

    $this->assertEquals('3032',$game->getGameNum());
    $this->assertEquals('Mon', $game->getDow());
    $this->assertEquals('Bell Mountain Park',$game->getSite());

  }
}
?>
