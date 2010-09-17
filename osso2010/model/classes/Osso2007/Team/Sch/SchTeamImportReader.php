<?php

class Osso2007_Team_Sch_SchTeamImportReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'Region'        => 'org',
    'Div'           => 'div',
    'Schedule Team' => 'sch_team',
    'Physical Team' => 'phy_team',
  );
}
?>
