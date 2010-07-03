<?php
class Eayso_Reg_Cert_RegCertReader extends Cerad_Reader_CSV
{
 protected $map = array
  (
    'Region'    => 'region',
    'AYSO ID'   => 'reg_num',
    'MY'        => 'reg_year',

    'LastName'      => 'lname',
    'FirstName'     => 'fname',
    'Gender'        => 'sex',
    'Email'         => 'email',
    'Homephone'     => 'phone_home',
    'BusinessPhone' => 'phone_work',

    'CertificationDesc'  => 'certDesc',
    'Certification Date' => 'datex',
  );
}
?>
