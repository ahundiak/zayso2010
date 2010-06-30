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
class Import
{
	protected $context = NULL;
	
	function __construct()
	{
        ini_set('include_path','.' . 
            PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'osso2007/data' .
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
        
        $context->tables = new Proj_Locator_Table($context);
        
        $context->models = new Proj_Locator_Model($context);
		
	}
	function run()
	{
		echo "OSSO Import\n";
		// $path = '/ahundiak/misc/soccer2008/spring/';
	    //$people = new PersonImport($this->context,$path . 'TeamsSpring2008.xml');
		//$teams  = new  TeamsImport($this->context,$path . 'TeamsSpring2008.xml');
		
		//$schedule = new MadisonScheduleImport($this->context);
		//$schedule->update = TRUE;
		//$schedule->import($path . 'schedules/2008 U12C Spring Soccer Schedule.xml');

		//$schedule = new MadisonScheduleImport($this->context);
		//$schedule->update = TRUE;
		//$schedule->import($path . 'schedules/2008 U14C Spring Soccer Schedule.xml');
		
		//$schedule = new MadisonScheduleImport($this->context);
		//$schedule->update = TRUE;
		//$schedule->import($path . 'schedules/2008 U16-19C Spring Soccer Schedule.xml');
		
		$schedule = new MadisonScheduleImport($this->context);
		$schedule->update = TRUE;
		$schedule->import('./data/schedules/Madison20080805.xml');
	}
}
$import = new Import();
$import->run();
?>