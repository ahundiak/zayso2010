<?php
class Osso2007_Project_ProjectSync2
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
    return 'Project Sync 2 Complete';
  }
  protected function processRS($project)
  {
    if ($project['type_id']        != 1) return;
    if ($project['season_type_id'] != 1) return;

    $repoPhyTeam = $this->context->repos->phyTeam;
    $repoProject = $this->context->repos->project;

    $pid = $project['id'];
    
    $search = array
    (
      'cal_year'       => $project['cal_year'],
      'season_type_id' => 1,
    );
    $teams = $repoPhyTeam->getForSeason($search);

    foreach($teams as $team)
    {
      $teamId = $team['id'];
      $orgId  = $team['org_id'];
      $repoProject->addOrg ($pid,$orgId);
      $repoProject->addTeam($pid,$teamId);
    }
    printf("RS %d %d\n",$pid,count($teams));
  }
  protected function processRT($project)
  {
    if ($project['type_id'] != 2) return;

    $repoPhyTeam = $this->context->repos->phyTeam;
    $repoProject = $this->context->repos->project;

    $pid   = $project['id'];
    $orgId = $project['admin_org_id'];
    $repoProject->addOrg($pid,$orgId);

    $search = array
    (
      'org_id'         => $orgId,
      'cal_year'       => $project['cal_year'],
      'season_type_id' => 1,
    );
    $teamIds = $repoPhyTeam->getIdsForOrgSeason($search);

    foreach($teamIds as $teamId)
    {
      $teamId = $teamId['id'];
      $repoProject->addTeam($pid,$teamId);
    }
    printf("RT %d %d %d\n",$pid,$orgId,count($teamIds));
  }
  public function process($params = array())
  {
    $projects = $this->context->repos->project->getActiveProjects();
    foreach($projects as $project)
    {
      $this->processRS($project);
      $this->processRT($project);
    }
  }
  public function process1($params = array())
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
    $rows[] = array('reg_year_id' => 11, 'season_type_id' => 2, 'schedule_type_id' => 1, 'unit_id' =>  4);
    $items = array();

    $lines   = array();
    $lines[] = 'id,status,event_num,sport,mem_year,cal_year,date_beg,date_end,season,type,admin,description,regions,project_table';
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

      $unitId = $row['unit_id'];
      if (isset($orgs[$unitId])) $org = $orgs[$unitId];
      else
      {
        $result = $directOrg->fetchRow(array('unit_id' => $unitId));
        $orgs[$unitId] = $org = $result->row;
      }
      $unitDesc = $org['desc_pick'];
      $unitKey  = $org['keyx'];
      $unitId   = $org['unit_id'];

      $regions[] = (int)substr($unitKey,1,4);

      $calYear = $row['reg_year_id'] + 2000;

      $seasonTypeId = $row['season_type_id'];
      switch($seasonTypeId)
      {
        case 1:
          $season = 'Fall';
          $dateBeg = $calYear . '0801';
          $dateEnd = $calYear . '1130';
          $memYear = $calYear;
          break;
        case 2: 
          $season = 'Winter';
          $dateBeg = $calYear . '0101';
          $dateEnd = $calYear . '0331';
          $memYear = $calYear - 1;
          break;
        case 3: 
          $season = 'Spring';
          $dateBeg = $calYear . '0201';
          $dateEnd = $calYear . '0731';
          $memYear = $calYear - 1;
         break;
        case 4: 
          $season = 'Summer';
          $dateBeg = $calYear . '0601';
          $dateEnd = $calYear . '0731';
          $memYear = $calYear - 1;
          break;
        default: die('Invalid season type ' . $seasonTypeId);
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
      $desc = sprintf('CY%d %s %s',$calYear,$season,$type);

      if (!$line)
      {
        // Status
        $status = 3;
        if (($calYear == 2010) && ($seasonTypeId == 1)) $status = 1;
        if ($calYear > 2010) $status = 2;
        
        // Admin organization
        $adminOrgId = 1;
        if ($schTypeId    == 2) $adminOrgId = $unitId; // RT
        if ($seasonTypeId == 2) $adminOrgId = $unitId; // Winter
        $sportTypeId = 1;
        $eventNum = 1000;

        $id++;
        $line = array
        (
          $id,$status,$eventNum,$sportTypeId,$memYear,$calYear,$dateBeg,$dateEnd,
          $seasonTypeId,$schTypeId,$adminOrgId,$desc
        );
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
