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
    array('skip' => 0, 'regionId' =>  8, 'name' => 'R0914 East Limestone'),
    array('skip' => 0, 'regionId' => 17, 'name' => 'R1565 Ardmore'),
    array('skip' => 0, 'regionId' =>  5, 'name' => 'R0557 SL/FAY'),
    array('skip' => 0, 'regionId' => 10, 'name' => 'R0991 Sewanee'),

    array('skip' => 0, 'regionId' =>  6, 'name' => 'R0778 Arab'),
    array('skip' => 0, 'regionId' => 21, 'name' => 'R0414 Cullman'),
    array('skip' => 0, 'regionId' => 24, 'name' => 'R0773 Hartselle'),

    array('skip' => 0, 'regionId' => 20, 'name' => 'R1062 Albertville'),
    array('skip' => 0, 'regionId' => 28, 'name' => 'R0662 Sand Rock'),
  );

  protected $rows = array
  (
    array('name' => 'U10 Boys/Coed', 'divTBD' => 'U10B', 'divs' => array(7  => 'U10B',  9 => 'U10C')),
    array('name' => 'U10 Girls',     'divTBD' => 'U10G', 'divs' => array(8  => 'U10G')),
    array('name' => 'U12 Boys/Coed', 'divTBD' => 'U12B', 'divs' => array(10 => 'U12B', 12 => 'U12C')),
    array('name' => 'U12 Girls',     'divTBD' => 'U12G', 'divs' => array(11 => 'U12G')),
    array('name' => 'U14 Boys/Coed', 'divTBD' => 'U14B', 'divs' => array(13 => 'U14B', 15 => 'U14C')),
    array('name' => 'U14 Girls',     'divTBD' => 'U14G', 'divs' => array(14 => 'U14G')),
    array('name' => 'U16 Boys/Coed', 'divTBD' => 'U16B', 'divs' => array(16 => 'U16B', 18 => 'U16C')),
    array('name' => 'U16 Girls',     'divTBD' => 'U16G', 'divs' => array(17 => 'U16G')),
    array('name' => 'U19 Boys/Coed', 'divTBD' => 'U19B', 'divs' => array(19 => 'U19B', 21 => 'U19C')),
    array('name' => 'U19 Girls',     'divTBD' => 'U19G', 'divs' => array(20 => 'U19G')),
  );

  protected function queryTeams($params)
  {
    $divs = $this->divs;
    $divs = implode(',',$divs);
    $search = array(
      //'project_id' => $params['project_id'], // Really need to add project to phy_teams
      'reg_year_id'    => 11,
      'season_type_id' => 1,
    //'division_id'    => $divs,
    );
    $sql = <<<EOT
SELECT 
  phy_team.unit_id          AS region_id,
  phy_team.division_id      AS div_id,
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
ORDER BY region_id,div_id,num;
EOT;
    $rows = $this->context->db->fetchRows($sql,$search);

    $data = array();
    foreach($rows as $row)
    {
      $regionId = $row['region_id'];
      $divId    = $row['div_id'];
      if (!isset($data[$regionId])) $data[$regionId] = array();
      if (!isset($data[$regionId][$divId])) $data[$regionId][$divId] = array($row);
      else                                  $data[$regionId][$divId][] = $row;
    }
    //Cerad_Debug::dump($data);
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
