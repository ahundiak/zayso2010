<?php
class Osso2007_Project_ProjectRepo extends Cerad_Repo_RepoBase
{
  protected $tblName = 'osso2007.project';
  protected $idName  = 'id';

  public function __get($name)
  {
    switch($name)
    {
      case 'tableProject':        return $this->tableProject        = new Cerad_Repo_RepoTable($this->db,'osso2007.project');
      case 'tableProjectOrg':     return $this->tableProjectOrg     = new Cerad_Repo_RepoTable($this->db,'osso2007.project_org');
      case 'tableProjectOrgTeam': return $this->tableProjectOrgTeam = new Cerad_Repo_RepoTable($this->db,'osso2007.project_org_team');
    }
  }
  /* --------------------
   * project_id or project_org_id
   * team_id
   * org_id
   */
  public function saveTeam($data)
  {
    $result = $this->result;

    if (isset($data['project_org_id'])) $projectOrgId = $data['project_org_id'];
    else
    {
      $projectOrgId = $this->getProjectOrgId($data['project_id'],$data['org_id']);
      $data['project_org_id'] = $projectOrgId;
    }
    if (!$projectOrgId) { $result->success = false; return; }

    // See if have a team already
    $tableProjectOrgTeam = $this->tableProjectOrgTeam;

    $rows = $tableProjectOrgTeam->query(null,array('project_org_id' => $projectOrgId, 'team_id' => $data['team_id']));
    if (count($rows < 1)) return $tableProjectOrgTeam->insert($data);

    $changes = $tableProjectOrgTeam->changes($row[0],$data);

    if ($changes)
    {
      return $tableProjectOrgTeam->insert($changes);
    }

    return 0;
  }
  protected $cacheProjectOrgIds = array();
  public function getProjectOrgId($projectId,$orgId)
  {
    if (isset($this->cacheProjectOrgIds[$projectId][$orgId])) return $this->cacheProjectOrgIds[$projectId][$orgId];

    //$sql = 'SELECT id FROM osso2007.project_org WHERE project_id = :project_id AND org_id = :org_id';
    //$search = array('project_id' => $projectId, 'org_id' => $orgId);
    //$rows = $this->db->fetchRows($sql,$search);

    $tableProjectOrg = $this->tableProjectOrg;
    $rows = $tableProjectOrg->query(array('id'),array('project_id' => $projectId, 'org_id' => $orgId));

    if (count($rows) == 1) $id = $rows[0]['id]'];
    else
    {
      // Maybe insert one???
      $id = 0;
    }
    $this->cacheProjectOrgIds[$projectId][$orgId] = $id;
    return $id;
  }
}

?>
