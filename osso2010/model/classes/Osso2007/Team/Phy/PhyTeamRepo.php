<?php

class Osso2007_Team_Phy_PhyTeamRepo
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
      case 'tablePhyTeam': return $this->tablePhyTeam = new Cerad_Repo_RepoTable($this->context->db,'osso2007.phy_team');
    }
  }
  public function generateKey($data)
  {
    $orgKey = $this->context->repos->org->getKeyForId($data['unit_id']);
    $divKey = $this->context->repos->div->getDivisionDesc($data['division_id']);
    
    $key = sprintf("%s%s%02u",$orgKey,$divKey,$data['division_seq_num']);

    return $key;
  }
  public function parseKey($key)
  {
    $data = array('org_id' => 0, 'div_id' => 0, 'seq_num' => 0);

    // Drop extra chars
    $tmp = explode(' ',$key);
    $key = $tmp[0];
    $key = str_replace('-','',$key);

    // Pull region
    $tmp = explode('U',$key);
    $region = $tmp[0];
    $data['org_id'] = $this->context->repos->org->getIdForKey($tmp[0]);

    // Pull division
    if (!isset($tmp[1])) return $data;
    $tmp = $tmp[1];
    $age = (int)$tmp;
    $pos = strpos($tmp,'G');
    if (!$pos) $pos = strpos($tmp,'B');
    if (!$pos) $pos = strpos($tmp,'C');
    if (!$pos) return $data;
    $div = sprintf('U%02u%s',$age,$tmp[$pos]);
    $data['div_id'] = $this->context->repos->div->getIdForKey($div);

    // And the sequence number
    $data['seq_num'] = (int)substr($tmp,$pos+1);
    
    return $data;
  }
  /* --------------------------------------------------------------------
   * Old style lookup
   */
  public function getIdsForOrgSeason($params)
  {
    $search = array
    (
      'unit_id'        => $params['org_id'],
      'reg_year_id'    => $params['cal_year'] - 2000,
      'season_type_id' => $params['season_type_id'],
    );
    $rows = $this->tablePhyTeam->query(array('phy_team_id' => 'id'),$search);
    return $rows;
  }
  public function getForSeason($params)
  {
    $search = array
    (
      'reg_year_id'    => $params['cal_year'] - 2000,
      'season_type_id' => $params['season_type_id'],
    );
    $cols = array('phy_team_id' => 'id','unit_id' => 'org_id');
    $rows = $this->tablePhyTeam->query($cols,$search);
    return $rows;
  }
  public function getForEaysoId($id)
  {
    $search = array
    (
      'eayso_id'    => $id,
    );
    $rows = $this->tablePhyTeam->query(NULL,$search);
    if (count($rows) != 1) return NULL;
    return $rows[0];
  }
  /* --------------------------------------------------------------------
   * This works through the schedule team to link project
   */
  protected $cacheIds = array();

  protected $cacheProjectKeyIds = array();

  public function getIdForProjectKey($pid,$key)
  {
    if (!$pid || !$key) return 0;
    
    if (isset($this->cacheProjectKeyIds[$pid][$key])) return $this->cacheProjectKeyIds[$pid][$key];

    $search = $this->parseKey($key);
    $search['project_id'] = $pid;

    $sql = <<<EOT
SELECT phy_team.phy_team_id AS id

FROM osso2007.project_item AS project_item

LEFT JOIN osso2007.phy_team AS phy_team ON
  phy_team.phy_team_id = project_item.item_id AND
  project_item.type_id    = 2                 AND
  project_item.project_id = :project_id

WHERE
  phy_team.unit_id          = :org_id AND
  phy_team.division_id      = :div_id AND
  phy_team.division_seq_num = :seq_num;
EOT;

    $rows = $this->context->db->fetchRows($sql,$search);

    if (isset($rows[0])) $id = $rows[0]['id'];
    else                 $id = 0;

    $this->cacheProjectKeyIds[$pid][$key] = $id;
    return $id;
  }
  public function getIdForKey($pid,$key)
  {
    if (isset($this->cacheIds[$pid][$key])) return $this->cacheIds[$pid][$key];

    $search = $this->parseKey($key);
    $search['project_id'] = $pid;

    $sql = <<<EOT
SELECT phy_team.phy_team_id AS id
FROM   osso2007.sch_team AS sch_team
LEFT JOIN osso2007.phy_team AS phy_team ON phy_team.phy_team_id = sch_team.phy_team_id
WHERE
  sch_team.project_id       = :project_id AND
  phy_team.unit_id          = :org_id     AND
  phy_team.division_id      = :div_id     AND
  phy_team.division_seq_num = :seq_num;
EOT;

    $rows = $this->context->db->fetchRows($sql,$search);

    if (isset($rows[0])) $id = $rows[0]['id'];
    else                 $id = 0;

    $this->cacheIds[$pid][$key] = $id;
    return $id;
  }
  public function getRowForKey($pid,$key)
  {
    $rows = $this->tableSchTeam->query('*',array('project_id' => $pid, 'desc_short' => $key));

    if (isset($rows[0])) $row = $rows[0];
    else                 $row = null;
    
    return $row;
  }
  public function addTeam($data)
  {
    $result = new Cerad_Repo_RepoResult();

    // Must have a project
    if (!isset($data['project_id']) || !$data['project_id'])
    {
      $result->error = 'Missing or invalid project id';
      return $result;
    }
    $pid = $data['project_id'];

    // Must have a key
    if (!isset($data['key']) || !$data['key'])
    {
      $result->error = 'Missing or invalid key';
      return $result;
    }
    $key = $data['key'];

    return $result;
  }
  public function insert($data)
  {
    return $this->tablePhyTeam->insert($data);
  }
  public function update($data)
  {
    return $this->tablePhyTeam->update($data);
  }
}
?>
