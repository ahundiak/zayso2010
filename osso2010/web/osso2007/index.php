<?php
error_reporting(0);

/* Wrap everything in a class to avoid global variables */
class WebIndex
{
	static function run()
	{
		/* This could get moved to .htaccess */
		ini_set('include_path','.' . 
			PATH_SEPARATOR . '/home/ahundiak/zayso2010/osso2007/osso2007/library' .
			PATH_SEPARATOR . '/home/ahundiak/zayso2010/osso2007/osso2007/data' .
			PATH_SEPARATOR . '/home/ahundiak/zayso2010/osso2007/osso2007/data/classes' .
            		PATH_SEPARATOR . '/home/ahundiak/zayso2010/osso2007/osso2007/apps/osso/library' .
            		PATH_SEPARATOR . '/home/ahundiak/zayso2010/ZendFramework-1.0.0/library' .
            		PATH_SEPARATOR . '/home/ahundiak/zayso2010/Cerad/library' . 
            		PATH_SEPARATOR . '/home/ahundiak/zayso2010/osso2010/model/classes'
		);
		//echo phpinfo(); die();

		$params['proj_dir'] = '/home/ahundiak/zayso2010/osso2007/osso2007';
		$params['app_dir']  = '/home/ahundiak/zayso2010/osso2007/osso2007/apps/osso';
		
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
