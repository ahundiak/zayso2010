<?php
class Osso_Org_OrgDirect extends Osso_Base_BaseDirect
{
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
    $items = array();
    foreach($rows as $row)
    {
      $items[$row['org_id']] = $row['org_desc'];
    }
    return array(
      'success' => true,
      'count'   => count($items),
      'records' => $items,
    );
  }
}
?>
