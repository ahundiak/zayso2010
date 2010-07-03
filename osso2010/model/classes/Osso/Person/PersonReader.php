<?php
class Osso_Person_PersonReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'id' => 'id',

    'region' => 'region',
    'aysoid' => 'aysoid',

    'lname'  => 'lname',
    'fname'  => 'fname',
    'nname'  => 'nname',
    'mname'  => 'mname',

    'phone_home' => 'phone_home',
    'phone_work' => 'phone_work',
    'phone_cell' => 'phone_cell',
    'email_home' => 'email_home',
    'email_work' => 'email_work',

    'member'  => 'member',
    'coach'   => 'coach',
    'referee' => 'referee',

  );

}
?>
