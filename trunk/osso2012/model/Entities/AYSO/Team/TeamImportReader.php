<?php

namespace AYSO\Team;

class TeamImportReader extends \Cerad\Reader\CSV
{
  protected $map = array
  (
    'TeamID'             => 'id',
    'TeamDesignation'    => 'desig',
    'TeamName'           => 'name',
    'DivisionName'       => 'division',
    'TeamColors'         => 'colors',
    'TeamCoachFName'     => 'coach_head_fname',
    'TeamCoachLName'     => 'coach_head_lname',
    'TeamCoachPhone'     => 'coach_head_phone',
    'TeamAsstCoachFName' => 'coach_asst_fname',
    'TeamAsstCoachLName' => 'coach_asst_lname',
    'TeamAsstCoachPhone' => 'coach_asst_phone',
    'TeamParentFName'    => 'manager_fname',
    'TeamParentLName'    => 'manager_lname',
    'TeamParentPhone'    => 'manager_phone',
    'RegionNumber'       => 'region_id',
    'RegionName'         => 'region_name',
  );
}
?>
