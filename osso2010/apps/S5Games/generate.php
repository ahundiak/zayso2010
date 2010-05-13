<?php
ERROR_REPORTING(E_ALL);

define('APP_CONFIG_HOME','/home/ahundiak/zayso2010/');
define('APP_CONFIG_DATA','/home/ahundiak/zayso2010/datax/');

ini_set('include_path','.' .
        PATH_SEPARATOR . APP_CONFIG_HOME . 'Cerad/library' .
        PATH_SEPARATOR . APP_CONFIG_HOME . 'osso2010/apps'
       );

require_once 'Cerad/Loader.php';
Cerad_Loader::registerAutoload();

/*
$refAvail = new RefAvail();

$refAvail->importCSV(ZAYSO2010_CONFIG_DATA . 'RefAvail.csv');
$refAvail->exportCSV(ZAYSO2010_CONFIG_DATA . 'RefAvailx.csv');
*/

$metrics = new S5Games_Metrics_Metrics();
$metrics->import(APP_CONFIG_DATA . 'S5GamesSchedule2009.csv');

?>
