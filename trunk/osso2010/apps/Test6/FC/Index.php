<?php
/* 
 * Index Front Controller
 */
class FC_Index
{
  protected $jsFiles = array
  (
 // 'Overrides',
    'DirectAPI',
    'App',
    'User',
    'Stores',
    'Viewport'
  );
  // Spits out one or more js files
  function jsLoad($file)
  {
    if ($file) $files = array(str_replace('-','/',$file));
    else       $files = $this->jsFiles;

    header('Content-Type: text/javascript');

    foreach($files as $file)
    {
      require APP_CONFIG_HOME . 'osso2010/apps/Zayso/Zayso/' . $file . '.js';
    }
  }
  protected function get(&$post,$name)
  {
    if (!isset($post[$name])) return null;
    $value = $post[$name];
    unset($post[$name]);
    return $value;
  }
  function direct()
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

    // Need some paths and autoload
    ini_set('include_path','.' .
      PATH_SEPARATOR . APP_CONFIG_HOME . 'osso2010/apps/Zayso' .
      PATH_SEPARATOR . APP_CONFIG_HOME . 'osso2010/data/classes' .
      PATH_SEPARATOR . APP_CONFIG_HOME . 'Cerad/library'
    );
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();

    // Always need a context
    $params = include('Config/' . APP_CONFIG_FILE);
    $context = new Cerad_Context($params);

    // Might have multiple requests, if so have index array instead of names parameters
    if (!isset($requests[0])) $requests = array($requests);

    foreach($requests as $data)
    {
      // Add a final sanity check here

      // Build class and method name
      $actionClassName  = 'Direct_' . $data['action'] . 'Action';
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
    // Direct calls are always POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      return $this->direct();
    }
    // Need to determine type of action
    if (isset($_GET['jsload'])) return $this->jsLoad($_GET['jsload']);

    // Must be an action
    if (isset($_GET['a'])) $action = $_GET['a'];
    else                   $action = 'Index';

    $loadFirebug = FALSE;
    $jsFiles = $this->jsFiles;
    $jsLoadFilesIndividually = true;

    $jsFile  = 'Actions/' . $action  . '.js.php';
    $tplFile = 'Actions/Action' . '.tpl.php';

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
$fc = new FC_Index();
$fc->execute();
?>
