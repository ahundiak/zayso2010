<?php
namespace AYSO\Team;

use Cerad\Debug;

class Listener
{
  protected $count;

  public function __construct($count)
  {
    $this->count = $count;
  }
  public function postUpdate(\Doctrine\ORM\Event\LifecycleEventArgs $e)
  {
    $this->count->updated++;
  }
  public function postRemove(\Doctrine\ORM\Event\LifecycleEventArgs $e)
  {
    $this->count->deleted++;
  }
  public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $e)
  {
    $this->count->inserted++;
  }
}
class TeamPlayerImport extends \Cerad\Import
{
  protected $readerClassName = 'AYSO\Team\TeamPlayerImportReader';

  protected $batchCount =   0;
  protected $batchSize  = 100;

  protected $teams   = array();
  protected $team    = NULL;
  protected $players = array();

  public function process($params)
  {
    $listener = new Listener($this->count);
    $this->services->em->getEventManager()->addEventListener(
      array(
        \Doctrine\ORM\Events::postUpdate,
        \Doctrine\ORM\Events::postRemove,
        \Doctrine\ORM\Events::postPersist,
      ),
      $listener);
    
    return parent::process($params);
  }
  protected function processEnd()
  {
    $em = $this->services->em;
    $em->flush();
    $em->clear();
  }
  protected function processTeam($data)
  {
    $em = $this->services->em;

    $regionId = $data['region_id'];
    $memYear  = 2010;
    $program  = 'Fall';
    $desig    = $data['desig'];

    // Only process each team once
    $key = $regionId . $memYear . $program . $desig;
    if (isset($this->teams[$key])) return $this->teams[$key];

    // Flush previous team
    if ($this->team)
    {
      // Delete Any unprocessed players

      // Flush team
      $em->flush();
      $em->clear();
    }
    $this->team = NULL;

    // Lookup team, could replace with dql to save second player query
    $teamRepo = $em->getRepository('AYSO\Team\TeamItem');
    $params = array('regionId' => $regionId, 'memYear' => $memYear, 'program' => $program, 'desig' => $desig);
    $team = $teamRepo->findOneBy($params);
    if (!$team) return NULL;

    // Current team
    $this->team = $team;
    $this->players = array();

    // Update Team Person information

    // Indexed list of current players
    $players = $team->getPlayers();
    foreach($players as $player)
    {
      $this->players[$player->getPlayer()->getId()] = $player;
    }
die('Count ' . count($players));
    // Done
    return $team;

  }
  public function processRowData($data)
  {
    // Process it
    $em = $this->services->em;
    $this->count->total++;

    // Get and process the team, team must exist
    $team = $this->processTeam($data);
    if (!$team) return;

    // See if player is already in roster
    $playerId = $data['player_aysoid'];
    if (isset($this->players[$playerId]))
    {
      $teamPlayer = $this->players[$playerId];
      unset($this->players[$playerId]);
    }
    else
    {
      $teamPlayer = new \AYSO\Team\TeamPlayerItem();
//    $teamPlayer->setTeam    ($team);
      $teamPlayer->setTeamId  ($team->getId());
      $teamPlayer->setPlayerId($playerId);
      $team->addPlayer($teamPlayer);
      $em->persist($teamPlayer);
      //Debug::dump($teamPlayer);
      //die('Team id ' . $team->getId());
    }

    // Jersey Number
    $jerseyNumber = (int)$data['jersey_number'];

    $teamPlayer->setJerseyNumber($jerseyNumber);

  }
}
?>
