<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Osso2007_Person_PersonDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'osso2007.person';
  protected $idName  = 'person_id';

  public function getCerts($params)
  {
    $result = $this->newResult();

    $personId = $params['person_id'];
    if (is_array($personId)) $personId = implode(',',$personId);

    if (!$personId) return array();
    
    $sql = <<<EOT
SELECT
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

  reg_cert.catx      AS cert_cat,
  reg_cert.typex     AS cert_type,
  reg_cert.datex     AS cert_date

FROM
 osso2007.person AS person

LEFT JOIN eayso.reg_main  AS reg_main ON reg_main.reg_num = person.aysoid
LEFT JOIN eayso.reg_cert  AS reg_cert ON reg_cert.reg_num = person.aysoid

LEFT JOIN osso2007.unit AS person_unit ON person_unit.unit_id = person.unit_id

WHERE person.person_id IN ($personId)

ORDER BY person.lname,person.fname
EOT;
    $rows = $this->db->fetchRows($sql);

    $items = array();
    foreach($rows as $row)
    {
      $id = $row['person_id'];

      if (isset($items[$id])) $item = $items[$id];
      else
      {
        $item = $row;
        unset($item['cert_cat']);
        unset($item['cert_type']);
        unset($item['cert_date']);
        $item['certs'] = array();
      }
      $item['certs'][] = array
      (
        'cert_cat'  => $row['cert_cat'],
        'cert_type' => $row['cert_type'],
        'cert_date' => $row['cert_date'],
      );
      $items[$id] = $item;
    }
    $result->rows = $items;
    return $result;
  }
}
?>
