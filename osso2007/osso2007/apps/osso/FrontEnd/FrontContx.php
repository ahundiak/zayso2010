<?php
class FrontContx
{
  protected $config = null;

  public function __construct($config)
  {
    $this->config = $config;
    $this->init();
  }
  protected function init()
  {
    $this->setIncludePath();
    // $this->setClassLoader();
  }
  protected function setIncludePath()
  {
    $ws = $this->config['ws'];
    ini_set('include_path','.' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/library' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/data' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/data/classes' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/apps/osso/library' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/apps/osso' .
            
      PATH_SEPARATOR . $ws . 'ZendFramework-1.0.0/library' .

      PATH_SEPARATOR . $ws . 'osso2010/model/classes' .
      PATH_SEPARATOR . $ws . 'Cerad/library'
    );
  }
  protected function setClassLoader()
  {
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
  }
  public function execute()
  {
    $ws = $this->config['ws'];

    $params = $this->config;
    $params['proj_dir'] = $ws . 'osso2007/osso2007';
    $params['app_dir']  = $ws . 'osso2007/osso2007/apps/osso';

    /* Loadin the Project and Application Context */
    require_once $ws . 'osso2007/osso2007/apps/osso/FrontEnd/ProjectContext.php';
    require_once $ws . 'osso2007/osso2007/apps/osso/FrontEnd/ApplicationContext.php';

    $context = new ApplicationContext($params);
    $context->fc->dispatch();
  }
}
// Merge in additional config items
$configx = require $config['ws'] . 'osso2010/model/config/config_' . $config['web_host'] . '.php';
$config  = array_merge($config,$configx);

$configx = require $config['ws'] . 'osso2007/osso2007/apps/osso/config/config.php';
$config  = array_merge($config,$configx);

$configx = NULL;

$fc = new FrontContx($config);
$config = NULL;
$fc->execute();

?>
