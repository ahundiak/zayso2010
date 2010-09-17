<?php
class Osso2007_Project_ProjectRepo
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function __get($name)
  {
    switch($name)
    {
      case 'tableProject':     return $this->tableProject     = new Cerad_Repo_RepoTable($this->context->db,'osso2007.project');
      case 'tableProjectItem': return $this->tableProjectItem = new Cerad_Repo_RepoTable($this->context->db,'osso2007.project_item');
    }
  }
  public function getActiveProjects($params = array())
  {
    $sql = <<<EOT
SELECT
  *
FROM osso2007.project AS project
WHERE project.status = 1;
EOT;
    $rows = $this->context->db->fetchRows($sql);
    return $rows;
  }
  /* ----------------------------------------------------------
   * Add item to project
   */
  protected $cacheProjectItemIds = array();
  protected function addItem($pid,$itemId,$typeId,$key = null)
  {
    if (isset($this->cacheProjectItemIds[$typeId][$pid][$itemId])) return $this->cacheProjectItemIds[$typeId][$pid][$itemId];

    $tableProjectItem = $this->tableProjectItem;

    // Do not allow dups
    $search = array('type_id' => $typeId, 'project_id' => $pid, 'item_id' => $itemId);

    $rows = $tableProjectItem->query('id',$search);
    if (count($rows))
    {
      $id = $rows[0]['id'];
      $this->cacheProjectItemIds[$typeId][$pid][$itemId] = $id;
      return $id;
    }
    // Do not allow duplicate keys
    if ($key)
    {
      // Problem is that different types of items will have different key structures
      // Physical team is the most intresting and does not currently exist
      // Might be better to check after the fact as it were
    }
    // Add it
    $id = $tableProjectItem->insert($search);
    $this->cacheProjectItemIds[$typeId][$pid][$itemId] = $id;
    return $id;
  }
  public function addOrg ($pid,$itemId) { return $this->addItem($pid,$itemId,1); }
  public function addTeam($pid,$itemId) { return $this->addItem($pid,$itemId,2); }

  protected $cacheProjectRows = array();
  public function getRowForId($id)
  {
    if (!$id) return null;
    if (isset($this->cacheProjectRows[$id])) return $this->cacheProjectRows[$id];

    $rows = $this->tableProject->query('*',array('id' => $id));
    if (isset($rows[0])) $row = $rows[0];
    else                 $row = null;

    $this->cacheProjectRows[$id] = $row;

    return $row;
  }
  /* ===========================================================================
   * All below is obsolete
   */
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
