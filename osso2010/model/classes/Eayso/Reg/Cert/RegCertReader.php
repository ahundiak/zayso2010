<?php
class Eayso_Reg_Cert_RegCertReader extends Cerad_Reader_CSV
{
 protected $map = array
  (
    'RegionNumber'       => 'region',
    'AYSOID'             => 'reg_num',
    'MembershipTermName' => 'reg_year',

    'LastName'      => 'lname',
    'FirstName'     => 'fname',
    'Gender'        => 'sex',
    'Email'         => 'email',
    'Homephone'     => 'phone_home',
    'BusinessPhone' => 'phone_work',

    'CertificationDesc'  => 'certDesc',
    'CertDate'           => 'datex',
  );
}
?>
