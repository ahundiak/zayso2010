<?php
error_reporting(E_ALL);

/* Wrap everything in a class to avoid global variables */
class WebIndex
{
	static function run()
	{
		/* This could get moved to .htaccess */
		ini_set('include_path','.' . 
			PATH_SEPARATOR . '/ahundiak/ws2007/osso2007/library' .     
			PATH_SEPARATOR . '/ahundiak/ws2007/osso2007/data' .
            PATH_SEPARATOR . '/ahundiak/ws2007/osso2007/apps/osso/library' .
            PATH_SEPARATOR . '/ahundiak/ws2007/ZendFramework-1.0.0/library'        
		);
		//echo phpinfo(); die();

		$params['proj_dir'] = '/ahundiak/ws2007/osso2007';
		$params['app_dir']  = '/ahundiak/ws2007/osso2007/apps/osso';
		
		$params['config_file_name'] = 'buffy2007.ini';
		
		/* Loadin the Project and Application Context */
		require_once $params['proj_dir'] . '/config/ProjectContext.php';
		require_once $params['app_dir']  . '/config/ApplicationContext.php';

		$context = new ApplicationContext($params);		
		$context->fc->dispatch();
	}
}
WebIndex::run();

?>
