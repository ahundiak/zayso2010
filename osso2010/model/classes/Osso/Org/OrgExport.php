<?php
/* 
 * Exports the unit out of osso2007
 */
class Org_Export_Org
{
  protected $context;
  protected $db;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->db = $this->context->dbOsso;
  }
  public function process($outFileName)
  {
    $sql = <<<EOT
SELECT org.*, org_type.keyx AS org_type_keyx FROM org
LEFT JOIN org_type ON org_type.id = org.org_type_id;
EOT;
    $rows = $this->db->fetchRows($sql);

    $fp = fopen($outFileName,'w');
    if (!$fp) return;

    $header = NULL;
    foreach($rows as $row)
    {
      if (!$header)
      {
        $header = array_keys($row);
        fputcsv($fp,$header);
      }
      fputcsv($fp,$row);
    }
    fclose($fp);
  }
}
?>
