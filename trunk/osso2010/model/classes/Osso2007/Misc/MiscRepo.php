<?php
class Osso2007_Misc_MiscRepo
{
  protected $context;
  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function __get($name)
  {
    // Allows access to constants via a instance
    $constName = "self::$name";
    if (defined($constName)) return constant($constName);

    return parent::__get($name);
  }

  /* ===================================================================
   * Season Type Stuff
   */
  const SEASON_TYPE_FALL   = 1;
  const SEASON_TYPE_WINTER = 2;
  const SEASON_TYPE_SPRING = 3;
  const SEASON_TYPE_SUMMER = 4;

  protected $pickListSeasonType = array
  (
    self::SEASON_TYPE_FALL   => 'Fall',
    self::SEASON_TYPE_WINTER => 'Winter',
    self::SEASON_TYPE_SPRING => 'Spring',
    self::SEASON_TYPE_SUMMER => 'Summer',
  );
  function getSeasonTypePickList() { return $this->pickListSeasonType; }

  function getSeasonTypeDesc($typeId)
  {
    if (isset($this->pickListSeasonType[$typeId])) return $this->pickListSeasonType[$typeId];
    return NULL;
  }

  /* ===================================================================
   * Schedule Type Stuff
   *
   */
  const SCHEDULE_TYPE_REGULAR_SEASON    = 1;
  const SCHEDULE_TYPE_TOURNAMENT_REGION = 2;
  const SCHEDULE_TYPE_TOURNAMENT_AREA   = 3;
  const SCHEDULE_TYPE_TOURNAMENT_STATE  = 4;

  protected $pickListScheduleType = array
  (
    self::SCHEDULE_TYPE_REGULAR_SEASON    => 'Regular Season',
    self::SCHEDULE_TYPE_TOURNAMENT_REGION => 'Tourn-Region',
    self::SCHEDULE_TYPE_TOURNAMENT_AREA   => 'Tourn-Area',
    self::SCHEDULE_TYPE_TOURNAMENT_STATE  => 'Tourn-State',
  );
  public function getScheduleTypePickList() { return $this->pickListScheduleType; }

  function getScheduleTypeDesc($typeId)
  {
    if (isset($this->pickListScheduleType[$typeId])) return $this->pickListScheduleType[$typeId];
    return NULL;
  }
  /* ===================================================================
   * Event Class Stuff
   */
  const EVENT_CLASS_RG = 1;
  const EVENT_CLASS_PP = 2;
  const EVENT_CLASS_QF = 3;
  const EVENT_CLASS_SF = 4;
  const EVENT_CLASS_F  = 5;
  const EVENT_CLASS_CM = 6;

  protected $pickListEventClass = array
  (
    self::EVENT_CLASS_RG => 'RG - Regular Game',
    self::EVENT_CLASS_PP => 'PP - Pool Play',
    self::EVENT_CLASS_QF => 'QF - Quarter Final',
    self::EVENT_CLASS_SF => 'SF - Semi Final',
    self::EVENT_CLASS_F  => 'F  - Final',
    self::EVENT_CLASS_CM => 'CM - Consolation Match',
  );
  protected $mapEventClassKey = array
  (
    'RG' => self::EVENT_CLASS_RG,
    'PP' => self::EVENT_CLASS_PP,
    'QF' => self::EVENT_CLASS_QF,
    'SF' => self::EVENT_CLASS_SF,
    'F'  => self::EVENT_CLASS_F,
    'CM' => self::EVENT_CLASS_CM,
  );
  public function getEventClassPickList() { return $this->pickListEventClass; }
  public function getEventClassIdForKey($key)
  {
    if (isset($this->mapEventClassKey[$key])) return $this->mapEventClassKey[$key];
    return 0;
  }
  /* ===================================================================
   * Event Type Stuff
   */
  const EVENT_TYPE_GAME      = 1;
  const EVENT_TYPE_PRACTICE  = 2;
  const EVENT_TYPE_SCRIMMAGE = 3;
  const EVENT_TYPE_JAMBOREE  = 4;
  const EVENT_TYPE_UPS       = 5;
  const EVENT_TYPE_TRYOUTS   = 6;
  const EVENT_TYPE_OTHER     = 9;

  protected $pickListEventType = array
  (
    self::EVENT_TYPE_GAME      => 'Game',
    self::EVENT_TYPE_PRACTICE  => 'Practice',
    self::EVENT_TYPE_SCRIMMAGE => 'Scrimmage',
    self::EVENT_TYPE_JAMBOREE  => 'Jamboree',
    self::EVENT_TYPE_UPS       => 'Unifieds',
    self::EVENT_TYPE_TRYOUTS   => 'Tryouts',
    self::EVENT_TYPE_OTHER     => 'Other',
  );
  public function getEventTypePickList() { return $this->pickListEventType; }

  function getEventTypeDesc($typeId)
  {
    if (isset($this->pickListEventType[$typeId])) return $this->pickListEventType[$typeId];
    return NULL;
  }

