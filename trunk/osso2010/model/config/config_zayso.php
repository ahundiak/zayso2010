<?php
// Want to add some code to detect host to allow these to be run on web machines
// or other development machines
return array
(
  'ws'    => '/home/ahundiak/zayso2010/',
  'datax' => '/home/ahundiak/datax/',

  'dbParams' => array
  (
    'default' => array (
      'host'     => 'db.telavant.com',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 'xxx',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    ),
    'dbMain' => array (
      'host'     => '127.0.0.1',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 'osso2007',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    ),
    'dbOsso2007' => array (
      'dbname'   => 'osso2007',
    ),
    'dbOsso' => array (
      'dbname'   => 'osso',
    ),
    'dbEayso' => array (
      'dbname'   => 'eayso',
    ),
    'dbSession' => array (
      'dbname'   => 'session',
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
);
?>