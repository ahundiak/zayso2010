<?php
class Osso2007_Schedule_SchReaderMA extends Cerad_Reader_XML2003
{
  protected $map = array
  (
    'Date'      => 'date',
    'Time'      => 'time',
    'Field'     => 'field',
    'Away Team' => 'away',
    'Home Team' => 'home',
  );
  public $workSheetNames = array('U10G','U10B','U12G','U12B','U14G','U14B','U16_19');
}
?>
