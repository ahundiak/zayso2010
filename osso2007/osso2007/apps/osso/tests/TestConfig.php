<?php
error_reporting(E_ALL);

class TestConfig
{
    static $dbParams;
    
    static function run()
    {
        ini_set('include_path','.' . 
            PATH_SEPARATOR . '/ahundiak/ws2007/osso2007/data' .
            PATH_SEPARATOR . '/ahundiak/ws2007/osso2007/library' .     
            PATH_SEPARATOR . '/ahundiak/ws2007/library' .
            PATH_SEPARATOR . '/ahundiak/ws2007/ZendFramework-1.0.0/library'             
        );
        $projDir = '/ahundiak/ws2007/osso2007';
        
        $dbParams = array (
            'host'     => '127.0.0.1',
            'username' => 'impd',
            'password' => 'impd894',
            'dbname'   => 'osso2007',
            'dbtype'   => 'mysql',
            'adapter'  => 'pdo_mysql'
        );
        self::$dbParams = $dbParams;
        
        /* Loadin the Project and Application Context */
        require_once $projDir . '/config/ProjectContext.php';
        require_once                       'TestContext.php';
    }
}
TestConfig::run();
?>
