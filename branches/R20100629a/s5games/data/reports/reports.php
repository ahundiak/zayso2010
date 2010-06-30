<?php
error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_WS',  '/home/ahundiak/zayso2010/');
define('MYAPP_CONFIG_DATA','/home/ahundiak/datax/s5games/');

class Reports
{
  protected $dbx = null;
    
  public function getDb()
  {
    if (!$this->dbx)
    {
      $dbParams = array
      (
        'host'     => '127.0.0.1',
        'username' => 'impd',
        'password' => 'impd894',
        'dbname'   => 's5games2010',
        'dbtype'   => 'mysql',
        'adapter'  => 'pdo_mysql'
      );
      $this->dbx = new Cerad_DatabaseAdapter($dbParams);
    }
    return $this->dbx;
  }
  function __get($name)
  {
    switch($name)
    {
      case 'db': return $this->getDb(); break;
    }
    return NULL;
  }
  public function execute()
  {
    // Startup stuff
    ini_set('include_path','.' . 
      PATH_SEPARATOR . MYAPP_CONFIG_WS . 'Cerad/library'
    );
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
        
    $path = MYAPP_CONFIG_DATA;

    $report = new RefereeReport($this);
    $report->generatePhoneList();
  }
}

$reports = new Reports();
$reports->execute();
?>
