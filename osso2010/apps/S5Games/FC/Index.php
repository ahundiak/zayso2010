<?php
/* 
 * Index Front Controller
 */
require_once APP_CONFIG_HOME . 'Cerad/library/ExtJS/FC/Index.php';

class FC_Index extends ExtJS_FC_Index
{
  //protected $jsPath = APP_CONFIG_HOME . 'osso2010/apps/S5Games/JS/';
  protected $jsFiles = array
  (
 // 'Overrides',
    'DirectAPI',
  );
  protected function setIncludePath()
  {
    ini_set('include_path','.' .
      PATH_SEPARATOR . APP_CONFIG_HOME . 'osso2010/apps/S5Games' .
      PATH_SEPARATOR . APP_CONFIG_HOME . 'osso2010/data/classes' .
      PATH_SEPARATOR . APP_CONFIG_HOME . 'Cerad/library'
    );
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
  }
}
$fc = new FC_Index();
$fc->execute();
?>
