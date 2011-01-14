<?php

class Osso2007_Org_OrgRepo
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
      case 'tableOrg': return $this->tableOrg = new Cerad_Repo_RepoTable($this->context->db,'osso2007.unit');
    }
  }
  protected $cacheIds = array();
  public function getIdForKey($key)
  {
    $num = (int)$key;
    if ($num) $key = sprintf('R%04u',$key);

    if (isset($this->cacheIds[$key])) return $this->cacheIds[$key];

    $rows = $this->tableOrg->query(array('unit_id'),array('keyx' => $key));

    if (isset($rows[0])) $id = $rows[0]['unit_id'];
    else                 $id = 0;

    $this->cacheIds[$key] = $id;
    return $id;
  }
  protected $cacheKeys = array();
  public function getKeyForId($id)
  {
    if (isset($this->cacheKeys[$id])) return $this->cacheKeys[$id];

    $rows = $this->tableOrg->query(array('keyx'),array('unit_id' => $id));

    if (isset($rows[0])) $key = $rows[0]['keyx'];
    else                 $key = 0;

    $this->cacheKeys[$id] = $key;
    return $key;
  }
  public function getPickList()
  {
    $sql = 'SELECT unit_id AS id, desc_pick AS desc1 FROM unit ORDER BY unit.keyx;';
    $rows = $this->context->db->fetchRows($sql);
    $list = array();
    foreach($rows as $row)
    {
      $list[$row['id']] = $row['desc1'];
    }
    return $list;
  }
}
?>
