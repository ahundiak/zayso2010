<?php
class Osso2007_Report_ReportTeamKeysCSV extends Osso2007_Report_ReportTeamBase
{
  protected $context = NULL;

  public function process($params)
  {
    $lines = array();
    
    $lines[] = $this->genHeaderLine();

    $data = $this->queryTeams($params);
    
    // for each division
    foreach($this->rows as $row)
    {
        $divs = $row['divs'];
        $divTBD = $row['divTBD'];
        
      //$divId  = $row['divId'];
      //$divKey = $row['divKey'];

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
          
          if (isset($col['regionId'])) $regionId = $col['regionId'];
          else                         $regionId = 0;
          
          $regionKeys = explode(' ',$col['name']);
          $regionKey  = $regionKeys[0];

          if ($index == 0)
          {
            $teamKey = sprintf('%s-%s-00 TBD',$regionKey,$divTBD);
            $line .= ',' . $teamKey;
            $haveTeams = TRUE;
          }
          else
          {
            $foundTeam = false;
            foreach($divs as $divId => $divKey) 
            {
                if (isset($data[$regionId][$divId][$index-1])) $team = $data[$regionId][$divId][$index-1];
                else                                           $team = null;
                
                if ($team)
                {
                    $teamKey = sprintf('%s-%s-%02u %s',$regionKey,$divKey,$team['num'],$team['head_coach_lname']);
                    $line .= ',' . $teamKey;
                    $haveTeams = TRUE;
                    $foundTeam = true;
                }
            }
            if (!$foundTeam) $line .= ',';
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
