<?php
class Eayso_Reg_Main_RegMainReader extends Cerad_Reader_CSV
{
  protected $map = array(
    'Region'         => 'region',
    'AYSOID'         => 'reg_num',
    'LastName'       => 'lname',
    'FirstName'      => 'fname',
    'NickName'       => 'nname',
    'MI'             => 'mname',
    'suffix'         => 'sname',
    'DOB'            => 'dob',
    'Gender'         => 'sex',
    'HomePhone'      => 'phone_home',
    'WorkPhone'      => 'phone_work',
    'WorkPhoneExt'   => 'phone_work_ext',
    'CellPhone'      => 'phone_cell',
    'Email'          => 'email',
    'Membershipyear' => 'reg_year',
  );
}
?>
