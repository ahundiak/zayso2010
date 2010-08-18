<?php
class Osso_Org_OrgDirect extends Osso_Base_BaseDirect
{
  public function insertOrgPerson($params)
  {

  }
  public function getOrgForKey($keyx)
  {

    $result = new Cerad_Direct_Result();
    
    if (is_array($keyx)) $keyx = $keyx['keyx'];

    $num = (int)$keyx;
    if ($num) $keyx = sprintf('R%04u',$keyx);

    $search = array('keyx' => $keyx);
    $row = $this->db->find('org','keyx',$search);

    $result->row = $row;

    return $result;
  }
  public function getOrgGroupOrgPicklist($params)
  {
    $search = NULL;

    $sql = 'SELECT org_id,org_desc FROM org_group_org_view WHERE ';

    if (isset($params['id'])) {
      $sql .= 'org_group_id = :id ';
      $search = array('id' => $params['id']);
    }
    if (isset($params['keyx']))
    {
      $sql .= 'org_group_key = :key ';
      $search = array('key' => $params['keyx']);
    }
    $sql .= 'ORDER BY org_key;';

    if ($search == NULL) return array('success' => false);

    $rows = $this->db->fetchRows($sql,$search);
    $items  = array();
    $itemsx = array();
    foreach($rows as $row)
    {
      $items[$row['org_id']] = $row['org_desc'];
      $itemsx[] = array('id' => $row['org_id'], 'value' => $row['org_desc']);
    }
    return array(
      'success' => true,
      'count'   => count($itemsx),
      'records' => $itemsx,
    );
  }
}
?>
