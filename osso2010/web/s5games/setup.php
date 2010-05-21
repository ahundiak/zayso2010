<?php
/* Standard way to get things going */
error_reporting(E_ALL | E_STRICT);

define('APP_CONFIG_HOME','/home/ahundiak/zayso2010/');
define('APP_CONFIG_FILE','config.php');

ini_set('include_path','.' .      
  PATH_SEPARATOR . APP_CONFIG_HOME . 'osso2010/apps/S5Games' .
  PATH_SEPARATOR . APP_CONFIG_HOME . 'osso2010x/data/classes' .   
  PATH_SEPARATOR . APP_CONFIG_HOME . 'Cerad/library'       
);
        
require_once 'Cerad/Loader.php';
Cerad_Loader::registerAutoload();
?>
