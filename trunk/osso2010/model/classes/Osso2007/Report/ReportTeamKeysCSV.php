<?php
class Osso2007_Report_ReportTeamKeysCSV extends Osso2007_Report_ReportTeamBase
{
  protected $context = NULL;

  public function process($params)
  {
    $lines = array();
    
    $lines[] = $this->genHeaderLine();

    $data = $this->queryTeams();
    
    foreach($this->rows as $row)
    {
      $divId  = $row['divId'];
      $divKey = $row['divKey'];

      $index = 0;
      $haveTeams = TRUE;
      while($haveTeams)
      {
        $haveTeams = FALSE;

        if ($index) $line = '';
        else
        {
          $lines[] = '';
          $line = $row['name'];
        }
        foreach($this->cols as $col)
        {
          if ($col['skip']) continue;

          $regionId   = $col['regionId'];
          $regionKeys = explode(' ',$col['name']);
          $regionKey  = $regionKeys[0];

          if ($index == 0)
          {
            $teamKey = sprintf('%s-%s-00 TBD',$regionKey,$divKey);
            $line .= ',' . $teamKey;
            $haveTeams = TRUE;
          }
          else
          {
            if (isset($data[$regionId][$divId][$index-1])) $team = $data[$regionId][$divId][$index-1];
            else                                           $team = null;
            if (!$team) $line .= ',';
            else
            {
              $teamKey = sprintf('%s-%s-%02u %s',$regionKey,$divKey,$team['num'],$team['head_coach_lname']);
              $line .= ',' . $teamKey;
              $haveTeams = TRUE;
            }
          }
        }
        if ($haveTeams) $lines[] = $line;
        $index++;
      }
    }
    return implode("\n",$lines);
  }
}
?>
