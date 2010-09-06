<?php
error_reporting(E_ALL);
$config = array
(
  'ws'        => '/home/ahundiak/zayso2010/',
  'web_name'  => 'local.osso2010.org', // No longer used
  'web_host'  => 'buffy',
  'web_path'  => '/osso2007/',         // No longer used
  'web_tools' => 'tools',              // Not being used
);
require_once $config['ws'] . 'osso2010/apps/Osso2007/FrontEnd/FrontCont.php';
exit();
?>
