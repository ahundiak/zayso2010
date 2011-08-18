Number,Date,Time,Field,Home Team, Away Team,HDIV,ADIV,HREG,AREG,AREA
<?php
  foreach($this->events as $event) 
  {
    $datex = $event->date;
    $date = substr($datex,4,2) . '/' . substr($datex,6,2) . '/' . substr($datex,0,4);
    
    $timex = $event->time;
    if (strlen($timex) == 3) $timex = '0' . $timex;
    if (strlen($timex) == 4) $time = substr($timex,0,2) . ':' . substr($timex,2,2);
    else                     $time = $event->time;
    
    $line = array();
  //$line[] = $event->id;
    $line[] = $event->num;
    $line[] = $date;
    $line[] = $time;
    $line[] = $event->fieldDesc;

    $homeTeam = $event->teamHome;
    $awayTeam = $event->teamAway;
    
    if ($homeTeam) $line[] = $homeTeam->schedDesc;
    else           $line[] = '';
    if ($awayTeam) $line[] = $awayTeam->schedDesc;
    else           $line[] = '';
    
    if ($homeTeam) $line[] = $homeTeam->divisionDesc;
    else           $line[] = '';
    if ($awayTeam) $line[] = $awayTeam->divisionDesc;
    else           $line[] = '';
    if ($homeTeam) $line[] = $homeTeam->phyTeam->unitKey;
    else           $line[] = '';
    if ($awayTeam) $line[] = $awayTeam->phyTeam->unitKey;
    else           $line[] = '';
    
    $area = false;
    if ($homeTeam && $awayTeam && ($homeTeam->unitKey != $awayTeam->unitKey)) $area = true;
    if ($area) $line[] = 'A';
    else       $line[] = '';
    
    echo implode(',',$line) . "\n";
  }
?>
