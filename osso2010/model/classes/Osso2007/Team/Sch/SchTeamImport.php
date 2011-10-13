<?php
class Osso2007_Team_Sch_SchTeamImport extends Cerad_Import
{
  protected $readerClassName = 'Osso2007_Team_Sch_SchTeamImportReader';
  protected $regions = array();

  protected function init()
  {
    parent::init();
    $this->db = $this->context->db;

    $repos = $this->context->repos;

    $this->repoDiv = $repos->div;
    $this->repoOrg = $repos->org;
    
    $this->repoProject = $repos->project;
    $this->repoSchTeam = $repos->schTeam;
    $this->repoPhyTeam = $repos->phyTeam;
    
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
  protected $sortx = 0;

  public function processRowData($data)
  {
    // Validation
    if (!$data['sch_team']) return;
    $schTeamKey = $data['sch_team'];

    // Need a valid division
    $divId = $this->repoDiv->getIdForKey($data['div']);
    if (!$divId) return;

    // Need a organization
    $orgId = $this->repoOrg->getIdForKey($data['org']);
    if (!$orgId) return;

    $this->count->total++;

    // Need the project ids
    $pidTeams    = 70;
    $pidSchedule = $this->projectId;
    $rowSchedule = $this->projectRow;

    // Lookup physical team if have one
    $phyTeamId = 0;
    if ($data['phy_team'])
    {
      $phyTeamKey = $data['phy_team'];
      $phyTeamId = $this->repoPhyTeam->getIdForProjectKey($pidTeams,$phyTeamKey);
    }
    // Have a schedule team already?
    $schTeamId = $this->repoSchTeam->getIdForProjectKey($pidSchedule,$schTeamKey);
    if (!$schTeamId)
    {
      $datax = array
      (
        'project_id'       => $pidSchedule,
        'unit_id'          => $orgId,
        'desc_short'       => $schTeamKey,
        'phy_team_id'      => $phyTeamId,
        'reg_year_id'      => $rowSchedule['cal_year'] - 2000,
        'season_type_id'   => $rowSchedule['season_type_id'],
        'schedule_type_id' => $rowSchedule['type_id'],
        'division_id'      => $divId,
        'sortx'            => ++$this->sortx,
      );
      $schTeamId = $this->repoSchTeam->insert($datax);
      // Cerad_Debug::dump($datax); die($schTeamId);
    }
    else
    {
      // Update phy team
      if ($phyTeamId)
      {
        $datax = array('sch_team_id' => $schTeamId, 'phy_team_id' => $phyTeamId);
        $this->repoSchTeam->update($datax);
      }
    }
    return;
  }
    // Needs to be a parameter array
  public function process($params)
  {
    // Need project info
    $pid = $params['project_id'];

    $row = $this->repoProject->getRowForId($pid);
    if (!$row) return;

    $this->projectId  = $pid;
    $this->projectRow = $row;

    parent::process($params);
  }
}
?>
