<?php
namespace S5GamesApp\Schedule\Stats;

class StatsView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle   = 'S5Games Schedule Statistics';
  protected $tplContent = 'S5GamesApp/Schedule/Stats/StatsTpl.html.php';

  protected $regions  = array();
  protected $teams    = array();
  protected $referees = array();

  protected $gameCount = 0;
  protected $slotCount = 0;

  public function processGameTeam($game,$team)
  {
    // Pull the region
    $data = explode('-',$team);
    if (count($data) < 2) return;

    $region = (int)$data[1];
    if ($region < 100) return;

    if (!isset($this->regions[$region]))
    {
      $this->regions[$region] = array
      (
        'region'     => $region,
        'team_count' => 0,
        'game_count' => 0,
        'slot_count' => 0,
        'ref_count'  => 0,
      );
    }
    $this->regions[$region]['game_count']++;

    // Pull the team
    $div = $game->getDiv();
    $key = $div . ' ' . $team;
    if (!isset($this->teams[$key]))
    {
      $this->teams[$key] = array
      (
        'div'        => $div,
        'region'     => $region,
        'team_key'   => $key,
        'game_count' => 0,
        'slot_count' => 0,
      );
      $this->regions[$region]['team_count']++;
    }
    $this->teams[$key]['game_count']++;
    return;
  }
  protected function processReferee($game,$person)
  {
    // Only cr/ar
    $posId = $person->getPosId();
    if (($posId < 1) || ($posId > 3)) return;
    $this->slotCount++;

    // Region info
    $region = $person->getRegion();
    if (!isset($this->regions[$region]))
    {
      $this->regions[$region] = array
      (
        'region' => $region,
        'team_count' => 0,
        'game_count' => 0,
        'slot_count' => 0,
        'ref_count'  => 0,
      );
    }
    $this->regions[$region]['slot_count']++;

    $refKey = $person->getRegion() . ' ' . $person->getFirstName() . ' ' . $person->getLastName();
    if (!isset($this->referees[$refKey]))
    {
      $this->referees[$refKey] = array
      (
        'region'     => $region,
        'ref_key'    => $refKey,
        'slot_count' => 0,
      );
      $this->regions[$region]['ref_count']++;
    }
    $this->referees[$refKey]['slot_count']++;
  }
  public function process($data)
  {
    $gameRepo = $this->services->repoGame;
    $games = $gameRepo->search($data);
    $this->gameCount = count($games);

    // Fill in regions and teams
    foreach($games as $game)
    {
      $this->processGameTeam($game,$game->getHomeTeam());
      $this->processGameTeam($game,$game->getAwayTeam());
    }
    // Now do referees
    foreach($games as $game)
    {
      foreach($game->getPersons() as $person)
      {
        $this->processReferee($game,$person);
      }
    }
    // Output
    usort($this->regions, array($this,'compareRegions'));
    usort($this->referees,array($this,'compareReferees'));

    $this->renderPage();
  }
  function compareRegions($a,$b)
  {
    $ax = $a['region'];
    $bx = $b['region'];

    $ax = $a['team_count'];
    $bx = $b['team_count'];

    if ($ax < $bx) return -1;
    if ($ax > $bx) return  1;
    return 0;
  }
  function compareReferees($a,$b)
  {
    $ax = $a['ref_key'];
    $bx = $b['ref_key'];

    if ($ax < $bx) return -1;
    if ($ax > $bx) return  1;
    return 0;
  }
}
?>
