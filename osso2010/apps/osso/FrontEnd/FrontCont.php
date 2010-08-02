<?php
include $config['ws'] . 'Cerad/library/Cerad/FrontEnd/FrontCont.php';

class FrontCont extends Cerad_FrontEnd_FrontCont
{
  protected $contextClassName = 'Osso_Context';
  protected $indexFileName    = 'FrontEnd/Index.html.php';

  protected $loadTypeClassNames = array(
      'css'     => 'FrontEnd_LoadCSS',
      'js'      => 'FrontEnd_LoadJS',

      'cont'    => 'FrontEnd_LoadCont',

      'action'  => 'FrontEnd_LoadAction',
      'actionx' => 'FrontEnd_LoadAction',
     
      'direct'  => 'FrontEnd_LoadDirect',
      'tab'     => 'FrontEnd_LoadTab',
  );

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
