<?php
class RefereeReport
{
  protected $context;
  protected $regions;
  protected $teams;
  protected $referees;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function processGameTeam($game,$teamKey)
  {
    $team = $game[$teamKey];

    // Pull the region
    $data   = explode(' ',$team);
    $region = (int)$data[0];
    if ($region < 100) return;
    
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
    $this->regions[$region]['game_count']++;

    // Pull the team
    $key = $game['game_div'] . ' ' . $team;
    if (!isset($this->teams[$key]))
    {
      $this->teams[$key] = array
      (
        'div'        => $game['game_div'],
        'region'     => $region,
        'team_key'   => $key,
        'game_count' => 0,
        'slot_count' => 0,
      );
      $this->regions[$region]['team_count']++;
    }
    $this->teams  [$key]   ['game_count']++;
    return;
  }
  public function execute()
  {
    echo "Processing Referee Report\n";
    $db   = $this->context->db;

    // Do the games
    $sql  = "SELECT * FROM games;";
    $rows = $db->fetchRows($sql);
    $gameCount = count($rows);
    $slotTotalCount = $gameCount * 3;

    $this->regions = array();
    $this->teams   = array();
    $this->referees = array();

    foreach($rows as $row)
    {
      $this->processGameTeam($row,'home_name');
      $this->processGameTeam($row,'away_name');
    }
    // Do the referees
    $sql  = "SELECT * FROM game_person WHERE pos_id IN (1,2,3);";
    $rows = $db->fetchRows($sql);
    $slotFilledCount = count($rows);
    foreach($rows as $row)
    {
      $region = $row['region'];
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

      $refKey = $row['region'] . ' ' . $row['fname'] . ' ' . $row['lname'];
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

    // Output
    usort($this->regions,array($this,'compareRegions'));

    echo "REG# TC GC SQ SC RC\n";

    foreach($this->regions as $region)
    {
      $slotsToCover = (int)($region['game_count'] * 3) / 2;
      printf("%4u %2u %2u %2u %2u %2u\n",
        $region['region'],
        $region['team_count'],
        $region['game_count'],
        $slotsToCover,
        $region['slot_count'],
        $region['ref_count' ]);
    }
    echo "REG# TC GC SQ SC RC\n";
    echo "-------------------\n";

    // Output
    usort($this->referees,array($this,'compareReferees'));
    foreach($this->referees as $referee)
    {
      echo "{$referee['ref_key']} ${referee['slot_count']}\n";
    }
    echo "-------------------\n";
    $teamCount   = count($this->teams);
    $refCount    = count($this->referees);
    $regionCount = count($this->regions);
    $slotFilledPercent = ($slotFilledCount/$slotTotalCount) * 100;

    printf(
      "Region Count: %u, Team Count: %u, Game Count: %u, Referee Count: %u, " .
      "Total Slots: %u, Slots Filled: %u, Slots Empty: %u, Coverage: %u%%\n",
      $regionCount,
      $teamCount,
      $gameCount,
      $refCount,
      $slotTotalCount,
      $slotFilledCount,
      $slotTotalCount - $slotFilledCount,
      $slotFilledPercent);
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
