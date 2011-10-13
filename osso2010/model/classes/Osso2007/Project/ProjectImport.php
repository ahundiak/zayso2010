<?php
/* =========================================================
 * 12 Oct 2011
 * Needed to load regional and area tournament projects
 * Found the spreadsheet and added the entries
 * First try pretty much wiped out schedule teams for current season
 * 
 * Code looks like it attempts to add assosrted things to project
 * 
 * Schedule types for 2011 are set to 3 and not 1?
 * 
 * So it only updates the project_org list
 * Not seeing it really do anything for schedule teams or physical teams
 * Need to look at the tournament import stuff for that
 */
class Osso2007_Project_ProjectImport extends Cerad_Import
{
  protected $readerClassName = 'Osso2007_Project_ProjectImportReader';

  protected function init()
  {
    parent::init();

    $this->directOrg = new Osso2007_Org_OrgDirect($this->context);

    $this->directEvent   = new Osso2007_Event_EventDirect($this->context);
    $this->directSchTeam = new Osso2007_Team_Sch_SchTeamDirect($this->context);

    $this->directProject = new Osso2007_Project_ProjectDirect($this->context);

    $this->directProjectOrg = new Osso2007_Project_Org_ProjectOrgDirect($this->context);

    $this->directProjectOrgTeam = new Osso2007_Project_Org_Team_ProjectOrgTeamDirect($this->context);
  }
  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
      $class, $file,
      $count->total,$count->inserted,$count->updated);
    return $msg;
  }
  protected function processEvents($data)
  {
    $eventNum = $data['event_num'];

    $search = array
    (
        'reg_year_id'      => $data['cal_year'] - 2000,
        'season_type_id'   => $data['season_type_id'],
        'schedule_type_id' => $data['type_id'],
    );
    $sql = <<<EOT
SELECT
  event_team.event_id AS event_id,
  event_team.unit_id  AS unit_id,
  event.event_num     AS event_num
FROM
  osso2007.event_team AS event_team

LEFT JOIN
  osso2007.event AS event ON event.event_id = event_team.event_id

WHERE
  event.reg_year_id      IN (:reg_year_id)    AND
  event.season_type_id   IN (:season_type_id) AND
  event.schedule_type_id IN (:schedule_type_id) AND
  event_team.event_team_type_id = 1

ORDER BY event.event_date,event.event_time,event.event_id;

EOT;
    $db = $this->context->dbOsso2007;
    $rows = $db->fetchRows($sql,$search);
    foreach($rows as $row)
    {
      // Cerad_Debug::dump($row); die();
      
      // Make sure rt are in correct region
      $update = true;
      if ($data['type_id'] == 2)
      {
        if ($data['admin_org_id'] != $row['unit_id']) $update = FALSE;
      }
      if (!$update) continue;

      // Determine event number
      $eventNumx = $row['event_num'];
      if ($eventNumx == 0) $eventNumx = ++$eventNum;
      else
      {
        if ($eventNumx > $eventNum) $eventNum = $eventNumx;
      }
      // Update
      $datax = array(
        'event_id'   => $row['event_id'],
        'project_id' => $data['id'],
        'event_num'  => $eventNumx,
      );
      // 12 Oct 2011 Don't see any reason to update this
      // $this->directEvent->update($datax);
    }
    return $eventNum;
  }
  // do it this way because have quite a few sch teams not assigned to any events
  protected function processSchTeams($data)
  {
    $search = array
    (
        'reg_year_id'      => $data['cal_year'] - 2000,
        'season_type_id'   => $data['season_type_id'],
        'schedule_type_id' => $data['type_id'],
    );
    $sql = <<<EOT
SELECT sch_team_id,unit_id,project_id
FROM
  osso2007.sch_team AS sch_team

WHERE
  reg_year_id      IN (:reg_year_id)    AND
  season_type_id   IN (:season_type_id) AND
  schedule_type_id IN (:schedule_type_id)
;
EOT;
    $db = $this->context->dbOsso2007;
    $rows = $db->fetchRows($sql,$search);
    foreach($rows as $row)
    {
      // Cerad_Debug::dump($row); die();

      // Make sure rt are in correct region
      $update = true;
      if ($data['type_id'] == 2)
      {
        if ($data['admin_org_id'] != $row['unit_id']) $update = FALSE;
      }
      if (!$update) continue;

      if ($row['project_id'] > 0)
      {
        //Cerad_Debug::dump($row);
        //die('project id already set');
      }
      // Update
      $datax = array(
        'sch_team_id' => $row['sch_team_id'],
        'project_id'  => $data['id'],
      );
      $this->directSchTeam->update($datax);
    }
  }
  /* ----------------------------------------------------
   * Physical teams have a many to many relation with projects
   * Verified that all physical teams have at least one schedule team
   * Verified that only have type 1,2,3 projects
   *
   * Really do not need the U6 teams in the regional tournament
   * Only want teams that played in area to be in list
   *
   * For project 25, area tournament, made schedule teams but never linked to
   * the physical teams, never made the schedule team form support this
   */
  protected function processPhyTeams()
  {
    $sql = <<<EOT
SELECT
  sch_team.project_id  AS project_id,
  phy_team.phy_team_id AS team_id,
  phy_team.unit_id     AS org_id
FROM sch_team
LEFT JOIN phy_team ON sch_team.phy_team_id = phy_team.phy_team_id
;
EOT;

    $db = $this->context->dbOsso2007;
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      if ($datax['team_id'] < 1) continue;

      $projectOrgId = $this->getProjectOrgId($row['project_id'],$row['org_id']);

      $datax = array
      (
        'project_org_id' => $projectOrgId,
        'team_id'        => $row['team_id'],
      );

      $this->directProjectOrgTeam->insert($datax);
    }
  }
  protected $projectOrgIds = array();
  protected function getProjectOrgId($projectid,$orgId)
  {
    if (isset($this->projectOrgIds[$projectId][$orgId])) return $this->projectOrgIds[$projectId][$orgId];

    $search = array
    (
      'project_id' => $projectId,
      'org_id'     => $orgId,
    );
    $result = $this->directProjectOrgTeam->fetchRows($search);
    if (!$result->count)
    {
      die('Could not find project org');
    }
    $row = $result->rows[0];

    $this->projectOrgIds[$projectId][$orgId] = $row['id'];

    return $row['id'];
  }
  public function processRowData($data)
  {  
    // Validation
    if (!$data['id']) return;
    $this->count->total++;

    $regions = $data['regions'];
    unset($data['regions']);

    // Set event number to max existing event
    //$eventNum = $this->processEvents($data);
    //if ($eventNum) $data['event_num'] = $eventNum;
    
    // All this did was to update existing schedule teams
    // $this->processSchTeams($data);

    if ($data['id'] == 39)
    {
      // print_r($data); die('insert');
    }
    $this->directProject->insert($data);
    $regions = explode(' ',$regions);
    foreach($regions as $region)
    {
      $regionId = $this->getRegion($region);
      if ($regionId)
      {
        $datax = array(
          'project_id' => $data['id'],
          'org_id'     => $regionId,
          'type_id'    => 1,
          'status'     => 1,
        );
        $this->directProjectOrg->insert($datax);
      }
    }
    // 12 Oct 2012 - Think this was already commented out
    // $this->processPhyTeams();
  }
  protected $regions = array();
  protected function getRegion($region)
  {
    if (!$region) return NULL;

    // Need to find the org_id for the region
    if (!isset($this->regions[$region]))
    {
      $result = $this->directOrg->getOrgForKey($region);

      $org = $result->row;
      if (!$org)
      {
        echo("Could not find region: $region\n"); // Some regions are revoked
        $this->regions[$region] = 0;
        return 0;
      }
      
      $this->regions[$region] = $org['unit_id'];
    }
    return $this->regions[$region];
  }
}
?>
