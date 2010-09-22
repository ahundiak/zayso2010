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
  /* -----------------------------------------------------------
   * Active project stuff
   */
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
  public function getActiveProjectsPickList($params = array())
  {
    $sql = <<<EOT
SELECT
  id,desc1
FROM osso2007.project AS project
WHERE project.status = 1;
EOT;
    $rows = $this->context->db->fetchRows($sql);
    $list = array();
    foreach($rows as $row)
    {
      $list[$row['id']] = $row['desc1'];
    }
    return $list;
  }
  /* ------------------------------------------------------
   * List of teams for
   * active projects or selected projects
   * selected regions
   * selected divisions
   *
   * Going to have an issue once have multiple teams
   * Really only for the regular season, this needs to be physical teams only
   */
  public function getTeams($params = array())
  {
    $sql = <<<EOT
SELECT
  sch_team.sch_team_id      AS sch_team_id,
  phy_team.phy_team_id      AS phy_team_id,
  phy_team.division_seq_num AS team_seq_num,
  phy_team_org.keyx         AS team_org_key,
  phy_team_div.desc_pick    AS team_div_key,
  coach_head.fname          AS coach_head_fname,
  coach_head.nname          AS coach_head_nname,
  coach_head.lname          AS coach_head_lname

FROM      osso2007.sch_team AS sch_team
LEFT JOIN osso2007.project  AS project      ON project.id = sch_team.project_id
LEFT JOIN osso2007.phy_team AS phy_team     ON phy_team.phy_team_id = sch_team.phy_team_id
LEFT JOIN osso2007.unit     AS phy_team_org ON phy_team_org.unit_id = phy_team.unit_id
LEFT JOIN osso2007.division AS phy_team_div ON phy_team_div.division_id = phy_team.division_id

LEFT JOIN osso2007.phy_team_person AS coach_headx ON
  coach_headx.phy_team_id = phy_team.phy_team_id AND
  coach_headx.vol_type_id = 16

LEFT JOIN osso2007.person AS coach_head ON coach_headx.person_id = coach_head.person_id

WHERE
  project.status = 1 AND
  phy_team_org.unit_id IN (:org_id) AND
  phy_team.division_id IN (:div_id)

ORDER BY
  phy_team_org.keyx,
  phy_team_div.sortx,
  phy_team.division_seq_num
;
EOT;
    $search = array
    (
        'div_id' => $params['div_id'],
        'org_id' => $params['org_id'],
    );
    $rows = $this->context->db->fetchRows($sql,$search);
    //Cerad_Debug::dump($rows); die();
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
