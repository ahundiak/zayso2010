<?php
error_reporting(E_ALL);

define('ZAYSO2010_CONFIG_HOME','/home/impd/zayso2010/');

/* Wrap everything in a class to avoid global variables */
class WebIndex
{
  static function run()
  {
    // include paths
    ini_set('include_path','.' .
      PATH_SEPARATOR . ZAYSO2010_CONFIG_HOME . 'Cerad/library'
    );
    session_start();
    date_default_timezone_set('US/Central');
		
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();

    $orgFileName = $_FILES['data-path']['name'];
    $tmpFileName = $_FILES['data-path']['tmp_name'];
    
    $_SESSION['tmpFileName'] = $tmpFileName;
    $_SESSION['orgFileName'] = $orgFileName;

    require_once ZAYSO2010_CONFIG_HOME . 'osso2010/apps/Arbiter/RefAvail/RefAvail.php';

    $refAvail = new RefAvail();

    $refAvail->importCSV($tmpFileName);
    $refAvail->exportCSV($tmpFileName . 'x');

    $results = array
    (
      'success' => true,
      'message' => 'It worked',
    	
      'orgName' => $orgFileName,
      'tmpName' => $tmpFileName,
    );
    echo json_encode($results);
  }
  static function debug()
  {
    $fileName = $_FILES['data-path']['name'];
    $tmpName  = $_FILES['data-path']['tmp_name'];

    $results = array
    (
      'success' => true,
      'file'    => $fileName,
      'tmp'     => $tmpName,
      'count'   => sizeof($_POST),
      'files'   => $_FILES,
      'post'    => $_POST,
    );
    echo json_encode($results);
  }
}
WebIndex::run();
?>
