<?php

error_reporting(E_ALL | E_STRICT);

define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2009/');


class Test
{
	protected $db = NULL;
		
	function __construct()
	{
        // Startup stuff
        ini_set('include_path','.' . 
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 's5games/library'      
        );
        require_once 'Cerad/Loader.php';
        Cerad_Loader::registerAutoload();
	}	
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
	function process()
	{
		$aysoid = '99437977';
		
		$eaysoRepo = new Zayso_Repo_Eayso();
		$vol = $eaysoRepo->findForAysoid($aysoid);
		echo "{$vol->getFullName()}\n";
		
		$params = array(
//			'region' => 894,
			'lname' => 'Hund%',
//			'fname' => 'Arthur',
		);
		$vols = $eaysoRepo->search($params);
		foreach($vols as $vol)
		{
			echo "{$vol->getAysoid()} {$vol->getFullName()}\n";
		}
		// Two database test
		$db = $this->getDb();
        $person = $db->find('person','aysoid',$aysoid);
    	
        //Cerad_Debug::dump($person);
		
	}
}
$test = new Test();
$test->process();
?>