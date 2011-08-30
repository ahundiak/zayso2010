<?php

require_once 'Items.php';

class Osso2007_Referee_Points_RefPointsBase
{
  protected $context = NULL;
  protected $db;

  protected $unitId       =  0;
  protected $regYearId    = 11;
  protected $seasonTypeId =  1;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->context->db = $this->context->dbOsso2007;
  }
  public function getOutputFileName() { return 'RefPointsBase.xml'; }

  public function getResultMessage()
  {
    $class = get_class($this);
    return "$class";
  }
  public function process($params = array())
  {
  }
  protected function getTeams()
  {
    $db = $this->context->db;

    $unitId = $this->unitId;
    if ($unitId == 1) $age1 =  8;
    else              $age1 = 10;

    $divRepo = new Osso2007_Div_DivRepo($this->context);

    $divisionIds = $divRepo->getDivisionIdsForAgeRange($age1,19,TRUE,TRUE,TRUE);
    $divisionIds = $db->quoteInto('?',$divisionIds);

    $sql = <<<EOT
SELECT
  phy_team.phy_team_id          AS phy_team_id,
  phy_team.division_seq_num     AS phy_team_div_seq_num,
  division.division_id          AS phy_team_div_id,
  division.desc_pick            AS phy_team_div,
  unit.unit_id                  AS phy_team_league_id,
  unit.keyx                     AS phy_team_league_key,
  phy_team_coach_head.person_id AS phy_team_coach_head_person_id,
  phy_team_coach_head.fname     AS phy_team_coach_head_person_fname,
  phy_team_coach_head.lname     AS phy_team_coach_head_person_lname

FROM phy_team

LEFT JOIN unit        ON unit.unit_id               = phy_team.unit_id
LEFT JOIN reg_year    ON reg_year.reg_year_id       = phy_team.reg_year_id
LEFT JOIN division    ON division.division_id       = phy_team.division_id
LEFT JOIN season_type ON season_type.season_type_id = phy_team.season_type_id

LEFT JOIN phy_team_person
  AS  phy_team_person_coach_head
  ON  phy_team_person_coach_head.phy_team_id = phy_team.phy_team_id
  AND phy_team_person_coach_head.vol_type_id = 16

LEFT JOIN person
  AS phy_team_coach_head
  ON phy_team_coach_head.person_id = phy_team_person_coach_head.person_id

WHERE
  phy_team.unit_id        = {$unitId} AND
  phy_team.season_type_id = {$this->seasonTypeId} AND
  phy_team.reg_year_id    = {$this->regYearId}    AND
  phy_team.division_id   IN ($divisionIds)

ORDER BY
  phy_team.unit_id,
  phy_team.division_id,
  phy_team.division_seq_num
;
EOT;
    $rows = $db->fetchRows($sql);

    $teams = array();
    foreach($rows as $row)
    {
      // try with objects
      $team = new Team();
      $team->id = $row['phy_team_id'];

      $team->divId     = $row['phy_team_div_id'];
      $team->divKey    = $row['phy_team_div'];
      $team->divSeqNum = $row['phy_team_div_seq_num'];

      $team->leagueId  = $row['phy_team_league_id'];
      $team->leagueKey = $row['phy_team_league_key'];

      $coach = new Coach();

      $coach->id    = $row['phy_team_coach_head_person_id'];
      $coach->fname = $row['phy_team_coach_head_person_fname'];
      $coach->lname = $row['phy_team_coach_head_person_lname'];

      $team->coach = $coach;

      $teams[$team->id] = $team;

      // Cerad_Debug::dump($team); die();
    }
    return $teams;
  }
  public function getReferees()
  {
    $directReferee = new Osso2007_Referee_RefereeDirect($this->context);
    $result = $directReferee->getReferees(array('unit_id' => $this->unitId));
    $rows = $result->rows;

    $referees = array();
    foreach($rows as $row)
    {
/*
      if ((!$row['referee_lname']) && (!$row['referee_fname'])) {
        echo "Missing person for vol_id: {$row['vol_id']}\n";
       	break;
      }
 */
      $referee = new Referee();
      $referee->id    = $row['person_id'];
      $referee->fname = $row['person_fname'];
      $referee->lname = $row['person_lname'];

      $referee->ageId = 19; // Adult
      $dob = $row['eayso_dob'];
      if ((strlen($dob) == 8) && ($dob > '19920801')) $referee->ageId = 20; // Youth

      $referees[$referee->id] = $referee;
    }
    return $referees;
  }
  public function getEventReferees()
  {
    $db = $this->context->db;

    $sql = <<<EOT
SELECT
  event_person.person_id            AS referee_id,
  event_person.event_id             AS event_id,
  event_person.event_person_type_id AS referee_position_id,

  event.event_date AS event_date,
  event.point2     AS event_point2,

  event_team.division_id        AS division_id,
  event_team.unit_id            AS event_team_unit_id,
  event_team.event_team_type_id AS event_team_type_id

FROM event_person

LEFT JOIN event ON event.event_id = event_person.event_id

LEFT JOIN event_team ON event_team.event_id = event.event_id

WHERE
  event_person.event_person_type_id IN (10,11,12,13) AND

  event.event_date >= '20110801' AND
  event.event_date <= '20111031' AND
  event.schedule_type_id IN (1,2)

ORDER BY event_person.event_id, event_person.event_person_type_id
;
EOT;
    $rows = $db->fetchRows($sql);
    $events = array();
    foreach($rows as $row)
    {
      $eventId = $row['event_id'];

      // Object version
      if (isset($events[$eventId])) $event = $events[$eventId];
      else
      {
        $event = new Event();
        $event->id     = $eventId;
        $event->point2 = $row['event_point2'];
        $events[$eventId] = $event;
      }
      if ($event->divId < $row['division_id']) $event->divId = $row['division_id'];

      if ($row['event_team_type_id'] == 1) $event->unitHomeId = $row['event_team_unit_id'];

      $referee = new Referee();
      $referee->id         = $row['referee_id'];
      $referee->positionId = $row['referee_position_id'];

      $event->addReferee($referee);
    }
    return $events;
  }
  public function linkTeamsReferees($teams,$referees)
  {
    $db = $this->context->db;

    $sql = <<<EOT
SELECT * FROM phy_team_referee

WHERE
  phy_team_referee.unit_id        = {$this->unitId}       AND
  phy_team_referee.season_type_id = {$this->seasonTypeId} AND
  phy_team_referee.reg_year_id    = {$this->regYearId}

ORDER BY referee_id, pri_regular;

EOT;

    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $teamId    = $row['phy_team_id'];
      $refereeId = $row['referee_id'];

      if (!isset($teams   [$teamId]   )) die("Missing team $teamId");

      if (!isset($referees[$refereeId]))
      {
        //echo("Missing referee $refereeId\n"); // From 2008

      }
      else
      {
        $team    = $teams[$teamId];
        $referee = $referees[$refereeId];

        $team->addReferee($referee);
        $referee->addTeam($team,$row['pri_regular'],$row['max_regular']);
      }
    }
  }
 }
?>