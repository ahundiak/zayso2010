<?php

namespace AYSO\Team;

class TeamPlayerImportReader extends \Cerad\Reader\CSV
{
  protected $map = array
  (
    'Region #'          => 'region_id',
    'Region Name'       => 'region_name',
    'Team Designation'  => 'desig',
    'Team Name'         => 'name',
    'Team Color'        => 'colors',
    'DivisionName'      => 'division',

    'Team Coach First Name' => 'coach_head_fname',
    'Team Coach Last Name'  => 'coach_head_lname',
    'Team Coach AYSO ID'    => 'coach_head_aysoid',
    'Team Coach Phone'      => 'coach_head_phone_home',
    'Team Coach Cell Phone' => 'coach_head_phone_cell',
    'Team Coach Email'      => 'coach_head_email',

    'Asst. Team Coach First Name' => 'coach_asst_fname',
    'Asst. Team Coach Last Name'  => 'coach_asst_lname',
    'Asst. Team Coach AYSO ID'    => 'coach_asst_aysoid',
    'Asst. Team Coach Phone'      => 'coach_asst_phone_home',
    'Asst. Team Coach Cell Phone' => 'coach_asst_phone_cell',
    'Asst. Team Coach Email'      => 'coach_asst_email',

    'Team Parent First Name' => 'manager_fname',
    'Team Parent Last Name'  => 'manager_lname',
    'Team Parent AYSOID'     => 'manager_aysoid',
    'Team Parent Phone'      => 'manager_phone_home',
    'Team Parent Cell Phone' => 'manager_phone_cell',
    'Team Parent Email'      => 'manager_email',

    'Player AYSO ID'     => 'player_aysoid',
    'JerseyNumber'       => 'jersey_number',
    'Player First Name'  => 'player_fname',
    'Player Last Name'   => 'player_lname',
    'Player Home Phone'  => 'player_phone_home',
    'Player DOB'         => 'player_dob',
  );
}
?>
