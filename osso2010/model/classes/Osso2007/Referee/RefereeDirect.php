<?php
class Osso2007_Referee_RefereeDirect extends Osso_Base_BaseDirect
{
  public function getReferees($search)
  {
    $db     = $this->db;
    $result = $this->newResult();
    
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

  reg_cert.catx       AS cert_cat,
  reg_cert.typex      AS cert_type,
  reg_cert.datex      AS cert_date,
  reg_cert_type.desc3 AS cert_desc

FROM
 osso2007.person AS person

LEFT JOIN eayso.reg_main      AS reg_main      ON reg_main.reg_num = person.aysoid
LEFT JOIN eayso.reg_cert      AS reg_cert      ON reg_cert.reg_num = reg_main.reg_num
LEFT JOIN eayso.reg_cert_type AS reg_cert_type ON reg_cert_type.id = reg_cert.typex

LEFT JOIN osso2007.unit AS person_unit ON person_unit.unit_id = person.unit_id

WHERE
    reg_main.reg_year >= 2009 AND
    reg_cert.catx      = 200  AND

WHEREX

ORDER BY person.lname,person.fname

EOT;

    // Process search parameters
    $wheres = array();
    if (isset($search['person_id']))
    {
      $personId = $db->quote($search['person_id']);
      if ($personId)
      {
        $wheres[] = "person.person_id IN ($personId)";
      }
    }
    if (isset($search['unit_id']))
    {
      $id = $db->quote($search['unit_id']);
      if ($id)
      {
        $wheres[] = "person_unit.unit_id IN ($id)";
      }
    }
    if (count($wheres)) $wherex = implode(' AND ',$wheres);
    else                $wherex = '';
    
    $sql = str_replace('WHEREX',$wherex,$sql);

    // Make the query
    $rows = $this->db->fetchRows($sql);

    $items = array();
    foreach($rows as $row)
    {
      $id = $row['person_id'];

      if (isset($items[$id])) $item = $items[$id];
      else                    $item = $row;
      
      $items[$id] = $item;
    }
    $result->rows = $items;
    return $result;
  }
  public function getRefereePickList($search)
  {
    $result = $this->getReferees($search);
    {
      $items = array();
      foreach($result->rows AS $row)
      {
        $name = $row['person_lname'] . ', ' . $row['person_fname'];
        $items[$row['person_id']] = $name;
      }
    }
    return $items;
  }
}
?>
