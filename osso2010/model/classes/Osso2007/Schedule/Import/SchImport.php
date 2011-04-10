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
    'R0160-U14C01-Tillery'  => 'R0160U14B01',
    'R0160-U14C02-Skinner'  => 'R0160U14B02',
    'R0160-U14C03-Mokhtari' => 'R0160U14B03',
    'R0160-U14C04-Dunning'  => 'R0160U14B04',
    'R0160-U14G01-Noller'   => 'R0160U14G01',
    'R0160-U14G02-Arriaga'  => 'R0160U14G02',

    'R0160-U12C01-Malek'         => 'R0160U12C01',
    'R0160-U12C02-Agrinzoni'     => 'R0160U12C02',
    'R0160-U12C03-Tichow'        => 'R0160U12C03',
    'R0160-U12C04-Buchanan'      => 'R0160U12C04',
    'R0160-U12C05-Hawke'         => 'R0160U12C05',
    'R0160-U12C06-Durodola'      => 'R0160U12C06',
    'R0160-U12G01-Holder'        => 'R0160U12G01',
    'R0160-U12G02-Henshaw'       => 'R0160U12G02',
    'R0160-U12G03-Earp'          => 'R0160U12G03',
    'R0160-U12G04-Phonthibsvads' => 'R0160U12G04',
    'R0160-U12G05-Verhage'       => 'R0160U12G05',

    'R0160-U10C01-Meehan'      => 'R0160U10C01',
    'R0160-U10C02-Burns'       => 'R0160U10C02',
    'R0160-U10C03-Cox'         => 'R0160U10C03',
    'R0160-U10C04-Morgan'      => 'R0160U10C04',
    'R0160-U10C05-Sharp'       => 'R0160U10C05',
    'R0160-U10C06-Carr'        => 'R0160U10C06',
    'R0160-U10G01-Allport'     => 'R0160U10G01',
    'R0160-U10G02-Davis'       => 'R0160U10G02',
    'R0160-U10G03-Firman'      => 'R0160U10G03',
    'R0160-U10G04-Graves'      => 'R0160U10G04',
    'R0160-U10G05-Lawson'      => 'R0160U10G05',
    'R0160-U10G06-Kross'       => 'R0160U10G06',
    'R0160-U10G07-Webster'     => 'R0160U10G07',

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

    'R0894-U10B01-Merritt'     => 'R0894U10B01',
    'R0894-U10B02-Stefadorous' => 'R0894U10B02',
    'R0894-U10B03-Griswell'    => 'R0894U10B03',
    'R0894-U10B04-Tillman'     => 'R0894U10B04',
    'R0894-U10B00-TBD'         => 'R0894U10B11',

    'R0894-U10C01-Richardson'  => 'R0894U10C01',
    'R0894-U10C02-Dutton'      => 'R0894U10C02',

    'R0894-U10G01-Rassmussen'  => 'R0894U10G01',
    'R0894-U10G02-Holderman'   => 'R0894U10G02',
    'R0894-U10G03-Weekley'     => 'R0894U10G03',

    'R0894-U12C01-Dollarhide'  => 'R0894U12C01',
    'R0894-U12C02-Quinn'       => 'R0894U12C02',
    'R0894-U12C03-Bayuga'      => 'R0894U12C03',
    'R0894-U12C04-Lashley'     => 'R0894U12C04',

    'R0894-U12G00-Guest'       => 'R0894U12G10',
    'R0894-U12GS11-Etzel'      => 'R0894U12G11',
    'R0894-U12GS12-Steely'     => 'R0894U12G12',

    'R0894-U14B01-McCoy'       => 'R0894U14B01',
    'R0894-U14B02-TBD'         => 'R0894U14B02',
    'R0894-U14G01-Harness'     => 'R0894U14G01',
    'R0894-U14G02-Bader'       => 'R0894U14G02',

    'R0498-U10B01-Dean'        => 'R0498U10B01',
    'R0498-U10B02-Cagle'       => 'R0498U10B02',
    'R0498-U10B03-Swartz'      => 'R0498U10B03',
    'R0498-U10B04-Ballard'     => 'R0498U10B04',
    'R0498-U10B05-Williams'    => 'R0498U10B05',
    'R0498-U10B06-Jackson'     => 'R0498U10B06',
    'R0498-U10B07-Fritz'       => 'R0498U10B07',
    'R0498-U10B08-Johnson'     => 'R0498U10B08',
    'R0498-U10B11S-Rami'       => 'R0498U10B11',
    'R0498-U10B-99-VIP'        => 'R0498U10B99',

    'R0498-U10G01-Coffey'      => 'R0498U10G01',
    'R0498-U10G02-Walker'      => 'R0498U10G02',
    'R0498-U10G03-Barrett'     => 'R0498U10G03',
    'R0498-U10G04-Songy'       => 'R0498U10G04',
    'R0498-U10G05-Benson'      => 'R0498U10G05',
    'R0498-U10G06-Zecher'      => 'R0498U10G06',
    'R0498-U10G07-King'        => 'R0498U10G07',
    'R0498-U10G08-Scherer'     => 'R0498U10G08',
    'R0498-U10G09-Brant'       => 'R0498U10G09',
    'R0498-U10G11S-Bierbauer'  => 'R0498U10G11',

    'R0498-U12C01-Rolf'        => 'R0498U12C01',
    'R0498-U12C02-Barrett'     => 'R0498U12C02',
    'R0498-U12C03-Tolleson'    => 'R0498U12C03',
    'R0498-U12C04-King'        => 'R0498U12C04',
    'R0498-U12C05-Waddell'     => 'R0498U12C05',
    'R0498-U12C06-Marden'      => 'R0498U12C06',
    'R0498-U12C07-Tomaino'     => 'R0498U12C07',
    'R0498-U12C08-Hall'        => 'R0498U12C08',
    'R0498-U12C09-Love'        => 'R0498U12C09',
    'R0498-U12C10-Belhadj'     => 'R0498U12C10',
    'R0498-U12C11-Prang'       => 'R0498U12C11',

    'R0498-U14C01-Rossetti'    => 'R0498U14C01',
    'R0498-U14C02-Patel'       => 'R0498U14C02',
    'R0498-U14C03-Gerlach'     => 'R0498U14C03',
    'R0498-U14C04-Walker'      => 'R0498U14C04',
    'R0498-U14C05-Draper'      => 'R0498U14C05',
    'R0498-U14C06-Worley'      => 'R0498U14C06',
    'R0498-U14C07-Cobb'        => 'R0498U14C07',
    'R0498-U14C08-Lozano'      => 'R0498U14C08',

    'R0498-U16C01 Jansen'      => 'R0498U16C01',
    'R0498-U16C02 Monnet'      => 'R0498U16C02',
    'R0498-U16C03 Sheffield'   => 'R0498U16C03',
    'R0498-U16C04 Steine'      => 'R0498U16C04',
    'R0498-U19C01-TBD'         => 'R0498U19C01',
    'R0498-U19C02-TBD'         => 'R0498U19C01',

    'R1174-U10C01-Waller'      => 'R1174U10C01',
    'R1174-U10C02-Richardson'  => 'R1174U10C02',
    'R1174-U10G01-Stewart'     => 'R1174U10G01',
    'R1174-U10G02-Berry'       => 'R1174U10G02',
    'R1174-U12C01-Moore'       => 'R1174U12C01',
    'R1174-U12C02-Hall'        => 'R1174U12C02',

    'R1174-08C01-Benson'  => 'R1174U08C01',
    'R1174-08C02-White'   => 'R1174U08C02',
    'R1174-08C03-Lay'     => 'R1174U08C03',
    'R1174-08C04-Freeman' => 'R1174U08C04',
    'R1174-08C05-Steel'   => 'R1174U08C05',
    'R1174-08C06-Glenn'   => 'R1174U08C06',

    'R0773-U10C01-Taylor'      => 'R0773U10C01',
    'R0773-U10C02-Dershem'     => 'R0773U10C02',
    'R0773-U10C03-Faulk'       => 'R0773U10C03',
    'R0773-U10C04-Long'        => 'R0773U10C04',
    'R0773-U12B01-Sexton'      => 'R0773U12C01',
    'R0773-U12B02-Evans'       => 'R0773U12C02',
    'R0773-U12G01-Golden'      => 'R0773U12C03',
    'R0773-U12G02-Parkman'     => 'R0773U12C04',

    'R0498-U08G01-TBD'        => 'R0498U08G01',
    'R0498-U08G02-TBD'        => 'R0498U08G02',
    'R0498-U08G03-TBD'        => 'R0498U08G03',
    'R0498-U08G04-TBD'        => 'R0498U08G04',
    'R0498-U08G05-TBD'        => 'R0498U08G05',
    'R0498-U08G06-TBD'        => 'R0498U08G06',
    'R0498-U08G07-TBD'        => 'R0498U08G07',
    'R0498-U08G08-TBD'        => 'R0498U08G08',
    'R0498-U08G09-TBD'        => 'R0498U08G09',

    'R0498-U08G10-TBD'        => 'R0498U08G10',
    'R0498-U08G11-TBD'        => 'R0498U08G11',
    'R0498-U08G12-TBD'        => 'R0498U08G12',
    'R0498-U08G13-TBD'        => 'R0498U08G13',
    'R0498-U08G14-TBD'        => 'R0498U08G14',
    'R0498-U08G15-TBD'        => 'R0498U08G15',
    'R0498-U08G16-TBD'        => 'R0498U08G16',

    'R0498-U08B01-TBD'        => 'R0498U08B01',
    'R0498-U08B02-TBD'        => 'R0498U08B02',
    'R0498-U08B03-TBD'        => 'R0498U08B03',
    'R0498-U08B04-TBD'        => 'R0498U08B04',
    'R0498-U08B05-TBD'        => 'R0498U08B05',
    'R0498-U08B06-TBD'        => 'R0498U08B06',
    'R0498-U08B07-TBD'        => 'R0498U08B07',
    'R0498-U08B08-TBD'        => 'R0498U08B08',
    'R0498-U08B09-TBD'        => 'R0498U08B09',
    'R0498-U08B10-TBD'        => 'R0498U08B10',
    'R0498-U08B11-TBD'        => 'R0498U08B11',
    'R0498-U08B12-TBD'        => 'R0498U08B12',
    'R0498-U08B13-TBD'        => 'R0498U08B13',
    'R0498-U08B14-TBD'        => 'R0498U08B14',

      'R0778-U12C01-Estes'  => 'R0778U12C01',
      'R0778-U14C01-Parker' => 'R0778U14C01',
  );
  protected function processTeam($team)
  {
    // Use global cache
    if (isset($this->teamMap[$team])) $team = $this->teamMap[$team];
    else die($team);
    
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
    $eventNum = (int)$data['number'];
   
    if (!$date)     return;
    if (!$time)     return;
    if (!$field)    return;
    if (!$homeTeam) return;
    if (!$eventNum) return;

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
