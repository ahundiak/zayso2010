<?php
error_reporting(E_ALL);

define('MYAPP_CONFIG_HOME','/home/ahundiak/zayso2010/');

class TestConfig
{
    static $dbParams;
    
    static function run()
    {
        ini_set('include_path','.' .       
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'Cerad/library' .
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'PHPUnit-3.4.11'
        );
        require_once 'Cerad/Loader.php';
        Cerad_Loader::registerAutoload();
        
        $dbParams = array (
            'host'     => '127.0.0.1',
            'username' => 'impd',
            'password' => 'impd894',
            'dbname'   => 'osso2007',
            'dbtype'   => 'mysql',
            'adapter'  => 'pdo_mysql'
        );
        self::$dbParams = $dbParams;
    }
}
TestConfig::run();
?>
