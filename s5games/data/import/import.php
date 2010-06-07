<?php
error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2009/');
define('MYAPP_CONFIG_DATA','E:/section/');

class ImportZayso
{
    protected $db = null;
    
    public function getDb()
    {
        if (!$this->db) {
            $dbParams = array (
                'host'     => '127.0.0.1',
                'username' => 'impd',
                'password' => 'impd894',
                'dbname'   => 's5games',
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
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 's5games/library'      
        );
        require_once 'Cerad/Loader.php';
        Cerad_Loader::registerAutoload();
        
        $path = MYAPP_CONFIG_DATA;

        $import = new ImportGames($this, $path . 'schedule/Schedule20090612.xml');
        echo "Imported {$import->count} games\n";
    }
}

$import = new ImportZayso();
$import->process();
echo "zayso import complete\n";
?>
