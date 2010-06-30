<?php
error_reporting(E_ALL | E_STRICT);

session_start();

date_default_timezone_set('US/Central');

define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2009/');

class WebIndex
{
    static function run()
    {
        ini_set('include_path','.' .      
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 's5games/library'      
        );
        require_once 'Cerad/Loader.php';
        Cerad_Loader::registerAutoload();
    }
}
WebIndex::run();

$dbParams = array (
    'host'     => '127.0.0.1',
    'username' => 'impd',
    'password' => 'impd894',
    'dbname'   => 's5games',
    'dbtype'   => 'mysql',
    'adapter'  => 'pdo_mysql'
);  

?>