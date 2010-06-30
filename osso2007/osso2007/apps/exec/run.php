<?php
if (!defined('PHPUnit2_MAIN_METHOD')) {
     define ('PHPUnit2_MAIN_METHOD', 'Run_AllTests::main');
}
ini_set('include_path','.' . 
	PATH_SEPARATOR . '/ahundiak/osso/ZendFramework-060715/library' .                
	PATH_SEPARATOR . '/ahundiak/osso/ZendProjects/library' .     
	PATH_SEPARATOR . '/ahundiak/osso/ZendProjects/data' 
);

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';

require_once 'AdminDB.php';

class Run_AllTests
{
    public static function main()
    {
		$params['proj_dir'] = '/ahundiak/osso/ZendProjects';
		$params['app_dir']  = '/ahundiak/osso/ZendProjects/apps/exec';
		
		$params['config_file_name'] = 'buffy2006.ini';
		
		/* Loadin the Project and Application Context */
		require_once $params['proj_dir'] . '/config/ProjectContext.php';
		require_once $params['app_dir']  . '/config/ApplicationContext.php';

		$context = new ApplicationContext($params);
		
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit2_Framework_TestSuite('Exec Apps');
		$suite->addTestSuite('AdminDB_Tests');
        return $suite;
    }
}
Run_AllTests::main();

