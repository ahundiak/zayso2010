<?php
error_reporting(E_ALL);
$config = array
(
  'ws'        => '/home/ahundiak/zayso2012/',
  'web_host'  => 'buffy',
  'web_path'  => '/area5cf/',
  'web_tools' => '/tools/',

  'project_id' => 41,
);
require_once $config['ws'] . 'osso2012/apps/Area5CFApp/base/FrontContWeb.php';
exit();
?>
