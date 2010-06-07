<?php
error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2009/');

class ImportEayso
{
    protected $db = null;
    
    public function getDb()
    {
        if (!$this->db) {
            $dbParams = array (
                'host'     => '127.0.0.1',
                'username' => 'impd',
                'password' => 'impd894',
                'dbname'   => 'eayso',
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
        
        $path = MYAPP_CONFIG_HOME . 's5games/eayso/data/';
        $path = './data/';
        $vols  = new ImportEaysoVolReg ($this, $path . 'VolsAll20090604.xml');
//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationCoach20080211.xml');  
//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationReferee20080211.xml');  
//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationManagement20080211.xml');  
//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationInstructor20080211.xml'); 

//      $vols  = new ImportEaysoVolPreAdult($this, $path . 'volunteers/PreRegVolunteerDataAdultAll20080327.xml');
//      $vols  = new ImportEaysoVolPreYouth($this, $path . 'volunteers/PreRegVolunteerDataYouthAll20080328.xml');
    }
}

$import = new ImportEayso();
$import->process();
echo "eayso import complete\n";
?>
