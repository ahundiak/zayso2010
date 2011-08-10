<?php
class Osso2007_FrontEnd_FrontCont
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
    $this->setClassLoader();
  }
  protected function setIncludePath()
  {
    $ws = $this->config['ws'];
    ini_set('include_path','.' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/library' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/data' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/data/classes' .
      PATH_SEPARATOR . $ws . 'osso2007/osso2007/apps/osso/library' .
            
      PATH_SEPARATOR . $ws . 'ZendFramework-1.0.0/library' .

      PATH_SEPARATOR . $ws . 'osso2010/model/classes' .
      PATH_SEPARATOR . $ws . 'osso2010/apps' .
      PATH_SEPARATOR . $ws . 'Cerad/library'
    );
  }
  protected function setClassLoader()
  {
    require_once 'Osso2007/Loader.php';
    Osso2007_Loader::registerAutoload();

    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
  }
  public function execute($args = null)
  {
    $ws = $this->config['ws'];

    $params = $this->config;
    $params['proj_dir'] = $ws . 'osso2007/osso2007';
    $params['app_dir']  = $ws . 'osso2007/osso2007/apps/osso';

    /* Loadin the Project and Application Context */
    require_once $ws . 'osso2010/apps/Osso2007/FrontEnd/ProjectContext.php';
    require_once $ws . 'osso2010/apps/Osso2007/FrontEnd/ApplicationContext.php';

    $context = new ApplicationContext($params);

    // Use pretty urls, pull args from the uri
    if (!$args)
    {
      $uri  = $_SERVER['REQUEST_URI'];
      $name = $_SERVER['SCRIPT_NAME'];

      if (substr($uri,0,strlen($name)) == $name) $args = substr($uri,strlen($name)+1);
      else                                       $args = substr($uri,strlen(dirname($name)) + 1);

      // Need to pull out any thing after ?
      $tmp  = explode('?',$args);
      $args = $tmp[0];

      // echo "URI: $uri <br />NAME: $name<br />ARGS: $args</br />"; die();
      // if (!$args) $args = 'index';
    }
    // Convert to array
    if (!is_array($args)) $args = explode('/',$args);
    if (!$args[0]) $args[0] = 'index_home';

    $actionClassName = null;
    $actionRoute = $args[0];
    $argCount = 1;
    if (isset($this->map[$actionRoute])) $actionClassName = $this->map[$actionRoute];
    else
    {
      if (isset($args[1]))
      {
        $actionRoute .= '_' . $args[1];
        if (isset($this->map[$actionRoute])) $actionClassName = $this->map[$actionRoute];
        $argCount = 2;
      }
    }
    if ($actionClassName)
    {
      // Still need the modeling stuff
      // $context = new Osso2007_Context($params);
        
      $action = new $actionClassName($context);

      while($argCount--) array_shift($args);

      $action->process($args);

      $response = $context->response;
      $response->sendResponse();
      exit();
    }
// Cerad_Debug::dump($args);

    $context->fc->dispatch();
  }
  protected $map = array
  (
    'sched_ref_list' => 'Osso2007_Referee_Schedule_RefSchListAction',
//  'sched_div_list' => 'Osso2007_Div_Schedule_DivSchListAction',

    'event_editx'     => 'Osso2007_Event_EventEditAction',

    'home_index'     => 'Osso2007_Index_IndexHomeAction',
    'index_home'     => 'Osso2007_Index_IndexHomeAction',

    'index_login_rpx' => 'Osso2007_Index_IndexLoginRpxAction',
      
    'account_index'           => 'Osso2007_Account_Home_AccountHomeAction',
    'account_home'            => 'Osso2007_Account_Home_AccountHomeAction',
    'account_openid_link'     => 'Osso2007_Account_Openid_OpenidLinkAction',
    'account_openid_link_rpx' => 'Osso2007_Account_Openid_OpenidLinkRpxAction',

    'report_proc' => 'Osso2007_Report_ReportProcAction',
    'import_proc' => 'Osso2007_Import_ImportProcAction',
  );
}
// Merge in additional config items
$configx = require $config['ws'] . 'osso2010/model/config/config_' . $config['web_host'] . '.php';
$config  = array_merge($config,$configx);

$configx = require $config['ws'] . 'osso2010/apps/Osso2007/FrontEnd/config.php';
$config  = array_merge($config,$configx);

$configx = NULL;

$fc = new Osso2007_FrontEnd_FrontCont($config);
$config = NULL;
$fc->execute();

?>
