<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Cerad_FrontEnd_FrontCont
{
  protected $config;
  protected $data;
  protected $contextClassName = 'Cerad_Context';

  protected $loadTypeClassNames = array(
      'css'     => 'Cerad_FrontEnd_LoadCSS',
      'js'      => 'Cerad_FrontEnd_LoadJS',
      'direct'  => 'Cerad_FrontEnd_LoadDirect',

      'action'  => 'FrontEnd_LoadAction',
      
      'html'    => 'FrontEnd_LoadHTML',
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
  function execute()
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
