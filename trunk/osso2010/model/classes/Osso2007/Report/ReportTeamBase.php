<?php
class Osso2007_Report_ReportTeamBase
{
  protected $context = NULL;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected $divs = array(7,8,9,10,11,12,13,14,15,16,17,18,19,20,21);

  protected $cols = array
  (
    array('skip' => 0, 'regionId' =>  7, 'name' => 'R0160 Huntsville'),
    array('skip' => 0, 'regionId' =>  4, 'name' => 'R0498 Madison'),
    array('skip' => 0, 'regionId' =>  1, 'name' => 'R0894 Monrovia'),
    array('skip' => 0, 'regionId' => 11, 'name' => 'R1174 NEMC'),
    array('skip' => 0, 'regionId' =>  5, 'name' => 'R0557 SL/FAY'),
    array('skip' => 0, 'regionId' => 28, 'name' => 'R0662 Sand Rock'),
    array('skip' => 0, 'regionId' => 10, 'name' => 'R0991 Sewanee'),

    array('skip' => 0, 'regionId' => 21, 'name' => 'R0414 Cullman'),
    array('skip' => 0, 'regionId' =>  6, 'name' => 'R0778 Arab'),

    array('skip' => 0, 'regionId' => 17, 'name' => 'R1565 Ardmore'),
    array('skip' => 0, 'regionId' => 20, 'name' => 'R1062 Albertville'),
    array('skip' => 0, 'regionId' => 24, 'name' => 'R0773 Hartselle'),
    array('skip' => 0, 'regionId' =>  8, 'name' => 'R0914 East Limestone'),
  );

  protected $rows = array
  (
    array('name' => 'U10 Boys',  'divId' =>  7, 'divKey' => 'U10B'),
    array('name' => 'U10 Girls', 'divId' =>  8, 'divKey' => 'U10G'),
    array('name' => 'U12 Boys',  'divId' => 10, 'divKey' => 'U12B'),
    array('name' => 'U12 Girls', 'divId' => 11, 'divKey' => 'U12G'),
    array('name' => 'U14 Boys',  'divId' => 13, 'divKey' => 'U14B'),
    array('name' => 'U14 Girls', 'divId' => 14, 'divKey' => 'U14G'),
    array('name' => 'U16 Boys',  'divId' => 16, 'divKey' => 'U16B'),
    array('name' => 'U16 Girls', 'divId' => 17, 'divKey' => 'U16G'),
    array('name' => 'U19 Boys',  'divId' => 19, 'divKey' => 'U19B'),
    array('name' => 'U19 Girls', 'divId' => 20, 'divKey' => 'U19G'),
  );

  protected function queryTeams()
  {
    $divs = $this->divs;
    $divs = implode(',',$divs);
    $search = array(
      'reg_year_id'    => 10,
      'season_type_id' => 1,
    //'division_id'    => $divs,
    );
    $sql = <<<EOT
SELECT 
  phy_team.unit_id          AS regionId,
  phy_team.division_id      AS divId,
  phy_team.division_seq_num AS num,
  
  head_coach.person_id AS head_coach_id,
  head_coach.fname     AS head_coach_fname,
  head_coach.lname     AS head_coach_lname

FROM osso2007.phy_team AS phy_team

LEFT JOIN osso2007.phy_team_person ON 
  osso2007.phy_team_person.phy_team_id = osso2007.phy_team.phy_team_id AND
  osso2007.phy_team_person.vol_type_id = 16

LEFT JOIN osso2007.person AS head_coach ON head_coach.person_id = osso2007.phy_team_person.person_id

WHERE
  reg_year_id    IN (:reg_year_id)    AND
  season_type_id IN (:season_type_id) AND
  division_id    IN ($divs)
ORDER BY regionId,divId,num;
EOT;
    $rows = $this->context->db->fetchRows($sql,$search);

    $data = array();
    foreach($rows as $row)
    {
      $regionId = $row['regionId'];
      $divId    = $row['divId'];
      if (!isset($data[$regionId])) $data['regionId'] = array();
      if (!isset($data[$regionId][$divId])) $data[$regionId][$divId] = array($row);
      else                                  $data[$regionId][$divId][] = $row;
    }

    return $data;
  }
  protected function genHeaderLine()
  {
    $line = 'Division';
    foreach($this->cols as $col)
    {
      if (!$col['skip']) $line .= ',' . $col['name'];
    }
    $line .= ',Total';
    return $line;
  }
}
?>
