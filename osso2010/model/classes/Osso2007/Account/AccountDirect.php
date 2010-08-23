<?php
class Osso2007_Account_AccountDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'osso2007.account';
  protected $idName  = 'account_id';
  protected $dbName  = 'dbOsso2007';

  public function getCerts($params)
  {
    $result = $this->newResult();

    $accountId = $params['account_id'];

    $sql = <<<EOT
SELECT
  account.account_id   AS account_id,
  account.account_name AS account_name,
  account.account_user AS account_user,

  member.member_id   AS member_id,
  member.level       AS member_level,
  member.member_name AS member_name,

  member_unit.unit_id   AS member_unit_id,
  member_unit.desc_pick AS member_unit_desc,

  person.person_id   AS person_id,
  person.unit_id     AS person_unit_id,
  person.fname       AS person_fname,
  person.lname       AS person_lname,
  person.nname       AS person_nname,
  person.aysoid      AS person_aysoid,

  person_unit.unit_id   AS person_unit_id,
  person_unit.desc_pick AS person_unit_desc,

  reg_main.reg_num   AS eayso_aysoid,
  reg_main.reg_year  AS eayso_reg_year,
  reg_main.fname     AS eayso_fname,
  reg_main.nname     AS eayso_nname,
  reg_main.lname     AS eayso_lname,
  reg_main.dob       AS eayso_dob,
  reg_main.sex       AS eayso_gender,

  reg_cert.catx       AS cert_cat,
  reg_cert.typex      AS cert_type,
  reg_cert.datex      AS cert_date,
  reg_cert_type.desc1 AS cert_desc

FROM
 osso2007.account AS account

LEFT JOIN osso2007.member     AS member        ON member.account_id  = account.account_id
LEFT JOIN osso2007.person     AS person        ON person.person_id   = member.person_id
LEFT JOIN eayso.reg_main      AS reg_main      ON reg_main.reg_num   = person.aysoid
LEFT JOIN eayso.reg_cert      AS reg_cert      ON reg_cert.reg_num   = person.aysoid
LEFT JOIN eayso.reg_cert_type AS reg_cert_type ON reg_cert_type.id   = reg_cert.typex

LEFT JOIN osso2007.unit AS member_unit ON member_unit.unit_id = member.unit_id
LEFT JOIN osso2007.unit AS person_unit ON person_unit.unit_id = person.unit_id

WHERE account.account_id IN (:account_id)

ORDER BY member.level
EOT;
    $rows = $this->db->fetchRows($sql,array('account_id' => $accountId));

    $items = array();
    foreach($rows as $row)
    {
      $id = $row['member_id'];

      if (isset($items[$id])) $item = $items[$id];
      else
      {
        $item = $row;
        unset($item['cert_cat']);
        unset($item['cert_type']);
        unset($item['cert_date']);
        unset($item['cert_desc']);
        $item['certs'] = array();
      }
      $item['certs'][] = array
      (
        'cert_cat'  => $row['cert_cat'],
        'cert_type' => $row['cert_type'],
        'cert_date' => $row['cert_date'],
        'cert_desc' => $row['cert_desc'],
      );
      $items[$id] = $item;
    }
    $result->rows = $items;
    return $result;
  }
}
?>
