Game,Date,Time,Field,Home Team, Away Team,Center,AR1,AR2,4th
<?php
  foreach($this->events as $event) 
  {
    $datex = $event->date;
    $date = substr($datex,4,2) . '/' . substr($datex,6,2) . '/' . substr($datex,0,4);
    
    $line = array();
    $line[] = $event->num;
    $line[] = $date;
    $line[] = $event->time;
    $line[] = $event->fieldDesc;

    foreach($event->teams as $team)
    {
      $line[] = $team->schedDesc;
    }
    $eventPersonTypes = array(10,11,12,13);
    $eventPersons = $event->persons;
    foreach($eventPersonTypes as $eventPersonType)
    {
      if (!isset($eventPersons[$eventPersonType])) $line[] = '';
      else
      {
        $eventPerson = $eventPersons[$eventPersonType];
        $name = $eventPerson->personFirstName . ' ' . $eventPerson->personLastName;
        $line[] = $name;
      }
    }
    echo implode(',',$line) . "\n";
  }
?>
