<?php
error_reporting(E_ALL);
$config = array
(
  'ws'        => '/home/ahundiak/zayso2012/',
  'web_host'  => 'buffy',
  'web_path'  => '/natgames/',
  'web_tools' => '/tools/',
);

if (isset($_SERVER['SERVER_NAME']))
{
  $serverName = $_SERVER['SERVER_NAME'];
  if (strstr($serverName,'zayso') !== FALSE)
  {
    $config['web_host'] = 'zayso';
  }
}

require_once $config['ws'] . 'osso2012/apps/NatGamesApp/FrontEnd/FrontContWeb.php';
exit();
?>
