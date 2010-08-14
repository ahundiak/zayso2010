<?php
class Osso2007_Report_ReportTeamSummaryCSV extends Osso2007_Report_ReportTeamBase
{
  protected $context = NULL;

  public function process($params)
  {
    $lines = array();
    
    $lines[] = $this->genHeaderLine();

    $data = $this->queryTeams();
    
    foreach($this->rows as $row)
    {
      $line   = $row['name'];
      $divId  = $row['divId'];
      $total = 0;
      foreach($this->cols as $col)
      {
        if ($col['skip']) continue;
        $regionId = $col['regionId'];

        if (isset($data[$regionId][$divId])) $cnt = count($data[$regionId][$divId]);
        else                                 $cnt = 0;
        $line  .= ',' . $cnt;
        $total += $cnt;
      }
      $line .= ',' . $total;
      $lines[] = $line;
    }
    return implode("\n",$lines);
  }
}
?>
