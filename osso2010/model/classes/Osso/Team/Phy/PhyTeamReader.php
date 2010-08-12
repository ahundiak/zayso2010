<?php

class Osso_Team_Phy_PhyTeamReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'TeamDesignation' => 'teamDes',
  //'TeamKey'         => 'teamKey',
    'TeamID'          => 'teamId',

    'DivisionName'    => 'divName',
    'RegionNumber'    => 'region',

    'TeamCoachFName'      => 'headCoachFName',
    'TeamCoachLName'      => 'headCoachLName',
    'TeamAsstCoachFName'  => 'asstCoachFName',
    'TeamAsstCoachLName'  => 'asstCoachLName',
    'TeamParentFName'     => 'managerFName',
    'TeamParentLName'     => 'managerLName',

    'TeamName'            => 'teamName',
    'TeamColors'          => 'teamColors',
  );
}
?>
