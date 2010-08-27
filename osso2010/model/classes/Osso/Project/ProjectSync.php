<?php
class Osso_Project_ProjectSync
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {

  }
  public function getResultMessage()
  {
    return 'Project Sync Complete';
  }
  public function process()
  {
    $db = $this->context->dbOsso;

    $sql = <<<EOT
SELECT DISTINCT 
  sch_team.reg_year_id       AS reg_year_id,
  sch_team.season_type_id    AS season_type_id,
  sch_team.schedule_type_id  AS schedule_type_id,
  
  season_type.descx  AS season_type_desc,
  schedule_type.keyx AS schedule_type_desc

FROM osso2007.sch_team AS sch_team

LEFT JOIN osso2007.season_type   AS season_type
LEFT JOIN osso2007.schedule_type AS schedule_type

ORDER BY sch_team.reg_year_id,sch_team.season_type_id,sch_team.schedule_type_id;
EOT;
    $sql = <<<EOT
SELECT DISTINCT
  sch_team.reg_year_id       AS reg_year_id,
  sch_team.season_type_id    AS season_type_id,
  sch_team.schedule_type_id  AS schedule_type_id,
  sch_team.unit_id           AS unit_id

FROM osso2007.sch_team AS sch_team

ORDER BY sch_team.reg_year_id,sch_team.season_type_id,sch_team.schedule_type_id;
EOT;
    $rows = $db->fetchRows($sql);
    $items = array();
    $count = 0;
    foreach($rows as $row)
    {
      $repeat = FALSE;
      if (isset($items[$row['reg_year_id']][$row['season_type_id']][$row['schedule_type_id']])) $repeat = TRUE;
      else
      {
        $items[$row['reg_year_id']][$row['season_type_id']][$row['schedule_type_id']] = TRUE;
        
      }
      $year = $row['reg_year_id'] + 2000;
      switch($row['season_type_id'])
      {
        case 1: $season = 'Fall';   break;
        case 2: $season = 'Winter'; break;
        case 3: $season = 'Spring'; break;
        case 4: $season = 'Summer'; break;
      }
      switch($row['schedule_type_id'])
      {
        case 1: $type = 'Regular Season';      break;
        case 2: $type = 'Regional Tournament'; break;
        case 3: $type = 'Area Tournament';     break;
        case 4: $type = 'State Tournament';    break;
      }
      $unitId = $row['unit_id'];

      $desc = sprintf('%d %s %s %d',$year,$season,$type,$unitId);

      if ($repeat) $show = FALSE;
      else         $show = TRUE;
      if ($row['schedule_type_id'] == 2) $show = TRUE;

      if ($show)
      {
        echo $desc . "\n";
        $count++;
      }
    }
    printf("Project count %d\n",$count);

    // Cerad_Debug::dump($rows);
  }
}

?>
