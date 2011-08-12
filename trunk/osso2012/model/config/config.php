<?php
/* ========================================================
 * Bootstrap correct config file
 */
$host = 'buffy';

if (isset($_SERVER['HOSTNAME']) && $_SERVER['HOSTNAME'] == 'locke.telavant.com') $host = 'zayso';

if (isset($_SERVER['SERVER_NAME']))
{
  $serverName = $_SERVER['SERVER_NAME'];
  if (strstr($serverName,'zayso') !== FALSE)
  {
    $host = 'zayso';
  }
  unset($serverName);
}
$config = require $host . '.php';
$config['web_host'] = $host;
unset($host);
return $config;
?>
