<?php
class Osso_Reg_Main_RegMainDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'reg_main';

  public function getForOrgName($orgId,$fname,$lname)
  {
    $result = $this->newResult();

    $sql = <<<EOT
SELECT
  reg_main.*
FROM
  reg_main
LEFT JOIN reg_org ON (reg_org.reg_type = reg_main.reg_type AND reg_org.reg_num = reg_main.reg_num)
WHERE
  reg_main.fname = :fname AND
  reg_main.lname = :lname AND
  reg_org.org_id = :org_id;
EOT;
    $search = array('org_id' => $orgId,'fname' => $fname, 'lname' => $lname);
    $result->rows = $this->db->fetchRows($sql,$search);
    return $result;

    $sql = <<<EOT
SELECT
  reg_main.id       AS id,
  reg_main.reg_type AS reg_type,
  reg_main.reg_num  AS reg_num,
  reg_main.reg_year AS reg_year,
  reg_main.fname    AS fname,
  reg_main.lname    AS lname,
  reg_org.org_id    AS org_id
FROM
  reg_main
LEFT JOIN reg_org ON (reg_org.reg_type = reg_main.reg_type AND reg_org.reg_num = reg_main.reg_num)
WHERE
  reg_main.fname = :fname AND
  reg_main.lname = :lname AND
  reg_org.org_id = :org_id;
EOT;
    
  }
}
?>
