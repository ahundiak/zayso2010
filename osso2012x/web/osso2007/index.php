<?php
error_reporting(E_ALL);
$config = array
(
  'ws'        => '/home/impd/zayso2012/',
  'web_host'  => 'willow',
  'web_tools' => 'tools',  // Not being used but probably should be
);
require_once $config['ws'] . 'osso2010/apps/Osso2007/FrontEnd/FrontCont.php';
exit();
?>
