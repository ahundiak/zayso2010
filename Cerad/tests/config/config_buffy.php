<?php
// Want to add some code to detect host to allow these to be run on web machines
// or other development machines
return array
(
  'ws'    => '/home/ahundiak/zayso2010/',
  'datax' => '/home/ahundiak/testx/',

  'dbParams' => array
  (
    'default' => array (
      'host'     => '127.0.0.1',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 'xxx',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    ),
    'dbTest'     => array ('dbname' => 'cerad_test'),
    'dbSession'  => array ('dbname' => 'session'),
    'dbOsso'     => array ('dbname' => 'osso'),
    'dbOsso2007' => array ('dbname' => 'osso2007'),
    'dbEayso'    => array ('dbname' => 'eayso'),
  ),
);
?>