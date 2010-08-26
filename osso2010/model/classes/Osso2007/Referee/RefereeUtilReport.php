<?php
class Osso2007_Referee_RefereeUtilReport
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    
  }
  public function process($params)
  {
    $directEvent   = new Osso2007_Event_EventDirect    ($this->context);
    $directReferee = new Osso2007_Referee_RefereeDirect($this->context);

    $result   = $directReferee->getReferees($params);
    $referees = $result->records;

    $result   = $directEvent->getDistinctIds($params);
    $eventIds = $result->records;

    $result = $directEvent->getPersons(array('event_id' => $eventIds));
    $events = $result->records;

    // Referees are indexed by person_id, count their games
    foreach($events AS $event)
    {
      foreach($event['persons'] AS $eventPerson)
      {
      $personId = $eventPerson['person_id'];
      $typeId   = $eventPerson['type_id'];
      switch($typeId)
      {
        case 10: // CR
          if (isset($referees[$personId]))
          {
            $referee = $referees[$personId];
            if (isset($referee['cr'])) $referee['cr']++;
            else                       $referee['cr'] = 1;
            $referees[$personId] = $referee;
          }
          break;

        case 11:
        case 12:
          if (isset($referees[$personId]))
          {
            $referee = $referees[$personId];
            if (isset($referee['ar'])) $referee['ar']++;
            else                       $referee['ar'] = 1;
            $referees[$personId] = $referee;
          }
          break;
      }
    }}
    $lines = array();
    $lines[] = 'Region,First Name,Nick Name,Last Name,AYSOID,Eayso Email,Cell Phone,Age,MYear,Ref Cert,SH Cert,CR Count,AR Count';

    foreach($referees AS $referee)
    {
      // Cerad_Debug::dump($referee); die();

      $personId = $referee['person_id'];

      $my  = 'MY' . $referee['eayso_reg_year'];
      $age = $referee['eayso_gender'] . substr($referee['eayso_dob'],0,4);

      if (isset($referee['cr'])) $cr = $referee['cr'];
      else                       $cr = 0;

      if (isset($referee['ar'])) $ar = $referee['ar'];
      else                       $ar = 0;

      $line = array
      (
        $referee['person_unit_desc'],
        $referee['person_fname'], $referee['person_nname'],$referee['person_lname'],
        $referee['person_aysoid'],
        $referee['eayso_email'], $referee['eayso_phone_cell'],
        $age,$my,
        $referee['cert_desc'],$referee['cert_sh_desc'],$cr,$ar
      );


      $lines[] = implode(',',$line);

    }
    return implode("\n",$lines);
  }
}

?>
