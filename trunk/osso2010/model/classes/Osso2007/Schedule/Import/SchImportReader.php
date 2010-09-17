<?php
// Date	Time	Away Coach	Away Team	Field	Home Team	Home Coach	Notes
class Osso2007_Schedule_Import_SchImportReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'Date'      => 'date',
    'Time'      => 'time',
    'Field'     => 'field',
    'Away Team' => 'away',
    'Home Team' => 'home',
    'Number'    => 'number',
    'Type'      => 'type',
  );
}
?>
