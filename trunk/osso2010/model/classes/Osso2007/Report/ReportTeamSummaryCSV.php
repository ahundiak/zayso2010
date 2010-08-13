<?php
class Osso2007_Report_ReportTeamSummaryCSV
{
  protected $context = NULL;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function process($params)
  {
    $lines = array();

    $cols = array
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
     );
    $line = 'Division';
    foreach($cols as $col)
    {
      if (!$col['skip']) $line .= ',' . $col['name'];
    }
    $line .= ',Total';
    $lines[] = $line;

    $divs = array(7,8,9,10,11,12,13,14,15,16,17,18,19,20,21);
    $divs = implode(',',$divs);
    $search = array(
      'reg_year_id'    => 10,
      'season_type_id' => 1,
    //'division_id'    => $divs,
    );
    $sql = <<<EOT
SELECT unit_id,division_id FROM osso2007.phy_team
WHERE
  reg_year_id    IN (:reg_year_id)    AND
  season_type_id IN (:season_type_id) AND
  division_id    IN ($divs);
EOT;
    $rows = $this->context->db->fetchRows($sql,$search);

    $data = array();
    foreach($rows as $row)
    {
      $regionId = $row['unit_id'];
      $divId    = $row['division_id'];
      if (!isset($data[$regionId])) $data['regionId'] = array();
      if (!isset($data[$regionId][$divId])) $data[$regionId][$divId] = 1;
      else                                  $data[$regionId][$divId]++;
    }
    $regionIds = array(7,4,1,11,5);
    $rows = array
    (
      array('name' => 'U10 Boys',  'divId' =>  7),
      array('name' => 'U10 Girls', 'divId' =>  8),
      array('name' => 'U12 Boys',  'divId' => 10),
      array('name' => 'U12 Girls', 'divId' => 11),
      array('name' => 'U14 Boys',  'divId' => 13),
      array('name' => 'U14 Girls', 'divId' => 14),
      array('name' => 'U16 Boys',  'divId' => 16),
      array('name' => 'U16 Girls', 'divId' => 17),
      array('name' => 'U19 Boys',  'divId' => 19),
      array('name' => 'U19 Girls', 'divId' => 20),
    );
    foreach($rows as $row)
    {
      $line   = $row['name'];
      $divId  = $row['divId'];
      $total = 0;
      foreach($cols as $col)
      {
        if ($col['skip']) continue;
        $regionId = $col['regionId'];

        if (isset($data[$regionId][$divId])) $cnt = $data[$regionId][$divId];
        else                                 $cnt = 0;
        $line  .= ',' . $cnt;
        $total += $cnt;
      }
      $line .= ',' . $total;
      $lines[] = $line;
    }
    return implode("\n",$lines);

  }
}
?>
