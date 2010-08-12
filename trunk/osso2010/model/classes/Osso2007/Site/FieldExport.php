<?php
class Osso2007_Site_FieldExport extends Cerad_Export
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
    $sql  = 'SELECT * FROM field ORDER BY unit_id,field_site_id,descx';

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
