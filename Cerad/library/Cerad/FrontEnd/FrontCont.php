<?php
class Cerad_FrontEnd_FrontCont
{
  protected $config;
  protected $data;
  protected $contextClassName = 'Cerad_Context';
  protected $indexFileName    = 'FrontEnd/Index.html.php';

  protected $loadTypeClassNames = array(
      'css'     => 'Cerad_FrontEnd_LoadCSS',
      'js'      => 'Cerad_FrontEnd_LoadJS',
      'direct'  => 'Cerad_FrontEnd_LoadDirect',

      'action'  => 'FrontEnd_LoadAction',
      'html'    => 'FrontEnd_LoadHTML',
      'tab'     => 'FrontEnd_LoadTab',
  );
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
       PATH_SEPARATOR . $ws . 'Cerad/library'
    );
  }
  protected function setClassLoader()
  {
    require_once 'Cerad/Loader.php';
    Cerad_Loader::registerAutoload();
  }
  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];

    $methodName = 'new' . ucfirst($name);

    return $this->$methodName();
  }
  protected function newContext()
  {
    $context = new $this->contextClassName($this->config);
    $this->data['context'] = $context;
    return $context;
  }
  // The args can eith be 'action/controller' or an array
  function execute($args = null)
  {
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

      // echo "URI: $uri <br />NAME: $name<br />ARGS: $args</br />";
      // if (!$args) $args = 'index';
    }
    // Convert to array
    if (!is_array($args)) $args = explode('/',$args);

    // Always get at least one element, might be ''
    if ($args[0] == '')
    {
      // Load in the default file
      header('Content-type: text/html');
      include  $this->indexFileName;
      return;
    }

    $loadType = $args[0];
    if (!isset($this->loadTypeClassNames[$loadType])) $loadType = 'action'; // default
    else                                              array_shift($args);   // pop the load type

    $loadTypeClassName = $this->loadTypeClassNames[$loadType];

    // For action always make sure have an argument
    if (($loadType == 'action') && (count($args) < 1)) $args = array('index');

    // And execute it
    $load = new $loadTypeClassName($this->context);
    $load->execute($args);
  }
  function executeFromRequest()
  {
    $context = $this->context;
    $request = $context->request;

    $loadType = $request->get('lt',$this->config['load_type']);

    $loadTypeClassName = $this->loadTypeClassNames[$loadType];

    $load = new $loadTypeClassName($context);
    $load->execute();

    // die($feClassName);

  }
}
?>
