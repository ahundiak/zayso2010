<?php
/* -----------------------------------------
 * Currently combines initial index startup along
 * with custom action, probably better to split
 */
class FC_IndexFC
{
	static $jsFiles = array
	(
    'Overrides',
    'DirectAPI',
    'App',
    'User',
    'Stores',
	  'Viewport'
	);
	function execute()
	{
    $hostName   = $_SERVER['HTTP_HOST'];
    $serverName = $_SERVER['SERVER_NAME'];
    $scriptName = $_SERVER['SCRIPT_NAME'];  // /test4/index.php
    
  //$phpSelf    = $_SERVER['PHP_SELF'];     // /test4/index.php/assign or /test4/index.php

    // Assorted paths
    $scriptPath     = dirname($scriptName);     // /test4
    $baseDomainPath = "http://{$hostName}";
    $baseAppPath    = $baseDomainPath . $scriptPath . '/';
    $baseToolsPath  = $baseDomainPath . '/tools'    . '/';
    $baseExtJSPath  = $baseToolsPath  . 'extjs'     . '/';
    $baseFirebugPath= $baseToolsPath  . 'firebug'   . '/';
    
    $loadFirebug = FALSE;

    $jsFiles = self::$jsFiles;
    $jsLoadFilesIndividually = true;
    
    // Allow for custom action
    if (isset($_GET['a']))
    {
    	$action = $_GET['a'];
    	
      $jsFile  = 'Actions/' . $action  . '.js.php';
      $tplFile = 'Actions/Action' . '.tpl.php';

      // Get the script code
      ob_start();
      require $jsFile;
      $js = ob_get_clean();
      
      // Template it
      ob_start();
      require $tplFile;
      $html = ob_get_clean();
      
    	return $html;
    }
    // Regular template
    ob_start();
    require 'IndexFC.tpl.php';
    $html = ob_get_clean();
      
    return $html;
	}
}
?>