<?php
class Osso_Person_Reg_PersonRegDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'person_reg';
  protected $ignoreDupKey = true;

  /* ===============================================
   * TODOx This needs to go away
   */
  public function insertPersonRegOrg($params)
  {
    $fields = array('person_reg_id','org_id');
    foreach($fields as $field) $data[$field] = $params[$field];
    
    $sql = <<<EOT
INSERT INTO person_reg_org (person_reg_id,org_id)

VALUES(:person_reg_id,:org_id)

ON DUPLICATE KEY UPDATE org_id = org_id;

EOT;
    $count = $this->db->execute($sql,$data);
    return array('success' => true);
  }
}
?>
