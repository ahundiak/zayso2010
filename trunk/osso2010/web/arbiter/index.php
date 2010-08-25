<?php
error_reporting(E_ALL);
$config = array
(
  'ws'        => '/home/ahundiak/zayso2010/',
  'db_host'   => '127.0.0.1',
  'web_host'  => 'buffy',
  'web_path'  => '/arbiter/',
  'web_tools' => '/tools/',
);
require_once $config['ws'] . 'osso2010/apps/Arbiter/FrontEnd/FrontCont.php';
exit();
?>
