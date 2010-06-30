<?php
include $config['ws'] . 'Cerad/library/Cerad/FrontEnd/FrontCont.php';

class FrontCont extends Cerad_FrontEnd_FrontCont
{
  protected $contextClassName = 'Osso_Context';

  protected function setIncludePath()
  {
    $ws = $this->config['ws'];
    ini_set('include_path','.' .
      PATH_SEPARATOR . $ws . 'osso2010/apps/osso' .
      PATH_SEPARATOR . $ws . 'osso2010/model/classes' .
      PATH_SEPARATOR . $ws . 'Cerad/library'
    );
  }
}
// Merge in additional config items
$configx = require '../../model/config/config.php';
$config  = array_merge($config,$configx);
$configx = NULL;

$fc = new FrontCont($config);
$config = NULL;
$fc->execute();
?>
