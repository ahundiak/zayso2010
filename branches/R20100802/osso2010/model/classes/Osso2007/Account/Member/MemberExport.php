<?php
class Osso2007_Account_Member_MemberExport extends Cerad_Export
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
    $sql  = 'SELECT * FROM member ORDER BY account_id,member_id;';
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
