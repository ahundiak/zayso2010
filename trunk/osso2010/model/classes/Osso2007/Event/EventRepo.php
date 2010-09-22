<?php
class Osso2007_Event_EventRepo
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function __get($name)
  {
    switch($name)
    {
      case 'tableEvent'      : return $this->tableEvent       = $this->context->tablesx->event;
      case 'tableEventTeam'  : return $this->tableEventTeam   = $this->context->tablesx->eventTeam;
      case 'tableEventPerson': return $this->tableEventPerson = $this->context->tablesx->eventPerson;

      case 'repoEventClass' : return $this->repoEventClass = $this->context->repos->eventClass;

      case 'result': return $this->result = new Cerad_Repo_RepoResult();
    }
  }
  public function getClassIdForKey($key) { return $this->repoEventClass->getIdForKey($key); }

  protected function extractData($prefix,$row)
  {
    $data = array();
    $len = strlen($prefix);
    foreach($row as $name => $value)
    {
      if (substr($name,0,$len) == $prefix)
      {
        $data[substr($name,$len)] = $value;
      }
    }
    return $data;
  }
  protected function processRows($rows)
  {
    $events = array();
    foreach($rows as $row)
    {
      $eventId = $row['event_id'];
      if (isset($events[$eventId])) $event = $events[$eventId];
      else
      {
        $event = $this->extractData('event_',$row);
        $event['teams'] = array();
      }
      $team = $this->extractData('team_',$row);
      $event['teams'][] = $team;
      
      $events[$eventId] = $event;
    }
    // Cerad_Debug::dump($events); die();
    return $events;
  }
  public function getRowsForProjectNum($pid,$num)
  {
    $selects = array
    (
       'event.event_id      AS event_id',
       'event.project_id    AS event_project_id',
       'project_type.key1   AS event_project_type_key',

       'event.event_num     AS event_num',
       'event.event_date    AS event_date',
       'event.event_time    AS event_time',
       'event.class_id      AS event_class_id',
       'event_class.key1    AS event_class_key',
       'event.event_type_id AS event_type_id',
       'event.field_id      AS event_field_id',
       'field.descx         AS event_field_key',
       'field.sortx         AS event_field_sort',

       'event_team.event_team_id      AS team_event_team_id',
       'event_team.event_team_type_id AS team_event_team_type_id',

       'sch_team.sch_team_id  AS team_sch_team_id',
       'sch_team.phy_team_id  AS team_phy_team_id',
       'sch_team.division_id  AS team_div_id',
       'div.desc_pick         AS team_div_key',
       'sch_team.desc_short   AS team_sch_key',
       'sch_team.unit_id      AS team_org_id',
       'org.keyx              AS team_org_key',

       'phy_team.division_seq_num AS team_seq_num',
    );
    $joins = array
    (
      'FROM osso2007.event AS event',
      'LEFT JOIN osso2007.event_team AS event_team ON event_team.event_id  = event.event_id',
      'LEFT JOIN osso2007.sch_team   AS sch_team   ON sch_team.sch_team_id = event_team.team_id',
      'LEFT JOIN osso2007.phy_team   AS phy_team   ON phy_team.phy_team_id = sch_team.phy_team_id',

      'LEFT JOIN osso2007.division AS `div` ON div.division_id = sch_team.division_id',
      'LEFT JOIN osso2007.unit     AS  org  ON org.unit_id     = sch_team.unit_id',

      'LEFT JOIN osso2007.event_class  AS event_class  ON event_class.id  = event.class_id',
      'LEFT JOIN osso2007.field        AS field        ON field.field_id  = event.field_id',
      'LEFT JOIN osso2007.project      AS project      ON project.id      = event.project_id',
      'LEFT JOIN osso2007.project_type AS project_type ON project_type.id = project.type_id',
    );
    $wheres = array
    (
      'event.project_id = :project_id',
      'event.event_num IN (:event_num)',
    );
    // Put it all together
    $selects = implode(",\n",$selects);
    $joins   = implode(" \n",$joins);

    if (!count($wheres)) $wheres = null;
    else                 $wheres = "WHERE\n" . implode(" AND\n",$wheres);

    $sql = <<<EOT
SELECT
$selects
$joins
$wheres
;
EOT;
    $search = array('project_id' => $pid, 'event_num' => $num);
    $rows = $this->context->db->fetchRows($sql,$search);

    return $this->processRows($rows);
  }
  protected function getDataValue($data,$name,$default = null)
  {
    if (isset($data[$name])) return $data[$name];
    return $default;
  }
  public function getIdForProjectNum($pid,$num)
  {
    $search = array
    (
      'project_id' => $pid,
      'event_num'  => $num,
    );
    $rows = $this->tableEvent->query(array('event_id' => 'id'),$search);
    if (isset($rows[0])) $id = $rows[0]['id'];
    else                 $id = 0;
    return $id;
  }
  public function getIdsForDateTimeField($date,$time,$fieldId)
  {
    $search = array
    (
      'event_date' => $date,
      'event_time' => $time,
      'field_id'   => $fieldId,
    );
    $rows = $this->tableEvent->query(array('event_id' => 'id'),$search);
    $ids = array();
    foreach($rows as $row) $ids[] = $row['id'];
    return $ids;
  }
  /* ======================================================================
   * Try for a generic save routine with error checking and lots of other nonsense
   */
  public function save($event)
  {
    $result = $this->result;

    // If have an id then shouod be an update
    if ($this->getDataValue($event,'id',0)) return $this->update($event);

    // If it has an event number assigned then see if one exists already
    $num = $this->getDataValue($event,'event_num',0);
    if ($num)
    {
      // Check for project id
      $pid = $this->getDataValue($event,'project_id',0);
      if (!$pid)
      {
        $result->error = 'Missing project id in event';
        return $result;
      }
      $id = $this->getIdForProjectNum($pid,$num);
      if ($id)
      {
        $event['id'] = $id;
        return $this->update($event);
      }
    }
    $ids = $this->getIdsForDateTimeField($event['event_date'],$event['event_time'],$event['field_id']);
    if (count($ids))
    {
      // Need to do a controlled update to avoid overwriting existing games
      // Basically ignore for now
      $result->error = 'Event already in schedule';
      return $result;
    }
    // Really is a new record
    return $this->insert($event);
  }
  protected function update($event)
  {
    Cerad_Debug::dump($event);
    die('update');
    return $this->result;
  }
  protected function insert($event)
  {
    $result = $this->result;

    // Make sure have at least the home team
    $homeTeam = null;
    $awayTeam = null;
    if (isset($event['teams'][1])) $homeTeam = $event['teams'][1];
    if (isset($event['teams'][2])) $awayTeam = $event['teams'][2];

    if (!$homeTeam)
    {
      $result->error = 'Missing home team for event';
      return $result;
    }
    $schTeamId = $this->getDataValue($homeTeam,'sch_team_id',0);
    if (!$schTeamId)
    {
      $result->error = 'Missing schedule team id for home team for event';
      return $result;
    }
    // Create the event record
    $id = $this->tableEvent->insert($event);
    if (!$id)
    {
      $result->error = 'Could not insert event record';
      return $result;
    }
    $result->id = $id;

    $homeTeam['event_id'] = $id;
    $homeTeam['team_id']  = $schTeamId;
    $homeTeam['event_team_type_id'] = 1;
    $this->tableEventTeam->insert($homeTeam);

    if (!$awayTeam) return $result;
    $schTeamId = $this->getDataValue($awayTeam,'sch_team_id',0);
    if (!$schTeamId) return $result;

    $awayTeam['event_id'] = $id;
    $awayTeam['team_id']  = $schTeamId;
    $awayTeam['event_team_type_id'] = 2;
    $this->tableEventTeam->insert($awayTeam);
  }
}
?>
