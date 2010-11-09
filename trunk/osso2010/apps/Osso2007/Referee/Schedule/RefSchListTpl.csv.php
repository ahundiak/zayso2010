Game,Date,Time,Field,Home Team, Away Team,Center,AR1,AR2,4th
<?php
  $orgModel =  $this->context->models->UnitModel;

  foreach($this->events as $event) 
  {
    $datex = $event->date;
    $date = substr($datex,4,2) . '/' . substr($datex,6,2) . '/' . substr($datex,0,4);

    $time = $event->time; // 1330 or 800

    $hour   = $time / 100;
    $minute = $time - ($hour * 100);
    if ($hour < 12) $suffix = 'AM';
    else
    {
      $suffix = 'PM';
      $hour -= 12;
    }
    $time = sprintf('%02d:%02d %s',$hour,$minute,$suffix);

    $line = array();
    $line[] = $event->num;
    $line[] = $date;
    $line[] = $time;
    $line[] = $event->fieldDesc;

    foreach($event->teams as $team)
    {
      if ($event->scheduleTypeId != 3) $desc = $team->schedDesc;
      else
      {
        $desc = $team->schTeam->desc . ' ' . $team->phyTeam->unitKey . ' ' . $team->coachHead->lastName;
      }
      $line[] = $desc;
    }
    $eventPersonTypes = array(10,11,12,13);
    $eventPersons = $event->persons;
    foreach($eventPersonTypes as $eventPersonType)
    {
      if (!isset($eventPersons[$eventPersonType])) $line[] = '';
      else
      {
        $eventPerson = $eventPersons[$eventPersonType];
        $orgKey = $orgModel->getKey($eventPerson->personUnitId);

        $name = $orgKey . ' ' . $eventPerson->personFirstName . ' ' . $eventPerson->personLastName;
        $line[] = $name;
      }
    }
    echo implode(',',$line) . "\n";
  }
?>
