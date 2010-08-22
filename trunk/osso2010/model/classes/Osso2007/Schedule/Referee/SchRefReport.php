<?php
class Osso2007_Schedule_Referee_SchRefReport
{
  protected $context = NULL;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}
  /*
object(SessionData)#28 (22) {
  ["eventTypeId"] => string(1) "0"
  ["seasonTypeId"] => string(1) "1"
  ["scheduleTypeId"] => string(1) "0"
  ["yearId"] => string(2) "10"
  ["unitId"] => string(1) "1"
  ["orderBy"] => string(1) "1"
  ["outputType"] => int(1)
  ["dateYear1"] => string(4) "2010"
  ["dateYear2"] => string(4) "2010"
  ["dateMonth1"] => string(2) "08"
  ["dateMonth2"] => string(2) "08"
  ["dateDay1"] => string(2) "17"
  ["dateDay2"] => string(2) "29"
  ["showAge1"] => string(2) "19"
  ["showAge2"] => string(2) "-1"
  ["showBoy"] => string(1) "1"
  ["showGirl"] => string(1) "1"
  ["showCoed"] => string(1) "1"
  ["showHome"] => string(1) "1"
  ["showAway"] => NULL
  ["point2"] => string(1) "0"
  ["show"] => string(1) "0"
} */
  public function process($data)
  {
    Cerad_Debug::dump($data); die();
  }
}
?>
