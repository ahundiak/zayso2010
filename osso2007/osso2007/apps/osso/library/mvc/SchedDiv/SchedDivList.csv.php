ID,Number,Date,Time,Field,HREG,HDIV,Home Team, Away Team,ADIV,AREG
<?php
  foreach($this->events as $event) 
  {
    $datex = $event->date;
    $date = substr($datex,4,2) . '/' . substr($datex,6,2) . '/' . substr($datex,0,4);
    
    $line = array();
    $line[] = $event->id;
    $line[] = $event->num;
    $line[] = $date;
    $line[] = $event->time;
    $line[] = $event->fieldDesc;

    $count = 1;
    foreach($event->teams as $team)
    {
        switch($count)
        {
            case 1:
                $line[] = $team->phyTeam->unitKey;
                $line[] = $team->divisionDesc;
                $line[] = $team->schedDesc;
                break;
        
            case 2:
                $line[] = $team->schedDesc;
                $line[] = $team->divisionDesc;
                $line[] = $team->phyTeam->unitKey;
                break;
        }
        $count++;
    }
    echo implode(',',$line) . "\n";
  }
?>
