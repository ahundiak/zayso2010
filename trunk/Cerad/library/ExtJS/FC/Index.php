<?php
/* 
 * Index Front Controller
 */
class ExtJS_FC_Index
{
  protected $contextx = NULL;
  protected $contextClassName = 'Cerad_Context';

  protected $loadFirebug             = FALSE;
  protected $loadJSFilesIndividually = TRUE;

  protected $jsFiles = array
  (
  );
  function __construct()
  {
    $this->init();
  }
  protected function init()
  {
    $this->setIncludePath();
  }
  protected function setIncludePath() {} // Override

  // Spits out one or more js files
  protected function jsLoad($file)
  {
    if ($file) $files = array(str_replace('-','/',$file));
    else       $files = $this->jsFiles;

    header('Content-Type: text/javascript');

    foreach($files as $file)
    {
      require 'JS/' . $file . '.js';
    }
  }
  // Spits out one php file
  protected function phpLoad($file)
  {
    $file = str_replace('-','/',$file);

    header('Content-Type: text/html');

    require 'JS/' . $file . '.php';
  }
  protected function get(&$post,$name)
  {
    if (!isset($post[$name])) return null;
    $value = $post[$name];
    unset($post[$name]);
    return $value;
  }
  public function __get($name)
  {
    switch($name)
    {
      case 'context': return $this->getContext(); break;

    }
  }
  protected function getContext()
  {
    if (!$this->contextx)
    {
      $params = include('Config/' . APP_CONFIG_FILE);
      $contextClassName = $this->contextClassName;
      $this->contextx = new $contextClassName($params);
    }
    return $this->contextx;
  }
  protected function direct()
  {
    // Most requests are json
    if(isset($GLOBALS['HTTP_RAW_POST_DATA']))
    {
      $requests = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
    }
    else
    {
      // Form submits use POST, not positive why, maybe file uploads?
      if (!isset($_POST['extAction'])) exit('Direct POST - No data');

      $post = $_POST;

      $requests = array
      (
        'action' => $this->get($post,'extAction'),
        'method' => $this->get($post,'extMethod'),
        'type'   => $this->get($post,'extType'),
        'tid'    => $this->get($post,'extTID'),
        'upLoad' => $this->get($post,'extUpload'),
      );
      // Data is everything left
      $requests['data'] = array($post);
    }
    if (count($requests) < 1) exit('Direct POST - No requests');

    // Always need a context
    $context = $this->getContext();

    // Might have multiple requests, if so have index array instead of names parameters
    if (!isset($requests[0])) $requests = array($requests);

    foreach($requests as $data)
    {
      // Add a final sanity check here

      // Build class and method name
      $actionClassName  = 'Direct_' . $data['action'];
      $actionMethodName = $data['method'];

      $action = new $actionClassName($context);

      // Execute the action
      $result = $action->$actionMethodName($data['data'][0]);

      $responses[] = array
      (
        'type'   => 'rpc',
        'tid'    => $data['tid'],
        'action' => $data['action'],
        'method' => $data['method'],
        'result' => $result
      );
    }
    // Send it
    header('Content-Type: text/javascript');
    echo json_encode($responses);
  }
  function execute()
  {
    // Directs are always POST but for now just check for a parameter called direct
    if (isset($_GET['direct'])) return $this->direct();

    // Direct calls are always POST
    if ($_SERVER['REQUEST_METHOD'] != 'GET')
    {
      die("Not direct and not GET, {$_SERVER['REQUEST_METHOD']}");
    }
    // Need to determine type of action
    if (isset($_GET['jsload']))  return $this->jsLoad ($_GET['jsload']);
    if (isset($_GET['phpload'])) return $this->phpLoad($_GET['phpload']);

    // Must be an action
    if (isset($_GET['a'])) $action = $_GET['a'];
    else                   $action = 'Index';

    $loadFirebug             = $this->loadFirebug;
    $loadJSFilesIndividually = $this->loadJSFilesIndividually;

    $jsFiles = $this->jsFiles;

    $jsFile  = 'FC/' . $action  . '.js.php';
    $tplFile = 'FC/' . 'Action.tpl.php';

    // Get the script code
    ob_start();
    require $jsFile;
    $js = ob_get_clean();

    // Template it, not sure if need to buffer it but okay for now
    ob_start();
    require $tplFile;
    $html = ob_get_clean();

    header('Content-Type: text/html');
    echo $html;
  }
}
?>
