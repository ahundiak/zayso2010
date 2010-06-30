<?php
/* Standard way to get things going */
error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2010/');
define('MYAPP_CONFIG_FILE','config.php');

ini_set('include_path','.' .      
  PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'osso2010x/apps/zayso' .   
  PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'osso2010x/data/classes' .   
  PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'Cerad/library'       
);
        
require_once 'Cerad/Loader.php';
Cerad_Loader::registerAutoload();
?>
