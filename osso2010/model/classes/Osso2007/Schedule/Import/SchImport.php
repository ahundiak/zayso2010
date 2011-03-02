<?php
class Osso2007_Schedule_Import_SchImport extends Osso2007_Schedule_Import_SchImportBase
{
  protected $readerClassName = 'Osso2007_Schedule_Import_SchImportReader';

  public function addEvent($date,$time,$field,$fieldId,$homeTeam,$awayTeam,$eventClassId,$eventNum)
  {
    // Must have home team
    if (!$homeTeam) return 0;

    // Project Information
    $dataProject = $this->projectRow;

    $event = array
    (
      'unit_id'          => $homeTeam['unit_id'],

      'project_id'       => $dataProject['id'],
      'reg_year_id'      => $dataProject['cal_year'] - 2000,
      'season_type_id'   => $dataProject['season_type_id'],
      'schedule_type_id' => $dataProject['type_id'],

      'event_type_id'    => 1,
      'class_id'         => $eventClassId,
      'event_num'        => $eventNum,

      'event_date'       => $date,
      'event_time'       => $time,
      'event_duration'   => 60,
      'field_id'         => $fieldId,
      'status'           => 1,
      'point1'           => 1,
      'point2'           => 1,
      'teams'            => array(1 => $homeTeam, 2 => $awayTeam)
    );
    $result = $this->repoEvent->save($event);

    return;
  }
  protected $teamMap = array(
    '160-U12C01-Malek'         => 'R0160U12C01',
    '160-U12C02-Agrinzoni'     => 'R0160U12C02',
    '160-U12C03-Tichow'        => 'R0160U12C03',
    '160-U12C04-Buchanan'      => 'R0160U12C04',
    '160-U12C05-Hawke'         => 'R0160U12C05',
    '160-U12C06-Durodola'      => 'R0160U12C06',
    '160-U12G01-Holder'        => 'R0160U12G01',
    '160-U12G02-Henshaw'       => 'R0160U12G02',
    '160-U12G03-Earp'          => 'R0160U12G03',
    '160-U12G04-Phonthibsvads' => 'R0160U12G04',
    '160-U12G05-Verhage'       => 'R0160U12G05',

    '160-U10C01-Meehan'        => 'R0160U10C01',
    '160-U10C02-Burns'         => 'R0160U10C02',
    '160-U10C03-Cox'           => 'R0160U10C03',
    '160-U10C04-Morgan'        => 'R0160U10C04',
    '160-U10C05-Sharp'         => 'R0160U10C05',
    '160-U10C06-Carr'          => 'R0160U10C06',
    '160-U10G01-Allport'       => 'R0160U10C01',
    '160-U10G02-Davis'         => 'R0160U10G02',
    '160-U10G03-Firman'        => 'R0160U10G03',
    '160-U10G04-Graves'        => 'R0160U10G04',
    '160-U10G05-Lawson'        => 'R0160U10G05',
    '160-U10G06-Kross'         => 'R0160U10G06',
    '160-U10G07-Webster'       => 'R0160U10G07',

    '160-U8C01-Stinson'        => 'R0160U08C01',
    '160-U8C02-Sullivan'       => 'R0160U08C02',
    '160-U8C03-Schaefer'       => 'R0160U08C03',
    '160-U8C04-McCurry'        => 'R0160U08C04',
    '160-U8C05-Johnson'        => 'R0160U08C05',
    '160-U8C06-Williams'       => 'R0160U08C06',
    '160-U8C07-Fary'           => 'R0160U08C07',
    '160-U8C08-Moore'          => 'R0160U08C08',
    '160-U8C09-Summers'        => 'R0160U08C09',
    '160-U8G01-Waggoner'       => 'R0160U08G01',
    '160-U8G02-Dodson'         => 'R0160U08G02',
    '160-U8G03-Hall'           => 'R0160U08G03',
    '160-U8G04-Slatcher'       => 'R0160U08G04',
    '160-U8G05-Stanley'        => 'R0160U08G05',
    '160-U8G06-Mathews'        => 'R0160U08G06',
    '160-U8G07-Stone'          => 'R0160U08G07',

    '160-U6C01-Weaver'         => 'R0160U06C01',
    '160-U6C03-Connell'        => 'R0160U06C03',
    '160-U6C04-English'        => 'R0160U06C04',
    '160-U6C05-Damon'          => 'R0160U06C05',
    '160-U6C06-Jouglet'        => 'R0160U06C06',
    '160-U6C07-Cozelos'        => 'R0160U06C07',
    '160-U6C08-Masterson'      => 'R0160U06C08',
    '160-U6C09-Firman'         => 'R0160U06C09',
    '160-U6C10-Lindy'          => 'R0160U06C10',
    '160-U6C11-Hodge'          => 'R0160U06C11',

    '160-U5C01-Davis'          => 'R0160U05C01',
    '160-U5C02-Williams'       => 'R0160U05C02',
    '160-U5C03-Carlson'        => 'R0160U05C03',
    '160-U5C04-Henshaw'        => 'R0160U05C04',
    '160-U5C05-Roche'          => 'R0160U05C05',
    '160-U5C06-Duncan'         => 'R0160U05C06',
    '160-U5C07-Billions'       => 'R0160U05C07',
    '160-U5C08-Sutherland'     => 'R0160U05C08',
    '160-U5C09-Hawke'          => 'R0160U05C09',
    '160-U5C10-Howard'         => 'R0160U05C10',

  );
  protected function processTeam($team)
  {
    // Use global cache
    if (isset($this->teamMap[$team])) $team = $this->teamMap[$team];

    $row = $this->repoSchTeam->getRowForProjectKey($this->projectId,$team);
    if (!$row) die('Missing Team ' . $team);
    return $row;
  }
  public function processRowData($data)
  {
    $event = array();

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

    $eventClassId = $this->repoMisc->getEventClassIdForKey($data['type']);
    if (!$eventClassId) $eventClassId = 1;

    $eventNum = (int)$data['number'];
    if (!$eventNum)
    {
      $eventNum = $this->repoProject->getNextEventNumber($this->projectId);
    }
    $this->addEvent($date,$time,$field,$fieldId,$homeTeam,$awayTeam,$eventClassId,$eventNum);

    // printf("Game %s %s %s %s %s\n",$date,$time,$fieldId,$homeTeam['sch_team_id'],$awayTeam['sch_team_id']);

    return;
  }
  public function processRowDatax($data)
  {
    Cerad_Debug::dump($data);
    printf("Project: %s\n",$this->projectRow['desc1']);
    die();
  }
  // Needs to be a parameter array
  public function process($params)
  {
    // Need project info
    $pid = $params['project_id'];

    $row = $this->context->repos->project->getRowForId($pid);
    if (!$row) return;

    $this->projectId  = $pid;
    $this->projectRow = $row;

    parent::process($params);
  }
}
?>
