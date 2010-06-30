<?php
error_reporting(E_ALL);

/* This could get moved to .htaccess */
ini_set('include_path','.;' .
	'/home/ahundiak/ws2008c/osso2007/library;' 
);
$appConfig = array(
	'db' => array(
    	'adapter'  => 'pdoMysql',
    	'host'     => 'localhost',
    	'username' => 'impd',
    	'password' => 'impd894',
    	'dbname'   => 'osso2003'
	),
);
?>
