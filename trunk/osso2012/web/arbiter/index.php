<?php
error_reporting(E_ALL);
$config = array
(
  'ws'        => '/home/ahundiak/zayso2012/',
  'web_host'  => 'buffy',
  'web_path'  => '/arbiter/',
  'web_tools' => '/tools/',
);
require_once $config['ws'] . 'osso2012/apps/ArbiterApp/FrontEnd/FrontCont.php';
exit();
?>
