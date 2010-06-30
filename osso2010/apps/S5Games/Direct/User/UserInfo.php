<?php
class Direct_User_UserInfo extends ExtJS_Direct_Base
{
  function read($params)
  {
    $sql = <<<EOT
SELECT
  member.member_id     AS member_id,
  member.person_id     AS person_id,
  member.unit_id       AS region_id,
  member.account_id    AS account_id,
  member.member_name   AS member_fname,
  account.account_name AS member_lname,
  account.account_user AS account_name,
  person.fname         AS person_fname,
  person.lname         AS person_lname,
  person.aysoid        AS person_aysoid,
  unit.keyx            AS region_key,
  unit.desc_pick       AS region_desc
  
FROM member

LEFT JOIN account ON account.account_id = member.account_id
LEFT JOIN person  ON person.person_id   = member.person_id
LEFT JOIN unit    ON unit.unit_id       = member.unit_id

WHERE member.member_id = :member_id;

EOT;

    // Looky memberId or default to Guest if none supplied
    if (isset($params['member_id'])) $memberId = $params['member_id'];
    else                             $memberId = $this->context->getUserDefaultId();

    // Allow for some predefined member Ids
    if ($memberId > 0)
    {
      // Handle 0 as a guest? Or add guest account
      $db   = $this->context->db;
      $rows = $db->fetchRows($sql,array('member_id' => $memberId));
    }
    else $rows = array();

    // If not found, treat as guest, not sure if this is best or not but try it
    if (count($rows) < 1)
    {
      $row = array
      (
        'member_id'  => $memberId,
        'account_id' => 0,
        'person_id'  => 0,
        'region_id'  => 0,
      );
    }
    else $row = $rows[0];
		
    // Clean up null stuff if person is not linked?
    if ($row['member_id'] < 1)
    {
      $userInfo = $this->context->getUserInfo($row['member_id']);
      $row['member_fname'] = $userInfo['name'];
      $row['member_lname'] = '';
    }
    if (!$row['account_id'])
    {
      $row['account_id']   = 0;
      $row['account_name'] = '';
    }
    if (!$row['person_id'])
    {
      $row['person_id']     = 0;
      $row['person_fname']  = '';
      $row['person_lname']  = '';
      $row['person_aysoid'] = '';
    }
    if (!$row['region_id'])
    {
      $row['region_id']   = 0;
      $row['region_key']  = '';
      $row['region_desc'] = '';
    }
    // Build the member name
    $name  = null;
    $fname = $row['member_fname'];
    $lname = $row['member_lname'];
    if ($fname && $lname) $name = $fname . ' ' . $lname;
    else
    {
      if ($fname) $name = $fname;
      if ($lname) $name = $lname;
    }
    if (!$name) $name = 'Guest';
    $row['member_name'] = $name;
    
    return $this->wrapResults(array($row));
  }
}