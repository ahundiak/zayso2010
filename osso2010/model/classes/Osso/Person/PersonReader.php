<?php
class Osso_Person_PersonReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'id' => 'id',

    'region' => 'region',
    'aysoid' => 'aysoid',
//  'year'   => 'year',

    'lname'  => 'lname',
    'fname'  => 'fname',
    'nname'  => 'nname',
    'mname'  => 'mname',

//  'sname'  => 'sname',
//  'DOB'            => 'dob',
//  'Gender'         => 'gender',

    'phone_home' => 'phone_home',
    'phone_work' => 'phone_work',
    'phone_cell' => 'phone_cell',
    'email_home' => 'email',
    'email_work' => 'email2',

    'member'  => 'member',
    'coach'   => 'coach',
    'referee' => 'referee',

  );

}
?>
