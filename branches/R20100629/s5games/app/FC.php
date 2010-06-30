<?php

class FC
{
  static function run()
  {
    /* This could get moved to .htaccess */
    ini_set('include_path','.' .
      PATH_SEPARATOR . MYAPP_CONFIG_HOME . 's5games/app' .
      PATH_SEPARATOR . MYAPP_CONFIG_HOME . 's5games/data/classes' .
      PATH_SEPARATOR . MYAPP_CONFIG_HOME . 'Cerad/library'
    );
    session_start();
    date_default_timezone_set('US/Central');
		
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();

    if (isset($_GET['page'])) $page = trim($_GET['page']);
    else                      $page = 'index';
		
    switch($page)
    {
      case 'index':
      case 'login':
      case 'schedule':
      case 'signup':
        break;
      default:
        $page = 'index';
    }
    $page = ucfirst($page);
    $contName = $page . 'Controller';
		
    $cont = new $contName();
    $cont->execute();
    }
}
FC::run();
?>