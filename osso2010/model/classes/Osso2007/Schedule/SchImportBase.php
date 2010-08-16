<?php
class Osso2007_Schedule_SchImportBase extends Cerad_Import
{
  protected $readerClassName = '';

  protected $regions = array();
  protected $fields  = array();
  protected $teams   = array();

  protected $year    = 10;
  protected $seaType = 1;
  protected $schType = 1;
  
  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso2007;
    $this->ts = $this->context->getTimeStamp();

    $this->directOrg       = new Osso_Org_OrgDirect             ($this->context);
    $this->directDiv       = new Osso2007_Div_DivDirect         ($this->context);
    $this->directField     = new Osso2007_Site_FieldDirect      ($this->context);
    $this->directEvent     = new Osso2007_Event_EventDirect     ($this->context);
    $this->directEventTeam = new Osso2007_Event_EventTeamDirect ($this->context);
    $this->directPhyTeam   = new Osso2007_Team_Phy_PhyTeamDirect($this->context);
    $this->directSchTeam   = new Osso2007_Team_Sch_SchTeamDirect($this->context);
  }

  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
      $class, $file,
      $count->total,$count->inserted,$count->updated);
    return $msg;
  }
  public function addEvent($date,$time,$fieldId,$homeTeam,$awayTeam)
  {
    // Need at least one team
    if (!$homeTeam && !$awayTeam) return 0;

    if ($homeTeam) $adminTeam = $homeTeam;
    else           $adminTeam = $awayTeam;

    $adminTeam['sch_team_id'] = 0;

    // See if event exists
    $search = array
    (
      'event_date' => $date,
      'event_time' => $time,
      'field_id'   => $fieldId,
    );
    $result = $this->directEvent->fetchRows($search);
    if ($result->rowCount == 0)
    {
      // Insert one
      $data = array
      (
        'unit_id'          => $adminTeam['unit_id'],
        'reg_year_id'      => $this->year,
        'season_type_id'   => $this->seaType,
        'schedule_type_id' => $this->schType,
        'event_type_id'    => 1,
        'event_date'       => $date,
        'event_time'       => $time,
        'field_id'         => $fieldId,
        'status'           => 1,
        'point1'           => 1,
        'point2'           => 1,
      );
      if ($this->allowUpdates)
      {
        $result  = $this->directEvent->insert($data);
        $eventId = $result->id;
      }
      else $eventId = 0;
      $this->count->inserted++;
    }
    else $eventId = $result->rows[0]['event_id'];

    // Process home team
    $result = $this->directEventTeam->fetchRows((array('event_id' => $eventId,'event_team_type_id' => 1)));
    if ($result->rowCount)
    {
      // Check that the team is not different
      $eventTeam = $result->rows[0];
      if ($eventTeam['team_id'] != $homeTeam['sch_team_id'])
      {
        // Deal with team change
        printf("Event home team mismatch %s %s\n",$date,$time);
        die();
      }
    }
    else
    {
      // Always need a home team
      if ($homeTeam) $addTeam = $homeTeam;
      else           $addTeam = $adminTeam;

      $data = array
      (
        'event_id'           => $eventId,
        'event_team_type_id' => 1,
        'team_id'            => $addTeam['sch_team_id'],
        'reg_year_id'        => $addTeam['reg_year_id'],
        'unit_id'            => $addTeam['unit_id'],
        'division_id'        => $addTeam['division_id'],
        'score' => 0,
      );
      if ($this->allowUpdates) $this->directEventTeam->insert($data);
    }
    // Process away team
    $result = $this->directEventTeam->fetchRows((array('event_id' => $eventId,'event_team_type_id' => 2)));
    if ($result->rowCount)
    {
      // Check that the team is not different
      $eventTeam = $result->rows[0];
      if ($eventTeam['team_id'] != $awayTeam['sch_team_id'])
      {
        die('event away team mismatch');
      }
    }
    else
    {
      // Only add if have one
      if (!$awayTeam) return $eventId;
      $addTeam = $awayTeam;

      $data = array
      (
        'event_id'           => $eventId,
        'event_team_type_id' => 2,
        'team_id'            => $addTeam['sch_team_id'],
        'reg_year_id'        => $addTeam['reg_year_id'],
        'unit_id'            => $addTeam['unit_id'],
        'division_id'        => $addTeam['division_id'],
        'score' => 0,
      );
      if ($this->allowUpdates) $this->directEventTeam->insert($data);
    }
    return $eventId;
  }
  public function processRowData($data)
  {   
    // Extract
    $date     = $data['date'];
    $time     = $data['time'];
    $field    = $data['field'];
    $homeTeam = $data['home'];
    $awayTeam = $data['away'];

    if (!$date)     return;
    if (!$time)     return;
    if (!$field)    return;
    if (!$homeTeam) return;

    $this->count->total++;

    $date    = $this->processDate ($date);
    $time    = $this->processTime ($time);
    $fieldId = $this->processField($field);

    $homeTeam = $this->processTeam($homeTeam);
    $awayTeam = $this->processTeam($awayTeam);

    $this->addEvent($date,$time,$fieldId,$homeTeam,$awayTeam);
    
    // printf("Game %s %s %s %s %s\n",$date,$time,$fieldId,$homeTeam['sch_team_id'],$awayTeam['sch_team_id']);

    return;
  }
  protected function processTeam($team)
  {
    // Check Cache
    if (!$team) return 0;
    if (isset($this->teams[$team])) return $this->teams[$team];

    if ($team === '???') return NULL;
    if ($team === 'Bye') return NULL;

    $team = str_replace('-','',$team);

    $tmps = explode('U',$team);
    if (count($tmps) != 2) die('Invalid team: ' . $team);

    $regionId = $this->getRegion($tmps[0]);
    if (!$regionId) die('Invalid region ' . $team);

    $divKey = 'U' . substr($tmps[1],0,3);
    $result = $this->directDiv->getForKey($divKey);
    if (!$result->row)
    {
      printf("Invalid division %s %s\n",$team,$divKey); die();
    }
    $divId = $result->row['id'];
    $num = (int)substr($tmps[1],3,2);
    if ($num == 0)
    {
      // TBD
      $teamData = array
      (
        'sch_team_id'    => 0,
        'unit_id'        => $regionId,
        'reg_year_id'    => $this->year,
        'season_type_id' => $this->seaType,
        'division_id'    => $divId,
      );
      $this->teams[$team] = $teamData;
      return $teamData;
    }
    $search = array
    (
      'unit_id'          => $regionId,
      'reg_year_id'      => $this->year,
      'season_type_id'   => $this->seaType,
      'division_id'      => $divId,
      'division_seq_num' => $num,
    );
    $result = $this->directPhyTeam->fetchRow($search);
    if (!$result->row)
    {
      printf("Invalid phy team %s\n",$team); die();
    }
    $phyTeamId = $result->row['phy_team_id'];

    $search = array
    (
      'phy_team_id'      => $phyTeamId,
      'schedule_type_id' => $this->schType,
    );
    $result = $this->directSchTeam->fetchRow($search);
    if (!$result->row)
    {
      printf("Invalid sch team %s\n",$team); die();
    }
    $teamData = $result->row;
    
    $this->teams[$team] = $teamData;
    return $this->teams[$team];
  }
  protected function processField($field)
  {
    // Check Cache
    if (!$field) return 0;
    if (isset($this->fields[$field])) return $this->fields[$field];

    $result = $this->directField->getField($field);

    if ($result->row)
    {
      $this->fields[$field] = $result->row['field_id'];
      return $this->fields[$field];
    }
    printf("*** Field '%s'\n",$field); die();
    return 0;
  }
  protected function processDate($date)
  {
    if (strlen($date) == 23) return $this->getDateFromExcelFormat($date);
    die($date);
    $dates = explode('/',$date);
    if (count($dates) != 3) die('Invalid date ' . $date);
    $date = sprintf('%04u%02u%02u',$dates[2],$dates[0],$dates[1]);
    return $date;
  }
  protected function processTime($time)
  {
    if (strlen($time) == 23) return $this->getTimeFromExcelFormat($time);
    die($time);
    $times = explode(' ',$time);
    if (count($times) != 2) die('Invalid time ' . $time);

    switch($times[1])
    {
      case 'AM': $offset =  0; break;
      case 'PM': $offset = 12; break;
      default:
        die('Invalid time ' . $time);
    }
    $times = explode(':',$times[0]);
    if (count($times) != 2) die('Invalid time ' . $time);
    $hours   = (int)$times[0] + $offset;
    if ($hours == 24) $hours = 12;
    $minutes = (int)$times[1];
    $time = sprintf('%02u%02u',$hours,$minutes);
    return $time;
    
  }
  protected function getRegion($region)
  {

    if (!$region) return NULL;

    // Need to find the org_id for the region
    if (!isset($this->regions[$region]))
    {
      $result = $this->directOrg->getOrgForKey($region);

      $org = $result->row;
      if (!$org)
      {
        echo("*** Could not find region: $region\n"); // Some regions are revoked
        $this->regions[$region] = 0;
        return 0;
      }
      
      $this->regions[$region] = $org['id'];
    }
    return $this->regions[$region];
  }
}
?>
