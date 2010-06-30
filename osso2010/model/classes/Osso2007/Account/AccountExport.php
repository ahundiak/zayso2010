<?php
class Osso2007_Account_AccountExport extends Cerad_Export
{
  protected $dbName = 'dbOsso2007';

  public function getResultMessage()
  {
    $className = get_class($this);
    $file = basename($this->outFileName);
    $msg = sprintf('%s %s, Total: %u',$className,$file,$this->countTotal);
    return $msg;
  }
  protected function processContent($fp)
  {
    $db = $this->db;
    $sql  = <<<EOT
SELECT
  account.account_id   AS account_id,
  account.account_user AS account_name,
  account.account_pass AS account_pass,

  member.member_id     AS account_person_id,
  member.person_id     AS person_id,
  member.unit_id       AS org_id,
  member.level         AS level,

  member.member_name   AS fname,
  account.account_name AS lname,
  account.email        AS email

FROM account
LEFT JOIN member ON member.account_id = account.account_id
ORDER BY account_id,member.level,member.member_id;
EOT;


    $rows = $db->fetchRows($sql);

    $header = NULL;
    foreach($rows as $row)
    {
      if (!$header)
      {
        $header = array_keys($row);
        fputcsv($fp,$header);
      }
      fputcsv($fp,$row);
      $this->countTotal++;
    }
  }
}

?>
