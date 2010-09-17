<?php

class Osso2007_Team_Sch_SchTeamRepo
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
      case 'tableSchTeam': return $this->tableSchTeam = new Cerad_Repo_RepoTable($this->context->db,'osso2007.sch_team');
    }
  }
  protected $cacheProjectKeyIds = array();
  public function getIdForProjectKey($pid,$key)
  {
    if (isset($this->cacheProjectKeyIds[$pid][$key])) return $this->cacheProjectKeyIds[$pid][$key];

    $rows = $this->tableSchTeam->query(array('sch_team_id'),array('project_id' => $pid, 'desc_short' => $key));

    if (isset($rows[0])) $id = (int) $rows[0]['sch_team_id'];
    else                 $id = 0;

    $this->cacheProjectKeyIds[$pid][$key] = $id;
    return $id;
  }
  protected $cacheProjectKeyRows = array();
  public function getRowForProjectKey($pid,$key)
  {
    if (isset($this->cacheProjectKeyRows[$pid][$key])) return $this->cacheProjectKeyRows[$pid][$key];

    $rows = $this->tableSchTeam->query('*',array('project_id' => $pid, 'desc_short' => $key));

    if (isset($rows[0])) $row = $rows[0];
    else                 $row = null;
    
    $this->cacheProjectKeyRows[$pid][$key] = $row;

    return $row;
  }
  public function insert($data)
  {
    return $this->tableSchTeam->insert($data);
  }
  public function update($data)
  {
    return $this->tableSchTeam->update($data);
  }
}
?>
