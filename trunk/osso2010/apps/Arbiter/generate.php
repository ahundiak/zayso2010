<?php
ERROR_REPORTING(E_ALL);

define('ZAYSO2010_CONFIG_HOME','/home/impd/zayso2010/');
define('ZAYSO2010_CONFIG_DATA','/home/impd/zayso2010/datax/');

ini_set('include_path','.:' .
        ZAYSO2010_CONFIG_HOME . 'Cerad/library');

require_once 'Cerad/Loader.php';
Cerad_Loader::registerAutoload();

require_once ZAYSO2010_CONFIG_HOME . 'osso2010/apps/Arbiter/RefAvail/RefAvail.php';

$refAvail = new RefAvail();

$refAvail->importCSV(ZAYSO2010_CONFIG_DATA . 'RefAvail.csv');
$refAvail->exportCSV(ZAYSO2010_CONFIG_DATA . 'RefAvailx.csv');

Cerad_Debug::dump('Just because');

?>