  /* ===================================================================
   * Event Team Type Stuff
   */
  const EVENT_TEAM_TYPE_HOME  = 1;
  const EVENT_TEAM_TYPE_AWAY  = 2;
  const EVENT_TEAM_TYPE_AWAY3 = 3;
  const EVENT_TEAM_TYPE_AWAY4 = 4;

  protected $pickListEventTeamType = array
  (
    self::EVENT_TEAM_TYPE_HOME  => 'Home',
    self::EVENT_TEAM_TYPE_AWAY  => 'Away',
    self::EVENT_TEAM_TYPE_AWAY3 => 'Away #3',
    self::EVENT_TEAM_TYPE_AWAY4 => 'Away #4',
  );
  public function getEventTeamTypePickList() { return $this->pickListEventTeamType; }

  function getEventTeamTypeDesc($typeId)
  {
    if (isset($this->pickListEventTeamType[$typeId])) return $this->pickListEventTeamType[$typeId];
    return NULL;
  }
  function getEventTeamTypeIds($home = FALSE, $away = FALSE)
  {
    $ids = array();

    if ($home) $ids[] = self::EVENT_TEAM_TYPE_HOME;

    if ($away)
    {
      $ids[] = self::EVENT_TEAM_TYPE_AWAY;
      $ids[] = self::EVENT_TEAM_TYPE_AWAY3;
      $ids[] = self::EVENT_TEAM_TYPE_AWAY4;
    }
    if (count($ids) < 1) return NULL;
    return $ids;
  }
  /* ===================================================================
   * Event Processing
   */
  protected $pickListEventProcessState = array
  (
    1 => 'Not yet processed',
    3 => 'Processed, points awarded',
    4 => 'Processed, no points awarded',
  );
  public function getEventProcessStatePickList() { return $this->pickListEventProcessState; }
  
  /* ===================================================================
   * Date Stuff
   */
  function getToday()
  {
    $date = time();
    return date('Ymd',$date);
  }
  function getNextSunday()
  {
    $date = time();
    $secondsPerDay = 60 * 60 * 24;

    if (date('w',$date) == 0) $date += $secondsPerDay;

    // Go to sunday
    while(date('w',$date) != 0) $date += $secondsPerDay;

    // Skip a week
    $date += ($secondsPerDay * 7);

    return date('Ymd',$date);
  }
  protected $pickListYear = array
  (
    2012 => 2012,
    2011 => 2011,
    2010 => 2010,
    2009 => 2009,
    2008 => 2008,
    2007 => 2007,
    2006 => 2006,
    2005 => 2005,
    2004 => 2004,
    2003 => 2003,
    2002 => 2002,
    2001 => 2001,
  );
  public function getYearPickList() { return $this->pickListYear; }

  function getMonthPickList()
  {
    return array
    (
       1 => 'Jan',
       2 => 'Feb',
       3 => 'Mar',
       4 => 'Apr',
       5 => 'May',
       6 => 'Jun',
       7 => 'Jul',
       8 => 'Aug',
       9 => 'Sep',
      10 => 'Oct',
      11 => 'Nov',
      12 => 'Dec',
    );
  }
  function getDayPickList()
  {
    return array
    (
       1 =>  1,  2 =>  2,  3 =>  3,  4 =>  4,  5 =>  5,
       6 =>  6,  7 =>  7,  8 =>  8,  9 =>  9, 10 => 10,
      11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15,
      16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20,
      21 => 21, 22 => 22, 23 => 23, 24 => 24, 25 => 25,
      26 => 26, 27 => 27, 28 => 28, 29 => 29, 30 => 30,
      31 => 31,
    );
  }
  function getHourPickList()
  {
    return array
    (
      '06' => ' 6:00 AM',
      '07' => ' 7:00 AM',
      '08' => ' 8:00 AM',
      '09' => ' 9:00 AM',
      '10' => '10:00 AM',
      '11' => '11:00 AM',
      '12' => '12:00 PM Noon',
      '13' => ' 1:00 PM',
      '14' => ' 2:00 PM',
      '15' => ' 3:00 PM',
      '16' => ' 4:00 PM',
      '17' => ' 5:00 PM',
      '18' => ' 6:00 PM',
      '19' => ' 7:00 PM',
      '20' => ' 8:00 PM',
      '21' => ' 9:00 PM',
      'BN' => 'BYE No Game',
      'BW' => 'BYE Want Game',
      'TB' => 'TBD',
      '22' => '10:00 PM',
      '23' => '11:00 PM',
      '00' => '12:00 AM Mid',
      '01' => ' 1:00 AM',
      '02' => ' 2:00 AM',
      '03' => ' 3:00 AM',
      '04' => ' 4:00 AM',
      '05' => ' 5:00 AM',
    );
  }
  function getMinutePickList()
  {
    return array
    (
       0 =>  0,
      15 => 15,
      30 => 30,
      45 => 45,
       5 =>  5,
      10 => 10,
      20 => 20,
      25 => 25,
      35 => 35,
      40 => 40,
      50 => 50,
      55 => 55,
    );
  }
  function getDurationPickList()
  {
    return array
    (
      60 =>  60,
      75 =>  75,
      90 =>  90,
     120 => 120,
    );
  }
}
?>
