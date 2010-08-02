<?php
class Osso2007_Account_AccountReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'account_id'        => 'account_id',
    'account_name'      => 'account_name',
    'account_pass'      => 'account_pass',
    'account_person_id' => 'account_person_id',
    'person_id'         => 'person_id',
    'org_id'            => 'org_id',
    'level'             => 'level',
    'fname'             => 'fname',
    'lname'             => 'lname',
    'email'             => 'email',
  );
}
?>
