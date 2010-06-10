<?php
error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_HOME','/home/ahundiak/zayso2010/');
define('MYAPP_CONFIG_DATA','/home/ahundiak/datax/');

class Import
{
  protected $db = null;
    
  public function getDb()
  {
    if (!$this->db)
    {
      $dbParams = array
      (
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
      PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'Cerad/library'
    );
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
        
    $vols  = new ImportEaysoVolReg ($this, MYAPP_CONFIG_DATA . 'EaysoSec5Vols2010_20100609.csv');
    echo "Eayso Vol Import 2010 {$vols->countInsert} {$vols->countUpdate}\n";

    $vols  = new ImportEaysoVolReg ($this, MYAPP_CONFIG_DATA . 'EaysoSec5Vols2009_20100609.csv');
    echo "Eayso Vol Import 2009 {$vols->countInsert} {$vols->countUpdate}\n";

    $vols  = new ImportEaysoVolReg ($this, MYAPP_CONFIG_DATA . 'EaysoSec5Vols2008_20100609.csv');
    echo "Eayso Vol Import 2008 {$vols->countInsert} {$vols->countUpdate}\n";

//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationCoach20080211.xml');  
//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationReferee20080211.xml');  
//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationManagement20080211.xml');  
//      $certs = new ImportEaysoVolCert($this, $path . 'volunteers/VolunteerCertificationInstructor20080211.xml'); 
    }
}
$import = new Import();
$import->process();
?>
