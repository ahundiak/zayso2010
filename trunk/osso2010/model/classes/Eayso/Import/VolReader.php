<?php
class Eayso_Import_VolReader extends Cerad_Reader_CSV
{
  protected $map = array(
    'Region'         => 'region',
    'AYSOID'         => 'person_reg_num',
    'LastName'       => 'lname',
    'FirstName'      => 'fname',
    'NickName'       => 'nname',
    'MI'             => 'mname',
    'suffix'         => 'sname',
    'DOB'            => 'dob',
    'Gender'         => 'gender',
    'HomePhone'      => 'phone_home',
    'WorkPhone'      => 'phone_work',
    'WorkPhoneExt'   => 'phone_work_ext',
    'CellPhone'      => 'phone_cell',
    'Email'          => 'email',
    'Membershipyear' => 'person_reg_year',
  );
}
?>
