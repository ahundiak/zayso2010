<?php

namespace Test;

use \Cerad\Debug;

class AysoTests extends BaseTests
{
  function testHerdoc()
  {
    $prefix = 'Prefix';
    $sql = <<<EOT
SELECT FROM {$prefix}Table;
EOT;
    $this->assertEquals('SELECT FROM PrefixTable;',$sql);
  }
  // This actually caches the results of the team players query
  function testTeamPlayers()
  {
    $em = $this->em;
    $logger = $em->getConfiguration()->getSQLLogger();
    $logger->enable(false);

    $id = '590591';
    $team = $em->find('AYSO\Team\TeamItem',$id);
    $this->assertEquals('U19G02R Harn',$team->getDesig());

    $players = $team->getPlayers();
    $this->assertEquals(19,count($players));

    $player = $players[0];
    $this->assertEquals('51480980',$player->getPlayer()->getId());

    $logger->enable(false);

  }
  // This actually caches the results of the team players query
  function testTeamPlayerLookup()
  {
    $em = $this->em;
    $search = array('player' => '51480980');

    $teamPlayers = $em->getRepository('AYSO\Team\TeamPlayerItem')->findBy($search);

    $this->assertEquals(1,count($teamPlayers));

    $teamPlayer = $teamPlayers[0];
    $this->assertEquals('590591',$teamPlayer->getTeamId());


  }
  function testTeamPlayersDQL()
  {
    $em = $this->em;

    $dql = <<<EOT
SELECT team, team_players, player
FROM \AYSO\Team\TeamItem team
LEFT JOIN team.players team_players
LEFT JOIN team_players.player player
WHERE team.id IN ('586776')
EOT;

    $dqlx = <<<EOT
SELECT team
FROM \AYSO\Team\TeamItem team
WHERE team.id IN ('590591')
EOT;
    $params = array('id' => '590591');
    $teams = $em->createQuery($dql)->execute();
    $this->assertEquals(1,count($teams));
    $team = $teams[0];
    
    $this->assertEquals('U05C06R Curt',$team->getDesig());

    $players = $team->getPlayers();
    $this->assertEquals(8,count($players));

    $player = $players[0]->getPlayer();
    $this->assertEquals('65416924',$player->getId());

    // $playerx = $player->getPlayer();
    $this->assertEquals('Curtis',$player->getLastName());
  }
}
?>
