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
  protected function processTeam($team)
  {
    // Use global cache
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
