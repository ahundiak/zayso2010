<?php
/* 
 * Index Front Controller
 */
require_once APP_CONFIG_HOME . 'Cerad/library/ExtJS/FC/Index.php';

class FC_Index extends ExtJS_FC_Index
{
  protected $contextClassName = 'S5Games_Context';

  protected $jsFiles = array
  (
 // 'Overrides',
    'DirectAPI',
    'User/User',
    'User/UserSignIn',
    'App',
    'Viewport',
    'Schedule',
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
  protected function getMemberId()
  {
    $sessionData = $this->context->getSessionData();
    return $sessionData['member_id'];
  }
}
$fc = new FC_Index();
$fc->execute();
?>
