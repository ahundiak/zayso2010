<?php
class Eayso_Import_VolCertReader extends Cerad_Reader_CSV
{
 protected $map = array
  (
    'Region'    => 'region',
    'AYSO ID'   => 'person_reg_num',

    'LastName'      => 'lname',
    'FirstName'     => 'fname',
    'Gender'        => 'gender',
    'Email'         => 'email',
    'Homephone'     => 'phone_home',
    'BusinessPhone' => 'phone_work',
    'MY'            => 'person_reg_year',

    'CertificationDesc'  => 'certDesc',
    'Certification Date' => 'certDate',

  );
}
?>
