<?php

namespace AYSO\Player;

class PlayerImportReader extends \Cerad\Reader\CSV
{
  protected $map = array
  (
    'Region Number' => 'region',
    'Division'      => 'division',
    'AYSO ID'       => 'id',
    'First Name'    => 'fname',
    'Middle Name'   => 'mname',
    'Last Name'     => 'lname',
    'Suffix'        => 'suffix',
    'AKA'           => 'nname',
    'Home Phone'    => 'phone_home',
    'Email'         => 'email',
    'Gender'        => 'gender',
    'DOB'           => 'dob',
    'Player Status' => 'status',

    'Jersey Size'   => 'jersey_size',
    'Jersey Number' => 'jersey_number',
  );
}
?>
