<?php
error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_HOME','/home/ahundiak/zayso2010/');
define('MYAPP_CONFIG_DATA','/home/ahundiak/datax/s5games/');

class Import
{
    protected $db = null;
    
    public function getDb()
    {
        if (!$this->db) {
            $dbParams = array (
                'host'     => '127.0.0.1',
                'username' => 'impd',
                'password' => 'impd894',
                'dbname'   => 's5games2010',
                'dbtype'   => 'mysql',
                'adapter'  => 'pdo_mysql'
            );
            $this->db = new Cerad_DatabaseAdapter($dbParams);  
        }
        return $this->db;
    }

    public function process()
    {
        // Startup stuff
        ini_set('include_path','.' . 
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'Cerad/library'
        );
        require_once 'Cerad/Loader.php';
        Cerad_Loader::registerAutoload();
        
        $path = MYAPP_CONFIG_DATA;

        $import = new ImportGames($this, $path . 'schedules/Schedule20100608.csv');
        echo "Imported {$import->count} games\n";
    }
}

$import = new Import();
$import->process();
?>
