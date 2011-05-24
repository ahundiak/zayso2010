<?php
error_reporting(E_ALL);
$config = array
(
  'ws'        => '/home/ahundiak/zayso2012/',
  'web_host'  => 'buffy',
  'web_path'  => '/s5games/',
  'web_tools' => '/tools/',
);
require_once $config['ws'] . 'osso2012/apps/S5GamesApp/FrontEnd/FrontContWeb.php';
exit();
?>
