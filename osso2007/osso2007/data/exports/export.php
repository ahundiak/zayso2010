<?php
/* ----------------------------------------------
 * 26 Mar 2008
 * There is rather bewildering array of test configurations
 * Which were used for importing data
 * Going to try and simplify by eliminating the testing dependency
 */
define('MYAPP_CONFIG_HOME','/home/ahundiak/ws2008c/');

class ApplicationContext
{
    public $db  = NULL;
    public $pdo = NULL;
    public $dbParams = NULL;
        
    function __construct()
    {
        ProjectContext::setInstance($this);
    }
}
class Export
{
	protected $context = NULL;
	
	function __construct()
	{
        ini_set('include_path','.' . 
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'osso2007/data' .
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'osso2007/data/classes' .
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'osso2007/library' .     
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'ZendFramework-1.0.0/library'             
        );       
        $dbParams = array (
            'host'     => '127.0.0.1',
            'username' => 'impd',
            'password' => 'impd894',
            'dbname'   => 'osso2007',
            'dbtype'   => 'mysql',
            'adapter'  => 'pdo_mysql'
        );
        $dbEaysoParams = array (
            'host'     => '127.0.0.1',
            'username' => 'impd',
            'password' => 'impd894',
            'dbname'   => 'eayso',
            'dbtype'   => 'mysql',
            'adapter'  => 'pdo_mysql'
        );
         
        /* Loadin the Project Context */
        require_once $projDir . MYAPP_CONFIG_HOME . 'osso2007/config/ProjectContext.php';

        spl_autoload_register(array('ProjectLoader','loadClass'));
		spl_autoload_register(array('Zend_Loader',  'loadClass'));
		
		/*
		 * Sort of cheating here since the app context is not extending the
		 * project context, assuming that only db,tables,models are being used
		 */
        $this->context = $context = new ApplicationContext();
        
        $context->db     = new Proj_Db_Adapter($dbParams);
        $context->dbEayso= new Proj_Db_Adapter($dbEaysoParams);
        
        $context->tables = new Proj_Locator_Table($context);
        
        $context->models = new Proj_Locator_Model($context);
		
	}
	function run()
	{
		echo "OSSO Export\n";
		
		//$export = new TeamRefereesExport2($this->context);
		//$export->process('/home/data/MadisonTeamReferees20091104.xml');
		
		$export = new RefAvailExport($this->context);
		$export->process('/home/data/Area5cRefAvail20091113.xml');
		
		//$export = new AreaTournScheduleExport($this->context);
		//$export->process('/home/data/Area5cTournRefSched20091110.xml');
		
	}
}
$export = new Export();
$export->run();
?>