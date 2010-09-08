<?php
class Osso2007_Project_ProjectSync
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
  public function process($params = array())
  {
    $db = $this->context->dbOsso;
    $directOrg = new Osso2007_Org_OrgDirect($this->context);
    $orgs = array();

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
    $regions = array(1,4,7,11);
    foreach($regions as $region)
    {
      $row = array('reg_year_id' => 10, 'season_type_id' => 1, 'schedule_type_id' => 2, 'unit_id' => $region);
      $rows[] = $row;
    }
    $rows[] = array('reg_year_id' => 10, 'season_type_id' => 1, 'schedule_type_id' => 3, 'unit_id' => 27);
    $rows[] = array('reg_year_id' => 10, 'season_type_id' => 1, 'schedule_type_id' => 4, 'unit_id' => 27);
    $items = array();

    $lines   = array();
    $lines[] = 'id,status,sport,year,date_beg,date_end,season,type,admin,description,regions,project_table';
    $line    = null;
    $regions = null;
    $id      = 0;
    foreach($rows as $row)
    {
      $repeat = FALSE;
      if (isset($items[$row['reg_year_id']][$row['season_type_id']][$row['schedule_type_id']])) $repeat = TRUE;
      else
      {
        // New row
        $items[$row['reg_year_id']][$row['season_type_id']][$row['schedule_type_id']] = TRUE;

        // Put out old one
        if ($line)
        {
          $line[]  = implode(' ',$regions);
          // Cerad_Debug::dump($line);
          $lines[] = implode(',',$line);
          $line    = null;
          $regions = null;
        }
      }
      $year = $row['reg_year_id'] + 2000;

      $unitId = $row['unit_id'];
      if (isset($orgs[$unitId])) $org = $orgs[$unitId];
      else
      {
        $result = $directOrg->fetchRow(array('unit_id' => $unitId));
        $orgs[$unitId] = $org = $result->row;
      }
      $unitDesc = $org['desc_pick'];
      $unitKey  = $org['keyx'];

      $regions[] = (int)substr($unitKey,1,4);

      $seasonTypeId = $row['season_type_id'];
      switch($seasonTypeId)
      {
        case 1:
          $season = 'Fall';
          $dateBeg = $year . '0801';
          $dateEnd = $year . '1130';
          break;
        case 2: 
          $season = 'Winter';
          $dateBeg = $year . '0101';
          $dateEnd = $year . '0331';
          break;
        case 3: 
          $season = 'Spring';
          $dateBeg = $year . '0201';
          $dateEnd = $year . '0731';
          break;
        case 4: 
          $season = 'Summer';
          $dateBeg = $year . '0601';
          $dateEnd = $year . '0731';
          break;
      }
      $schTypeId = $row['schedule_type_id'];
      switch($schTypeId)
      {
        case 1: $type = 'Regular Season Area 5C/5F';        break;
        case 2: $type = 'Regional Tournament ' . $unitDesc; break;
        case 3: $type = 'Area Tournament Area 5C';          break;
        case 4: $type = 'State Tournament Area 5C/5F';      break;
      }
      if ($seasonTypeId == 2)
      {
        $type = 'Regular Season ' . $unitDesc; // Winter
      }
      $desc = sprintf('CY%d %s %s',$year,$season,$type);

      if (!$line)
      {
        if (($year == 2010) && ($seasonTypeId == 1)) $status = 1;
        else                                         $status = 0;
        $id++;
        $line = array($id,$status,1,$year,$dateBeg,$dateEnd,$row['season_type_id'],$row['schedule_type_id'],1,$desc);
      }
      // Always write regional tournaments
      if ($schTypeId == 2)
      {
        $line[]  = implode(' ',$regions);
        // Cerad_Debug::dump($line);
        $lines[] = implode(',',$line);
        $line    = null;
        $regions = null;
      }
    }
    if ($line)
    {
      $line[]  = implode(' ',$regions);
      $lines[] = implode(',',$line);
    }
    //Cerad_Debug::dump($lines);
    $response = $this->context->response;
    $response->setBody(implode("\n",$lines));
    $response->setFileHeaders('ProjectSync.csv');

    return true;

    // Cerad_Debug::dump($rows);
  }
}

?>
