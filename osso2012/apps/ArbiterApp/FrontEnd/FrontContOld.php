<?php
require_once $config['ws'] . 'Cerad/library/Cerad/FrontEnd/FrontCont.php';

class Arbiter_FrontEnd_FrontCont extends Cerad_FrontEnd_FrontCont
{
  protected $indexFileName    = 'Arbiter/FrontEnd/Index.html.php';

  protected $loadTypeClassNames = array
  (
      'css'     => 'Cerad_FrontEnd_LoadCSS',
      'js'      => 'Cerad_FrontEnd_LoadJS',
      'direct'  => 'Cerad_FrontEnd_LoadDirect',

      'action'  => 'Arbiter_FrontEnd_LoadAction',
      
      'html'    => 'FrontEnd_LoadHTML',
  );
  protected function setIncludePath()
  {
    $ws = $this->config['ws'];
    ini_set('include_path','.' .
      PATH_SEPARATOR . $ws . 'Cerad/library' .
      PATH_SEPARATOR . $ws . 'osso2010/apps'
    );
  }
}
// Merge in additional config items
$configx = require $config['ws'] . 'osso2010/model/config/config_' . $config['web_host'] . '.php';
$config  = array_merge($config,$configx);

//$configx = require $config['ws'] . 'osso2007/osso2007/apps/osso/config/config.php';
//$config  = array_merge($config,$configx);

$configx = NULL;

$fc = new Arbiter_FrontEnd_FrontCont($config);
$config = NULL;
$fc->execute();

?>
