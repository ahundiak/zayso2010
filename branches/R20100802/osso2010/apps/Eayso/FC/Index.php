<?php
/* 
 * Index Front Controller
 */
require_once APP_CONFIG_WS . 'Cerad/library/ExtJS/FC/Index.php';

class FC_Index extends ExtJS_FC_Index
{
  protected $contextClassName = 'Eayso_Context';

  protected $jsFiles = array
  (
    'DirectAPI',
    'Vols',
  );
  protected function setIncludePath()
  {
    ini_set('include_path','.' .
      PATH_SEPARATOR . APP_CONFIG_WS . 'osso2010/apps/Eayso' .
      PATH_SEPARATOR . APP_CONFIG_WS . 'osso2010/model/classes' .
      PATH_SEPARATOR . APP_CONFIG_WS . 'Cerad/library'
    );
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
  }
}
$fc = new FC_Index();
$fc->execute();
?>
