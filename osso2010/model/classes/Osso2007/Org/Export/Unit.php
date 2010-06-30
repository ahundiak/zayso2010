<?php
/* 
 * Exports the unit out of osso2007
 */
class Org_Export_Unit
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
    $this->db = $this->context->dbOsso2007;
  }
  // Does not seem to work as expected
  // ob_start does not capture STDOUT
  // Don't know how to capture fputcsv and don't really want to write my own
  public function processBuffered($outFileName)
  {
    ob_start();
    $this->processToBuffer();
    $contents = ob_get_clean();

    $status = file_put_contents($outFileName,$contents);
    if ($status === FALSE) die("Could not write to: $outFileName\n");
  }
  public function process($outFileName)
  {
    $sql = <<<EOT
SELECT unit.*, unit_type.descx AS unit_type_desc FROM unit
LEFT JOIN unit_type ON unit_type.unit_type_id = unit.unit_type_id;
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
