<?php
return array
(
  'dbs' => array
  (
    'dbMain' => array (
      'host'     => '127.0.0.1',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 'osso2007',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    ),
    'dbEayso' => array (
      'host'     => '127.0.0.1',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 'eayso',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    ),
    'dbS5Games' => array (
      'host'     => '127.0.0.1',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 's5games',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    ),
  ),
  'users' => array
  (
    'Guest'   => array('id' => -1, 'name' => 'Guest',  'pass' => ''),
    'Referee' => array('id' => -2, 'name' => 'Referee','pass' => 's5games'),
    'Admin'   => array('id' => -3, 'name' => 'Admin',  'pass' => 'admin5'),
  ),
  'userDefaultId' => -1,
);
?>