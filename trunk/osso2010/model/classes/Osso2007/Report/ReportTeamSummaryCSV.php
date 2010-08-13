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
    $header = 'Name,R0160 Huntsville,R0498 Madison,R0894 Monrovia,R1174 NEMC,R0557 SL/FAY,Total';
    $lines[] = $header;

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
      foreach($regionIds as $regionId)
      {
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
