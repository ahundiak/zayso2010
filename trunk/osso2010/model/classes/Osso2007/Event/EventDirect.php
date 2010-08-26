<?php
class Osso2007_Event_EventDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'osso2007.event';
  protected $idName  = 'event_id';

  protected function condition(&$wheres, $search,$name,$tpl)
  {
    if (!isset($search[$name])) return;

    $value = $search[$name];
    if (!$value) return;

    $value = $this->db->quote($value);
    
    $wheres[] = str_replace(':' . $name,$value,$tpl);
  }
  public function getDistinctIds($search)
  {
    $result = $this->newResult();

    $sql = <<<EOT
SELECT distinct event.event_id AS id

FROM osso2007.event_team AS event_team

LEFT JOIN osso2007.event AS event ON event.event_id = event_team.event_id

EOT;

    $wheres = array();

    $this->condition($wheres,$search,'date_ge',  'event.event_date >= :date_ge');
    $this->condition($wheres,$search,'date_le',  'event.event_date <= :date_le');
    $this->condition($wheres,$search,'field_id', 'event.field_id IN (:field_id)');
    $this->condition($wheres,$search,'event_type_id',    'event.field_id IN (:event_type_id)');
    $this->condition($wheres,$search,'schedule_type_id', 'event.field_id IN (:schedule_type_id)');
    $this->condition($wheres,$search,'point2',           'event.field_id IN (:point2)');

    $this->condition($wheres,$search,'unit_id',            'event_team.unit_id            IN (:unit_id)');
    $this->condition($wheres,$search,'team_id',            'event_team.team_id            IN (:team_id)');
    $this->condition($wheres,$search,'division_id',        'event_team.division_id        IN (:division_id)');
    $this->condition($wheres,$search,'event_team_type_id', 'event_team.event_team_type_id IN (:event_team_type_id)');

    if (!count($wheres)) return result;

    $sql .= "WHERE\n" . implode(" AND \n",$wheres) . "\n;\n";
//die($sql);

    $rows = $this->db->fetchRows($sql);
    $ids = array();
    foreach($rows as $row)
    {
      $ids[] = $row['id'];
    }
    $result->rows = $ids;
    return $result;
  }
  public function getSchedule($search)
  {
    $result = $this->newResult();

    $ids = $search['event_id'];
    if (!is_array($ids)) $ids = array($ids);

    if (count($ids) < 1) return result;
    $ids = $this->db->quote($ids);

    $sql = <<<EOT
SELECT
  event.event_id   AS  id,
  event.event_num  AS  num,
  event.event_date AS `date`,
  event.event_time AS `time`,
  event.point2     AS `point2`,

  event.event_type_id AS type_id,
  event_type.descx    AS type_desc,

  event.schedule_type_id AS sch_type_id,

  event.field_id   AS  field_id,
  field.descx      AS  field_desc,

  event_team.event_team_id      AS team_id,
  event_team.team_id            AS team_sch_id,

  event_team.event_team_type_id AS team_type_id,
  event_team_type.descx         AS team_type_desc,
    
  event_team.unit_id            AS team_unit_id,
  event_team_unit.keyx          AS team_unit_desc,

  event_team.division_id        AS team_div_id,
  event_team_div.desc_pick      AS team_div_desc,

  coach_head.fname AS coach_head_fname,
  coach_head.lname AS coach_head_lname,
  coach_head.nname AS coach_head_nname

FROM osso2007.event AS event

LEFT JOIN osso2007.field AS field ON field.field_id = event.field_id

LEFT JOIN osso2007.event_type AS event_type ON event_type.event_type_id = event.event_type_id

LEFT JOIN osso2007.event_team AS event_team ON event_team.event_id = event.event_id

LEFT JOIN osso2007.event_team_type AS event_team_type ON event_team_type.event_team_type_id = event_team.event_team_type_id

LEFT JOIN osso2007.unit AS event_team_unit ON event_team_unit.unit_id = event_team.unit_id

LEFT JOIN osso2007.division AS event_team_div ON event_team_div.division_id = event_team.division_id

LEFT JOIN osso2007.sch_team AS sch_team ON sch_team.sch_team_id = event_team.team_id

LEFT JOIN osso2007.phy_team AS phy_team ON phy_team.phy_team_id = sch_team.phy_team_id

LEFT JOIN osso2007.phy_team_person AS coach_headx ON coach_headx.phy_team_id = phy_team.phy_team_id AND coach_headx.vol_type_id = 16

LEFT JOIN osso2007.person AS coach_head ON coach_head.person_id = coach_headx.person_id
    
WHERE event.event_id IN ($ids)

EOT;
    $rows = $this->db->fetchRows($sql);
    $events = array();
    foreach($rows as $row)
    {
      $id = $row['id'];
      if (isset($events[$id])) $event = $events[$id];
      else
      {
        $event = array
        (
          'id'         => $row['id'],
          'num'        => $row['num'],
          'date'       => $row['date'],
          'time'       => $row['time'],
          'point2'     => $row['point2'],
          'type_id'    => $row['type_id'],
          'type_desc'  => $row['type_desc'],
          'field_id'   => $row['field_id'],
          'sch_type_id'=> $row['sch_type_id'],
          'field_desc' => $row['field_desc'],
          'teams'      => array(),
          'persons'    => array(),
         );
      }
      // Do teams
      $teamTypeId = $row['team_type_id'];
      if (isset($event['teams'][$teamTypeId])) $team = $event['teams'][$teamTypeId];
      else $team = array
      (
        'id' => $row['team_id'],
        
        'type_id'   => $row['team_type_id'],
        'type_desc' => $row['team_type_desc'],

        'unit_id'   => $row['team_unit_id'],
        'unit_desc' => $row['team_unit_desc'],

        'div_id'    => $row['team_div_id'],
        'div_desc'  => $row['team_div_desc'],

        'coach_head_fname' => $row['coach_head_fname'],
        'coach_head_lname' => $row['coach_head_lname'],
        'coach_head_nname' => $row['coach_head_nname'],
      );
      $event['teams'][$teamTypeId] = $team;

      // Save event
      $events[$id] = $event;
    }
    // Cerad_Debug::dump($event);
    $result->rows = $events;
    return $result;
  }
  public function getPersons($search)
  {
    $result = $this->newResult();

    $ids = $search['event_id'];
    if (!is_array($ids)) $ids = array($ids);

    if (count($ids) < 1) return result;
    $ids = $this->db->quote($ids);

    $sql = <<<EOT
SELECT
  event_person.event_person_id      AS id,
  event_person.event_id             AS event_id,
  event_person.person_id            AS person_id,
  event_person.event_person_type_id AS type_id,
  event_person_type.keyx            AS type_desc,
  person.fname                      AS person_fname,
  person.lname                      AS person_lname,
  person.nname                      AS person_nname

FROM osso2007.event_person AS event_person

LEFT JOIN osso2007.person AS person ON person.person_id = event_person.person_id

LEFT JOIN osso2007.vol_type AS event_person_type ON event_person_type.vol_type_id = event_person.event_person_type_id

WHERE event_id IN ($ids)

ORDER BY event_id,type_id;

EOT;

    $rows = $this->db->fetchRows($sql);
    $events = array();
    foreach($rows as $row)
    {
      $id = $row['event_id'];
      if (isset($events[$id])) $event = $events[$id];
      else                     $event = array('id' => $id,'persons' => array());

      $typeId = $row['type_id'];
      $event['persons'][$typeId] = $row;
      
      $events[$id] = $event;
    }
    // Cerad_Debug::dump($events);die();
    $result->rows = $events;
    $result->message = 'WTF';
    return $result;
  }
}
?>
