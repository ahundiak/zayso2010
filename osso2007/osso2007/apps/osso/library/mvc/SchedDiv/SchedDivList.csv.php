Game,Date,Time,Field,Home Team, Away Team
<?php
  foreach($this->events as $event) 
  {
    $datex = $event->date;
    $date = substr($datex,4,2) . '/' . substr($datex,6,2) . '/' . substr($datex,0,4);
    
    $line = array();
    $line[] = $event->id;
    $line[] = $date;
    $line[] = $event->time;
    $line[] = $event->fieldDesc;

    foreach($event->teams as $team)
    {
      $line[] = $team->schedDesc;
    }
    echo implode(',',$line) . "\n";
  }
?>
